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
            $preguntas_seguridad = (new Usuarios())->searchIdPregunta_S();
            require_once __DIR__ . "/../Views/V_Usuarios.php";
            break;
        case "insert":
            $usuario = new Usuarios();
            $usuario->setNombre($_POST['nombre'])
                    ->setRol($_POST['id_rol'])
                    ->setUsuario($_POST['usuario'])
                    ->setClave(hash('sha256', $_POST['clave']))
                    ->setPreguntaS1($_POST['pregunta_s_1'])
                    ->setRespuestaS1(hash('sha256', $_POST['respuesta_s_1']))
                    ->setPreguntaS2($_POST['pregunta_s_2'])
                    ->setRespuestaS2(hash('sha256', $_POST['respuesta_s_2']))
                    ->setPreguntaS3($_POST['pregunta_s_3'])
                    ->setRespuestaS3(hash('sha256', $_POST['respuesta_s_3']));
            if ($usuario->insert()) {
                $success = "Usuario registrado exitosamente.";
                header("Location: ?c=usuarios&accion=view");
            } else {
                $error = "Error al insertar el usuario.";
            }
            break;
        case "update":
            $usuario = new Usuarios();
            $usuario->setId($_POST['id'])
                    ->setNombre($_POST['nombre'])
                    ->setRol($_POST['id_rol'])
                    ->setUsuario($_POST['usuario']);
            
            if (!empty($_POST['clave'])) {
                $usuario->setClave(hash('sha256', $_POST['clave']));
            } else {
                $existing = $usuario->searchId($_POST['id']);
                $usuario->setClave($existing['clave']);
            }

            if ($usuario->update()) {
                $success = "Usuario actualizado exitosamente.";
                header("Location: ?c=usuarios&accion=view");
            } else {
                $error = "Error al actualizar el usuario.";
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
                $success = "Usuario activado exitosamente.";
                header("Location: ?c=usuarios&accion=view");
            } else {
                $error = "Error al activar el usuario.";
            }
            break;
        default:
            http_response_code(404);
            require_once __DIR__ . "/../Views/errors/404.php";
            break;
    }