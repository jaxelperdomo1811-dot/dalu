<?php
    namespace Lenovo\Dalu\Controllers;
    use Lenovo\Dalu\Models\Proveedores;

    $accion = $_GET['accion'] ?? $_POST['accion'] ?? 'view';

    switch ($accion) {
        case 'view':
            $proveedores = (new Proveedores())->search();
            $proveedoresInactivos = (new Proveedores())->searchInactive();
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
                header("Location: ?c=proveedores&accion=view");
                exit();
            } else {
                echo "Error al insertar el proveedor.";
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
                header("Location: ?c=proveedores&accion=view");
                exit();
            } else {
                echo "Error al actualizar el proveedor.";
            }
            break;
        case 'delete':
            $proveedor = new Proveedores();
            $proveedor->setId($_POST['id'] ?? null);
            if ($proveedor->delete()) {
                header("Location: ?c=proveedores&accion=view");
                exit();
            } else {
                echo "Error al eliminar el proveedor.";
            }
            break;
        case 'active':
            $proveedor = new Proveedores();
            $proveedor->setId($_POST['id'] ?? null);
            if ($proveedor->activate()) {
                header("Location: ?c=proveedores&accion=view");
                exit();
            } else {
                echo "Error al activar el proveedor.";
            }
            break;
        default:
            http_response_code(404);
            require_once __DIR__ . '/../Views/errors/404.php';
            break;
    }