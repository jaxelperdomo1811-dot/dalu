<?php
namespace Lenovo\Dalu\Models;

use PDO;
use PDOException;

class MetodosPago extends Conexion {
    public function __construct() {
        parent::__construct();
    }
    
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
