<?php
namespace Lenovo\Dalu\Models;

use Lenovo\Dalu\Models\Conexion;
use PDO;

class Despachos extends Conexion {
    private $id;
    private $id_nota_entrega;
    private $numero_despacho;
    private $fecha_despacho;
    private $estado;
    private $fecha_registro;

    public function __construct($id = null, $id_nota_entrega = null, $numero_despacho = null, $fecha_despacho = null, $estado = 'pendiente') {
        parent::__construct();
        $this->id = $id;
        $this->id_nota_entrega = $id_nota_entrega;
        $this->numero_despacho = $numero_despacho;
        $this->fecha_despacho = $fecha_despacho;
        $this->estado = $estado;
    }
    
    // SETTERS
    public function setId($id) { $this->id = $id; return $this; }
    public function setIdNotaEntrega($id_nota_entrega) { $this->id_nota_entrega = $id_nota_entrega; return $this; }
    public function setNumeroDespacho($numero_despacho) { $this->numero_despacho = $numero_despacho; return $this; }
    public function setFechaDespacho($fecha_despacho) { $this->fecha_despacho = $fecha_despacho; return $this; }
    public function setEstado($estado) { $this->estado = $estado; return $this; }

    // GETTERS
    public function getId() { return $this->id; }
    public function getIdNotaEntrega() { return $this->id_nota_entrega; }
    public function getNumeroDespacho() { return $this->numero_despacho; }
    public function getFechaDespacho() { return $this->fecha_despacho; }
    public function getEstado() { return $this->estado; }
    public function getFechaRegistro() { return $this->fecha_registro; }

    public function insert() {
        try {
            $sql = "INSERT INTO despachos (id_nota_entrega, numero_despacho, fecha_despacho, estado) 
                    VALUES (:id_nota_entrega, :numero_despacho, :fecha_despacho, :estado)";
            
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id_nota_entrega", $this->id_nota_entrega);
            $stmt->bindParam(":numero_despacho", $this->numero_despacho);
            $stmt->bindParam(":fecha_despacho", $this->fecha_despacho);
            $stmt->bindParam(":estado", $this->estado);
            
            if (!$stmt->execute()) {
                return false;
            }
            
            $this->id = $this->lastInsertId();
            return $this->id;
            
        } catch (\PDOException $e) {
            error_log("Error en insert despacho: " . $e->getMessage());
            return false;
        }
    }

    public function search() {
        $sql = "SELECT d.*, n.total, c.nombre as cliente_nombre, c.apellido as cliente_apellido, c.cedula as cliente_cedula 
                FROM despachos d
                INNER JOIN notas_entrega n ON d.id_nota_entrega = n.id
                INNER JOIN clientes c ON n.id_cliente = c.id
                ORDER BY d.fecha_registro DESC";
        $stmt = $this->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT d.*, n.total, c.nombre as cliente_nombre, c.apellido as cliente_apellido, c.cedula as cliente_cedula 
                FROM despachos d
                INNER JOIN notas_entrega n ON d.id_nota_entrega = n.id
                INNER JOIN clientes c ON n.id_cliente = c.id
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
        $sql = "SELECT nd.*, p.nombre as producto_nombre, pv.nombre_variante as variante_nombre 
                FROM despachos d
                INNER JOIN notas_entrega_detalles nd ON d.id_nota_entrega = nd.id_nota_entrega
                LEFT JOIN producto_variantes pv ON nd.id_variante = pv.id
                LEFT JOIN productos p ON pv.id_producto = p.id
                WHERE d.id = :id_despacho";
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
