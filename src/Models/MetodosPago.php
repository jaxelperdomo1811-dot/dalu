<?php
namespace Lenovo\Dalu\Models;

use PDO;
use PDOException;

class MetodosPago extends Conexion {
    private $id;
    private $nombre;
    private $descripcion;
    private $activo;
    
    public function __construct() {
        parent::__construct();
    }
    
    public function setId($id) { $this->id = $id; return $this; }
    public function setNombre($nombre) { $this->nombre = $nombre; return $this; }
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; return $this; }
    public function setActivo($activo) { $this->activo = $activo; return $this; }
    
    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    public function getDescripcion() { return $this->descripcion; }
    public function getActivo() { return $this->activo; }
    
    public function getActivos() {
        try {
            $sql = "SELECT * FROM metodos_pago WHERE activo = 1 ORDER BY nombre ASC";
            $stmt = $this->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al buscar metodos de pago: " . $e->getMessage());
            return [];
        }
    }
}
