<?php
namespace Lenovo\Dalu\Controllers;

use Lenovo\Dalu\Models\Roles;

$accion = $_GET['accion'] ?? $_POST['accion'] ?? '';

switch ($accion) {
    case 'insert':
        $rol = new Roles();
        $rol->setNombre($_POST['nombre']);
        $rol->setDescripcion($_POST['descripcion']);
        
        if ($rol->insert()) {
            $success = "Rol registrado exitosamente.";
            header("Location: ?c=usuarios&accion=view&tab=roles");
        } else {
            $error = "Error al registrar el rol.";
        }
        break;

    case 'update':
        $rol = new Roles();
        $rol->setId($_POST['id']);
        $rol->setNombre($_POST['nombre']);
        $rol->setDescripcion($_POST['descripcion']);

        if ($rol->update()) {
            $success = "Rol actualizado exitosamente.";
            header("Location: ?c=usuarios&accion=view&tab=roles");
        } else {
            $error = "Error al actualizar el rol.";
        }
        break;

    case 'delete':
        $rol = new Roles();
        $rol->setId($_POST['id']);

        if ($rol->delete()) {
            $success = "Rol inhabilitado exitosamente.";
            header("Location: ?c=usuarios&accion=view&tab=roles");
        } else {
            $error = "Error al inhabilitar el rol.";
        }
        break;

    case 'active':
        $rol = new Roles();
        $rol->setId($_POST['id']);

        if ($rol->activate()) {
            $success = "Rol habilitado exitosamente.";
            header("Location: ?c=usuarios&accion=view&tab=roles");
        } else {
            $error = "Error al habilitar el rol.";
        }
        break;

    default:
        http_response_code(404);
        require_once __DIR__ . "/../Views/errors/404.php";
        break;
}
