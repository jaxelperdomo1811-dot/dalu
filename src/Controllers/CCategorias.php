<?php
    namespace Lenovo\Dalu\Controllers;
    use Lenovo\Dalu\Models\Categorias;
    $accion = $_GET['accion'] ?? $_POST['accion'] ?? 'view';

        switch($accion) {
        case "view":
            break;
        case "insert":
            $categorias = new Categorias();
            $categorias->setNombre($_POST['nombre'])
                        ->setDescripcion($_POST['descripcion']);
            if ($categorias->insert()) {
                header("Location: ?c=productos&accion=view");
            } else {
                echo "Error al insertar el producto.";
            }
            break;
        case "update":
            $categorias = new Categorias();
            $categorias->setId($_POST['id'])
                        ->setNombre($_POST['nombre'])
                        ->setDescripcion($_POST['descripcion']);
            if ($categorias->update()) {
                header("Location: ?c=productos&accion=view");
            } else {
                echo "Error al actualizar la categoría.";
            }
            break;
        case "delete":
            $categorias = new Categorias();
            $categorias->setId($_POST['id']);
            if ($categorias->delete()) {
                header("Location: ?c=productos&accion=view");
            } else {
                echo "Error al eliminar la categoría.";
            }
            break;
        case "active":
            $categorias = new Categorias();
            $categorias->setId($_POST['id']);
            if ($categorias->activate()) {
                header("Location: ?c=productos&accion=view");
            } else {
                echo "Error al activar la categoría.";
            }
            break;
        default:
            http_response_code(404);
            require_once __DIR__ . "/../Views/errors/404.php";
            break;
    }