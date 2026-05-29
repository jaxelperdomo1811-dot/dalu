<?php
namespace Lenovo\TiendaDalu\Controllers;

use Lenovo\Dalu\Models\Despachos;
use Lenovo\Dalu\Models\Clientes;
use Lenovo\Dalu\Models\Productos;

$accion = $_GET['accion'] ?? $_POST['accion'] ?? 'view';

switch($accion) {
    case "view":
        $modeloDespachos = new Despachos();
        $despachos = $modeloDespachos->search();
        
        $modeloClientes = new Clientes();
        $clientes = $modeloClientes->search();
        
        $modeloProductos = new Productos();
        $productos = $modeloProductos->search();
        
        require_once __DIR__ . "/../Views/V_Despachos.php";
        break;
        
    case "insert":
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $modeloDespachos = new Despachos();
            $modeloDespachos->setIdCliente($_POST['id_cliente'] ?? null);
            $modeloDespachos->setNumeroDespacho($_POST['numero_despacho'] ?? 'DSP-' . time());
            $modeloDespachos->setFechaDespacho($_POST['fecha_despacho'] ?? date('Y-m-d'));
            
            $detalles = [];
            $total = 0;
            if (isset($_POST['detalles']) && is_array($_POST['detalles'])) {
                foreach ($_POST['detalles'] as $detalle) {
                    if (!empty($detalle['id_producto']) && !empty($detalle['cantidad']) && !empty($detalle['precio_unitario'])) {
                        $detalles[] = $detalle;
                        $total += ($detalle['cantidad'] * $detalle['precio_unitario']);
                    }
                }
            }
            
            $modeloDespachos->setTotal($total);
            $modeloDespachos->setDetalles($detalles);
            
            if ($modeloDespachos->insert()) {
                $success = "Despacho registrado correctamente.";
            } else {
                $error = "Error al registrar el despacho.";
            }
            
            // Recargar vista
            header("Location: ?c=Despacho&msg=success");
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