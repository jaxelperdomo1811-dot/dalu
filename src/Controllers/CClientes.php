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
            $telefonoRaw = trim($_POST['telefono'] ?? '');
            // Quitar espacios, guiones y paréntesis
            $telefono = preg_replace('/[^\d+]/', '', $telefonoRaw);
            
            // Normalizar si se omitió el código de país (asumiendo Venezuela por defecto)
            if (preg_match('/^0[1-9]\d{9}$/', $telefono)) {
                $telefono = '+58' . substr($telefono, 1);
            } elseif (preg_match('/^[1-9]\d{9}$/', $telefono)) {
                $telefono = '+58' . $telefono;
            }

            // Validar formato E.164 internacional
            if (!preg_match('/^\+[1-9]\d{6,14}$/', $telefono)) {
                $_SESSION['error'] = "Error: El número de teléfono no es válido o no tiene el formato correcto (+58...).";
                header("Location: ?c=clientes&accion=view");
                exit();
            }

            $cliente = new Clientes();
            $tipoPersona = $_POST['tipo_persona'] ?? '';
            $cedulaNumero = $_POST['cedula'] ?? '';
            $cedulaCompleta = trim($tipoPersona . $cedulaNumero);

            $cliente->setNombre($_POST['nombre'] ?? null)
                    ->setApellido($_POST['apellido'] ?? null)
                    ->setCorreo($_POST['correo'] ?? null)
                    ->setTelefono($telefono)
                    ->setDireccion($_POST['direccion'] ?? null)
                    ->setCedula($cedulaCompleta !== '' ? $cedulaCompleta : null);
            if ($cliente->insert()) {
                $_SESSION['success'] = "Cliente registrado exitosamente.";
            } else {
                $_SESSION['error'] = "Error al insertar el cliente.";
            }
            header("Location: ?c=clientes&accion=view");
            exit();
            break;
        case 'update':
            $telefonoRaw = trim($_POST['telefono'] ?? '');
            // Quitar espacios, guiones y paréntesis
            $telefono = preg_replace('/[^\d+]/', '', $telefonoRaw);
            
            // Normalizar si se omitió el código de país (asumiendo Venezuela por defecto)
            if (preg_match('/^0[1-9]\d{9}$/', $telefono)) {
                $telefono = '+58' . substr($telefono, 1);
            } elseif (preg_match('/^[1-9]\d{9}$/', $telefono)) {
                $telefono = '+58' . $telefono;
            }

            // Validar formato E.164 internacional
            if (!preg_match('/^\+[1-9]\d{6,14}$/', $telefono)) {
                $_SESSION['error'] = "Error: El número de teléfono no es válido o no tiene el formato correcto (+58...).";
                header("Location: ?c=clientes&accion=view");
                exit();
            }

            $cliente = new Clientes();
            $cliente->setId($_POST['id'] ?? null)
                    ->setNombre($_POST['nombre'] ?? null)
                    ->setApellido($_POST['apellido'] ?? null)
                    ->setCorreo($_POST['correo'] ?? null)
                    ->setTelefono($telefono)
                    ->setDireccion($_POST['direccion'] ?? null);
            if ($cliente->update()) {
                $_SESSION['success'] = "Cliente actualizado exitosamente.";
            } else {
                $_SESSION['error'] = "Error al actualizar los datos del cliente.";
            }
            header("Location: ?c=clientes&accion=view");
            exit();
            break;

        case 'delete':
            $cliente = new Clientes();
            $cliente->setId($_POST['id'] ?? null);
            if ($cliente->delete()) {
                $_SESSION['success'] = "Cliente inhabilitado exitosamente.";
            } else {
                $_SESSION['error'] = "Error al inhabilitar el cliente.";
            }
            header("Location: ?c=clientes&accion=view");
            exit();
            break;
        case 'active':
            $cliente = new Clientes();
            $cliente->setId($_POST['id'] ?? null);
            if ($cliente->activate()) {
                $_SESSION['success'] = "Cliente activado exitosamente.";
            } else {
                $_SESSION['error'] = "Error al activar el cliente.";
            }
            header("Location: ?c=clientes&accion=view");
            exit();
            break;
        default:
            http_response_code(404);
            require_once __DIR__ . '/../Views/errors/404.php';
            break;
    }