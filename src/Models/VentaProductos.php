<?php
namespace Lenovo\Dalu\Models;

use Lenovo\Dalu\Models\Conexion;
use PDO;
use PDOException;

class VentaProductos extends Conexion {
    private $id;
    private $id_cliente;
    private $id_nota_entrega;
    private $fecha_venta;
    private $total;
    private $estado;
    private $detalles = [];

    public function __construct() {
        parent::__construct();
    }

    public function setId($id) { $this->id = $id; return $this; }
    public function setIdCliente($id_cliente) { $this->id_cliente = $id_cliente; return $this; }
    public function setIdNotaEntrega($id_nota_entrega) { $this->id_nota_entrega = $id_nota_entrega; return $this; }
    public function setFechaVenta($fecha_venta) { $this->fecha_venta = $fecha_venta; return $this; }
    public function setTotal($total) { $this->total = $total; return $this; }
    public function setEstado($estado) { $this->estado = $estado; return $this; }
    public function setDetalles($detalles) { $this->detalles = $detalles; return $this; }

    public function getId() { return $this->id; }
    public function getIdCliente() { return $this->id_cliente; }
    public function getIdNotaEntrega() { return $this->id_nota_entrega; }
    public function getFechaVenta() { return $this->fecha_venta; }
    public function getTotal() { return $this->total; }
    public function getEstado() { return $this->estado; }
    public function getDetalles() { return $this->detalles; }

    public function insert() {
        try {
            $this->beginTransaction();

            $total_venta = 0;
            foreach ($this->detalles as $detalle) {
                $subtotal = ($detalle['cantidad'] * $detalle['precio_unitario']);
                $total_venta += $subtotal;
            }

            $sql = "INSERT INTO venta_productos (id_cliente, id_nota_entrega, fecha_venta, total, estado) 
                    VALUES (:id_cliente, :id_nota_entrega, NOW(), :total, :estado)";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id_cliente", $this->id_cliente);
            $stmt->bindParam(":id_nota_entrega", $this->id_nota_entrega);
            $stmt->bindParam(":total", $total_venta);
            $estado = $this->estado ?? 'confirmado';
            $stmt->bindParam(":estado", $estado);
            $stmt->execute();

            $id_venta = $this->lastInsertId();
            $this->id = $id_venta;

            foreach ($this->detalles as $detalle) {
                $subtotal = ($detalle['cantidad'] * $detalle['precio_unitario']);
                $sqlDet = "INSERT INTO detalles_venta_producto (id_venta, id_variante, cantidad, precio_unitario, subtotal)
                           VALUES (:id_venta, :id_variante, :cantidad, :precio_unitario, :subtotal)";
                $stmtDet = $this->prepare($sqlDet);
                $stmtDet->bindParam(":id_venta", $id_venta);
                $stmtDet->bindParam(":id_variante", $detalle['id_variante']);
                $stmtDet->bindParam(":cantidad", $detalle['cantidad']);
                $stmtDet->bindParam(":precio_unitario", $detalle['precio_unitario']);
                $stmtDet->bindParam(":subtotal", $subtotal);
                $stmtDet->execute();

                // Descontar stock de la variante
                if (!empty($detalle['id_variante'])) {
                    $sqlStock = "UPDATE producto_variantes SET stock = GREATEST(0, stock - :cantidad) WHERE id = :id_variante";
                    $stmtStock = $this->prepare($sqlStock);
                    $stmtStock->bindParam(":cantidad", $detalle['cantidad']);
                    $stmtStock->bindParam(":id_variante", $detalle['id_variante']);
                    $stmtStock->execute();
                }
            }

            $this->commit();
            return $id_venta;

        } catch (PDOException $e) {
            $this->rollBack();
            error_log("Error al insertar venta_productos: " . $e->getMessage());
            return false;
        }
    }

    public function search() {
        try {
            $sql = "SELECT v.*, CONCAT(c.nombre, ' ', COALESCE(c.apellido, '')) as cliente_nombre, c.cedula
                    FROM venta_productos v
                    LEFT JOIN clientes c ON v.id_cliente = c.id
                    ORDER BY v.fecha_registro DESC";
            $stmt = $this->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al buscar ventas: " . $e->getMessage());
            return [];
        }
    }

    public function getDetallesVenta($id_venta) {
        try {
            $sql = "SELECT dv.*, pv.nombre_variante, p.nombre as producto_nombre
                    FROM detalles_venta_producto dv
                    LEFT JOIN producto_variantes pv ON dv.id_variante = pv.id
                    LEFT JOIN productos p ON pv.id_producto = p.id
                    WHERE dv.id_venta = :id_venta";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id_venta", $id_venta);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener detalles de venta: " . $e->getMessage());
            return [];
        }
    }
}
