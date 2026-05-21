<?php
namespace Lenovo\Dalu\Controllers;

use Lenovo\Dalu\Models\Entradas;

$accion = $_GET['accion'] ?? $_POST['accion'] ?? 'view';

switch ($accion) {
    case 'insert':
        $entradas = new Entradas();
        
        $id_proveedor = $_POST['id_proveedor'] ?? null;
        $numero_lote = $_POST['numero_lote'] ?? null;
        $fecha_ingreso = $_POST['fecha_ingreso'] ?? null;
        
        // Arrays from dynamic form
        $productos = $_POST['id_producto'] ?? [];
        $cantidades = $_POST['cantidad'] ?? [];
        $precios = $_POST['precio_compra'] ?? [];

        $detalles = [];
        for ($i = 0; $i < count($productos); $i++) {
            if (!empty($productos[$i]) && !empty($cantidades[$i]) && !empty($precios[$i])) {
                $detalles[] = [
                    'id_producto' => $productos[$i],
                    'cantidad' => $cantidades[$i],
                    'precio_compra' => $precios[$i]
                ];
            }
        }

        if (count($detalles) > 0 && $id_proveedor && $numero_lote && $fecha_ingreso) {
            if ($entradas->registrarEntrada($id_proveedor, $numero_lote, $fecha_ingreso, $detalles)) {
                // Redirigir de vuelta a proveedores, tal vez agregando un parámetro para abrir la pestaña
                header("Location: ?c=proveedores&tab=entradas");
                exit();
            } else {
                echo "Error al registrar la entrada.";
            }
        } else {
            echo "Faltan datos requeridos o detalles de productos.";
        }
        break;

    case 'view_detalles':
        // Si más adelante se quiere ver el detalle por ajax
        $entradas = new Entradas();
        $id_entrada = $_GET['id'] ?? null;
        if ($id_entrada) {
            $detalles = $entradas->getDetalles($id_entrada);
            
            if (empty($detalles)) {
                echo "<div class='alert alert-info'>No hay detalles registrados para esta entrada.</div>";
            } else {
                $html = "<table class='table table-bordered table-striped'>";
                $html .= "<thead><tr class='table-dark'><th>Producto</th><th>Cantidad</th><th>Precio Compra ($)</th><th>Subtotal ($)</th></tr></thead><tbody>";
                $total_general = 0;
                foreach ($detalles as $det) {
                    $subtotal = $det['cantidad'] * $det['precio_compra'];
                    $total_general += $subtotal;
                    $html .= "<tr>";
                    $html .= "<td>" . htmlspecialchars($det['producto_nombre']) . "</td>";
                    $html .= "<td>" . htmlspecialchars($det['cantidad']) . "</td>";
                    $html .= "<td>" . number_format($det['precio_compra'], 2) . "</td>";
                    $html .= "<td>" . number_format($subtotal, 2) . "</td>";
                    $html .= "</tr>";
                }
                $html .= "</tbody>";
                $html .= "<tfoot><tr><th colspan='3' class='text-end'>TOTAL:</th><th>" . number_format($total_general, 2) . "</th></tr></tfoot>";
                $html .= "</table>";
                echo $html;
            }
        }
        break;

    default:
        http_response_code(404);
        require_once __DIR__ . '/../Views/errors/404.php';
        break;
}
