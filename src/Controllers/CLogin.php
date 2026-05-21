<?php
    namespace Lenovo\Dalu\Controllers;
    use Lenovo\Dalu\Models\Usuarios;

    $accion = $_GET['accion'] ?? $_POST['accion'] ?? 'view';

    switch($accion) {
        case "view":
            require_once __DIR__ . "/../Views/V_login.php";
            break;
        
        case "login":
            $usuario = $_POST['usuario'] ?? '';
            // El formulario de V_login manda "password" en el input
            $clave = $_POST['password'] ?? $_POST['clave'] ?? '';
            
            // Hashear con sha256
            $clave_hash = hash('sha256', $clave);

            $model = new Usuarios();
            $result = $model->login($usuario, $clave_hash);
            
            if ($result) {
                session_start();
                // Almacenando nombre, id y rol en variables de sesion
                $_SESSION['id'] = $result['id'];
                $_SESSION['nombre'] = $result['nombre'];
                $_SESSION['rol'] = $result['rol'];
                header("Location: index.php");
                exit();
            } else {
                $error = "Usuario o contraseña incorrectos.";
                require_once __DIR__ . "/../Views/V_login.php";
            }
            break;
            
        case "Logout":
        case "logout":
            session_start();
            session_unset();
            session_destroy();
            header("Location: index.php");
            break;
        
        default:
            http_response_code(404);
            require_once __DIR__ . "/../Views/errors/404.php";
            break;
    }