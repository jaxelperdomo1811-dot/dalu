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
            $_SESSION['success'] = "Rol registrado exitosamente.";
        } else {
            $_SESSION['error'] = "Error al registrar el rol.";
        }
        header("Location: ?c=usuarios&accion=view&tab=roles");
        exit();
        break;

    case 'update':
        $rol = new Roles();
        $rol->setId($_POST['id']);
        $rol->setNombre($_POST['nombre']);
        $rol->setDescripcion($_POST['descripcion']);

        if ($rol->update()) {
            $_SESSION['success'] = "Rol actualizado exitosamente.";
        } else {
            $_SESSION['error'] = "Error al actualizar el rol.";
        }
        header("Location: ?c=usuarios&accion=view&tab=roles");
        exit();
        break;

    case 'delete':
        $rol = new Roles();
        $rol->setId($_POST['id']);

        if ($rol->delete()) {
            $_SESSION['success'] = "Rol inhabilitado exitosamente.";
        } else {
            $_SESSION['error'] = "Error al inhabilitar el rol.";
        }
        header("Location: ?c=usuarios&accion=view&tab=roles");
        exit();
        break;

    case 'active':
        $rol = new Roles();
        $rol->setId($_POST['id']);

        if ($rol->activate()) {
            $_SESSION['success'] = "Rol habilitado exitosamente.";
        } else {
            $_SESSION['error'] = "Error al habilitar el rol.";
        }
        header("Location: ?c=usuarios&accion=view&tab=roles");
        exit();
        break;

    default:
        http_response_code(404);
        require_once __DIR__ . "/../Views/errors/404.php";
        break;
}
