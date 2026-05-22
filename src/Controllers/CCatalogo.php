<?php
    namespace Lenovo\TiendaDalu\Controllers;

    $accion = $_GET['accion'] ?? $_POST['accion'] ?? 'view';

    switch($accion) {
        case "view":
            require_once __DIR__ . "/../Views/V_Catalogo.php";
            break;
        case "add":
            if (isset($_POST[""]) && $_POST[""]
            && $_POST[""] && $_POST[""]) {
                // Lógica para agregar un nuevo producto al catálogo
                // ...
                header("Location: ?c=CCatalogo&accion=view&success=1");
            } else {
                header("Location: ?c=CCatalogo&accion=view&error=1");
            }
            break;
            
        default:
            http_response_code(404);
            require_once __DIR__ . "/../Views/errors/404.php";
            break;
    }