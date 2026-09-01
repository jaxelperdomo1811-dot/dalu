<?php
namespace Lenovo\Dalu\Controllers;

use Lenovo\Dalu\Models\CompraProductos;

$accion = $_GET['accion'] ?? $_POST['accion'] ?? 'view';

switch ($accion) {
    case 'insert':
        $compraModel = new CompraProductos();
        
        $id_proveedor = $_POST['id_proveedor'] ?? null;
        $numero_lote = $_POST['numero_lote'] ?? null;
        $fecha_ingreso = $_POST['fecha_ingreso'] ?? null;
        
        $productos = $_POST['id_producto'] ?? [];
        $variantes = $_POST['id_variante'] ?? [];
        $cantidades = $_POST['cantidad'] ?? [];
        $precios = $_POST['precio_compra'] ?? [];

        $detalles = [];
        for ($i = 0; $i < count($productos); $i++) {
            if (!empty($productos[$i]) && !empty($cantidades[$i]) && !empty($precios[$i])) {
                $detalles[] = [
                    'id_producto' => $productos[$i],
                    'id_variante' => !empty($variantes[$i]) ? $variantes[$i] : null,
                    'cantidad' => $cantidades[$i],
                    'precio_compra' => $precios[$i]
                ];
            }
        }

        if (count($detalles) > 0 && $id_proveedor && $numero_lote && $fecha_ingreso) {
            $compraModel->setIdProveedor($id_proveedor)
                        ->setNumeroLote($numero_lote)
                        ->setFechaIngreso($fecha_ingreso)
                        ->setDetalles($detalles);

            try {
                if ($compraModel->insert()) {
                    $_SESSION['success'] = "Compra de productos registrada exitosamente.";
                } else {
                    $_SESSION['error'] = "Error al registrar la compra de productos.";
                }
            } catch (\PDOException $e) {
                if ($e->getCode() == 23000) {
                    $_SESSION['error'] = "Error: Esta compra ya se encuentra registrada.";
                } else {
                    $_SESSION['error'] = "Error de base de datos: " . $e->getMessage();
                }
            }
        } else {
            $_SESSION['error'] = "Faltan datos requeridos o detalles de productos.";
        }
        header("Location: ?c=proveedores&tab=compras");
        exit();
        break;

    case 'view_detalles':
        $compraModel = new CompraProductos();
        $id_compra = $_GET['id'] ?? null;
        if ($id_compra) {
            $detalles = $compraModel->getDetalles($id_compra);
            
            if (empty($detalles)) {
                echo "<div class='alert alert-info'>No hay detalles registrados para esta compra.</div>";
            } else {
                $html = "<table class='table table-bordered table-striped'>";
                $html .= "<thead><tr class='table-dark'><th>Producto</th><th>Variante</th><th>Cantidad</th><th>Precio Compra ($)</th><th>Subtotal ($)</th></tr></thead><tbody>";
                $total_general = 0;
                foreach ($detalles as $det) {
                    $subtotal = $det['cantidad'] * $det['precio_compra'];
                    $total_general += $subtotal;
                    $html .= "<tr>";
                    $html .= "<td>" . htmlspecialchars($det['producto_nombre'] ?? 'N/A') . "</td>";
                    $html .= "<td>" . htmlspecialchars($det['nombre_variante'] ?? 'N/A') . "</td>";
                    $html .= "<td>" . htmlspecialchars($det['cantidad']) . "</td>";
                    $html .= "<td>" . number_format($det['precio_compra'], 2) . "</td>";
                    $html .= "<td>" . number_format($subtotal, 2) . "</td>";
                    $html .= "</tr>";
                }
                $html .= "</tbody>";
                $html .= "<tfoot><tr><th colspan='4' class='text-end'>TOTAL:</th><th>" . number_format($total_general, 2) . "</th></tr></tfoot>";
                $html .= "</table>";
                echo $html;
            }
        }
        break;

    case 'crearDesdePedido':
        $pedido_id = $_POST['pedido_id'] ?? $_GET['pedido_id'] ?? null;
        if (!$pedido_id) {
            $_SESSION['error'] = 'ID de pedido inválido.';
            header('Location: ?c=pedidos&accion=view');
            exit();
        }
        
        $compraModel = new CompraProductos();
        $res = $compraModel->crearDesdePedido($pedido_id);
        
        if (isset($res['success'])) {
            $_SESSION['success'] = "Compra registrada y pedido de proveedor recibido correctamente.";
            header('Location: ?c=proveedores&tab=compras');
        } else {
            $_SESSION['error'] = $res['error'] ?? 'Error al procesar la compra desde el pedido.';
            if (isset($res['pendientes'])) {
                header("Location: ?c=pedidos&accion=resolverDetalles&id={$pedido_id}");
            } else {
                header('Location: ?c=pedidos&accion=view');
            }
        }
        exit();
        break;

    default:
        header("Location: ?c=proveedores&tab=compras");
        exit();
        break;
}
