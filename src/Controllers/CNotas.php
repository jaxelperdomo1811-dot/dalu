<?php
namespace Lenovo\Dalu\Controllers;

use Lenovo\Dalu\Models\NotasEntrega;
use Lenovo\Dalu\Models\Clientes;
use Lenovo\Dalu\Models\Productos;
use Lenovo\Dalu\Models\Tasa;
use Lenovo\Dalu\Models\Creditos;
use Lenovo\Dalu\Models\Pagos;

$accion = $_GET['accion'] ?? $_POST['accion'] ?? 'view';

switch ($accion) {
    case 'view':
        $notasModel = new NotasEntrega();
        $notas_debito = $notasModel->getByTipo('debito');
        $notas_credito = $notasModel->getByTipo('credito');
        
        $clientes = (new Clientes())->search();
        
        $productosObj = new Productos();
        $productos = $productosObj->search();
        // Cargar variantes para que JS pueda usarlas
        foreach ($productos as &$prod) {
            $prod['variantes'] = $productosObj->getVariantesByProducto($prod['id']);
        }

        // Cargar tasa
        $tasaModel = new \Lenovo\Dalu\Models\Tasa();
        $tasaActual = $tasaModel->getLatest();
        
        // Cargar metodos de pago
        $metodosPagoModel = new \Lenovo\Dalu\Models\MetodosPago();
        $metodosPago = $metodosPagoModel->getActivos();

        // Cargar pagos de cada nota para saber monto pagado
        $pagosModel = new \Lenovo\Dalu\Models\Pagos();
        $creditoModel = new \Lenovo\Dalu\Models\Creditos();
        
        $procesarNotas = function(&$arrayNotas) use ($pagosModel, $creditoModel) {
            foreach ($arrayNotas as &$nota) {
                $nota['pagos'] = $pagosModel->getByNotaEntrega($nota['id']);
                $totalPagado = 0;
                foreach ($nota['pagos'] as $p) {
                    $totalPagado += floatval($p['monto_usd']);
                }
                $nota['total_pagado'] = $totalPagado;
                $nota['saldo_pendiente'] = max(0, $nota['total'] - $totalPagado);
                
                // Lógica para despacho:
                $nota['puede_despachar'] = false;
                if ($nota['estado'] === 'confirmado') {
                    if ($nota['tipo'] === 'credito') {
                        // Buscar cuota 0 (inicial)
                        $credito = $creditoModel->getCreditoPorNota($nota['id']);
                        if ($credito) {
                            $cuotas = $creditoModel->getCuotasPorCredito($credito['id']);
                            foreach ($cuotas as $cuota) {
                                if ($cuota['tipo_cuota'] === 'inicial') {
                                    if ($cuota['estado'] === 'pagado') {
                                        $nota['puede_despachar'] = true;
                                    }
                                    break;
                                }
                            }
                        }
                    } else {
                        if ($nota['saldo_pendiente'] <= 0) {
                            $nota['puede_despachar'] = true;
                        }
                    }
                }
            }
        };

        $procesarNotas($notas_debito);
        $procesarNotas($notas_credito);
        $notas_entrega = array_merge($notas_debito, $notas_credito);
        
        require_once __DIR__ . '/../Views/V_NotasEntrega.php';
        break;

    case 'insert':
        $idCliente = $_POST['id_cliente'] ?? null;
        
        // Lógica para registrar cliente si no existe
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
                    
                    $clienteModel->setNombre($n)->setApellido($a)->setCedula($cedulaCompleta)
                                 ->setTelefono(null)->setCorreo(null)->setDireccion(null);
                    if ($clienteModel->insert()) {
                        $nuevoCli = $clienteModel->getByCedula($cedulaCompleta);
                        $idCliente = $nuevoCli['id'] ?? null;
                    }
                }
            }
        }
        
        if (empty($idCliente)) {
            $_SESSION['error'] = 'Debe seleccionar un cliente o llenar los datos para registrarlo.';
            header('Location: ?c=Notas&accion=view');
            exit();
        }

        $estado = $_POST['estado'] ?? 'pendiente';
        $tipo = $_POST['tipo'] ?? 'debito';
        $observaciones = $_POST['observaciones'] ?? '';
        $fecha_pedido = date('Y-m-d H:i:s');

        // Procesar detalles
        $detalles = [];
        $total = 0;
        
        $id_variantes = $_POST['id_variante'] ?? [];
        $cantidades = $_POST['cantidad'] ?? [];
        $precios_unitarios = $_POST['precio_unitario'] ?? [];
        
        for ($i = 0; $i < count($id_variantes); $i++) {
            if (!empty($id_variantes[$i]) && !empty($cantidades[$i]) && !empty($precios_unitarios[$i])) {
                $cant = (int)$cantidades[$i];
                $precio = (float)$precios_unitarios[$i];
                $subtotal = $cant * $precio;
                $total += $subtotal;
                
                $detalles[] = [
                    'id_variante' => $id_variantes[$i],
                    'cantidad' => $cant,
                    'precio_unitario' => $precio,
                    'descripcion' => ''
                ];
            }
        }

        if (count($detalles) > 0) {
            $notasModel = new NotasEntrega();
            $notasModel->setIdCliente($idCliente)
                       ->setFechaPedido($fecha_pedido)
                       ->setEstado($estado)
                       ->setTipo($tipo)
                       ->setTotal($total)
                       ->setObservaciones($observaciones)
                       ->setDetalles($detalles);
            
            $idNotaNueva = $notasModel->insert();
            
            if ($idNotaNueva) {
                if ($tipo === 'credito') {
                    $porcentaje_inicial = (int)($_POST['porcentaje_inicial'] ?? 40);
                    $nro_cuotas = (int)($_POST['nro_cuotas'] ?? 1);
                    $frecuencia = $_POST['frecuencia'] ?? 'semanal';

                    $monto_cuota_inicial = $total * ($porcentaje_inicial / 100);
                    $restante = $total - $monto_cuota_inicial;
                    $monto_por_cuota = ($nro_cuotas > 0) ? ($restante / $nro_cuotas) : 0;

                    $creditoModel = new Creditos();
                    $idCredito = $creditoModel->insertarCredito($idNotaNueva, $porcentaje_inicial, $monto_cuota_inicial, $nro_cuotas, $monto_por_cuota, $frecuencia);
                    
                    if ($idCredito) {
                        // Insertar cuota inicial (0) vence el mismo dia
                        $creditoModel->insertarCuota($idCredito, 'inicial', 0, $monto_cuota_inicial, date('Y-m-d'));

                        // Insertar cuotas regulares
                        $dias_frecuencia = ['semanal' => 7, 'quincenal' => 15, 'mensual' => 30];
                        $dias = $dias_frecuencia[$frecuencia] ?? 7;
                        
                        $fecha_actual = date('Y-m-d');
                        for ($j = 1; $j <= $nro_cuotas; $j++) {
                            $fecha_vencimiento = date('Y-m-d', strtotime($fecha_actual . " + " . ($dias * $j) . " days"));
                            $creditoModel->insertarCuota($idCredito, 'regular', $j, $monto_por_cuota, $fecha_vencimiento);
                        }
                    }
                }

                // PROCESAR PAGOS AL CREAR LA NOTA
                $metodos = $_POST['id_metodo_pago'] ?? [];
                $monedas = $_POST['moneda'] ?? [];
                $montos = $_POST['monto_ingresado'] ?? [];
                $referencias = $_POST['referencia'] ?? [];
                
                if (!empty($metodos)) {
                    $tasaModel = new Tasa();
                    $tasa_actual = $tasaModel->getLatest();
                    $valor_tasa = $tasa_actual ? floatval($tasa_actual['valor']) : 1;
                    $modeloPagos = new Pagos();
                    
                    for ($i = 0; $i < count($metodos); $i++) {
                        $id_metodo_pago = $metodos[$i] ?? null;
                        $moneda = $monedas[$i] ?? 'USD';
                        $monto_ingresado = floatval($montos[$i] ?? 0);
                        $referencia = $referencias[$i] ?? '';
                        
                        if ($id_metodo_pago && $monto_ingresado > 0) {
                            $monto_usd = null;
                            $monto_bs = null;
                            
                            if ($moneda === 'USD') {
                                $monto_usd = $monto_ingresado;
                                $monto_bs = $monto_usd * $valor_tasa;
                            } else {
                                $monto_bs = $monto_ingresado;
                                $monto_usd = $valor_tasa > 0 ? ($monto_bs / $valor_tasa) : 0;
                            }

                            $modeloPagos->setIdNotaEntrega($idNotaNueva)
                                        ->setIdMetodoPago($id_metodo_pago)
                                        ->setMontoBs($monto_bs)
                                        ->setMontoUsd($monto_usd)
                                        ->setTasa($valor_tasa)
                                        ->setReferencia($referencia);

                            if ($modeloPagos->insert()) {
                                // Logica para deducir cuotas si es credito
                                if ($tipo === 'credito' && isset($idCredito)) {
                                    $pago_restante = $monto_usd;
                                    $cuotas_pendientes = $creditoModel->obtenerCuotasPendientes($idCredito);
                                    foreach ($cuotas_pendientes as $cuota) {
                                        if ($pago_restante <= 0.01) break;
                                        
                                        $deuda = floatval($cuota['monto_restante']);
                                        if ($pago_restante >= $deuda) {
                                            $creditoModel->actualizarMontoRestanteCuota($cuota['id'], 0);
                                            $pago_restante -= $deuda;
                                        } else {
                                            $nuevo_restante = $deuda - $pago_restante;
                                            $creditoModel->actualizarMontoRestanteCuota($cuota['id'], $nuevo_restante);
                                            $pago_restante = 0;
                                        }
                                    }
                                    
                                    $pendientes = $creditoModel->obtenerCuotasPendientes($idCredito);
                                    if (count($pendientes) === 0) {
                                        $creditoModel->actualizarEstadoCredito($idCredito, 'pagado');
                                    }
                                }
                            }
                        }
                    }
                }

                $_SESSION['success'] = 'Nota de entrega registrada correctamente junto con sus pagos iniciales.';
            } else {
                $_SESSION['error'] = 'Error al registrar la nota de entrega.';
            }
        } else {
            $_SESSION['error'] = 'Debe agregar al menos un detalle (producto con variante) a la nota de entrega.';
        }

        header('Location: ?c=Notas&accion=view');
        exit();
        break;

    case 'avanzarEstado':
        $id = $_POST['id'] ?? null;
        $nuevo_estado = $_POST['nuevo_estado'] ?? null;
        
        if ($id && $nuevo_estado) {
            $notasModel = new NotasEntrega();
            if ($notasModel->cambiarEstado($id, $nuevo_estado)) {
                $_SESSION['success'] = "Estado actualizado a $nuevo_estado.";
            } else {
                $_SESSION['error'] = 'Error al actualizar el estado.';
            }
        }
        header('Location: ?c=Notas&accion=view');
        exit();
        break;

    case 'cancelar':
        $id = $_POST['id'] ?? null;
        if ($id) {
            $notasModel = new NotasEntrega();
            if ($notasModel->cambiarEstado($id, 'cancelado')) {
                $_SESSION['success'] = 'Nota de entrega cancelada.';
            } else {
                $_SESSION['error'] = 'Error al cancelar la nota.';
            }
        }
        header('Location: ?c=Notas&accion=view');
        exit();
        break;

    case 'detalles_ajax':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $notasModel = new NotasEntrega();
            $detalles = $notasModel->getDetalles($id);
            $nota = $notasModel->getById($id);
            
            echo json_encode([
                'success' => true,
                'nota' => $nota,
                'detalles' => $detalles
            ]);
        } else {
            echo json_encode(['success' => false, 'error' => 'ID no proporcionado']);
        }
        exit();
        break;

    default:
        http_response_code(404);
        require_once __DIR__ . '/../Views/errors/404.php';
        break;
}