<?php
namespace Lenovo\Dalu\Controllers;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$loggeado = isset($_SESSION['id']) && !empty($_SESSION['id']);

$controladorVerificar = isset($controlador) ? strtolower($controlador) : 'chome';

$accion = isset($_GET['accion']) ? strtolower($_GET['accion']) : (isset($_POST['accion']) ? strtolower($_POST['accion']) : '');
$a = isset($_GET['a']) ? strtolower($_GET['a']) : (isset($_POST['a']) ? strtolower($_POST['a']) : '');
$esLogout = ($accion === 'logout' || $a === 'logout');

switch ($controladorVerificar) {
    case 'clogin':
    case 'crecoverpass':
        // Permitir acceso público a CLogin y CRecoverPass.
        // Si intenta acceder a CLogin y ya está loggeado (y NO es logout), redirigir a Home
        if ($controladorVerificar === 'clogin' && $loggeado && !$esLogout) {
            header("Location: ?c=home");
            exit();
        }
        break;

    default:
        // Cualquier otro controlador (incluyendo home, productos, etc.) requiere sesión
        if (!$loggeado) {
            header("Location: ?c=login");
            exit();
        }
        break;
}
