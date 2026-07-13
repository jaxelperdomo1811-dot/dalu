<?php
namespace Lenovo\Dalu\Models;

use Lenovo\Dalu\Models\Conexion;
use PDO;

class CamposVariante extends Conexion {
    private $id;
    private $nombre;
    private $tipo;
    private $opciones;
    private $requerido;
    private $activo;

    public function __construct($id = null, $nombre = null, $tipo = 'text', $opciones = null, $requerido = 0) {
        parent::__construct();
        $this->id = $id;
        $this->nombre = $nombre;
        $this->tipo = $tipo;
        $this->opciones = $opciones;
        $this->requerido = $requerido;
    }
    
    public function setId($id) { $this->id = $id; return $this; }
    public function setNombre($nombre) { $this->nombre = $nombre; return $this; }
    public function setTipo($tipo) { $this->tipo = $tipo; return $this; }
    public function setOpciones($opciones) { $this->opciones = $opciones; return $this; }
    public function setRequerido($requerido) { $this->requerido = $requerido; return $this; }

    public function insert() {
        $sql = "INSERT INTO campos_variante (nombre, tipo, opciones, requerido) VALUES (:nombre, :tipo, :opciones, :requerido)";
        $stmt = $this->prepare($sql);
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":tipo", $this->tipo);
        $opc = $this->opciones ? json_encode($this->opciones) : null;
        $stmt->bindParam(":opciones", $opc);
        $stmt->bindParam(":requerido", $this->requerido);
        if ($stmt->execute()) {
            return $this->lastInsertId();
        } else {
            return false;
        }
    }

    public function search() {
        $sql = "SELECT * FROM campos_variante WHERE activo = 1";
        $stmt = $this->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as &$row) {
            $row['opciones'] = $row['opciones'] ? json_decode($row['opciones'], true) : null;
        }
        return $result;
    }

    public function update() {
        $sql = "UPDATE campos_variante SET nombre = :nombre, tipo = :tipo, opciones = :opciones, requerido = :requerido WHERE id = :id";
        $stmt = $this->prepare($sql);
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":tipo", $this->tipo);
        $opc = $this->opciones ? json_encode($this->opciones) : null;
        $stmt->bindParam(":opciones", $opc);
        $stmt->bindParam(":requerido", $this->requerido);
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }

    public function delete() {
        $sql = "UPDATE campos_variante SET activo = 0 WHERE id = :id";
        $stmt = $this->prepare($sql);
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }

    public function activate() {
        $sql = "UPDATE campos_variante SET activo = 1 WHERE id = :id";
        $stmt = $this->prepare($sql);
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }

    public function searchInactive() {
        $sql = "SELECT * FROM campos_variante WHERE activo = 0";
        $stmt = $this->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as &$row) {
            $row['opciones'] = $row['opciones'] ? json_decode($row['opciones'], true) : null;
        }
        return $result;
    }
}
