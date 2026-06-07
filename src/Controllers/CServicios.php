<?php
    namespace Lenovo\Dalu\Controllers;
    use Lenovo\Dalu\Models\Pedidos;
    use Lenovo\Dalu\Models\Clientes;
    use Lenovo\Dalu\Models\Proveedores;
    use Lenovo\Dalu\Models\Productos;

    $accion = $_GET['accion'] ?? $_POST['accion'] ?? 'view';

    switch ($accion) {
        case 'view':
            $pedidosC = (new Pedidos())->getByTipo('cliente');
            $clientes = (new Clientes())->search();
            $proveedores = (new Proveedores())->search();
            $prodModel = new Productos();
            $productos = $prodModel->search();
            foreach ($productos as &$p) {
                $p['variantes'] = $prodModel->getVariantesByProducto($p['id']);
            }
            
            // Cargar tasa
            $tasaModel = new \Lenovo\Dalu\Models\Tasa();
            $tasaActual = $tasaModel->getLatest();
            
            // Cargar metodos de pago
            $metodosPagoModel = new \Lenovo\Dalu\Models\MetodosPago();
            $metodosPago = $metodosPagoModel->getActivos();
            
            require_once __DIR__ . '/../Views/V_Servicios.php';
            break;
            
        case 'insertCliente':
            $idCliente = $_POST['id_cliente'] ?? null;
            
            if (empty($idCliente)) {
                $cedulaCli = $_POST['cedula_cliente'] ?? '';
                $tipoPer = $_POST['tipo_persona'] ?? '';
                $nombreCli = $_POST['nombre_cliente'] ?? '';
                
                if (!empty($cedulaCli) && !empty($tipoPer) && !empty($nombreCli)) {
                    $cedulaCompleta = trim($tipoPer . $cedulaCli);
                    $clienteModel = new Clientes();
                    $existente = $clienteModel->getByCedula($cedulaCompleta);
                    
                    if ($existente) {
                        $idCliente = $existente['id'];
                    } else {
                        $partes = explode(' ', trim($nombreCli), 2);
                        $n = $partes[0];
                        $a = $partes[1] ?? '';
                        
                        $clienteModel->setNombre($n)
                                     ->setApellido($a)
                                     ->setCedula($cedulaCompleta)
                                     ->setTelefono(null)
                                     ->setCorreo(null)
                                     ->setDireccion(null);
                        
                        if ($clienteModel->insert()) {
                            $nuevoCli = $clienteModel->getByCedula($cedulaCompleta);
                            $idCliente = $nuevoCli['id'] ?? null;
                        }
                    }
                }
            }
            
            if (empty($idCliente)) {
                $_SESSION['error'] = 'Debe seleccionar un cliente o llenar los datos para registrarlo.';
                header('Location: ?c=servicios&accion=view');
                exit();
            }

            $pedido = new Pedidos();
            $pedido->setTipo('cliente')
                   ->setEstado($_POST['estado'] ?? 'pendiente')
                   ->setIdCliente($idCliente)
                   ->setNombreProveedor(null);

            // Procesar detalles opcionales y calcular total
            $productosModel = new Productos();
            $detalles = [];
            $totalPedido = 0;
            
            if (isset($_POST['detalles']) && is_array($_POST['detalles'])) {
                foreach ($_POST['detalles'] as $index => $d) {
                    $hasFile = isset($_FILES['detalleImagens']['tmp_name'][$index]) && $_FILES['detalleImagens']['error'][$index] === UPLOAD_ERR_OK;
                    $hasDetalle = !empty($d['id_producto']) || !empty($d['nombre_producto']) || !empty($d['link']) || $hasFile;
                    if ($hasDetalle) {
                        $imagenRuta = '';
                        if ($hasFile) {
                            $categoriaNombre = !empty($d['id_producto']) ? $productosModel->getNombreCategoria($d['id_producto']) : 'sin_categoria';
                            $nombreImagen = !empty($d['nombre_producto']) ? $d['nombre_producto'] : 'detalle_' . $index;
                            $fileData = [
                                'name' => $_FILES['detalleImagens']['name'][$index],
                                'type' => $_FILES['detalleImagens']['type'][$index],
                                'tmp_name' => $_FILES['detalleImagens']['tmp_name'][$index],
                                'error' => $_FILES['detalleImagens']['error'][$index],
                                'size' => $_FILES['detalleImagens']['size'][$index],
                            ];
                            $imagenRuta = $productosModel->subirImagen($fileData, $categoriaNombre, $nombreImagen) ?? '';
                        }

                        $cantidad = !empty($d['cantidad']) ? (int) $d['cantidad'] : 1;
                        $precio_unitario = !empty($d['precio_unitario']) ? (float) $d['precio_unitario'] : 0;
                        $totalPedido += ($cantidad * $precio_unitario);

                        $detalles[] = [
                            'tipo' => 'cliente',
                            'imagen' => $imagenRuta,
                            'link' => trim($d['link'] ?? ''),
                            'estado' => $d['estado'] ?? 'pendiente',
                            'nombre_producto' => trim($d['nombre_producto'] ?? '') ?: null,
                            'id_producto' => !empty($d['id_producto']) ? $d['id_producto'] : null,
                            'cantidad' => $cantidad,
                            'precio_unitario' => $precio_unitario,
                            'descripcion_producto' => null,
                            'id_variante' => !empty($d['id_variante']) ? $d['id_variante'] : null,
                        ];
                    }
                }
            }
            $pedido->setDetalles($detalles);

            if ($pedido->insert()) {
                $idPedido = $pedido->getId();
                
                // Actualizar id_nota_entrega en el pedido si creamos la nota exitosamente
                $tipoModalidad = $_POST['tipo_modalidad'] ?? 'debito';
                $porcentajeInicial = (float)($_POST['porcentaje_inicial'] ?? 40);
                $frecuencia = $_POST['frecuencia'] ?? 'mensual';
                $nroCuotas = (int)($_POST['nro_cuotas'] ?? 1);
                
                if ($tipoModalidad === 'debito') {
                    $nroCuotas = 1; // De contado al entregar = 1 sola cuota por el restante
                }

                $montoInicial = $totalPedido * ($porcentajeInicial / 100);
                $montoRestante = $totalPedido - $montoInicial;
                $montoPorCuota = ($nroCuotas > 0) ? ($montoRestante / $nroCuotas) : 0;
                
                // 1. Crear la Nota de Entrega fantasma para mantener el flujo
                $notaModel = new \Lenovo\Dalu\Models\NotasEntrega();
                $observacionesNota = "Generada automáticamente desde Pedido de Servicio #" . $idPedido;
                // Usamos un array de detalles vacío para la nota, porque los verdaderos están en el pedido
                $notaModel->setIdCliente($idCliente)
                          ->setFechaPedido(date('Y-m-d H:i:s'))
                          ->setEstado('servicio')
                          ->setTipo($tipoModalidad)
                          ->setTotal($totalPedido)
                          ->setObservaciones($observacionesNota)
                          ->setDetalles([]);
                $exitoNota = $notaModel->insert();
                
                if ($exitoNota) {
                    $db = new \Lenovo\Dalu\Models\Conexion();
                    $idNotaNueva = $db->lastInsertId(); // Ojo: NotasEntrega::insert no retorna el ID, pero lastInsertId() en la DB debería funcionar si estamos en la misma conexión. Sin embargo, para ser seguros:
                    // Mejor busquemos la nota recién insertada
                    $notaReciente = $notaModel->search()[0] ?? null;
                    $idNotaNueva = $notaReciente['id'] ?? null;
                    
                    if ($idNotaNueva) {
                        // Actualizar id_nota_entrega en el pedido
                        $sqlUpd = "UPDATE pedidos SET id_nota_entrega = :id_nota WHERE id = :id_pedido";
                        $stmtUpd = $db->prepare($sqlUpd);
                        $stmtUpd->bindParam(':id_nota', $idNotaNueva);
                        $stmtUpd->bindParam(':id_pedido', $idPedido);
                        $stmtUpd->execute();
                        
                        // 2. Insertar en Creditos (siempre se crea crédito porque el adelanto no cubre todo)
                        $creditoModel = new \Lenovo\Dalu\Models\Creditos();
                        $idCredito = $creditoModel->insertarCredito($idNotaNueva, $porcentajeInicial, $montoInicial, $nroCuotas, $montoPorCuota, $frecuencia);
                        
                        if ($idCredito) {
                            // Crear la cuota inicial (0)
                            $creditoModel->insertarCuota($idCredito, 'inicial', 0, $montoInicial, date('Y-m-d H:i:s'));
                            
                            // Crear las cuotas restantes
                            $fecha_vencimiento = new \DateTime();
                            for ($i = 1; $i <= $nroCuotas; $i++) {
                                if ($frecuencia === 'semanal') $fecha_vencimiento->modify('+1 week');
                                elseif ($frecuencia === 'quincenal') $fecha_vencimiento->modify('+15 days');
                                elseif ($frecuencia === 'mensual') $fecha_vencimiento->modify('+1 month');
                                
                                $creditoModel->insertarCuota($idCredito, 'regular', $i, $montoPorCuota, $fecha_vencimiento->format('Y-m-d H:i:s'));
                            }
                            
                            // 3. Procesar los Pagos Iniciales y amortizar la cuota inicial
                            $montoAbonadoTotal = 0;
                            $pagosModel = new \Lenovo\Dalu\Models\Pagos();
                            
                            if (isset($_POST['id_metodo_pago']) && is_array($_POST['id_metodo_pago'])) {
                                foreach ($_POST['id_metodo_pago'] as $k => $id_metodo) {
                                    $monto = (float)($_POST['monto_ingresado'][$k] ?? 0);
                                    $moneda = $_POST['moneda'][$k] ?? 'USD';
                                    $referencia = $_POST['referencia'][$k] ?? '';
                                    
                                    if ($monto > 0) {
                                        $tasa = ($moneda === 'VES') ? $tasaActual['valor'] : 1;
                                        $montoBs = ($moneda === 'VES') ? $monto : ($monto * $tasaActual['valor']);
                                        $montoUsd = ($moneda === 'VES') ? ($monto / $tasaActual['valor']) : $monto;
                                        
                                        $pagosModel->setIdNotaEntrega($idNotaNueva)
                                                   ->setIdMetodoPago($id_metodo)
                                                   ->setMontoBs($montoBs)
                                                   ->setMontoUsd($montoUsd)
                                                   ->setTasa($tasaActual['valor'])
                                                   ->setReferencia($referencia);
                                        $pagosModel->insert();
                                        $montoAbonadoTotal += $montoUsd;
                                    }
                                }
                            }
                            
                            // Actualizar la cuota inicial como pagada
                            $cuotas = $creditoModel->getCuotasPorCredito($idCredito);
                            foreach ($cuotas as $cuota) {
                                if ($cuota['nro_cuota'] == 0) {
                                    $nuevoRestante = $cuota['monto_restante'] - $montoAbonadoTotal;
                                    if ($nuevoRestante < 0) $nuevoRestante = 0;
                                    $creditoModel->actualizarMontoRestanteCuota($cuota['id'], $nuevoRestante);
                                    break;
                                }
                            }
                        }
                    }
                }

                $_SESSION['success'] = 'Pedido de cliente registrado con su Nota de Entrega y pagos exitosamente.';
            } else {
                $_SESSION['error'] = 'Error al registrar el pedido de cliente.';
            }

            header('Location: ?c=servicios&accion=view');
            exit();
            break;
        default:
            http_response_code(404);
            require_once __DIR__ . '/../Views/errors/404.php';
            break;
    }