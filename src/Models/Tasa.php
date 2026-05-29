<?php
namespace Lenovo\Dalu\Models;

use PDO;
use PDOException;

class Tasa {
    private $db;
    
    public function __construct() {
        $this->db = new Conexion();
    }
    
    public function getLatest() {
        try {
            $stmt = $this->db->prepare("SELECT * FROM tasa ORDER BY id DESC LIMIT 1");
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public function update($id, $valor) {
        try {
            if ($id) {
                $stmt = $this->db->prepare("UPDATE tasa SET valor = :valor, fecha_actualizacion = current_timestamp() WHERE id = :id");
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            } else {
                $stmt = $this->db->prepare("INSERT INTO tasa (valor, fecha_actualizacion) VALUES (:valor, current_timestamp())");
            }
            $stmt->bindParam(':valor', $valor);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}
