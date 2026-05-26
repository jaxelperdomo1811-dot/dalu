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
                $_SESSION['success'] = "Producto registrado exitosamente.";
            } else {
                $_SESSION['error'] = "Error al registrar el producto.";
            }
            header("Location: ?c=productos&accion=view");
            exit();
            break;
        case "update":
            $producto = new Productos();
            $producto->setId($_POST['id'])
                        ->setIdCategoria($_POST['id_categoria'])
                        ->setNombre($_POST['nombre'])
                        ->setDescripcion($_POST['descripcion'])
                        ->setPrecio($_POST['precio']);
            if ($producto->update()) {
                $_SESSION['success'] = "Producto actualizado exitosamente.";
            } else {
                $_SESSION['error'] = "Error al actualizar el producto.";
            }
            header("Location: ?c=productos&accion=view");
            exit();
            break;
        case "delete":
            $producto = new Productos();
            $producto->setId($_POST['id']);
            if ($producto->delete()) {
                $_SESSION['success'] = "Producto inhabilitado exitosamente.";
            } else {
                $_SESSION['error'] = "Error al inhabilitar el producto.";
            }
            header("Location: ?c=productos&accion=view");
            exit();
            break;
        case "active":
            $producto = new Productos();
            $producto->setId($_POST['id']);
            if ($producto->activate()) {
                $_SESSION['success'] = "Producto activado exitosamente.";
            } else {
                $_SESSION['error'] = "Error al activar el producto.";
            }
            header("Location: ?c=productos&accion=view");
            exit();
            break;
        default:
            http_response_code(404);
            require_once __DIR__ . "/../Views/errors/404.php";
            break;
    }