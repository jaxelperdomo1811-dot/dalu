<?php
namespace Lenovo\Dalu\Controllers;
use Lenovo\Dalu\Models\Usuarios;

$accion = $_GET['accion'] ?? $_POST['accion'] ?? 'view';

switch($accion) {
    case "view":
        $preguntas_seguridad = (new Usuarios())->searchIdPregunta_S();
        require_once __DIR__ . "/../Views/V_RecoverPass.php";
        break;
    case "recovery":
        $usuario = $_POST['usuario_recuperacion'] ?? '';
        $pregunta = $_POST['pregunta_seguridad'] ?? '';
        $respuesta = $_POST['respuesta_seguridad'] ?? '';
        $nuevaClave = $_POST['nueva_clave'] ?? '';
        $confirmarClave = $_POST['confirmar_clave'] ?? '';

        if (empty($usuario) || empty($pregunta) || empty($respuesta) || empty($nuevaClave) || empty($confirmarClave)) {
            $error = "Todos los campos son obligatorios.";
            require_once __DIR__ . "/../Views/V_RecoverPass.php";
            break;
        }

        if ($nuevaClave !== $confirmarClave) {
            $error = "La contraseña y la verificación no coinciden.";
            require_once __DIR__ . "/../Views/V_RecoverPass.php";
            break;
        }

        $model = new Usuarios();
        $user = $model->findByUsuario($usuario);
        if (!$user) {
            $error = "Usuario no encontrado.";
            require_once __DIR__ . "/../Views/V_RecoverPass.php";
            break;
        }

        $userId = $user['id'];
        $preguntaId = $model->getPreguntaByKey($pregunta);
        if (!$preguntaId) {
            $error = "Pregunta de seguridad inválida.";
            require_once __DIR__ . "/../Views/V_RecoverPass.php";
            break;
        }

        $respuestas = $model->searchPregunta_SById($userId);
        $stored = null;
        foreach ($respuestas as $r) {
            if ($r['id_pregunta'] == $preguntaId) {
                $stored = $r['respuesta_hash'];
                break;
            }
        }

        if ($stored === null) {
            $error = "No se encontró la respuesta de seguridad para ese usuario.";
            require_once __DIR__ . "/../Views/V_RecoverPass.php";
            break;
        }

        $respuestaHash = hash('sha256', trim($respuesta));
        if ($stored !== $respuestaHash) {
            $error = "La respuesta de seguridad no coincide.";
            require_once __DIR__ . "/../Views/V_RecoverPass.php";
            break;
        }

        $nuevaClaveHash = hash('sha256', $nuevaClave);
        if ($model->updatePassword($userId, $nuevaClaveHash)) {
            $success = "Contraseña restablecida correctamente. Ahora puede iniciar sesión con su nueva contraseña.";
        } else {
            $error = "No se pudo actualizar la contraseña. Intente nuevamente.";
        }

        require_once __DIR__ . "/../Views/V_RecoverPass.php";
        break;

    default:
        http_response_code(404);
        require_once __DIR__ . "/../Views/errors/404.php";
        break;
}