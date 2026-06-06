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
            $telefonoRaw = trim($_POST['telefono'] ?? '');
            $telefonoRaw2 = trim($_POST['telefono2'] ?? '');
            // Quitar espacios, guiones y paréntesis
            $telefono = preg_replace('/[^\d+]/', '', $telefonoRaw);
            $telefono2 = preg_replace('/[^\d+]/', '', $telefonoRaw2);
            
            // Normalizar si se omitió el código de país (asumiendo Venezuela por defecto)
            if (preg_match('/^0[1-9]\d{9}$/', $telefono)) {
                $telefono = '+58' . substr($telefono, 1);
            } elseif (preg_match('/^[1-9]\d{9}$/', $telefono)) {
                $telefono = '+58' . $telefono;
            }
            if (preg_match('/^0[1-9]\d{9}$/', $telefono2)) {
                $telefono2 = '+58' . substr($telefono2, 1);
            } elseif (preg_match('/^[1-9]\d{9}$/', $telefono2)) {
                $telefono2 = '+58' . $telefono2;
            }

            // Validar formato E.164 internacional
            if (!preg_match('/^\+[1-9]\d{6,14}$/', $telefono)) {
                $_SESSION['error'] = "Error: El número de teléfono no es válido o no tiene el formato correcto (+58...).";
                header("Location: ?c=proveedores&accion=view");
                exit();
            }
            if (!preg_match('/^\+[1-9]\d{6,14}$/', $telefono2)) {
                $_SESSION['error'] = "Error: El número de teléfono no es válido o no tiene el formato correcto (+58...).";
                header("Location: ?c=proveedores&accion=view");
                exit();
            }

            $proveedor = new Proveedores();
            $tipoPersona = $_POST['tipo_persona'] ?? '';
            $cedulaNumero = $_POST['cedula'] ?? '';
            $cedulaCompleta = trim($tipoPersona . $cedulaNumero);
            $proveedor->setNombre($_POST['nombre'] ?? null)
                      ->setApellido($_POST['apellido'] ?? null)
                      ->setRazonSocial($_POST['razon_social'] ?? null)
                      ->setRif($_POST['rif'] ?? null)
                      ->setDocumentoIdentidad($cedulaCompleta !== '' ? $cedulaCompleta : null)
                      ->setTelefono($telefono)
                      ->setEmail($_POST['email'] ?? null)
                      ->setDireccion($_POST['direccion'] ?? null);
            try {
                if ($proveedor->insert()) {
                    $_SESSION['success'] = "Proveedor registrado exitosamente.";
                } else {
                    $_SESSION['error'] = "Error al registrar el proveedor.";
                }
            } catch (\PDOException $e) {
                if ($e->getCode() == 23000) {
                    $_SESSION['error'] = "Error: Ya existe un proveedor con este RIF, documento de identidad o correo.";
                } else {
                    $_SESSION['error'] = "Error de base de datos: " . $e->getMessage();
                }
            }
            header("Location: ?c=proveedores&accion=view");
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
                header("Location: ?c=proveedores&accion=view");
                exit();
            }

            $proveedor = new Proveedores();
            $tipoPersona = $_POST['tipo_persona'] ?? '';
            $cedulaNumero = $_POST['cedula'] ?? '';
            $cedulaCompleta = trim($tipoPersona . $cedulaNumero);
            $proveedor->setId($_POST['id'] ?? null)
                      ->setNombre($_POST['nombre'] ?? null)
                      ->setRazonSocial($_POST['razon_social'] ?? null)
                      ->setRif($_POST['rif'] ?? null)
                      ->setDocumentoIdentidad($cedulaCompleta !== '' ? $cedulaCompleta : null)
                      ->setTelefono($telefono)
                      ->setEmail($_POST['email'] ?? null)
                      ->setDireccion($_POST['direccion'] ?? null);
            try {
                if ($proveedor->update()) {
                    $_SESSION['success'] = "Proveedor actualizado exitosamente.";
                } else {
                    $_SESSION['error'] = "Error al actualizar el proveedor.";
                }
            } catch (\PDOException $e) {
                if ($e->getCode() == 23000) {
                    $_SESSION['error'] = "Error: Ya existe otro proveedor con este RIF, documento de identidad o correo.";
                } else {
                    $_SESSION['error'] = "Error de base de datos: " . $e->getMessage();
                }
            }
            header("Location: ?c=proveedores&accion=view");
            exit();
            break;
        case 'delete':
            $proveedor = new Proveedores();
            $proveedor->setId($_POST['id'] ?? null);
            if ($proveedor->delete()) {
                $_SESSION['success'] = "Proveedor inhabilitado exitosamente.";
            } else {
                $_SESSION['error'] = "Error al inhabilitar el proveedor.";
            }
            header("Location: ?c=proveedores&accion=view");
            exit();
            break;
        case 'active':
            $proveedor = new Proveedores();
            $proveedor->setId($_POST['id'] ?? null);
            if ($proveedor->activate()) {
                $_SESSION['success'] = "Proveedor activado exitosamente.";
            } else {
                $_SESSION['error'] = "Error al activar el proveedor.";
            }
            header("Location: ?c=proveedores&accion=view");
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
        default:
            http_response_code(404);
            require_once __DIR__ . '/../Views/errors/404.php';
            break;
    }