<?php
namespace Lenovo\Dalu\Controllers;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Evitar que el navegador guarde la página en caché (soluciona el problema del botón 'Atrás')
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$loggeado = isset($_SESSION['id']) && !empty($_SESSION['id']);

// $controlador is defined in index.php
$controladorVerificar = isset($controlador) ? strtolower($controlador) : 'chome';

// Identificar si la acción es un intento de logout
$accion = isset($_GET['accion']) ? strtolower($_GET['accion']) : (isset($_POST['accion']) ? strtolower($_POST['accion']) : '');
$a = isset($_GET['a']) ? strtolower($_GET['a']) : (isset($_POST['a']) ? strtolower($_POST['a']) : '');
$esLogout = ($accion === 'logout' || $a === 'logout');

// Manejar las rutas con un switch siguiendo el estándar de controladores procedimentales
switch ($controladorVerificar) {
    case 'clogin':
        // Si intenta acceder a CLogin y ya está loggeado (y NO es logout), redirigir a Home
        if ($loggeado && !$esLogout) {
            header("Location: ?c=home");
            exit();
        }
        break;
        
    default:
        // Cualquier otro controlador (incluyendo home, productos, etc.)
        if (!$loggeado) {
            header("Location: ?c=login");
            exit();
        }
        break;
}
