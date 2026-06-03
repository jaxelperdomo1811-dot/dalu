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
            $productos = (new Productos())->search();
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

            // Procesar detalles opcionales
            $productosModel = new Productos();
            $detalles = [];
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

                        $detalles[] = [
                            'tipo' => 'cliente',
                            'imagen' => $imagenRuta,
                            'link' => trim($d['link'] ?? ''),
                            'estado' => $d['estado'] ?? 'pendiente',
                            'nombre_producto' => trim($d['nombre_producto'] ?? '') ?: null,
                            'id_producto' => $d['id_producto'] ?? null,
                            'cantidad' => !empty($d['cantidad']) ? (int) $d['cantidad'] : 1,
                            'precio_unitario' => null,
                            'descripcion_producto' => null,
                            'id_variante' => null,
                        ];
                    }
                }
            }
            $pedido->setDetalles($detalles);

            if ($pedido->insert()) {
                $_SESSION['success'] = 'Pedido de cliente registrado correctamente.';
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