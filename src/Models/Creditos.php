<?php
namespace Lenovo\Dalu\Models;

use PDO;

class Creditos extends Conexion {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function insertarCredito($id_nota_entrega, $porcentaje_inicial, $monto_cuota_inicial, $nro_cuotas, $monto_por_cuota, $frecuencia) {
        $sql = "INSERT INTO creditos (id_nota_entrega, porcentaje_inicial, monto_cuota_inicial, nro_cuotas, monto_por_cuota, frecuencia) 
                VALUES (:id_nota_entrega, :porcentaje_inicial, :monto_cuota_inicial, :nro_cuotas, :monto_por_cuota, :frecuencia)";
        $stmt = $this->prepare($sql);
        $stmt->bindParam(":id_nota_entrega", $id_nota_entrega);
        $stmt->bindParam(":porcentaje_inicial", $porcentaje_inicial);
        $stmt->bindParam(":monto_cuota_inicial", $monto_cuota_inicial);
        $stmt->bindParam(":nro_cuotas", $nro_cuotas);
        $stmt->bindParam(":monto_por_cuota", $monto_por_cuota);
        $stmt->bindParam(":frecuencia", $frecuencia);
        
        if ($stmt->execute()) {
            return $this->lastInsertId();
        }
        return false;
    }

    public function insertarCuota($id_credito, $tipo_cuota, $nro_cuota, $monto, $fecha_vencimiento) {
        $sql = "INSERT INTO creditos_cuotas (id_credito, tipo_cuota, nro_cuota, monto, monto_restante, fecha_vencimiento) 
                VALUES (:id_credito, :tipo_cuota, :nro_cuota, :monto, :monto, :fecha_vencimiento)";
        $stmt = $this->prepare($sql);
        $stmt->bindParam(":id_credito", $id_credito);
        $stmt->bindParam(":tipo_cuota", $tipo_cuota);
        $stmt->bindParam(":nro_cuota", $nro_cuota);
        $stmt->bindParam(":monto", $monto);
        $stmt->bindParam(":fecha_vencimiento", $fecha_vencimiento);
        
        return $stmt->execute();
    }

    public function getCreditoPorNota($id_nota_entrega) {
        $sql = "SELECT * FROM creditos WHERE id_nota_entrega = :id_nota_entrega";
        $stmt = $this->prepare($sql);
        $stmt->bindParam(":id_nota_entrega", $id_nota_entrega);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCuotasPorCredito($id_credito) {
        $sql = "SELECT * FROM creditos_cuotas WHERE id_credito = :id_credito ORDER BY nro_cuota ASC";
        $stmt = $this->prepare($sql);
        $stmt->bindParam(":id_credito", $id_credito);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizarMontoRestanteCuota($id_cuota, $nuevo_restante) {
        $estado = ($nuevo_restante <= 0) ? 'pagado' : 'pendiente';
        $fecha_pago = ($nuevo_restante <= 0) ? date('Y-m-d H:i:s') : null;

        $sql = "UPDATE creditos_cuotas SET monto_restante = :restante, estado = :estado";
        if ($fecha_pago) {
            $sql .= ", fecha_pago = :fecha_pago";
        }
        $sql .= " WHERE id = :id";
        
        $stmt = $this->prepare($sql);
        $stmt->bindParam(":restante", $nuevo_restante);
        $stmt->bindParam(":estado", $estado);
        if ($fecha_pago) {
            $stmt->bindParam(":fecha_pago", $fecha_pago);
        }
        $stmt->bindParam(":id", $id_cuota);
        return $stmt->execute();
    }

    public function actualizarEstadoCredito($id_credito, $estado) {
        $sql = "UPDATE creditos SET estado = :estado WHERE id = :id";
        $stmt = $this->prepare($sql);
        $stmt->bindParam(":estado", $estado);
        $stmt->bindParam(":id", $id_credito);
        return $stmt->execute();
    }

    public function obtenerCuotasPendientes($id_credito) {
        $sql = "SELECT * FROM creditos_cuotas WHERE id_credito = :id_credito AND estado != 'pagado' ORDER BY nro_cuota ASC";
        $stmt = $this->prepare($sql);
        $stmt->bindParam(":id_credito", $id_credito);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAll() {
        $sql = "SELECT cr.*, n.id as nota_id, n.total as nota_total, n.fecha_pedido, c.nombre as cliente_nombre 
                FROM creditos cr
                INNER JOIN notas_entrega n ON cr.id_nota_entrega = n.id
                LEFT JOIN clientes c ON n.id_cliente = c.id
                ORDER BY cr.fecha_registro DESC";
        $stmt = $this->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
