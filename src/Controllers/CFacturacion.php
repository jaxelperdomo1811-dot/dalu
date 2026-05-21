<?php
    namespace Lenovo\Dalu\Controllers;
    use Lenovo\Dalu\Models\Productos;

    $accion = $_GET['accion'] ?? $_POST['accion'] ?? 'view';

    switch($accion) {
        case "view":
            $productos = (new Productos())->search();
            require_once __DIR__ . "/../Views/V_Facturacion.php";
            break;
        default:
            http_response_code(404);
            require_once __DIR__ . "/../Views/errors/404.php";
            break;
    }