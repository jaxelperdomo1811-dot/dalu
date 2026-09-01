<?php
namespace Lenovo\Dalu\Controllers;

use Lenovo\Dalu\Models\Proveedores;
use Lenovo\Dalu\Models\CompraProductos;
use Lenovo\Dalu\Models\Productos;

$accion = $_GET['accion'] ?? $_POST['accion'] ?? 'view';

switch ($accion) {
    case 'view':
        $proveedorModel = new Proveedores();
        $proveedoresActivos = $proveedorModel->search();
        $proveedoresInactivos = $proveedorModel->searchInactive();
        $proveedores = $proveedoresActivos;
        
        $compraModel = new CompraProductos();
        $entradasLista = $compraModel->search();
        $comprasLista = $entradasLista;
        
        $prodModel = new Productos();
        $productos = $prodModel->search();
        foreach ($productos as &$p) {
            $p['variantes'] = $prodModel->getVariantesByProducto($p['id']);
        }
        
        require_once __DIR__ . '/../Views/V_Proveedores.php';
        break;

    case 'consultarCedula':
        header('Content-Type: application/json');
        $tipoPersona = $_REQUEST['tipo_persona'] ?? '';
        $cedula = $_REQUEST['cedula'] ?? '';

        if (empty($tipoPersona) || empty($cedula)) {
            echo json_encode(['success' => false, 'error' => 'Faltan parámetros']);
            exit;
        }

        $cedulaCompleta = trim($tipoPersona . $cedula);
        $proveedorModel = new Proveedores();
        $proveedorExistente = $proveedorModel->getByDocumentoIdentidad($cedulaCompleta);

        if ($proveedorExistente) {
            echo json_encode([
                'success' => true,
                'exists' => true,
                'source' => 'db',
                'data' => [
                    'id' => $proveedorExistente['id'],
                    'nombre_completo' => trim(($proveedorExistente['nombre'] ?? '') . ' ' . ($proveedorExistente['apellido'] ?? ''))
                ]
            ]);
            exit;
        }

        $isValidFormat = preg_match('/^\d{6,9}$/', $cedula);
        $nombre = trim($_REQUEST['nombre'] ?? '');
        $apellido = trim($_REQUEST['apellido'] ?? '');

        if ($isValidFormat && ($nombre !== '' || $apellido !== '')) {
            $nuevo = new Proveedores();
            $telefono = preg_replace('/[^\d+]/', '', $_REQUEST['telefono'] ?? '');
            $nuevo->setNombre($nombre ?: null)
                  ->setApellido($apellido ?: null)
                  ->setDocumentoIdentidad($cedulaCompleta)
                  ->setRif($_REQUEST['rif'] ?? null)
                  ->setTelefono($telefono ?: null)
                  ->setEmail($_REQUEST['email'] ?? null)
                  ->setDireccion($_REQUEST['direccion'] ?? null)
                  ->setRazonSocial($_REQUEST['razon_social'] ?? null);
            try {
                if ($nuevo->insert()) {
                    $creado = $proveedorModel->getByDocumentoIdentidad($cedulaCompleta);
                    echo json_encode([
                        'success' => true,
                        'exists' => true,
                        'source' => 'created',
                        'data' => [
                            'id' => $creado['id'] ?? null,
                            'nombre_completo' => trim(($creado['nombre'] ?? '') . ' ' . ($creado['apellido'] ?? ''))
                        ]
                    ]);
                    exit;
                } else {
                    echo json_encode(['success' => false, 'error' => 'db_insert_failed']);
                    exit;
                }
            } catch (\PDOException $e) {
                if ($e->getCode() == 23000) {
                    echo json_encode(['success' => false, 'error' => 'duplicate', 'message' => 'Ya existe un proveedor con este documento o RIF.']);
                } else {
                    echo json_encode(['success' => false, 'error' => 'db_error', 'message' => $e->getMessage()]);
                }
                exit;
            }
        }

        echo json_encode([
            'success' => true,
            'exists' => false,
            'valid_format' => (bool)$isValidFormat,
            'message' => $isValidFormat ? 'Cédula válida sintácticamente.' : 'Formato de cédula inválido.'
        ]);
        exit;
        break;

    case 'insert':
        $telefonoRaw = trim($_POST['telefono'] ?? '');
        $telefono = preg_replace('/[^\d+]/', '', $telefonoRaw);
        
        if (preg_match('/^0(?:412|414|416|422|424|426)\d{7}$/', $telefono)) {
            $telefono = '+58' . substr($telefono, 1);
        } elseif (preg_match('/^(?:412|414|416|422|424|426)\d{7}$/', $telefono)) {
            $telefono = '+58' . $telefono;
        } elseif ($telefono !== '' && strpos($telefono, '+') !== 0) {
            $telefono = '+' . $telefono;
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
        $telefono = preg_replace('/[^\d+]/', '', $telefonoRaw);
        
        if (preg_match('/^0(?:412|414|416|422|424|426)\d{7}$/', $telefono)) {
            $telefono = '+58' . substr($telefono, 1);
        } elseif (preg_match('/^(?:412|414|416|422|424|426)\d{7}$/', $telefono)) {
            $telefono = '+58' . $telefono;
        } elseif ($telefono !== '' && strpos($telefono, '+') !== 0) {
            $telefono = '+' . $telefono;
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

    default:
        http_response_code(404);
        require_once __DIR__ . '/../Views/errors/404.php';
        break;
}