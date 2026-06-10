<?php
namespace Lenovo\TiendaDalu\Controllers;

use Lenovo\Dalu\Models\Despachos;

$accion = $_GET['accion'] ?? $_POST['accion'] ?? 'view';

switch($accion) {
    case "view":
        $modeloDespachos = new Despachos();
        $despachos = $modeloDespachos->search();
        
        require_once __DIR__ . "/../Views/V_Despachos.php";
        break;
        
    case "insert":
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $modeloDespachos = new Despachos();
            $modeloDespachos->setIdNotaEntrega($_POST['id_nota_entrega'] ?? null);
            $modeloDespachos->setNumeroDespacho('DSP-' . time());
            $modeloDespachos->setFechaDespacho(date('Y-m-d'));
            $modeloDespachos->setEstado('enviado'); // Si se crea el despacho asume que ya se envió o está pendiente
            
            if (!empty($_POST['id_nota_entrega']) && $modeloDespachos->insert()) {
                $_SESSION['success'] = "Despacho creado y vinculado a la nota de entrega.";
            } else {
                $_SESSION['error'] = "Error al registrar el despacho.";
            }
            
            // Recargar vista (se puede devolver a Notas o Despachos, dependiendo de donde vino)
            $referer = $_SERVER['HTTP_REFERER'] ?? '?c=Despacho';
            header("Location: " . $referer);
            exit;
        }
        break;
        
    case "detalles":
        if (isset($_GET['id'])) {
            $modeloDespachos = new Despachos();
            $detalles = $modeloDespachos->getDetallesByDespacho($_GET['id']);
            echo json_encode($detalles);
        }
        break;

    case "cambiarEstado":
        if (isset($_POST['id']) && isset($_POST['estado'])) {
            $modeloDespachos = new Despachos();
            $modeloDespachos->setId($_POST['id']);
            $modeloDespachos->setEstado($_POST['estado']);
            $modeloDespachos->updateEstado();
            header("Location: ?c=Despacho");
            exit;
        }
        break;

    default:
        http_response_code(404);
        require_once __DIR__ . "/../Views/errors/404.php";
        break;
}