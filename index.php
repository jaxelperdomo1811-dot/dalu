<?php
/**
 * Front Controller - Enfoque Procedural con Composer
 * 
 * URL esperada: ?c=controlador/accion
 * Ejemplo: ?c=home/view o ?c=usuarios/crear
 */

// 1. Inicializar sesión si no existe
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Cargar autoload de Composer
require_once __DIR__ . '/vendor/autoload.php';

// 3. Usar namespaces con "use" para clases necesarias
// use lenovo\tienda_dalu\Controllers\Router; // Si se implementa un router
// use lenovo\tienda_dalu\Database; // Si se necesita

// 4. Obtener y parsear la ruta
$ruta = isset($_GET['c']) ? $_GET['c'] : "home/view";
$partes = explode("/", $ruta);
$controlador = "C" . ucfirst(strtolower($partes[0])); // Ej: home -> CHome, clientes -> CClientes
$accion = isset($partes[1]) ? $partes[1] : "view";

// 6. Construir ruta del archivo controlador
$archivoControlador = __DIR__ . "/src/Controllers/" . $controlador . ".php";

// 7. Verificar Login Activo y Redireccionar si es necesario
require_once __DIR__ . '/src/Controllers/CAuth.php';

// 7. Verificar existencia del archivo y ejecutar
if (file_exists($archivoControlador)) {
    require_once $archivoControlador;
} else {
    echo "NO EXISTE EL CONTROLADOR";
}
?>