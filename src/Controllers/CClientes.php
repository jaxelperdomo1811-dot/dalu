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
            $tipoPersona = $_POST['tipo_persona'] ?? '';
            $cedulaNumero = $_POST['cedula'] ?? '';
            $cedulaCompleta = trim($tipoPersona . $cedulaNumero);

            $cliente->setNombre($_POST['nombre'] ?? null)
                    ->setApellido($_POST['apellido'] ?? null)
                    ->setCorreo($_POST['correo'] ?? null)
                    ->setTelefono($_POST['telefono'] ?? null)
                    ->setDireccion($_POST['direccion'] ?? null)
                    ->setCedula($cedulaCompleta !== '' ? $cedulaCompleta : null);
            if ($cliente->insert()) {
                $success = "Cliente registrado exitosamente.";
                header("Location: ?c=clientes&accion=view");
                exit();
            } else {
                $error = "Error al insertar el cliente.";
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
                $success = "Cliente actualizado exitosamente.";
                header("Location: ?c=clientes&accion=view");
                exit();
            } else {
                $error = "Error al actualizar los datos del cliente.";

            }
            break;

        case 'delete':
            $cliente = new Clientes();
            $cliente->setId($_POST['id'] ?? null);
            if ($cliente->delete()) {
                $success = "Cliente eliminado exitosamente.";
                header("Location: ?c=clientes&accion=view");
                exit();
            } else {
                $error = "Error al eliminar el cliente.";
            }
            break;
        case 'active':
            $cliente = new Clientes();
            $cliente->setId($_POST['id'] ?? null);
            if ($cliente->activate()) {
                $success = "Cliente activado exitosamente.";
                header("Location: ?c=clientes&accion=view");
                exit();
            } else {
                $error = "Error al activar el cliente.";
            }
            break;
        default:
            http_response_code(404);
            require_once __DIR__ . '/../Views/errors/404.php';
            break;
    }