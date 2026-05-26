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
            $proveedor->setNombre($_POST['nombre'] ?? null)
                      ->setRif($_POST['rif'] ?? null)
                      ->setTelefono($telefono)
                      ->setEmail($_POST['email'] ?? null)
                      ->setDireccion($_POST['direccion'] ?? null);
            if ($proveedor->insert()) {
                $_SESSION['success'] = "Proveedor registrado exitosamente.";
            } else {
                $_SESSION['error'] = "Error al registrar el proveedor.";
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
            $proveedor->setId($_POST['id'] ?? null)
                      ->setNombre($_POST['nombre'] ?? null)
                      ->setRif($_POST['rif'] ?? null)
                      ->setTelefono($telefono)
                      ->setEmail($_POST['email'] ?? null)
                      ->setDireccion($_POST['direccion'] ?? null);
            if ($proveedor->update()) {
                $_SESSION['success'] = "Proveedor actualizado exitosamente.";
            } else {
                $_SESSION['error'] = "Error al actualizar el proveedor.";
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
        default:
            http_response_code(404);
            require_once __DIR__ . '/../Views/errors/404.php';
            break;
    }