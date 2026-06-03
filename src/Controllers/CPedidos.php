<?php
namespace Lenovo\Dalu\Controllers;

use Lenovo\Dalu\Models\Pedidos;
use Lenovo\Dalu\Models\Clientes;
use Lenovo\Dalu\Models\Proveedores;
use Lenovo\Dalu\Models\Productos;

$accion = $_GET['accion'] ?? $_POST['accion'] ?? 'view';

switch ($accion) {
    case 'view':
        $pedidos = (new Pedidos())->getByTipo('propios');
        $pedidosC = (new Pedidos())->getByTipo('cliente');
        $pedidosP = (new Pedidos())->getByTipo('proveedor');
        $clientes = (new Clientes())->search();
        $proveedores = (new Proveedores())->search();
        require_once __DIR__ . '/../Views/V_Pedidos.php';
        break;

    case 'insertTienda':
        if (empty($_POST['nombre_proveedor'])) {
            $_SESSION['error'] = 'Debe ingresar el nombre del proveedor.';
            $redirect_url = isset($_POST['from_servicios']) ? '?c=servicios&accion=view' : '?c=pedidos&accion=view';
            header('Location: ' . $redirect_url);
            exit();
        }

        $pedido = new Pedidos();
        $pedido->setTipo('propios')
               ->setEstado($_POST['estado'] ?? 'pendiente')
               ->setIdCliente(null)
               ->setNombreProveedor(trim($_POST['nombre_proveedor']));

        // Procesar detalles opcionales
        $productosModel = new Productos();
        $detalles = [];
        if (isset($_POST['detalles']) && is_array($_POST['detalles'])) {
            foreach ($_POST['detalles'] as $index => $d) {
                $hasFile = isset($_FILES['detalleImagens']['tmp_name'][$index]) && $_FILES['detalleImagens']['error'][$index] === UPLOAD_ERR_OK;
                $hasDetalle = !empty($d['id_producto']) || !empty($d['nombre_producto']) || !empty($d['link']) || $hasFile;
                if ($hasDetalle) {
                    $imagenRuta = '';
                    if ($hasFile) {
                        $categoriaNombre = !empty($d['id_producto']) ? $productosModel->getNombreCategoria($d['id_producto']) : 'sin_categoria';
                        $nombreImagen = !empty($d['nombre_producto']) ? $d['nombre_producto'] : 'detalle_' . $index;
                        $fileData = [
                            'name' => $_FILES['detalleImagens']['name'][$index],
                            'type' => $_FILES['detalleImagens']['type'][$index],
                            'tmp_name' => $_FILES['detalleImagens']['tmp_name'][$index],
                            'error' => $_FILES['detalleImagens']['error'][$index],
                            'size' => $_FILES['detalleImagens']['size'][$index],
                        ];
                        $imagenRuta = $productosModel->subirImagen($fileData, $categoriaNombre, $nombreImagen) ?? '';
                    }

                    $detalles[] = [
                        'tipo' => 'proveedor',
                        'imagen' => $imagenRuta,
                        'link' => trim($d['link'] ?? ''),
                        'estado' => $d['estado'] ?? 'pendiente',
                        'nombre_producto' => trim($d['nombre_producto'] ?? '') ?: null,
                        'id_producto' => $d['id_producto'] ?? null,
                        'cantidad' => !empty($d['cantidad']) ? (int) $d['cantidad'] : 1,
                        'precio_unitario' => null,
                        'descripcion_producto' => null,
                        'id_variante' => null,
                    ];
                }
            }
        }
        $pedido->setDetalles($detalles);

        if ($pedido->insert()) {
            $_SESSION['success'] = 'Pedido de tienda registrado correctamente.';
        } else {
            $_SESSION['error'] = 'Error al registrar el pedido de tienda.';
        }

        $redirect_url = isset($_POST['from_servicios']) ? '?c=servicios&accion=view' : '?c=pedidos&accion=view';
            header('Location: ' . $redirect_url);
        exit();
        break;


    case 'insertProveedor':
        if (empty($_POST['id_proveedor'])) {
            $_SESSION['error'] = 'Debe seleccionar un proveedor.';
            $redirect_url = isset($_POST['from_servicios']) ? '?c=servicios&accion=view' : '?c=pedidos&accion=view';
            header('Location: ' . $redirect_url);
            exit();
        }

        $pedido = new Pedidos();
        $pedido->setTipo('proveedor')
               ->setEstado($_POST['estado'] ?? 'pendiente')
               ->setIdCliente(null)
               ->setNombreProveedor(null);

        // Procesar detalles opcionales
        $productosModel = new Productos();
        $detalles = [];
        if (isset($_POST['detalles']) && is_array($_POST['detalles'])) {
            foreach ($_POST['detalles'] as $index => $d) {
                $hasFile = isset($_FILES['detalleImagens']['tmp_name'][$index]) && $_FILES['detalleImagens']['error'][$index] === UPLOAD_ERR_OK;
                $hasDetalle = !empty($d['id_producto']) || !empty($d['nombre_producto']) || !empty($d['link']) || $hasFile;
                if ($hasDetalle) {
                    $imagenRuta = '';
                    if ($hasFile) {
                        $categoriaNombre = !empty($d['id_producto']) ? $productosModel->getNombreCategoria($d['id_producto']) : 'sin_categoria';
                        $nombreImagen = !empty($d['nombre_producto']) ? $d['nombre_producto'] : 'detalle_' . $index;
                        $fileData = [
                            'name' => $_FILES['detalleImagens']['name'][$index],
                            'type' => $_FILES['detalleImagens']['type'][$index],
                            'tmp_name' => $_FILES['detalleImagens']['tmp_name'][$index],
                            'error' => $_FILES['detalleImagens']['error'][$index],
                            'size' => $_FILES['detalleImagens']['size'][$index],
                        ];
                        $imagenRuta = $productosModel->subirImagen($fileData, $categoriaNombre, $nombreImagen) ?? '';
                    }

                    $detalles[] = [
                        'tipo' => 'proveedor',
                        'imagen' => $imagenRuta,
                        'link' => trim($d['link'] ?? ''),
                        'estado' => $d['estado'] ?? 'pendiente',
                        'nombre_producto' => trim($d['nombre_producto'] ?? '') ?: null,
                        'id_producto' => $d['id_producto'] ?? null,
                        'cantidad' => !empty($d['cantidad']) ? (int) $d['cantidad'] : 1,
                        'precio_unitario' => null,
                        'descripcion_producto' => null,
                        'id_variante' => null,
                    ];
                }
            }
        }
        $pedido->setDetalles($detalles);

        if ($pedido->insert()) {
            $_SESSION['success'] = 'Pedido de proveedor registrado correctamente.';
        } else {
            $_SESSION['error'] = 'Error al registrar el pedido de proveedor.';
        }

        $redirect_url = isset($_POST['from_servicios']) ? '?c=servicios&accion=view' : '?c=pedidos&accion=view';
            header('Location: ' . $redirect_url);
        exit();
        break;

    case 'cancelarPedido':
        $pedidoId = $_POST['id'] ?? null;
        if (empty($pedidoId)) {
            $_SESSION['error'] = 'ID de pedido inválido.';
            $redirect_url = isset($_POST['from_servicios']) ? '?c=servicios&accion=view' : '?c=pedidos&accion=view';
            header('Location: ' . $redirect_url);
            exit();
        }

        $pedido = new Pedidos();
        $pedido->setId($pedidoId);
        if ($pedido->cancel()) {
            $_SESSION['success'] = 'Pedido cancelado correctamente.';
        } else {
            $_SESSION['error'] = 'No se pudo cancelar el pedido.';
        }

        $redirect_url = isset($_POST['from_servicios']) ? '?c=servicios&accion=view' : '?c=pedidos&accion=view';
            header('Location: ' . $redirect_url);
        exit();
        break;

    case 'avanzarEstado':
        $pedidoId = $_POST['id'] ?? null;
        if (empty($pedidoId)) {
            $_SESSION['error'] = 'ID de pedido inválido.';
            $redirect_url = isset($_POST['from_servicios']) ? '?c=servicios&accion=view' : '?c=pedidos&accion=view';
            header('Location: ' . $redirect_url);
            exit();
        }

        $pedidoModel = new Pedidos();
        $pedido = $pedidoModel->getById($pedidoId);
        if (!$pedido) {
            $_SESSION['error'] = 'Pedido no encontrado.';
            $redirect_url = isset($_POST['from_servicios']) ? '?c=servicios&accion=view' : '?c=pedidos&accion=view';
            header('Location: ' . $redirect_url);
            exit();
        }

        $ordenEstados = [
            'pendiente' => 'confirmado',
            'confirmado' => 'enviado',
            'enviado' => 'recibido',
            'recibido' => 'entregado',
        ];

        $estadoActual = $pedido['estado'] ?? 'pendiente';
        if (!isset($ordenEstados[$estadoActual])) {
            $_SESSION['error'] = 'El pedido no puede avanzar desde el estado actual.';
            $redirect_url = isset($_POST['from_servicios']) ? '?c=servicios&accion=view' : '?c=pedidos&accion=view';
            header('Location: ' . $redirect_url);
            exit();
        }

        $pedidoModel->setId($pedidoId)->setEstado($ordenEstados[$estadoActual]);
        if ($pedidoModel->updateEstado()) {
            $_SESSION['success'] = 'Estado del pedido actualizado a ' . $ordenEstados[$estadoActual] . '.';
        } else {
            $_SESSION['error'] = 'No se pudo actualizar el estado del pedido.';
        }

        $redirect_url = isset($_POST['from_servicios']) ? '?c=servicios&accion=view' : '?c=pedidos&accion=view';
            header('Location: ' . $redirect_url);
        exit();
        break;

    case 'view_detalles':
        $pedidoId = $_GET['id'] ?? null;
        if (!$pedidoId) {
            echo '<div class="alert alert-danger">ID de pedido inválido.</div>';
            break;
        }

        $pedidoModel = new Pedidos();
        $pedido = $pedidoModel->getById($pedidoId);

        if (!$pedido) {
            echo '<div class="alert alert-warning">No se encontró el pedido solicitado.</div>';
            break;
        }

        $clienteNombre = $pedido['cliente_nombre'] ? trim($pedido['cliente_nombre'] . ' ' . $pedido['cliente_apellido']) : null;
        $origen = $pedido['tipo'] === 'cliente' ? $clienteNombre : ($pedido['nombre_proveedor'] ?: 'Proveedor no registrado');
        $fecha = $pedido['fecha_pedido'] ?? $pedido['fecha_registro'] ?? 'No disponible';
        $estado = htmlspecialchars($pedido['estado']);
        $tipo = htmlspecialchars($pedido['tipo']);

        $html = '<div class="row mb-3">';
        $html .= '<div class="col-md-4"><strong>Pedido #</strong> ' . htmlspecialchars($pedido['id']) . '</div>';
        $html .= '<div class="col-md-4"><strong>Origen</strong> ' . htmlspecialchars($origen) . '</div>';
        $html .= '<div class="col-md-4"><strong>Fecha</strong> ' . htmlspecialchars($fecha) . '</div>';
        $html .= '<div class="col-md-4"><strong>Tipo</strong> ' . $tipo . '</div>';
        $html .= '<div class="col-md-4"><strong>Estado</strong> ' . $estado . '</div>';
        $html .= '</div>';

        if (empty($pedido['detalles'])) {
            $html .= '<div class="alert alert-info">No hay detalles registrados para este pedido.</div>';
            echo $html;
            break;
        }

        $html .= '<div class="table-responsive"><table class="table table-bordered table-striped">';
        $html .= '<thead class="table-dark"><tr><th>#</th><th>Producto / Descripción</th><th>Cantidad</th><th>Link</th><th>Imagen</th><th>Variante</th><th>Estado inventario</th></tr></thead><tbody>';
        foreach ($pedido['detalles'] as $index => $detalle) {
            $productoNombre = $detalle['producto_nombre'] ?: $detalle['nombre_producto'] ?: 'No definido';
            $linkHtml = !empty($detalle['link']) ? '<a href="' . htmlspecialchars($detalle['link']) . '" target="_blank">Ver</a>' : '-';
            $imagenHtml = !empty($detalle['imagen']) ? '<a href="' . htmlspecialchars($detalle['imagen']) . '" target="_blank">Imagen</a>' : '-';
            $variante = '-';
            if (!empty($detalle['variante_nombre'])) {
                $variante = htmlspecialchars($detalle['variante_nombre']);
                if (!empty($detalle['variante_atributos']) && is_array($detalle['variante_atributos'])) {
                    $variante .= ' (' . htmlspecialchars(json_encode($detalle['variante_atributos'], JSON_UNESCAPED_UNICODE)) . ')';
                }
            }
            $html .= '<tr>';
            $html .= '<td>' . ($index + 1) . '</td>';
            $html .= '<td>' . htmlspecialchars($productoNombre) . '</td>';
            $html .= '<td>' . htmlspecialchars($detalle['cantidad']) . '</td>';
            $html .= '<td>' . $linkHtml . '</td>';
            $html .= '<td>' . $imagenHtml . '</td>';
            $html .= '<td>' . $variante . '</td>';
            $html .= '<td>' . htmlspecialchars($detalle['status_inventario'] ?? $detalle['estado']) . '</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody></table></div>';

        echo $html;
        break;

    case 'resolverDetalles':
        $pedidoId = $_GET['id'] ?? null;
        if (!$pedidoId) {
            $_SESSION['error'] = 'ID de pedido inválido.';
            $redirect_url = isset($_POST['from_servicios']) ? '?c=servicios&accion=view' : '?c=pedidos&accion=view';
            header('Location: ' . $redirect_url);
            exit();
        }
        
        $pedidoModel = new Pedidos();
        $pedido = $pedidoModel->getPedidoConDetallesPendientes($pedidoId);
        
        if (!$pedido) {
            $_SESSION['error'] = 'Pedido no encontrado.';
            $redirect_url = isset($_POST['from_servicios']) ? '?c=servicios&accion=view' : '?c=pedidos&accion=view';
            header('Location: ' . $redirect_url);
            exit();
        }
        
        if (empty($pedido['detalles_pendientes'])) {
            $_SESSION['success'] = 'Todos los detalles del pedido ya están resueltos.';
            // En caso de que ya estén resueltos, podría redirigir de vuelta o a crear entrada
            $redirect_url = isset($_POST['from_servicios']) ? '?c=servicios&accion=view' : '?c=pedidos&accion=view';
            header('Location: ' . $redirect_url);
            exit();
        }
        
        $productos = (new Productos())->search(); // Para el buscador de productos existentes
        $categorias = (new \Lenovo\Dalu\Models\Categorias())->search(); // Para el modal de nuevo producto
        
        require_once __DIR__ . '/../Views/V_ResolverDetalles.php';
        break;

    case 'vincularDetalleAjax':
        header('Content-Type: application/json');
        $detalleId = $_POST['detalle_id'] ?? null;
        $idProducto = $_POST['id_producto'] ?? null;
        $idVariante = !empty($_POST['id_variante']) ? $_POST['id_variante'] : null;
        
        if (!$detalleId || !$idProducto) {
            echo json_encode(['success' => false, 'error' => 'Faltan datos requeridos']);
            exit();
        }
        
        $pedidoModel = new Pedidos();
        $success = $pedidoModel->vincularDetalleAProducto($detalleId, $idProducto, $idVariante);
        
        echo json_encode(['success' => $success]);
        exit();
        break;

    case 'crearProductoDesdeDetalleAjax':
        header('Content-Type: application/json');
        $detalleId = $_POST['detalle_id'] ?? null;
        
        $productoData = [
            'nombre' => $_POST['nombre'] ?? '',
            'id_categoria' => $_POST['id_categoria'] ?? null,
            'precio_venta' => $_POST['precio_venta'] ?? 0,
            'precio_compra' => $_POST['precio_compra'] ?? 0,
            'cantidad' => $_POST['cantidad'] ?? 1,
            'marca' => $_POST['marca'] ?? null,
            'descripcion' => $_POST['descripcion'] ?? '',
            'atributos' => !empty($_POST['atributos']) ? json_decode($_POST['atributos'], true) : []
        ];
        
        // Manejo de imagen opcional si viene en FormData
        if (isset($_FILES['imagen_producto']) && $_FILES['imagen_producto']['error'] === UPLOAD_ERR_OK) {
            $productosModel = new Productos();
            $categoriaNombre = $productosModel->getNombreCategoria($productoData['id_categoria']);
            $fileData = [
                'name' => $_FILES['imagen_producto']['name'],
                'type' => $_FILES['imagen_producto']['type'],
                'tmp_name' => $_FILES['imagen_producto']['tmp_name'],
                'error' => $_FILES['imagen_producto']['error'],
                'size' => $_FILES['imagen_producto']['size'],
            ];
            $productoData['imagen'] = $productosModel->subirImagen($fileData, $categoriaNombre, $productoData['nombre']);
        }
        
        if (!$detalleId || !$productoData['nombre'] || !$productoData['id_categoria']) {
            echo json_encode(['success' => false, 'error' => 'Faltan datos obligatorios del producto']);
            exit();
        }
        
        $pedidoModel = new Pedidos();
        $success = $pedidoModel->crearProductoDesdeDetalle($detalleId, $productoData);
        
        echo json_encode(['success' => $success !== false]);
        exit();
        break;

    case 'ignorarDetalleAjax':
        header('Content-Type: application/json');
        $detalleId = $_POST['detalle_id'] ?? null;
        
        if (!$detalleId) {
            echo json_encode(['success' => false, 'error' => 'ID de detalle inválido']);
            exit();
        }
        
        $pedidoModel = new Pedidos();
        $success = $pedidoModel->ignorarDetalle($detalleId);
        
        echo json_encode(['success' => $success]);
        exit();
        break;

    default:
        http_response_code(404);
        require_once __DIR__ . '/../Views/errors/404.php';
        break;
}