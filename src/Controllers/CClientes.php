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
        case 'consultarCedula':
            header('Content-Type: application/json');
            $tipoPersona = $_GET['tipo_persona'] ?? '';
            $cedula = $_GET['cedula'] ?? '';
            
            $nacionalidad = str_replace('-', '', $tipoPersona);
            
            $envFile = __DIR__ . '/../../.env';
            if (file_exists($envFile)) {
                $env = parse_ini_file($envFile);
                $app_id = $env['app_id'] ?? '';
                $token = $env['token'] ?? '';
                
                $url = "https://api.cedula.com.ve/api/v1?app_id={$app_id}&token={$token}&nacionalidad={$nacionalidad}&cedula={$cedula}";
                
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $response = curl_exec($ch);
                curl_close($ch);
                
                if ($response !== false) {
                    echo $response;
                    exit;
                }
            }
            echo json_encode(['error' => 'No se pudo consultar la API']);
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

            $nacionalidad = str_replace('-', '', $tipoPersona);
            $envFile = __DIR__ . '/../../.env';
            if (file_exists($envFile)) {
                $env = parse_ini_file($envFile);
                $app_id = $env['app_id'] ?? '';
                $token = $env['token'] ?? '';
                
                $url = "https://api.cedula.com.ve/api/v1?app_id={$app_id}&token={$token}&nacionalidad={$nacionalidad}&cedula={$cedula}";
                
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $response = curl_exec($ch);
                curl_close($ch);
                
                if ($response !== false) {
                    $apiData = json_decode($response, true);
                    if (!empty($apiData['data'])) {
                        $persona = $apiData['data'];
                        $nombre = trim(($persona['primer_nombre'] ?? '') . ' ' . ($persona['segundo_nombre'] ?? ''));
                        $apellido = trim(($persona['primer_apellido'] ?? '') . ' ' . ($persona['segundo_apellido'] ?? ''));
                        
                        $clienteModel->setNombre($nombre)
                                     ->setApellido($apellido)
                                     ->setCedula($cedulaCompleta)
                                     ->setTelefono(null)
                                     ->setCorreo(null)
                                     ->setDireccion(null);
                        
                        try {
                            if ($clienteModel->insert()) {
                                $nuevoCliente = $clienteModel->getByCedula($cedulaCompleta);
                                echo json_encode(['success' => true, 'source' => 'api_and_db', 'data' => [
                                    'id' => $nuevoCliente['id'],
                                    'nombre_completo' => trim($nombre . ' ' . $apellido)
                                ]]);
                                exit;
                            } else {
                                echo json_encode(['success' => false, 'error' => 'Error al guardar en BD']);
                                exit;
                            }
                        } catch (\Exception $e) {
                            echo json_encode(['success' => false, 'error' => 'Error BD: ' . $e->getMessage()]);
                            exit;
                        }
                    }
                }
            }
            
            echo json_encode(['success' => false, 'error' => 'Cédula no encontrada']);
            exit;
            break;
        default:
            http_response_code(404);
            require_once __DIR__ . '/../Views/errors/404.php';
            break;
    }