<?php
    namespace Lenovo\Dalu\Controllers;
    use Lenovo\Dalu\Models\Productos;
    use Lenovo\Dalu\Models\Categorias;
    $accion = $_GET['accion'] ?? $_POST['accion'] ?? 'view';

    switch($accion) {
        case "view":
            $productos = (new Productos())->search();
            $productosInactivos = (new Productos())->searchInactive();
            $categorias = (new Categorias())->search();
            $categoriasInactivas = (new Categorias())->searchInactive();
            require_once __DIR__ . "/../Views/V_Productos.php";
            break;
        case "insert":
            $producto = new Productos();
            $producto->setIdCategoria($_POST['id_categoria'])
                        ->setNombre($_POST['nombre'])
                        ->setDescripcion($_POST['descripcion'])
                        ->setPrecio($_POST['precio']);
            if ($producto->insert()) {
                $success = "Producto registrado exitosamente.";
                header("Location: ?c=productos&accion=view");
            } else {
                $error = "Error al insertar el producto.";
            }
            break;
        case "update":
            $producto = new Productos();
            $producto->setId($_POST['id'])
                        ->setIdCategoria($_POST['id_categoria'])
                        ->setNombre($_POST['nombre'])
                        ->setDescripcion($_POST['descripcion'])
                        ->setPrecio($_POST['precio']);
            if ($producto->update()) {
                $success = "Producto actualizado exitosamente.";
                header("Location: ?c=productos&accion=view");
            } else {
                $error = "Error al actualizar el producto.";
            }
            break;
        case "delete":
            $producto = new Productos();
            $producto->setId($_POST['id']);
            if ($producto->delete()) {
                $success = "Producto eliminado exitosamente.";
                header("Location: ?c=productos&accion=view");
            } else {
                $error = "Error al eliminar el producto.";
            }
            break;
        case "active":
            $producto = new Productos();
            $producto->setId($_POST['id']);
            if ($producto->activate()) {
                $success = "Producto activado exitosamente.";
                header("Location: ?c=productos&accion=view");
            } else {
                $error = "Error al activar el producto.";
            }
            break;
        default:
            http_response_code(404);
            require_once __DIR__ . "/../Views/errors/404.php";
            break;
    }