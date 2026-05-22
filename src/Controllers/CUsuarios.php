<?php
    namespace Lenovo\Dalu\Controllers;
    use Lenovo\Dalu\Models\Usuarios;
    use Lenovo\Dalu\Models\Roles;

    $accion = $_GET['accion'] ?? $_POST['accion'] ?? 'view';

    switch($accion) {
        case "view":
            $roles = (new Roles())->search();
            $rolesInactivos = (new Roles())->searchInactive();
            $usuarios = (new Usuarios())->search();
            $usuariosInactivos = (new Usuarios())->searchInactive();
            require_once __DIR__ . "/../Views/V_Usuarios.php";
            break;
        case "insert":
            $usuario = new Usuarios();
            $usuario->setNombre($_POST['nombre'])
                    ->setRol($_POST['rol'])
                    ->setUsuario($_POST['usuario'])
                    ->setClave($_POST['clave']);
            if ($usuario->insert()) {
                header("Location: ?c=usuarios&accion=view");
            } else {
                echo "Error al insertar el usuario.";
            }
            break;
        case "update":
            $usuario = new Usuarios();
            $usuario->setId($_POST['id'])
                    ->setNombre($_POST['nombre'])
                    ->setRol($_POST['rol'])
                    ->setUsuario($_POST['usuario'])
                    ->setClave($_POST['clave']);
            if ($usuario->update()) {
                header("Location: ?c=usuarios&accion=view");
            } else {
                echo "Error al actualizar el usuario.";
            }
            break;
        case "delete":
            $usuario = new Usuarios();
            $usuario->setId($_POST['id']);
            if ($usuario->delete()) {
                header("Location: ?c=usuarios&accion=view");
            } else {
                echo "Error al eliminar el usuario.";
            }
            break;
        case "active":
            $usuario = new Usuarios();
            $usuario->setId($_POST['id']);
            if ($usuario->active()) {
                header("Location: ?c=usuarios&accion=view");
            } else {
                echo "Error al activar el usuario.";
            }
            break;
        default:
            http_response_code(404);
            require_once __DIR__ . "/../Views/errors/404.php";
            break;
    }