<?php
namespace Lenovo\Dalu\Models;
use Lenovo\Dalu\Models\Conexion;
use PDO;
use PDOException;

class NotasEntrega extends Conexion {
    private $id;
    private $id_cliente;
    private $fecha_pedido;
    private $estado;
    private $tipo;
    private $total;
    private $observaciones;
    private $detalles = [];
    
    public function __construct() {
        parent::__construct();
    }   
    
    public function setId($id) { $this->id = $id; return $this; }
    public function setIdCliente($id_cliente) { $this->id_cliente = $id_cliente; return $this; }
    public function setFechaPedido($fecha_pedido) { $this->fecha_pedido = $fecha_pedido; return $this; }
    public function setEstado($estado) { $this->estado = $estado; return $this; }
    public function setTipo($tipo) { $this->tipo = $tipo; return $this; }
    public function setTotal($total) { $this->total = $total; return $this; }
    public function setObservaciones($observaciones) { $this->observaciones = $observaciones; return $this; }
    public function setDetalles($detalles) { $this->detalles = $detalles; return $this; }
    
    public function getId() { return $this->id; }
    public function getIdCliente() { return $this->id_cliente; }
    public function getFechaPedido() { return $this->fecha_pedido; }
    public function getEstado() { return $this->estado; }
    public function getTipo() { return $this->tipo; }
    public function getTotal() { return $this->total; }
    public function getObservaciones() { return $this->observaciones; }
    public function getDetallesObj() { return $this->detalles; }

    public function search() {
        try {
            $sql = "SELECT n.*, c.nombre as nombre_cliente 
                    FROM notas_entrega n
                    LEFT JOIN clientes c ON n.id_cliente = c.id
                    ORDER BY n.fecha_registro DESC";
            $stmt = $this->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al buscar notas de entrega: " . $e->getMessage());
            return [];
        }
    }

    public function getByTipo($tipo) {
        try {
            $sql = "SELECT n.*, c.nombre as nombre_cliente 
                    FROM notas_entrega n
                    LEFT JOIN clientes c ON n.id_cliente = c.id
                    WHERE n.tipo = :tipo
                    ORDER BY n.fecha_registro DESC";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(':tipo', $tipo);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al buscar notas de entrega por tipo: " . $e->getMessage());
            return [];
        }
    }

    public function getDetalles($id_nota) {
        try {
            $sql = "SELECT d.*, pv.nombre_variante, p.nombre as producto_nombre, p.id as id_producto 
                    FROM notas_entrega_detalles d
                    INNER JOIN producto_variantes pv ON d.id_variante = pv.id
                    INNER JOIN productos p ON pv.id_producto = p.id
                    WHERE d.id_nota_entrega = :id_nota";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id_nota", $id_nota);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener detalles: " . $e->getMessage());
            return [];
        }
    }

    public function getById($id_nota) {
        try {
            $sql = "SELECT n.*, c.nombre as nombre_cliente 
                    FROM notas_entrega n
                    LEFT JOIN clientes c ON n.id_cliente = c.id
                    WHERE n.id = :id_nota";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id_nota", $id_nota);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener nota por id: " . $e->getMessage());
            return null;
        }
    }

    public function insert() {
        try {
            $sql = "INSERT INTO notas_entrega (id_cliente, fecha_pedido, estado, tipo, total, observaciones) 
                    VALUES (:id_cliente, :fecha_pedido, :estado, :tipo, :total, :observaciones)";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id_cliente", $this->id_cliente);
            $stmt->bindParam(":fecha_pedido", $this->fecha_pedido);
            $stmt->bindParam(":estado", $this->estado);
            $stmt->bindParam(":tipo", $this->tipo);
            $stmt->bindParam(":total", $this->total);
            $stmt->bindParam(":observaciones", $this->observaciones);
            $stmt->execute();

            $id_nota = $this->lastInsertId();

            foreach ($this->detalles as $det) {
                $sqlDet = "INSERT INTO notas_entrega_detalles (id_nota_entrega, id_variante, cantidad, precio_unitario, descripcion) 
                           VALUES (:id_nota, :id_variante, :cantidad, :precio_unitario, :descripcion)";
                $stmtDet = $this->prepare($sqlDet);
                $stmtDet->bindParam(":id_nota", $id_nota);
                $stmtDet->bindParam(":id_variante", $det['id_variante']);
                $stmtDet->bindParam(":cantidad", $det['cantidad']);
                $stmtDet->bindParam(":precio_unitario", $det['precio_unitario']);
                $stmtDet->bindParam(":descripcion", $det['descripcion']);
                $stmtDet->execute();
            }

            return $id_nota;
        } catch (PDOException $e) {
            error_log("Error al insertar nota de entrega: " . $e->getMessage());
            return false;
        }
    }

    public function cambiarEstado($id, $nuevo_estado) {
        try {
            // Si el estado es confirmado, descontar inventario
            if ($nuevo_estado === 'confirmado') {
                $detalles = $this->getDetalles($id);
                foreach ($detalles as $det) {
                    $sqlVar = "UPDATE producto_variantes SET stock = stock - :cantidad WHERE id = :id_variante";
                    $stmtVar = $this->prepare($sqlVar);
                    $stmtVar->bindParam(":cantidad", $det['cantidad']);
                    $stmtVar->bindParam(":id_variante", $det['id_variante']);
                    $stmtVar->execute();
                }
            }

            // Actualizar estado
            $sql = "UPDATE notas_entrega SET estado = :estado WHERE id = :id";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":estado", $nuevo_estado);
            $stmt->bindParam(":id", $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al cambiar estado: " . $e->getMessage());
            return false;
        }
    }

    public function delete($id) {
        try {
            $sql = "DELETE FROM notas_entrega WHERE id = :id";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id", $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al borrar nota: " . $e->getMessage());
            return false;
        }
    }
}
