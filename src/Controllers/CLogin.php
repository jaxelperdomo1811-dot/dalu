<?php
    namespace lenovo\tiendadalu\Controllers;
    use lenovo\tiendadalu\Models\Usuarios;

    $accion = $_GET['accion'] ?? $_POST['accion'] ?? 'view';

    switch($accion) {
        case "view":
            require_once __DIR__ . "/../Views/V_login.php";
            break;
        
        case "login":
            $usuario = $_POST['usuario'] ?? '';
            $clave = $_POST['clave'] ?? '';
            $model = new Usuarios();
            $result = $model->login($usuario, $clave);
            if ($result) {
                session_start();
                $_SESSION['user_id'] = $result['id'];
                $_SESSION['user_name'] = $result['nombre'];
                $_SESSION['user_role'] = $result['rol'];
                header("Location: index.php");
                exit();
            } else {
                http_response_code(401);
                require_once __DIR__ . "/../Views/errors/401.php";
            }
            break;
        
        default:
            http_response_code(404);
            require_once __DIR__ . "/../Views/errors/404.php";
            break;
    }