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
            header('Location: ?c=pedidos&accion=view');
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
                        'tipo' => $d['tipo'] ?? 'producto',
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

        header('Location: ?c=pedidos&accion=view');
        exit();
        break;

    case 'insertCliente':
        if (empty($_POST['id_cliente'])) {
            $_SESSION['error'] = 'Debe seleccionar un cliente.';
            header('Location: ?c=pedidos&accion=view');
            exit();
        }

        $pedido = new Pedidos();
        $pedido->setTipo('cliente')
               ->setEstado($_POST['estado'] ?? 'pendiente')
               ->setIdCliente($_POST['id_cliente'])
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
                        'tipo' => $d['tipo'] ?? 'producto',
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
            $_SESSION['success'] = 'Pedido de cliente registrado correctamente.';
        } else {
            $_SESSION['error'] = 'Error al registrar el pedido de cliente.';
        }

        header('Location: ?c=pedidos&accion=view');
        exit();
        break;

    case 'insertProveedor':
        if (empty($_POST['id_proveedor'])) {
            $_SESSION['error'] = 'Debe seleccionar un proveedor.';
            header('Location: ?c=pedidos&accion=view');
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
                        'tipo' => $d['tipo'] ?? 'producto',
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

        header('Location: ?c=pedidos&accion=view');
        exit();
        break;

    default:
        http_response_code(404);
        require_once __DIR__ . '/../Views/errors/404.php';
        break;
}