<?php
namespace Lenovo\Dalu\Models;

use PDO;

class Creditos extends Conexion {
    private $id;
    private $id_nota_entrega;
    private $porcentaje_inicial;
    private $monto_cuota_inicial;
    private $nro_cuotas;
    private $monto_por_cuota;
    private $frecuencia;
    private $estado;
    
    public function __construct() {
        parent::__construct();
    }
    
    public function setId($id) { $this->id = $id; return $this; }
    public function setIdNotaEntrega($id_nota_entrega) { $this->id_nota_entrega = $id_nota_entrega; return $this; }
    public function setPorcentajeInicial($porcentaje_inicial) { $this->porcentaje_inicial = $porcentaje_inicial; return $this; }
    public function setMontoCuotaInicial($monto_cuota_inicial) { $this->monto_cuota_inicial = $monto_cuota_inicial; return $this; }
    public function setNroCuotas($nro_cuotas) { $this->nro_cuotas = $nro_cuotas; return $this; }
    public function setMontoPorCuota($monto_por_cuota) { $this->monto_por_cuota = $monto_por_cuota; return $this; }
    public function setFrecuencia($frecuencia) { $this->frecuencia = $frecuencia; return $this; }
    public function setEstado($estado) { $this->estado = $estado; return $this; }
    
    public function getId() { return $this->id; }
    public function getIdNotaEntrega() { return $this->id_nota_entrega; }
    public function getPorcentajeInicial() { return $this->porcentaje_inicial; }
    public function getMontoCuotaInicial() { return $this->monto_cuota_inicial; }
    public function getNroCuotas() { return $this->nro_cuotas; }
    public function getMontoPorCuota() { return $this->monto_por_cuota; }
    public function getFrecuencia() { return $this->frecuencia; }
    public function getEstado() { return $this->estado; }
    
    public function insert() {
        $sql = "INSERT INTO creditos (id_nota_entrega, porcentaje_inicial, monto_cuota_inicial, nro_cuotas, monto_por_cuota, frecuencia) 
                VALUES (:id_nota_entrega, :porcentaje_inicial, :monto_cuota_inicial, :nro_cuotas, :monto_por_cuota, :frecuencia)";
        $stmt = $this->prepare($sql);
        $stmt->bindParam(":id_nota_entrega", $this->id_nota_entrega);
        $stmt->bindParam(":porcentaje_inicial", $this->porcentaje_inicial);
        $stmt->bindParam(":monto_cuota_inicial", $this->monto_cuota_inicial);
        $stmt->bindParam(":nro_cuotas", $this->nro_cuotas);
        $stmt->bindParam(":monto_por_cuota", $this->monto_por_cuota);
        $stmt->bindParam(":frecuencia", $this->frecuencia);
        
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
