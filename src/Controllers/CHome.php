<?php
namespace Lenovo\Dalu\Controllers;

use Lenovo\Dalu\Models\Clientes;
use Lenovo\Dalu\Models\Productos;
use Lenovo\Dalu\Models\Usuarios;

// Obtener la acción desde GET o POST, o establecer un valor por defecto
$accion = $_GET['accion'] ?? $_POST['accion'] ?? 'view';

// El switch maneja las diferentes acciones
switch($accion) {
    case "view":
        
        // Obtener conteos para el dashboard
        $clientesModel = new Clientes();
        $cantidadClientes = count($clientesModel->search());

        $productosModel = new Productos();
        $cantidadProductos = count($productosModel->search());

        $usuariosModel = new Usuarios();
        $cantidadUsuarios = count($usuariosModel->search());
        
        // Cargar la vista
        require_once __DIR__ . "/../Views/V_Inicio.php";
        break;
        
    case "about":
        // Página acerca de
        $titulo = "Acerca de Nosotros";
        require_once __DIR__ . "/../Views/home/about.php";
        break;
        
    case "contacto":
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Procesar formulario de contacto
            $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
            $mensaje = htmlspecialchars($_POST['mensaje']);
            
            // Aquí podrías usar un modelo para guardar
            $resultado = "Mensaje enviado correctamente";
        }
        
        require_once __DIR__ . "/../Views/home/contacto.php";
        break;
        
    default:
        // Acción no reconocida
        http_response_code(404);
        require_once __DIR__ . "/../Views/errors/404.php";
        break;
}