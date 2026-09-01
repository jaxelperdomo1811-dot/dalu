<?php
namespace Lenovo\Dalu\Controllers;

use Lenovo\Dalu\Models\Ajustes;
use Lenovo\Dalu\Models\Tasa;

$accion = $_GET['accion'] ?? $_POST['accion'] ?? 'view';

switch ($accion) {
    case 'view':
        // Cargar todas las tasas existentes
        $tasaModel = new Tasa();
        $tasaBcv = $tasaModel->getLatest('BCV');
        $tasaZelle = $tasaModel->getLatest('Zelle');

        // Cargar todos los ajustes
        $ajustesModel = new Ajustes();
        $configuraciones = $ajustesModel->getAll();

        require_once __DIR__ . '/../Views/V_Ajustes.php';
        break;

    case 'guardarTasa':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $valor = $_POST['valor'] ?? '';

            if ($nombre && is_numeric($valor)) {
                $tasaModel = new Tasa();
                $tasaModel->setNombre($nombre);
                $tasaModel->setValor(floatval($valor));

                if ($tasaModel->insert()) {
                    $_SESSION['success'] = "Tasa {$nombre} registrada exitosamente.";
                } else {
                    $_SESSION['error'] = "Error al registrar la tasa {$nombre}.";
                }
            } else {
                $_SESSION['error'] = "Datos inválidos para la tasa.";
            }
        }
        header('Location: ?c=Ajustes&accion=view');
        exit;

    case 'guardarAjustes':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ajustes = $_POST['ajustes'] ?? [];
            $ajustesModel = new Ajustes();
            
            $exito = true;
            foreach ($ajustes as $clave => $valor) {
                if (is_numeric($valor)) {
                    $ajustesModel->setClave($clave);
                    $ajustesModel->setValor(floatval($valor));
                    
                    if (!$ajustesModel->update()) {
                        $exito = false;
                    }
                }
            }

            if ($exito) {
                $_SESSION['success'] = "Ajustes globales actualizados exitosamente.";
            } else {
                $_SESSION['error'] = "Ocurrió un error al actualizar algunos ajustes.";
            }
        }
        header('Location: ?c=Ajustes&accion=view');
        exit;

    default:
        http_response_code(404);
        require_once __DIR__ . '/../Views/errors/404.php';
        break;
}
