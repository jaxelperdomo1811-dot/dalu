<?php
namespace Lenovo\Dalu\Models;

use PDO;
use PDOException;

class Ajustes extends Conexion {
    private $id;
    private $clave;
    private $valor;
    
    public function __construct() {
        parent::__construct();
    }
    
    public function setId($id) { $this->id = $id; return $this; }
    public function setClave($clave) { $this->clave = $clave; return $this; }
    public function setValor($valor) { $this->valor = $valor; return $this; }
    
    public function getId() { return $this->id; }
    public function getClave() { return $this->clave; }
    public function getValor() { return $this->valor; }
    
    /**
     * Obtiene todos los ajustes como un arreglo asociativo [clave => valor]
     */
    public function getAll() {
        try {
            $stmt = $this->prepare("SELECT * FROM ajustes");
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
            $stmt = $this->prepare("SELECT valor FROM ajustes WHERE clave = :clave");
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
    public function update() {
        try {
            $stmt = $this->prepare("UPDATE ajustes SET valor = :valor WHERE clave = :clave");
            $stmt->bindParam(':clave', $this->clave, PDO::PARAM_STR);
            $stmt->bindParam(':valor', $this->valor);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}
