<?php
namespace Lenovo\Dalu\Controllers;
use Lenovo\Dalu\Models\Categorias;

$accion = $_GET['accion'] ?? $_POST['accion'] ?? 'view';

switch ($accion) {
    case "view":
        // View is handled inside CProductos.php, so this is unused, but we keep it just in case
        header("Location: ?c=productos&accion=view&tab=categorias");
        exit();
        break;

    case "insert":
        $categorias = new Categorias();
        $categorias->setNombre($_POST['nombre'] ?? null)
                   ->setDescripcion($_POST['descripcion'] ?? null);
        try {
            if ($categorias->insert()) {
                $_SESSION['success'] = "Categoría registrada exitosamente.";
            } else {
                $_SESSION['error'] = "Error al registrar la categoría.";
            }
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000) {
                $_SESSION['error'] = "Error: Ya existe una categoría con este nombre.";
            } else {
                $_SESSION['error'] = "Error de base de datos: " . $e->getMessage();
            }
        }
        header("Location: ?c=productos&accion=view&tab=categorias");
        exit();
        break;

    case "update":
        $categorias = new Categorias();
        $categorias->setId($_POST['id'] ?? null)
                   ->setNombre($_POST['nombre'] ?? null)
                   ->setDescripcion($_POST['descripcion'] ?? null);
        try {
            if ($categorias->update()) {
                $_SESSION['success'] = "Categoría actualizada exitosamente.";
            } else {
                $_SESSION['error'] = "Error al actualizar la categoría.";
            }
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000) {
                $_SESSION['error'] = "Error: Ya existe una categoría con este nombre.";
            } else {
                $_SESSION['error'] = "Error de base de datos: " . $e->getMessage();
            }
        }
        header("Location: ?c=productos&accion=view&tab=categorias");
        exit();
        break;

    case "delete":
        $categorias = new Categorias();
        $categorias->setId($_POST['id'] ?? null);
        if ($categorias->delete()) {
            $_SESSION['success'] = "Categoría inhabilitada exitosamente.";
        } else {
            $_SESSION['error'] = "Error al inhabilitar la categoría.";
        }
        header("Location: ?c=productos&accion=view&tab=categorias");
        exit();
        break;

    case "active":
        $categorias = new Categorias();
        $categorias->setId($_POST['id'] ?? null);
        if ($categorias->activate()) {
            $_SESSION['success'] = "Categoría activada exitosamente.";
        } else {
            $_SESSION['error'] = "Error al activar la categoría.";
        }
        header("Location: ?c=productos&accion=view&tab=categorias");
        exit();
        break;

    default:
        http_response_code(404);
        require_once __DIR__ . "/../Views/errors/404.php";
        break;
}