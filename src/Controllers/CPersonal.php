<?php
    namespace Lenovo\TiendaDalu\Controllers;

    $accion = $_GET['accion'] ?? $_POST['accion'] ?? 'view';

    switch($accion) {
        case "view":
            require_once __DIR__ . "/../Views/V_Personal.php";
            break;
        default:
            http_response_code(404);
            require_once __DIR__ . "/../Views/errors/404.php";
            break;
    }