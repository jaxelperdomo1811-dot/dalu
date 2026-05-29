<?php
namespace Lenovo\Dalu\Models;
use Lenovo\Dalu\Models\Conexion;
use PDO;
use PDOException;

class Entradas extends Conexion {
    
    public function __construct() {
        parent::__construct();
    }

    /**
     * Registra una nueva entrada y sus detalles, actualizando el stock.
     */
    public function registrarEntrada($id_proveedor, $numero_lote, $fecha_ingreso, $detalles) {
        try {

            // Calcular el total de la entrada
            $total_entrada = 0;
            foreach ($detalles as $detalle) {
                $total_entrada += ($detalle['cantidad'] * $detalle['precio_compra']);
            }

            // Insertar la cabecera (entrada)
            $sql = "INSERT INTO entradas (id_proveedor, numero_lote, fecha_ingreso, total) 
                    VALUES (:id_proveedor, :numero_lote, :fecha_ingreso, :total)";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id_proveedor", $id_proveedor);
            $stmt->bindParam(":numero_lote", $numero_lote);
            $stmt->bindParam(":fecha_ingreso", $fecha_ingreso);
            $stmt->bindParam(":total", $total_entrada);
            $stmt->execute();

            $id_entrada = $this->lastInsertId();

            // Insertar detalles y actualizar stock
            foreach ($detalles as $detalle) {
                // Insertar detalle
                $sqlDetalle = "INSERT INTO detalles_entrada (id_entrada, id_producto, cantidad, precio_compra) 
                               VALUES (:id_entrada, :id_producto, :cantidad, :precio_compra)";
                $stmtDetalle = $this->prepare($sqlDetalle);
                $stmtDetalle->bindParam(":id_entrada", $id_entrada);
                $stmtDetalle->bindParam(":id_producto", $detalle['id_producto']);
                $stmtDetalle->bindParam(":cantidad", $detalle['cantidad']);
                $stmtDetalle->bindParam(":precio_compra", $detalle['precio_compra']);
                $stmtDetalle->execute();

                // Actualizar stock del producto
                $sqlUpdateStock = "UPDATE productos SET stock_total = stock_total + :cantidad WHERE id = :id_producto";
                $stmtStock = $this->prepare($sqlUpdateStock);
                $stmtStock->bindParam(":cantidad", $detalle['cantidad']);
                $stmtStock->bindParam(":id_producto", $detalle['id_producto']);
                $stmtStock->execute();
            }

            return true;

        } catch (PDOException $e) {
            error_log("Error al registrar entrada: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene el listado de todas las entradas.
     */
    public function search() {
        try {
            $sql = "SELECT e.id, e.numero_lote, e.fecha_ingreso, e.total, e.fecha_registro, p.nombre as proveedor_nombre 
                    FROM entradas e
                    LEFT JOIN proveedores p ON e.id_proveedor = p.id
                    ORDER BY e.fecha_registro DESC";
            $stmt = $this->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al buscar entradas: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene los detalles de una entrada específica.
     */
    public function getDetalles($id_entrada) {
        try {
            $sql = "SELECT d.cantidad, d.precio_compra, p.nombre as producto_nombre 
                    FROM detalles_entrada d
                    INNER JOIN productos p ON d.id_producto = p.id
                    WHERE d.id_entrada = :id_entrada";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id_entrada", $id_entrada);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener detalles: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Crear una entrada a partir de un pedido de tienda resuelto
     */
    public function crearDesdePedido($pedido_id) {
        $pedidoModel = new \Lenovo\Dalu\Models\Pedidos();
        $pedido = $pedidoModel->getById($pedido_id);
        
        if (!$pedido || $pedido['tipo'] !== 'propios') {
            return ['error' => 'Solo pedidos de tienda pueden generar entradas'];
        }
        
        // Verificar que no haya detalles pendientes
        $pedidoModel->setId($pedido_id);
        $pendientes = $pedidoModel->getDetallesPendientes();
        
        if (!empty($pendientes)) {
            return ['error' => 'Hay detalles pendientes de resolver', 'pendientes' => $pendientes];
        }
        
        // Procesar detalles del pedido
        $detallesPedido = $pedido['detalles'];
        $detallesEntrada = [];
        
        foreach ($detallesPedido as $dp) {
            // Solo procesar los vinculados/creados
            if ($dp['status_inventario'] === 'vinculado' || $dp['status_inventario'] === 'creado' || (!empty($dp['id_producto']) && $dp['status_inventario'] !== 'ignorado')) {
                // Asumimos un precio de compra igual al precio_unitario del pedido, o 0 si no hay
                $precio_compra = $dp['precio_unitario'] ?? 0;
                $detallesEntrada[] = [
                    'id_producto' => $dp['id_producto'],
                    'cantidad' => $dp['cantidad'],
                    'precio_compra' => $precio_compra,
                    'id_variante' => $dp['id_variante'] ?? null
                ];
            }
        }
        
        if (empty($detallesEntrada)) {
            return ['error' => 'No hay productos válidos para generar la entrada (todos fueron ignorados).'];
        }
        
        // Obtener o crear proveedor
        $nombre_proveedor = $pedido['nombre_proveedor'] ?: 'Proveedor Genérico';
        $id_proveedor = $this->obtenerOCrearProveedor($nombre_proveedor);
        
        $numero_lote = 'LOTE-PED-' . $pedido_id . '-' . date('Ymd');
        $fecha_ingreso = date('Y-m-d');
        
        try {
            $this->beginTransaction();
            
            // 1. Insertar la cabecera (entrada)
            $total_entrada = 0;
            foreach ($detallesEntrada as $detalle) {
                $total_entrada += ($detalle['cantidad'] * $detalle['precio_compra']);
            }
            
            $sql = "INSERT INTO entradas (id_proveedor, numero_lote, fecha_ingreso, total) 
                    VALUES (:id_proveedor, :numero_lote, :fecha_ingreso, :total)";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id_proveedor", $id_proveedor);
            $stmt->bindParam(":numero_lote", $numero_lote);
            $stmt->bindParam(":fecha_ingreso", $fecha_ingreso);
            $stmt->bindParam(":total", $total_entrada);
            $stmt->execute();
            
            $id_entrada = $this->lastInsertId();
            
            // 2. Insertar detalles y actualizar stock
            foreach ($detallesEntrada as $detalle) {
                // Insertar detalle
                $sqlDetalle = "INSERT INTO detalles_entrada (id_entrada, id_producto, cantidad, precio_compra) 
                               VALUES (:id_entrada, :id_producto, :cantidad, :precio_compra)";
                $stmtDetalle = $this->prepare($sqlDetalle);
                $stmtDetalle->bindParam(":id_entrada", $id_entrada);
                $stmtDetalle->bindParam(":id_producto", $detalle['id_producto']);
                $stmtDetalle->bindParam(":cantidad", $detalle['cantidad']);
                $stmtDetalle->bindParam(":precio_compra", $detalle['precio_compra']);
                $stmtDetalle->execute();

                // Actualizar stock del producto (stock_total)
                // Usando un query directo como en registrarEntrada
                $sqlUpdateStock = "UPDATE productos SET stock_total = stock_total + :cantidad WHERE id = :id_producto";
                $stmtStock = $this->prepare($sqlUpdateStock);
                $stmtStock->bindParam(":cantidad", $detalle['cantidad']);
                $stmtStock->bindParam(":id_producto", $detalle['id_producto']);
                $stmtStock->execute();
                
                // Actualizar stock de la variante si aplica
                if (!empty($detalle['id_variante'])) {
                    $sqlUpdateVar = "UPDATE producto_variantes SET stock = stock + :cantidad WHERE id = :id_variante";
                    $stmtVar = $this->prepare($sqlUpdateVar);
                    $stmtVar->bindParam(":cantidad", $detalle['cantidad']);
                    $stmtVar->bindParam(":id_variante", $detalle['id_variante']);
                    $stmtVar->execute();
                }
            }
            
            // 3. Actualizar estado del pedido a recibido
            $pedidoModel->marcarRecibido();
            
            $this->commit();
            return ['success' => true, 'id_entrada' => $id_entrada];
            
        } catch (\Exception $e) {
            $this->rollBack();
            error_log("Error en crearDesdePedido: " . $e->getMessage());
            return ['error' => 'Ocurrió un error al crear la entrada: ' . $e->getMessage()];
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
        
        // Crear nuevo proveedor
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
