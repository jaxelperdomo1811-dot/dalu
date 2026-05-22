<?php
    namespace Lenovo\Dalu\Controllers;
    use Lenovo\Dalu\Models\Clientes;

    $accion = $_GET['accion'] ?? $_POST['accion'] ?? 'view';

    switch ($accion) {
        case 'view':
            $clientes = (new Clientes())->search();
            $clientesInactivos = (new Clientes())->searchInactive();
            require_once __DIR__ . '/../Views/V_cliente.php';
            break;
        case 'insert':

            $cliente = new Clientes();
            $cliente->setNombre($_POST['nombre'] ?? null)
                    ->setApellido($_POST['apellido'] ?? null)
                    ->setCorreo($_POST['correo'] ?? null)
                    ->setTelefono($_POST['telefono'] ?? null)
                    ->setDireccion($_POST['direccion'] ?? null)
                    ->setCedula($_POST['cedula'] ?? null);
            if ($cliente->insert()) {
                header("Location: ?c=clientes&accion=view");
                exit();
            } else {
                echo "Error al insertar el cliente.";
            }
            break;
        case 'update':
            $cliente = new Clientes();
            $cliente->setId($_POST['id'] ?? null)
                    ->setNombre($_POST['nombre'] ?? null)
                    ->setApellido($_POST['apellido'] ?? null)
                    ->setCorreo($_POST['correo'] ?? null)
                    ->setTelefono($_POST['telefono'] ?? null)
                    ->setDireccion($_POST['direccion'] ?? null);
            if ($cliente->update()) {
                header("Location: ?c=clientes&accion=view");
                exit();
            } else {
                echo "Error al actualizar el cliente.";
            }
            break;

        case 'delete':
            $cliente = new Clientes();
            $cliente->setId($_POST['id'] ?? null);
            if ($cliente->delete()) {
                header("Location: ?c=clientes&accion=view");
                exit();
            } else {
                echo "Error al eliminar el cliente.";
            }
            break;
        case 'active':
            $cliente = new Clientes();
            $cliente->setId($_POST['id'] ?? null);
            if ($cliente->activate()) {
                header("Location: ?c=clientes&accion=view");
                exit();
            } else {
                echo "Error al activar el cliente.";
            }
            break;
        default:
            http_response_code(404);
            require_once __DIR__ . '/../Views/errors/404.php';
            break;
    }