<?php
namespace Lenovo\Dalu\Models;

use Lenovo\Dalu\Models\Conexion;
use PDO;
use PDOException;

class CompraProductos extends Conexion {
    private $id;
    private $id_proveedor;
    private $numero_lote;
    private $fecha_ingreso;
    private $total;
    private $detalles = [];

    public function __construct() {
        parent::__construct();
    }

    public function setId($id) { $this->id = $id; return $this; }
    public function setIdProveedor($id_proveedor) { $this->id_proveedor = $id_proveedor; return $this; }
    public function setNumeroLote($numero_lote) { $this->numero_lote = $numero_lote; return $this; }
    public function setFechaIngreso($fecha_ingreso) { $this->fecha_ingreso = $fecha_ingreso; return $this; }
    public function setTotal($total) { $this->total = $total; return $this; }
    public function setDetalles($detalles) { $this->detalles = $detalles; return $this; }

    public function getId() { return $this->id; }
    public function getIdProveedor() { return $this->id_proveedor; }
    public function getNumeroLote() { return $this->numero_lote; }
    public function getFechaIngreso() { return $this->fecha_ingreso; }
    public function getTotal() { return $this->total; }
    public function getDetallesArray() { return $this->detalles; }

    public function insert() {
        try {
            // Calcular el total de la compra
            $total_compra = 0;
            foreach ($this->detalles as $detalle) {
                $total_compra += ($detalle['cantidad'] * $detalle['precio_compra']);
            }

            // Insertar la cabecera (compra_productos)
            $sql = "INSERT INTO compra_productos (id_proveedor, numero_lote, fecha_ingreso, total) 
                    VALUES (:id_proveedor, :numero_lote, :fecha_ingreso, :total)";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id_proveedor", $this->id_proveedor);
            $stmt->bindParam(":numero_lote", $this->numero_lote);
            $stmt->bindParam(":fecha_ingreso", $this->fecha_ingreso);
            $stmt->bindParam(":total", $total_compra);
            $stmt->execute();

            $id_compra = $this->lastInsertId();
            $this->id = $id_compra;

            // Insertar detalles y actualizar stock
            foreach ($this->detalles as $detalle) {
                $sqlDetalle = "INSERT INTO detalles_compra_producto (id_compra, id_variante, cantidad, precio_compra) 
                               VALUES (:id_compra, :id_variante, :cantidad, :precio_compra)";
                $stmtDetalle = $this->prepare($sqlDetalle);
                $stmtDetalle->bindParam(":id_compra", $id_compra);
                
                $id_variante = !empty($detalle['id_variante']) ? $detalle['id_variante'] : null;
                $stmtDetalle->bindParam(":id_variante", $id_variante);
                $stmtDetalle->bindParam(":cantidad", $detalle['cantidad']);
                $stmtDetalle->bindParam(":precio_compra", $detalle['precio_compra']);
                $stmtDetalle->execute();

                // Identificar id_producto para actualización de precios
                $id_producto_actualizar = null;
                if (!empty($detalle['id_variante'])) {
                    $stmtProd = $this->prepare("SELECT id_producto FROM producto_variantes WHERE id = :id");
                    $stmtProd->bindParam(":id", $detalle['id_variante']);
                    $stmtProd->execute();
                    $resProd = $stmtProd->fetch(PDO::FETCH_ASSOC);
                    if ($resProd) $id_producto_actualizar = $resProd['id_producto'];
                } elseif (!empty($detalle['id_producto'])) {
                    $id_producto_actualizar = $detalle['id_producto'];
                }

                if ($id_producto_actualizar && floatval($detalle['precio_compra']) > 0) {
                    $prodModel = new \Lenovo\Dalu\Models\Productos();
                    $nuevo_precio_venta = $prodModel->calcularPrecioVentaDesdeCompra($detalle['precio_compra']);
                    
                    $sqlUpdatePrecio = "UPDATE productos SET precio_compra = :pc, precio_venta = :pv WHERE id = :id";
                    $stmtUpdPrecio = $this->prepare($sqlUpdatePrecio);
                    $stmtUpdPrecio->bindParam(":pc", $detalle['precio_compra']);
                    $stmtUpdPrecio->bindParam(":pv", $nuevo_precio_venta);
                    $stmtUpdPrecio->bindParam(":id", $id_producto_actualizar);
                    $stmtUpdPrecio->execute();
                }

                // Actualizar stock de la variante si aplica
                if (!empty($detalle['id_variante'])) {
                    $sqlUpdateVar = "UPDATE producto_variantes SET stock = stock + :cantidad WHERE id = :id_variante";
                    $stmtVar = $this->prepare($sqlUpdateVar);
                    $stmtVar->bindParam(":cantidad", $detalle['cantidad']);
                    $stmtVar->bindParam(":id_variante", $detalle['id_variante']);
                    $stmtVar->execute();
                }
            }

            return true;

        } catch (PDOException $e) {
            error_log("Error al registrar compra_productos: " . $e->getMessage());
            return false;
        }
    }

    public function search() {
        try {
            $sql = "SELECT c.id, c.numero_lote, c.fecha_ingreso, c.total, c.fecha_registro, p.nombre as proveedor_nombre 
                    FROM compra_productos c
                    LEFT JOIN proveedores p ON c.id_proveedor = p.id
                    ORDER BY c.fecha_registro DESC";
            $stmt = $this->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al buscar compra_productos: " . $e->getMessage());
            return [];
        }
    }

    public function getDetalles($id_compra) {
        try {
            $sql = "SELECT d.cantidad, d.precio_compra, p.nombre as producto_nombre, pv.nombre_variante 
                    FROM detalles_compra_producto d
                    LEFT JOIN producto_variantes pv ON d.id_variante = pv.id
                    LEFT JOIN productos p ON pv.id_producto = p.id
                    WHERE d.id_compra = :id_compra";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id_compra", $id_compra);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener detalles de compra: " . $e->getMessage());
            return [];
        }
    }

    public function crearDesdePedido($pedido_id) {
        $pedidoModel = new \Lenovo\Dalu\Models\Pedidos();
        $pedido = $pedidoModel->getById($pedido_id);
        
        if (!$pedido || ($pedido['tipo'] !== 'propios' && $pedido['tipo'] !== 'proveedor')) {
            return ['error' => 'Solo pedidos a proveedor o tienda pueden generar compras de productos'];
        }
        
        $pedidoModel->setId($pedido_id);
        $pendientes = $pedidoModel->getDetallesPendientes();
        
        if (!empty($pendientes)) {
            return ['error' => 'Hay detalles pendientes de resolver en el pedido', 'pendientes' => $pendientes];
        }
        
        $detallesPedido = $pedido['detalles'];
        $detallesCompra = [];
        
        foreach ($detallesPedido as $dp) {
            if ($dp['status_inventario'] === 'vinculado' || $dp['status_inventario'] === 'creado' || (!empty($dp['id_producto']) && $dp['status_inventario'] !== 'ignorado')) {
                $precio_compra = $dp['precio_unitario'] ?? 0;
                $detallesCompra[] = [
                    'id_producto' => $dp['id_producto'] ?? null,
                    'cantidad' => $dp['cantidad'],
                    'precio_compra' => $precio_compra,
                    'id_variante' => $dp['id_variante'] ?? null
                ];
            }
        }
        
        if (empty($detallesCompra)) {
            return ['error' => 'No hay productos válidos para generar la compra (todos fueron ignorados).'];
        }
        
        $id_proveedor = $pedido['id_proveedor'] ?? null;
        if (!$id_proveedor) {
            $nombre_proveedor = $pedido['nombre_proveedor'] ?: 'Proveedor Genérico';
            $id_proveedor = $this->obtenerOCrearProveedor($nombre_proveedor);
        }
        
        $numero_lote = 'LOTE-PED-' . $pedido_id . '-' . date('Ymd');
        $fecha_ingreso = date('Y-m-d');
        
        try {
            $this->beginTransaction();
            
            $total_compra = 0;
            foreach ($detallesCompra as $detalle) {
                $total_compra += ($detalle['cantidad'] * $detalle['precio_compra']);
            }
            
            $sql = "INSERT INTO compra_productos (id_proveedor, numero_lote, fecha_ingreso, total) 
                    VALUES (:id_proveedor, :numero_lote, :fecha_ingreso, :total)";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id_proveedor", $id_proveedor);
            $stmt->bindParam(":numero_lote", $numero_lote);
            $stmt->bindParam(":fecha_ingreso", $fecha_ingreso);
            $stmt->bindParam(":total", $total_compra);
            $stmt->execute();
            
            $id_compra = $this->lastInsertId();
            
            foreach ($detallesCompra as $detalle) {
                $sqlDetalle = "INSERT INTO detalles_compra_producto (id_compra, id_variante, cantidad, precio_compra) 
                               VALUES (:id_compra, :id_variante, :cantidad, :precio_compra)";
                $stmtDetalle = $this->prepare($sqlDetalle);
                $stmtDetalle->bindParam(":id_compra", $id_compra);
                $stmtDetalle->bindParam(":id_variante", $detalle['id_variante']);
                $stmtDetalle->bindParam(":cantidad", $detalle['cantidad']);
                $stmtDetalle->bindParam(":precio_compra", $detalle['precio_compra']);
                $stmtDetalle->execute();

                if (!empty($detalle['id_variante'])) {
                    $sqlUpdateVar = "UPDATE producto_variantes SET stock = stock + :cantidad WHERE id = :id_variante";
                    $stmtVar = $this->prepare($sqlUpdateVar);
                    $stmtVar->bindParam(":cantidad", $detalle['cantidad']);
                    $stmtVar->bindParam(":id_variante", $detalle['id_variante']);
                    $stmtVar->execute();
                }

                if (!empty($detalle['id_producto']) && floatval($detalle['precio_compra']) > 0) {
                    $prodModel = new \Lenovo\Dalu\Models\Productos();
                    $nuevo_precio_venta = $prodModel->calcularPrecioVentaDesdeCompra($detalle['precio_compra']);
                    
                    $sqlUpdatePrecio = "UPDATE productos SET precio_compra = :pc, precio_venta = :pv WHERE id = :id";
                    $stmtUpdPrecio = $this->prepare($sqlUpdatePrecio);
                    $stmtUpdPrecio->bindParam(":pc", $detalle['precio_compra']);
                    $stmtUpdPrecio->bindParam(":pv", $nuevo_precio_venta);
                    $stmtUpdPrecio->bindParam(":id", $detalle['id_producto']);
                    $stmtUpdPrecio->execute();
                }
            }
            
            $pedidoModel->marcarRecibido();
            
            $this->commit();
            return ['success' => true, 'id_compra' => $id_compra];
            
        } catch (\Exception $e) {
            $this->rollBack();
            error_log("Error en crearDesdePedido (CompraProductos): " . $e->getMessage());
            return ['error' => 'Ocurrió un error al crear la compra: ' . $e->getMessage()];
        }
    }
    
    private function obtenerOCrearProveedor($nombre) {
        $sql = "SELECT id FROM proveedores WHERE razon_social = :nombre OR nombre = :nombre LIMIT 1";
        $stmt = $this->prepare($sql);
        $stmt->bindParam(":nombre", $nombre);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            return $row['id'];
        }
        
        $sqlInsert = "INSERT INTO proveedores (razon_social, documento_identidad, nombre, apellido, correo) 
                      VALUES (:razon_social, :doc, :nombre, '', 'sin_correo@ejemplo.com')";
        $stmtInsert = $this->prepare($sqlInsert);
        $doc = 'N/A';
        $stmtInsert->bindParam(":razon_social", $nombre);
        $stmtInsert->bindParam(":doc", $doc);
        $stmtInsert->bindParam(":nombre", $nombre);
        $stmtInsert->execute();
        
        return $this->lastInsertId();
    }
}
