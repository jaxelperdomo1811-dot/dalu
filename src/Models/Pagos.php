<?php
namespace Lenovo\Dalu\Models;

use PDO;
use PDOException;

class Pagos extends Conexion {
    private $id;
    private $id_nota_entrega;
    private $id_metodo_pago;
    private $monto_bs;
    private $monto_usd;
    private $id_tasa;
    private $referencia;
    private $estado;
    
    public function __construct() {
        parent::__construct();
    }
    
    public function setId($id) { $this->id = $id; return $this; }
    public function setIdNotaEntrega($id_nota_entrega) { $this->id_nota_entrega = $id_nota_entrega; return $this; }
    public function setIdMetodoPago($id_metodo_pago) { $this->id_metodo_pago = $id_metodo_pago; return $this; }
    public function setMontoBs($monto_bs) { $this->monto_bs = $monto_bs; return $this; }
    public function setMontoUsd($monto_usd) { $this->monto_usd = $monto_usd; return $this; }
    public function setIdTasa($id_tasa) { $this->id_tasa = $id_tasa; return $this; }
    public function setReferencia($referencia) { $this->referencia = $referencia; return $this; }
    public function setEstado($estado) { $this->estado = $estado; return $this; }
    
    public function getId() { return $this->id; }
    public function getIdNotaEntrega() { return $this->id_nota_entrega; }
    public function getIdMetodoPago() { return $this->id_metodo_pago; }
    public function getMontoBs() { return $this->monto_bs; }
    public function getMontoUsd() { return $this->monto_usd; }
    public function getIdTasa() { return $this->id_tasa; }
    public function getReferencia() { return $this->referencia; }
    public function getEstado() { return $this->estado; }
    
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
            $pagos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $tasaModel = new \Lenovo\Dalu\Models\Tasa();
            foreach ($pagos as &$pago) {
                $tasaData = $tasaModel->getById($pago['id_tasa']);
                $pago['tasa_valor'] = $tasaData ? floatval($tasaData['valor']) : 1;
            }
            return $pagos;
        } catch (PDOException $e) {
            error_log("Error al buscar pagos: " . $e->getMessage());
            return [];
        }
    }

    public function insert() {
        try {
            $sql = "INSERT INTO pagos (id_nota_entrega, id_metodo_pago, monto_bs, monto_usd, id_tasa, referencia) 
                    VALUES (:id_nota, :id_metodo, :monto_bs, :monto_usd, :id_tasa, :referencia)";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id_nota", $this->id_nota_entrega);
            $stmt->bindParam(":id_metodo", $this->id_metodo_pago);
            $stmt->bindParam(":monto_bs", $this->monto_bs);
            $stmt->bindParam(":monto_usd", $this->monto_usd);
            $stmt->bindParam(":id_tasa", $this->id_tasa);
            $stmt->bindParam(":referencia", $this->referencia);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al insertar pago: " . $e->getMessage());
            return false;
        }
    }

    public function getAgrupadosPorNota($tipo_nota = null) {
        try {
            $sql = "SELECT n.id as id_nota, 
                           CONCAT(c.nombre, ' ', COALESCE(c.apellido, '')) as cliente_nombre,
                           n.total,
                           COUNT(pag.id) as total_pagos,
                           SUM(CASE WHEN pag.estado = 'por verificar' THEN 1 ELSE 0 END) as pagos_por_verificar,
                           n.tipo as tipo_nota
                    FROM notas_entrega n
                    JOIN pagos pag ON n.id = pag.id_nota_entrega
                    LEFT JOIN clientes c ON n.id_cliente = c.id";

            if ($tipo_nota) {
                $sql .= " WHERE n.tipo = :tipo_nota";
            }
            
            $sql .= " GROUP BY n.id ORDER BY n.fecha_pedido DESC";
            
            $stmt = $this->prepare($sql);
            if ($tipo_nota) {
                $stmt->bindParam(":tipo_nota", $tipo_nota);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al agrupar pagos por nota: " . $e->getMessage());
            return [];
        }
    }

    public function updateEstado() {
        try {
            $sql = "UPDATE pagos SET estado = :estado WHERE id = :id";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":estado", $this->estado);
            $stmt->bindParam(":id", $this->id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al actualizar estado del pago: " . $e->getMessage());
            return false;
        }
    }
}
