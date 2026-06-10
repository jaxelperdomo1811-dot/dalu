<?php
namespace Lenovo\Dalu\Models;

use PDO;
use PDOException;

class Tasa extends Conexion {
    private $id;
    private $nombre;
    private $valor;
    private $fecha_actualizacion;
    
    public function __construct() {
        parent::__construct();
    }
    
    public function setId($id) { $this->id = $id; return $this; }
    public function setNombre($nombre) { $this->nombre = $nombre; return $this; }
    public function setValor($valor) { $this->valor = $valor; return $this; }
    public function setFechaActualizacion($fecha_actualizacion) { $this->fecha_actualizacion = $fecha_actualizacion; return $this; }
    
    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    public function getValor() { return $this->valor; }
    public function getFechaActualizacion() { return $this->fecha_actualizacion; }
    
    public function getLatest($nombre = 'BCV') {
        try {
            $stmt = $this->prepare("SELECT * FROM tasa WHERE nombre = :nombre ORDER BY id DESC LIMIT 1");
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public function update() {
        try {
            if ($this->id) {
                $stmt = $this->prepare("UPDATE tasa SET valor = :valor, fecha_actualizacion = current_timestamp() WHERE id = :id");
                $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
            } else {
                $stmt = $this->prepare("INSERT INTO tasa (nombre, valor, fecha_actualizacion) VALUES (:nombre, :valor, current_timestamp())");
                $nombre = $this->nombre ?? 'BCV';
                $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            }
            $stmt->bindParam(':valor', $this->valor);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}
