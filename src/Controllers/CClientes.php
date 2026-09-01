<?php
    namespace Lenovo\Dalu\Controllers;
    use Lenovo\Dalu\Models\Clientes;

    $accion = $_GET['accion'] ?? $_POST['accion'] ?? 'view';

    switch ($accion) {
        case 'consultarCedula':
            header('Content-Type: application/json');
            $tipoPersona = $_GET['tipo_persona'] ?? '';
            $cedula = $_GET['cedula'] ?? '';

            if (empty($tipoPersona) || empty($cedula)) {
                echo json_encode(['success' => false, 'error' => 'Faltan parámetros']);
                exit;
            }

            $cedulaCompleta = trim($tipoPersona . $cedula);
            $clienteModel = new Clientes();
            $clienteExistente = $clienteModel->getByCedula($cedulaCompleta);

            if ($clienteExistente) {
                echo json_encode([
                    'success' => true,
                    'exists' => true,
                    'source' => 'db',
                    'data' => [
                        'id' => $clienteExistente['id'],
                        'nombre_completo' => trim(($clienteExistente['nombre'] ?? '') . ' ' . ($clienteExistente['apellido'] ?? ''))
                    ]
                ]);
                exit;
            }

            // Validación sintáctica básica de cédula (6-9 dígitos)
            $isValidFormat = preg_match('/^\d{6,9}$/', $cedula);

            echo json_encode([
                'success' => true,
                'exists' => false,
                'valid_format' => (bool)$isValidFormat,
                'message' => $isValidFormat ? 'Cédula válida sintácticamente.' : 'Formato de cédula inválido.'
            ]);
            exit;
                } else {
                    $_SESSION['error'] = "Error de base de datos: " . $e->getMessage();
                }
            }
            header("Location: ?c=clientes&accion=view");
            exit();
            break;
        case 'update':
            $telefonoRaw = trim($_POST['phone_full'] ?? $_POST['telefono'] ?? '');
            $telefono = preg_replace('/[^\d+]/', '', $telefonoRaw);
            
            if (preg_match('/^0(?:412|414|416|422|424|426)\d{7}$/', $telefono)) {
                $telefono = '+58' . substr($telefono, 1);
            } elseif (preg_match('/^(?:412|414|416|422|424|426)\d{7}$/', $telefono)) {
                $telefono = '+58' . $telefono;
            } elseif ($telefono !== '' && strpos($telefono, '+') !== 0) {
                $telefono = '+' . $telefono;
            }

            if (!empty($telefono) && !preg_match('/^\+[1-9]\d{6,14}$/', $telefono)) {
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
            try {
                if ($cliente->update()) {
                    $_SESSION['success'] = "Cliente actualizado exitosamente.";
                } else {
                    $_SESSION['error'] = "Error al actualizar los datos del cliente.";
                }
            } catch (\PDOException $e) {
                if ($e->getCode() == 23000) {
                    $_SESSION['error'] = "Error: Ya existe otro cliente con esta cédula.";
                } else {
                    $_SESSION['error'] = "Error de base de datos: " . $e->getMessage();
                }
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
        case 'consultarCedula':
            header('Content-Type: application/json');
            $tipoPersona = $_GET['tipo_persona'] ?? '';
            $cedula = $_GET['cedula'] ?? '';
            
            if (empty($tipoPersona) || empty($cedula)) {
                echo json_encode(['error' => 'Faltan parámetros']);
                exit;
            }

            $cedulaCompleta = trim($tipoPersona . $cedula);
            $clienteModel = new Clientes();
            $clienteExistente = $clienteModel->getByCedula($cedulaCompleta);

            if ($clienteExistente) {
                echo json_encode(['error_db' => 'El cliente ya se encuentra registrado.']);
                exit;
            }
            
            $nacionalidad = str_replace('-', '', $tipoPersona);
            
            // Consulta externa deshabilitada: usar registro manual o buscar en BD
            echo json_encode(['error' => 'API_disabled', 'message' => 'Consulta externa de cédula deshabilitada.']);
            exit;
            break;
        case 'buscarYRegistrarCedula':
            header('Content-Type: application/json');
            $tipoPersona = $_GET['tipo_persona'] ?? '';
            $cedula = $_GET['cedula'] ?? '';
            
            if (empty($tipoPersona) || empty($cedula)) {
                echo json_encode(['success' => false, 'error' => 'Faltan parámetros']);
                exit;
            }

            $cedulaCompleta = trim($tipoPersona . $cedula);
            $clienteModel = new Clientes();
            $clienteExistente = $clienteModel->getByCedula($cedulaCompleta);

            if ($clienteExistente) {
                echo json_encode(['success' => true, 'source' => 'db', 'data' => [
                    'id' => $clienteExistente['id'],
                    'nombre_completo' => trim(($clienteExistente['nombre'] ?? '') . ' ' . ($clienteExistente['apellido'] ?? ''))
                ]]);
                exit;
            }

            // API externa deshabilitada: devolver instrucción para registro manual
            echo json_encode(['success' => false, 'error' => 'API_disabled', 'message' => 'Consulta externa deshabilitada. Registre el cliente manualmente.']);
            exit;
            break;
        default:
            http_response_code(404);
            require_once __DIR__ . '/../Views/errors/404.php';
            break;
    }