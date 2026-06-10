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
            try {
                if ($usuario->insert()) {
                    $_SESSION['success'] = "Usuario registrado exitosamente.";
                } else {
                    $_SESSION['error'] = "Error al registrar el usuario.";
                }
            } catch (\PDOException $e) {
                if ($e->getCode() == 23000) {
                    $_SESSION['error'] = "Error: Ya existe un usuario con este nombre de usuario.";
                } else {
                    $_SESSION['error'] = "Error de base de datos: " . $e->getMessage();
                }
            }
            header("Location: ?c=usuarios&accion=view");
            exit();
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

            try {
                if ($usuario->update()) {
                    $_SESSION['success'] = "Usuario actualizado exitosamente.";
                } else {
                    $_SESSION['error'] = "Error al actualizar el usuario.";
                }
            } catch (\PDOException $e) {
                if ($e->getCode() == 23000) {
                    $_SESSION['error'] = "Error: Ya existe otro usuario con este nombre de usuario.";
                } else {
                    $_SESSION['error'] = "Error de base de datos: " . $e->getMessage();
                }
            }
            header("Location: ?c=usuarios&accion=view");
            exit();
            break;
        case "delete":
            $usuario = new Usuarios();
            $usuario->setId($_POST['id']);
            if ($usuario->delete()) {
                $_SESSION['success'] = "Usuario inhabilitado exitosamente.";
            } else {
                $_SESSION['error'] = "Error al inhabilitar el usuario.";
            }
            header("Location: ?c=usuarios&accion=view");
            exit();
            break;
        case "active":
            $usuario = new Usuarios();
            $usuario->setId($_POST['id']);
            if ($usuario->active()) {
                $_SESSION['success'] = "Usuario activado exitosamente.";
            } else {
                $_SESSION['error'] = "Error al activar el usuario.";
            }
            header("Location: ?c=usuarios&accion=view");
            exit();
            break;
        default:
            http_response_code(404);
            require_once __DIR__ . "/../Views/errors/404.php";
            break;
    }