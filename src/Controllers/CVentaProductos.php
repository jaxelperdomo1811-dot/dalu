<?php
namespace Lenovo\Dalu\Controllers;

use Lenovo\Dalu\Models\VentaProductos;
use Lenovo\Dalu\Models\Clientes;
use Lenovo\Dalu\Models\Productos;

$accion = $_GET['accion'] ?? $_POST['accion'] ?? 'view';

switch ($accion) {
    case 'view':
        $ventaModel = new VentaProductos();
        $ventas = $ventaModel->search();
        
        $clienteModel = new Clientes();
        $clientes = $clienteModel->search();
        
        $prodModel = new Productos();
        $productos = $prodModel->search();
        foreach ($productos as &$p) {
            $p['variantes'] = $prodModel->getVariantesByProducto($p['id']);
        }
        
        require_once __DIR__ . '/../Views/V_Ventas.php';
        break;

    case 'insert':
        $ventaModel = new VentaProductos();
        $id_cliente = $_POST['id_cliente'] ?? null;
        $id_nota_entrega = $_POST['id_nota_entrega'] ?? null;
        
        $variantes = $_POST['id_variante'] ?? [];
        $cantidades = $_POST['cantidad'] ?? [];
        $precios = $_POST['precio_unitario'] ?? [];

        $detalles = [];
        for ($i = 0; $i < count($variantes); $i++) {
            if (!empty($variantes[$i]) && !empty($cantidades[$i]) && isset($precios[$i])) {
                $detalles[] = [
                    'id_variante' => $variantes[$i],
                    'cantidad' => (int)$cantidades[$i],
                    'precio_unitario' => (float)$precios[$i]
                ];
            }
        }

        if (count($detalles) > 0) {
            $ventaModel->setIdCliente($id_cliente)
                       ->setIdNotaEntrega($id_nota_entrega)
                       ->setEstado($_POST['estado'] ?? 'confirmado')
                       ->setDetalles($detalles);

            if ($ventaModel->insert()) {
                $_SESSION['success'] = "Venta de productos registrada exitosamente.";
            } else {
                $_SESSION['error'] = "Error al registrar la venta de productos.";
            }
        } else {
            $_SESSION['error'] = "Debe agregar al menos un producto con variante y cantidad válida.";
        }
        header("Location: ?c=ventaProductos&accion=view");
        exit();
        break;

    case 'view_detalles':
        $ventaModel = new VentaProductos();
        $id_venta = $_GET['id'] ?? null;
        if ($id_venta) {
            $detalles = $ventaModel->getDetallesVenta($id_venta);
            if (empty($detalles)) {
                echo "<div class='alert alert-info'>No hay detalles registrados para esta venta.</div>";
            } else {
                $html = "<table class='table table-bordered table-striped'>";
                $html .= "<thead><tr class='table-dark'><th>Producto</th><th>Variante</th><th>Cantidad</th><th>Precio Unit. ($)</th><th>Subtotal ($)</th></tr></thead><tbody>";
                $total_general = 0;
                foreach ($detalles as $det) {
                    $total_general += $det['subtotal'];
                    $html .= "<tr>";
                    $html .= "<td>" . htmlspecialchars($det['producto_nombre'] ?? 'N/A') . "</td>";
                    $html .= "<td>" . htmlspecialchars($det['nombre_variante'] ?? 'N/A') . "</td>";
                    $html .= "<td>" . htmlspecialchars($det['cantidad']) . "</td>";
                    $html .= "<td>" . number_format($det['precio_unitario'], 2) . "</td>";
                    $html .= "<td>" . number_format($det['subtotal'], 2) . "</td>";
                    $html .= "</tr>";
                }
                $html .= "</tbody>";
                $html .= "<tfoot><tr><th colspan='4' class='text-end'>TOTAL:</th><th>" . number_format($total_general, 2) . "</th></tr></tfoot>";
                $html .= "</table>";
                echo $html;
            }
        }
        break;

    default:
        header("Location: ?c=ventaProductos&accion=view");
        exit();
        break;
}
