<?php
namespace Lenovo\Dalu\Models;

use PDO;
use PDOException;

class Pagos extends Conexion {
    public function __construct() {
        parent::__construct();
    }
    
    public function getByNotaEntrega($id_nota_entrega) {
        try {
            $sql = "SELECT p.*, m.nombre as metodo_pago_nombre 
                    FROM pagos p 
                    LEFT JOIN metodos_pago m ON p.id_metodo_pago = m.id 
                    WHERE p.id_nota_entrega = :id_nota_entrega 
                    ORDER BY p.fecha DESC";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id_nota_entrega", $id_nota_entrega);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al buscar pagos: " . $e->getMessage());
            return [];
        }
    }

    public function insert($id_nota_entrega, $id_metodo_pago, $monto_bs, $monto_usd, $tasa, $referencia) {
        try {
            $sql = "INSERT INTO pagos (id_nota_entrega, id_metodo_pago, monto_bs, monto_usd, tasa, referencia) 
                    VALUES (:id_nota, :id_metodo, :monto_bs, :monto_usd, :tasa, :referencia)";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id_nota", $id_nota_entrega);
            $stmt->bindParam(":id_metodo", $id_metodo_pago);
            $stmt->bindParam(":monto_bs", $monto_bs);
            $stmt->bindParam(":monto_usd", $monto_usd);
            $stmt->bindParam(":tasa", $tasa);
            $stmt->bindParam(":referencia", $referencia);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al insertar pago: " . $e->getMessage());
            return false;
        }
    }
}
