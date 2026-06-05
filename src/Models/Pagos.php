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
    private $tasa;
    private $referencia;
    
    public function __construct() {
        parent::__construct();
    }
    
    public function setId($id) { $this->id = $id; return $this; }
    public function setIdNotaEntrega($id_nota_entrega) { $this->id_nota_entrega = $id_nota_entrega; return $this; }
    public function setIdMetodoPago($id_metodo_pago) { $this->id_metodo_pago = $id_metodo_pago; return $this; }
    public function setMontoBs($monto_bs) { $this->monto_bs = $monto_bs; return $this; }
    public function setMontoUsd($monto_usd) { $this->monto_usd = $monto_usd; return $this; }
    public function setTasa($tasa) { $this->tasa = $tasa; return $this; }
    public function setReferencia($referencia) { $this->referencia = $referencia; return $this; }
    
    public function getId() { return $this->id; }
    public function getIdNotaEntrega() { return $this->id_nota_entrega; }
    public function getIdMetodoPago() { return $this->id_metodo_pago; }
    public function getMontoBs() { return $this->monto_bs; }
    public function getMontoUsd() { return $this->monto_usd; }
    public function getTasa() { return $this->tasa; }
    public function getReferencia() { return $this->referencia; }
    
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

    public function insert() {
        try {
            $sql = "INSERT INTO pagos (id_nota_entrega, id_metodo_pago, monto_bs, monto_usd, tasa, referencia) 
                    VALUES (:id_nota, :id_metodo, :monto_bs, :monto_usd, :tasa, :referencia)";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id_nota", $this->id_nota_entrega);
            $stmt->bindParam(":id_metodo", $this->id_metodo_pago);
            $stmt->bindParam(":monto_bs", $this->monto_bs);
            $stmt->bindParam(":monto_usd", $this->monto_usd);
            $stmt->bindParam(":tasa", $this->tasa);
            $stmt->bindParam(":referencia", $this->referencia);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al insertar pago: " . $e->getMessage());
            return false;
        }
    }
}
