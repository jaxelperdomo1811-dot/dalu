<?php
    namespace Lenovo\Dalu\Models;
    use Lenovo\Dalu\Models\Conexion;
    use Lenovo\Dalu\Interfaces\IRoles;
    class Roles extends Conexion implements IRoles {
        private $id;
        private $nombre;
        private $descripcion;

        public function __construct($id = null, $nombre = null, $descripcion = null) {
            parent::__construct();
            $this->id = $id;
            $this->nombre = $nombre;
            $this->descripcion = $descripcion;
        }
        
        public function setId($id) { $this->id = $id; return $this; }
        public function setNombre($nombre) { $this->nombre = $nombre; return $this; }
        public function setDescripcion($descripcion) { $this->descripcion = $descripcion; return $this; }
        public function getId() { return $this->id; }
        public function getNombre() { return $this->nombre; }
        public function getDescripcion() { return $this->descripcion; }

        public function insert() {
            $sql = "INSERT INTO roles (nombre, descripcion) VALUES (:nombre, :descripcion)";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":nombre", $this->nombre);
            $stmt->bindParam(":descripcion", $this->descripcion);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }
        public function search() {
            $sql = "SELECT * FROM roles WHERE activo = 1";
            $stmt = $this->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        public function searchInactive() {
            $sql = "SELECT * FROM roles WHERE activo = 0";
            $stmt = $this->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        public function update() {
            $sql = "UPDATE roles SET nombre = :nombre, descripcion = :descripcion WHERE id = :id";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":nombre", $this->nombre);
            $stmt->bindParam(":descripcion", $this->descripcion);
            $stmt->bindParam(":id", $this->id);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }
        public function delete() {
            $sql = "UPDATE roles SET activo = 0 WHERE id = :id";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id", $this->id);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }
        public function activate() {
            $sql = "UPDATE roles SET activo = 1 WHERE id = :id";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id", $this->id);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }

    }