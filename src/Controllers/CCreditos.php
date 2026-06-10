<?php
namespace Lenovo\Dalu\Controllers;

use Lenovo\Dalu\Models\Creditos;
use Lenovo\Dalu\Models\MetodosPago;
use Lenovo\Dalu\Models\Tasa;

$accion = $_GET['accion'] ?? $_POST['accion'] ?? 'view';

switch ($accion) {
    case 'view':
        $creditosModel = new Creditos();
        $creditos = $creditosModel->getAll();
        
        // Adjuntamos las cuotas a cada crédito
        foreach ($creditos as &$credito) {
            $credito['cuotas'] = $creditosModel->getCuotasPorCredito($credito['id']);
        }
        
        $metodosPagoModel = new MetodosPago();
        $metodosPago = $metodosPagoModel->getActivos();

        $tasaModel = new Tasa();
        $tasaActual = $tasaModel->getLatest();
        
        require_once __DIR__ . '/../Views/V_Creditos.php';
        break;

    case 'detalles_ajax':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $creditosModel = new Creditos();
            $cuotas = $creditosModel->getCuotasPorCredito($id);
            
            echo json_encode([
                'success' => true,
                'cuotas' => $cuotas
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
