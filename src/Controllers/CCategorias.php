<?php
namespace Lenovo\Dalu\Controllers;
use Lenovo\Dalu\Models\Categorias;

$accion = $_GET['accion'] ?? $_POST['accion'] ?? 'view';

switch ($accion) {
    case "view":
        // View is handled inside CProductos.php, so this is unused, but we keep it just in case
        header("Location: ?c=productos&accion=view&tab=categorias");
        exit();
        break;

    case "insert":
        $categorias = new Categorias();
        $categorias->setNombre($_POST['nombre'] ?? null)
                   ->setDescripcion($_POST['descripcion'] ?? null);
        try {
            $id = $categorias->insert();
            if ($id) {
                if (isset($_POST['campos']) && is_array($_POST['campos'])) {
                    $categorias->setCampos($id, $_POST['campos']);
                }
                $_SESSION['success'] = "Categoría registrada exitosamente.";
            } else {
                $_SESSION['error'] = "Error al registrar la categoría.";
            }
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000) {
                $_SESSION['error'] = "Error: Ya existe una categoría con este nombre.";
            } else {
                $_SESSION['error'] = "Error de base de datos: " . $e->getMessage();
            }
        }
        header("Location: ?c=productos&accion=view&tab=categorias");
        exit();
        break;

    case "update":
        $categorias = new Categorias();
        $id = $_POST['id'] ?? null;
        $categorias->setId($id)
                   ->setNombre($_POST['nombre'] ?? null)
                   ->setDescripcion($_POST['descripcion'] ?? null);
        try {
            if ($categorias->update()) {
                if (isset($_POST['campos']) && is_array($_POST['campos'])) {
                    $categorias->setCampos($id, $_POST['campos']);
                } else {
                    $categorias->setCampos($id, []);
                }
                $_SESSION['success'] = "Categoría actualizada exitosamente.";
            } else {
                $_SESSION['error'] = "Error al actualizar la categoría.";
            }
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000) {
                $_SESSION['error'] = "Error: Ya existe una categoría con este nombre.";
            } else {
                $_SESSION['error'] = "Error de base de datos: " . $e->getMessage();
            }
        }
        header("Location: ?c=productos&accion=view&tab=categorias");
        exit();
        break;

    case "delete":
        $categorias = new Categorias();
        $categorias->setId($_POST['id'] ?? null);
        if ($categorias->delete()) {
            $_SESSION['success'] = "Categoría inhabilitada exitosamente.";
        } else {
            $_SESSION['error'] = "Error al inhabilitar la categoría.";
        }
        header("Location: ?c=productos&accion=view&tab=categorias");
        exit();
        break;

    case "active":
        $categorias = new Categorias();
        $categorias->setId($_POST['id'] ?? null);
        if ($categorias->activate()) {
            $_SESSION['success'] = "Categoría activada exitosamente.";
        } else {
            $_SESSION['error'] = "Error al activar la categoría.";
        }
        header("Location: ?c=productos&accion=view&tab=categorias");
        exit();
        break;
        
    case "createCampo":
        require_once __DIR__ . "/../Models/CamposVariante.php";
        $campo = new \Lenovo\Dalu\Models\CamposVariante();
        $campo->setNombre($_POST['nombre']);
        $campo->setTipo($_POST['tipo'] ?? 'text');
        
        $id = $campo->insert();
        if ($id) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'id' => $id, 'nombre' => $_POST['nombre']]);
        } else {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Error al crear el campo']);
        }
        exit();
        
    case "updateCampo":
        require_once __DIR__ . "/../Models/CamposVariante.php";
        $campo = new \Lenovo\Dalu\Models\CamposVariante();
        $campo->setId($_POST['id']);
        $campo->setNombre($_POST['nombre']);
        $campo->setTipo($_POST['tipo'] ?? 'text');
        
        if ($campo->update()) {
            $_SESSION['success'] = "Campo actualizado exitosamente.";
        } else {
            $_SESSION['error'] = "Error al actualizar el campo.";
        }
        header("Location: ?c=productos&accion=view&tab=categorias");
        exit();

    case "deleteCampo":
        require_once __DIR__ . "/../Models/CamposVariante.php";
        $campo = new \Lenovo\Dalu\Models\CamposVariante();
        $campo->setId($_POST['id']);
        
        if ($campo->delete()) {
            $_SESSION['success'] = "Campo inhabilitado exitosamente.";
        } else {
            $_SESSION['error'] = "Error al inhabilitar el campo.";
        }
        header("Location: ?c=productos&accion=view&tab=categorias");
        exit();

    case "activeCampo":
        require_once __DIR__ . "/../Models/CamposVariante.php";
        $campo = new \Lenovo\Dalu\Models\CamposVariante();
        $campo->setId($_POST['id']);
        
        if ($campo->activate()) {
            $_SESSION['success'] = "Campo activado exitosamente.";
        } else {
            $_SESSION['error'] = "Error al activar el campo.";
        }
        header("Location: ?c=productos&accion=view&tab=categorias");
        exit();
        
    case "getTodosCampos":
        require_once __DIR__ . "/../Models/CamposVariante.php";
        $campo = new \Lenovo\Dalu\Models\CamposVariante();
        header('Content-Type: application/json');
        echo json_encode($campo->search());
        exit();

    default:
        http_response_code(404);
        require_once __DIR__ . "/../Views/errors/404.php";
        break;
}