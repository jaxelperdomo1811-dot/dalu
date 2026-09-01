<?php
namespace Lenovo\Dalu\Controllers;

use Lenovo\Dalu\Models\Pagos;
use Lenovo\Dalu\Models\MetodosPago;
use Lenovo\Dalu\Models\Tasa;
use Lenovo\Dalu\Models\NotasEntrega;

$accion = $_GET['accion'] ?? $_POST['accion'] ?? 'view';

switch($accion) {
    case 'view':
        $pagosModel = new Pagos();
        $notas = $pagosModel->getAgrupadosPorNota();
        
        require_once __DIR__ . '/../Views/V_Pagos.php';
        break;

    case 'ajaxGetPagos':
        header('Content-Type: application/json');
        $id_nota_entrega = $_GET['id_nota'] ?? null;
        if ($id_nota_entrega) {
            $pagosModel = new Pagos();
            $pagos = $pagosModel->getByNotaEntrega($id_nota_entrega);
            echo json_encode(['success' => true, 'pagos' => $pagos]);
        } else {
            echo json_encode(['success' => false, 'message' => 'ID de nota no proporcionado']);
        }
        exit;

    case 'ajaxUpdateEstado':
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $estado = $_POST['estado'] ?? null;
            
            if ($id && in_array($estado, ['por verificar', 'verificado', 'rechazado'])) {
                $pagosModel = new Pagos();
                $pagosModel->setId($id);
                $pagosModel->setEstado($estado);
                
                if ($pagosModel->updateEstado()) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error al actualizar el estado en BD']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        }
        exit;

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

                        $modeloPagos->setIdNotaEntrega($id_nota_entrega)
                                    ->setIdMetodoPago($id_metodo_pago)
                                    ->setMontoBs($monto_bs)
                                    ->setMontoUsd($monto_usd)
                                    ->setIdTasa($tasa_actual['id'] ?? 1)
                                    ->setReferencia($referencia);

                        if ($modeloPagos->insert()) {
                            $exitos++;

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
