<?php
    namespace Lenovo\Dalu\Controllers;
    use Lenovo\Dalu\Models\Proveedores;
    use Lenovo\Dalu\Models\Entradas;
    use Lenovo\Dalu\Models\Productos;

    // Incluir archivos de los modelos si el autoload falla
    require_once __DIR__ . '/../Models/Proveedores.php';
    require_once __DIR__ . '/../Models/Entradas.php';
    require_once __DIR__ . '/../Models/Productos.php';

    $accion = $_GET['accion'] ?? $_POST['accion'] ?? 'view';

    switch ($accion) {
        case 'view':
            $proveedores = (new Proveedores())->search();
            $proveedoresInactivos = (new Proveedores())->searchInactive();
            
            // Cargar datos para el módulo de entradas
            $entradasLista = (new Entradas())->search();
            $productosDisponibles = (new Productos())->search();

            require_once __DIR__ . '/../Views/V_Proveedores.php';
            break;
        case 'insert':
            $proveedor = new Proveedores();
            $proveedor->setNombre($_POST['nombre'] ?? null)
                      ->setRif($_POST['rif'] ?? null)
                      ->setTelefono($_POST['telefono'] ?? null)
                      ->setEmail($_POST['email'] ?? null)
                      ->setDireccion($_POST['direccion'] ?? null);
            if ($proveedor->insert()) {
                $success = "Proveedores registrado exitosamente.";
                header("Location: ?c=proveedores&accion=view");
                exit();
            } else {
                $error = "Error al insertar el proveedor.";
            }
            break;
        case 'update':
            $proveedor = new Proveedores();
            $proveedor->setId($_POST['id'] ?? null)
                      ->setNombre($_POST['nombre'] ?? null)
                      ->setRif($_POST['rif'] ?? null)
                      ->setTelefono($_POST['telefono'] ?? null)
                      ->setEmail($_POST['email'] ?? null)
                      ->setDireccion($_POST['direccion'] ?? null);
            if ($proveedor->update()) {
                $success = "Proveedor actualizado exitosamente.";
                header("Location: ?c=proveedores&accion=view");
                exit();
            } else {
                $error = "Error al actualizar el proveedor.";
            }
            break;
        case 'delete':
            $proveedor = new Proveedores();
            $proveedor->setId($_POST['id'] ?? null);
            if ($proveedor->delete()) {
                $success = "Proveedor eliminado exitosamente.";
                header("Location: ?c=proveedores&accion=view");
                exit();
            } else {
                $error = "Error al eliminar el proveedor.";
            }
            break;
        case 'active':
            $proveedor = new Proveedores();
            $proveedor->setId($_POST['id'] ?? null);
            if ($proveedor->activate()) {
                $success = "Proveedor activado exitosamente.";
                header("Location: ?c=proveedores&accion=view");
                exit();
            } else {
                $error = "Error al activar el proveedor.";
            }
            break;
        default:
            http_response_code(404);
            require_once __DIR__ . '/../Views/errors/404.php';
            break;
    }