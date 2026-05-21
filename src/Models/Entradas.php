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
                $sqlUpdateStock = "UPDATE productos SET stock = stock + :cantidad WHERE id = :id_producto";
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
                    INNER JOIN proveedores p ON e.id_proveedor = p.id
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
}
