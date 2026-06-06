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
            $entradas->setIdProveedor($id_proveedor)
                     ->setNumeroLote($numero_lote)
                     ->setFechaIngreso($fecha_ingreso)
                     ->setDetalles($detalles);

            try {
                if ($entradas->insert()) {
                    $_SESSION['success'] = "Entrada registrada exitosamente.";
                } else {
                    $_SESSION['error'] = "Error al registrar la entrada.";
                }
            } catch (\PDOException $e) {
                if ($e->getCode() == 23000) {
                    $_SESSION['error'] = "Error: Esta entrada ya se encuentra registrada.";
                } else {
                    $_SESSION['error'] = "Error de base de datos: " . $e->getMessage();
                }
            }
        } else {
            $_SESSION['error'] = "Faltan datos requeridos o detalles de productos.";
        }
        header("Location: ?c=proveedores&tab=entradas");
        exit();
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
                $html .= "<thead><tr class='table-dark'><th>Producto</th><th>Variante</th><th>Cantidad</th><th>Precio Compra ($)</th><th>Subtotal ($)</th></tr></thead><tbody>";
                $total_general = 0;
                foreach ($detalles as $det) {
                    $subtotal = $det['cantidad'] * $det['precio_compra'];
                    $total_general += $subtotal;
                    $html .= "<tr>";
                    $html .= "<td>" . htmlspecialchars($det['producto_nombre']) . "</td>";
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
        
        $entradasModel = new Entradas();
        $resultado = $entradasModel->crearDesdePedido($pedido_id);
        
        if (isset($resultado['error'])) {
            $_SESSION['error'] = $resultado['error'];
            if (isset($resultado['pendientes'])) {
                header("Location: ?c=pedidos&accion=resolverDetalles&id=$pedido_id");
            } else {
                header("Location: ?c=pedidos&accion=view");
            }
        } else {
            $_SESSION['success'] = 'Entrada creada correctamente.';
            header("Location: ?c=proveedores&tab=entradas");
        }
        exit();
        break;

    default:
        http_response_code(404);
        require_once __DIR__ . '/../Views/errors/404.php';
        break;
}
