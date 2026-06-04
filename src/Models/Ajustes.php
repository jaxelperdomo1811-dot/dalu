<?php
namespace Lenovo\Dalu\Models;

use PDO;
use PDOException;

class Ajustes {
    private $db;
    
    public function __construct() {
        $this->db = new Conexion();
    }
    
    /**
     * Obtiene todos los ajustes como un arreglo asociativo [clave => valor]
     */
    public function getAll() {
        try {
            $stmt = $this->db->prepare("SELECT * FROM ajustes");
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $ajustes = [];
            foreach ($results as $row) {
                $ajustes[$row['clave']] = floatval($row['valor']);
            }
            return $ajustes;
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Obtiene el valor de un ajuste específico por su clave
     */
    public function get($clave) {
        try {
            $stmt = $this->db->prepare("SELECT valor FROM ajustes WHERE clave = :clave");
            $stmt->bindParam(':clave', $clave, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? floatval($result['valor']) : null;
        } catch (PDOException $e) {
            return null;
        }
    }
    
    /**
     * Actualiza el valor de un ajuste existente
     */
    public function update($clave, $valor) {
        try {
            $stmt = $this->db->prepare("UPDATE ajustes SET valor = :valor WHERE clave = :clave");
            $stmt->bindParam(':clave', $clave, PDO::PARAM_STR);
            $stmt->bindParam(':valor', $valor);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}
