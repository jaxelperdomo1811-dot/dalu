<?php
namespace Lenovo\Dalu\Models;

use Lenovo\Dalu\Models\Conexion;
use PDO;

class Despachos extends Conexion {
    private $id;
    private $id_cliente;
    private $numero_despacho;
    private $fecha_despacho;
    private $total;
    private $estado;
    private $fecha_registro;
    
    private $detalles = [];

    public function __construct($id = null, $id_cliente = null, $numero_despacho = null, $fecha_despacho = null, $total = 0, $estado = 'pendiente') {
        parent::__construct();
        $this->id = $id;
        $this->id_cliente = $id_cliente;
        $this->numero_despacho = $numero_despacho;
        $this->fecha_despacho = $fecha_despacho;
        $this->total = $total;
        $this->estado = $estado;
    }
    
    // SETTERS
    public function setId($id) { $this->id = $id; return $this; }
    public function setIdCliente($id_cliente) { $this->id_cliente = $id_cliente; return $this; }
    public function setNumeroDespacho($numero_despacho) { $this->numero_despacho = $numero_despacho; return $this; }
    public function setFechaDespacho($fecha_despacho) { $this->fecha_despacho = $fecha_despacho; return $this; }
    public function setTotal($total) { $this->total = $total; return $this; }
    public function setEstado($estado) { $this->estado = $estado; return $this; }
    public function setDetalles($detalles) { $this->detalles = $detalles; return $this; }

    // GETTERS
    public function getId() { return $this->id; }
    public function getIdCliente() { return $this->id_cliente; }
    public function getNumeroDespacho() { return $this->numero_despacho; }
    public function getFechaDespacho() { return $this->fecha_despacho; }
    public function getTotal() { return $this->total; }
    public function getEstado() { return $this->estado; }
    public function getFechaRegistro() { return $this->fecha_registro; }
    public function getDetalles() { return $this->detalles; }

    public function insert() {
        try {
            $this->beginTransaction();

            $sql = "INSERT INTO despachos (id_cliente, numero_despacho, fecha_despacho, total, estado) 
                    VALUES (:id_cliente, :numero_despacho, :fecha_despacho, :total, :estado)";
            
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id_cliente", $this->id_cliente);
            $stmt->bindParam(":numero_despacho", $this->numero_despacho);
            $stmt->bindParam(":fecha_despacho", $this->fecha_despacho);
            $stmt->bindParam(":total", $this->total);
            $stmt->bindParam(":estado", $this->estado);
            
            if (!$stmt->execute()) {
                $this->rollBack();
                return false;
            }
            
            $despacho_id = $this->lastInsertId();
            $this->id = $despacho_id;
            
            if (!empty($this->detalles)) {
                if (!$this->insertDetalles($despacho_id)) {
                    $this->rollBack();
                    return false;
                }
            }
            
            $this->commit();
            return $despacho_id;
            
        } catch (\PDOException $e) {
            if ($this->inTransaction()) {
                $this->rollBack();
            }
            error_log("Error en insert despacho: " . $e->getMessage());
            return false;
        }
    }

    private function insertDetalles($despacho_id) {
        $sql = "INSERT INTO detalles_despachos (id_despacho, id_producto, id_variante, cantidad, precio_unitario) 
                VALUES (:id_despacho, :id_producto, :id_variante, :cantidad, :precio_unitario)";
        $stmt = $this->prepare($sql);
        
        $sqlStock = "UPDATE productos SET stock_total = stock_total - :cantidad WHERE id = :id_producto AND stock_total >= :cantidad";
        $stmtStock = $this->prepare($sqlStock);

        $sqlVarianteStock = "UPDATE producto_variantes SET stock = stock - :cantidad WHERE id = :id_variante AND stock >= :cantidad";
        $stmtVarianteStock = $this->prepare($sqlVarianteStock);
        
        foreach ($this->detalles as $detalle) {
            $params = [
                ':id_despacho' => $despacho_id,
                ':id_producto' => $detalle['id_producto'],
                ':id_variante' => !empty($detalle['id_variante']) ? $detalle['id_variante'] : null,
                ':cantidad' => $detalle['cantidad'],
                ':precio_unitario' => $detalle['precio_unitario']
            ];
            
            if (!$stmt->execute($params)) {
                return false;
            }

            // Descontar inventario producto
            $stmtStock->bindParam(":cantidad", $detalle['cantidad']);
            $stmtStock->bindParam(":id_producto", $detalle['id_producto']);
            if (!$stmtStock->execute() || $stmtStock->rowCount() == 0) {
                // Stock insuficiente
                return false;
            }

            // Descontar inventario variante si existe
            if (!empty($detalle['id_variante'])) {
                $stmtVarianteStock->bindParam(":cantidad", $detalle['cantidad']);
                $stmtVarianteStock->bindParam(":id_variante", $detalle['id_variante']);
                if (!$stmtVarianteStock->execute() || $stmtVarianteStock->rowCount() == 0) {
                    return false;
                }
            }
        }
        return true;
    }

    public function search() {
        $sql = "SELECT d.*, c.nombre as cliente_nombre, c.apellido as cliente_apellido 
                FROM despachos d
                LEFT JOIN clientes c ON d.id_cliente = c.id
                ORDER BY d.fecha_registro DESC";
        $stmt = $this->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT d.*, c.nombre as cliente_nombre, c.apellido as cliente_apellido, c.cedula as cliente_cedula 
                FROM despachos d
                LEFT JOIN clientes c ON d.id_cliente = c.id
                WHERE d.id = :id";
        $stmt = $this->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $despacho = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($despacho) {
            $despacho['detalles'] = $this->getDetallesByDespacho($id);
        }
        return $despacho;
    }

    public function getDetallesByDespacho($despacho_id) {
        $sql = "SELECT dd.*, p.nombre as producto_nombre, pv.nombre_variante as variante_nombre 
                FROM detalles_despachos dd
                JOIN productos p ON dd.id_producto = p.id
                LEFT JOIN producto_variantes pv ON dd.id_variante = pv.id
                WHERE dd.id_despacho = :id_despacho";
        $stmt = $this->prepare($sql);
        $stmt->bindParam(":id_despacho", $despacho_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateEstado() {
        $sql = "UPDATE despachos SET estado = :estado WHERE id = :id";
        $stmt = $this->prepare($sql);
        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }
}
