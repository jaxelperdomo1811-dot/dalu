<?php
namespace Lenovo\Dalu\Models;
use Lenovo\Dalu\Models\Conexion;
use PDO;
use PDOException;

class NotasEntrega extends Conexion {
    
    public function __construct() {
        parent::__construct();
    }

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

    public function insert($id_cliente, $fecha_pedido, $estado, $tipo, $total, $observaciones, $detalles) {
        try {
            // Nota: No se usan transacciones según solicitud
            $sql = "INSERT INTO notas_entrega (id_cliente, fecha_pedido, estado, tipo, total, observaciones) 
                    VALUES (:id_cliente, :fecha_pedido, :estado, :tipo, :total, :observaciones)";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id_cliente", $id_cliente);
            $stmt->bindParam(":fecha_pedido", $fecha_pedido);
            $stmt->bindParam(":estado", $estado);
            $stmt->bindParam(":tipo", $tipo);
            $stmt->bindParam(":total", $total);
            $stmt->bindParam(":observaciones", $observaciones);
            $stmt->execute();

            $id_nota = $this->lastInsertId();

            foreach ($detalles as $det) {
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

            return true;
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
                    // Descontar inventario de la variante
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
