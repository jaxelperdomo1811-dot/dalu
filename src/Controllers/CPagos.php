<?php
namespace Lenovo\TiendaDalu\Controllers;

use Lenovo\Dalu\Models\Pagos;
use Lenovo\Dalu\Models\MetodosPago;
use Lenovo\Dalu\Models\Tasa;
use Lenovo\Dalu\Models\NotasEntrega;
use Lenovo\Dalu\Models\Creditos;

$accion = $_GET['accion'] ?? $_POST['accion'] ?? '';

switch($accion) {
    case 'insert':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_nota_entrega = $_POST['id_nota_entrega'] ?? null;
            $metodos = $_POST['id_metodo_pago'] ?? [];
            $monedas = $_POST['moneda'] ?? [];
            $montos = $_POST['monto_ingresado'] ?? [];
            $referencias = $_POST['referencia'] ?? [];
            
            $tasaModel = new Tasa();
            $tasa_actual = $tasaModel->getLatest();
            $valor_tasa = $tasa_actual ? floatval($tasa_actual['valor']) : 1;
            
            $modeloPagos = new Pagos();
            $exitos = 0;
            
            if ($id_nota_entrega && is_array($metodos)) {
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

                        if ($modeloPagos->insert($id_nota_entrega, $id_metodo_pago, $monto_bs, $monto_usd, $valor_tasa, $referencia)) {
                            $exitos++;
                            
                            // Logica para deducir cuotas si es credito
                            $notasModel = new NotasEntrega();
                            $nota = $notasModel->getById($id_nota_entrega);
                            if ($nota && $nota['tipo'] === 'credito') {
                                $creditoModel = new Creditos();
                                $credito = $creditoModel->getCreditoPorNota($id_nota_entrega);
                                if ($credito) {
                                    $pago_restante = $monto_usd;
                                    $cuotas_pendientes = $creditoModel->obtenerCuotasPendientes($credito['id']);
                                    foreach ($cuotas_pendientes as $cuota) {
                                        if ($pago_restante <= 0.01) break; // Tolerancia
                                        
                                        $deuda = floatval($cuota['monto_restante']);
                                        if ($pago_restante >= $deuda) {
                                            // Paga toda esta cuota
                                            $creditoModel->actualizarMontoRestanteCuota($cuota['id'], 0);
                                            $pago_restante -= $deuda;
                                        } else {
                                            // Abono parcial
                                            $nuevo_restante = $deuda - $pago_restante;
                                            $creditoModel->actualizarMontoRestanteCuota($cuota['id'], $nuevo_restante);
                                            $pago_restante = 0;
                                        }
                                    }
                                    
                                    $pendientes = $creditoModel->obtenerCuotasPendientes($credito['id']);
                                    if (count($pendientes) === 0) {
                                        $creditoModel->actualizarEstadoCredito($credito['id'], 'pagado');
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if ($exitos > 0) {
                $_SESSION['success'] = "Se registraron $exitos pago(s) correctamente.";
            } else {
                $_SESSION['error'] = "No se pudieron registrar los pagos o datos incompletos.";
            }
            
            $referer = $_SERVER['HTTP_REFERER'] ?? '?c=Notas';
            header("Location: " . $referer);
            exit;
        }
        break;

    default:
        http_response_code(404);
        echo "Acción no encontrada";
        break;
}
