<?php
    namespace Lenovo\Dalu\Models;
    use Lenovo\Dalu\Models\Conexion;

    class Clientes extends Conexion {
        private $id;
        private $nombre;
        private $cedula;
        private $apellido;
        private $correo;
        private $telefono;
        private $direccion;

        public function __construct($id = null, $nombre = null, $cedula = null, $apellido = null, $correo = null, $telefono = null, $direccion = null) {
            parent::__construct();
            $this->id = $id;
            $this->nombre = $nombre;
            $this->cedula = $cedula;
            $this->apellido = $apellido;
            $this->correo = $correo;
            $this->telefono = $telefono;
            $this->direccion = $direccion;

        }
        
        public function setId($id) { $this->id = $id; return $this; }
        public function setNombre($nombre) { $this->nombre = $nombre; return $this; }
        public function setCedula($cedula) { $this->cedula = $cedula; return $this; }
        public function setApellido($apellido) { $this->apellido = $apellido; return $this; }
        public function setCorreo($correo) { $this->correo = $correo; return $this; }
        public function setTelefono($telefono) { $this->telefono = $telefono; return $this; }
        public function setDireccion($direccion) { $this->direccion = $direccion; return $this; }

        public function getId() { return $this->id; }
        public function getNombre() { return $this->nombre; }
        public function getCedula() { return $this->cedula; }
        public function getApellido() { return $this->apellido; }
        public function getCorreo() { return $this->correo; }
        public function getTelefono() { return $this->telefono; }
        public function getDireccion() { return $this->direccion; }

        public function insert() {
            $sql = "INSERT INTO clientes (nombre, cedula, apellido, correo, telefono, direccion) VALUES (:nombre, :cedula, :apellido, :correo, :telefono, :direccion)";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":nombre", $this->nombre);
            $stmt->bindParam(":cedula", $this->cedula);
            $stmt->bindParam(":apellido", $this->apellido);
            $stmt->bindParam(":correo", $this->correo);
            $stmt->bindParam(":telefono", $this->telefono);
            $stmt->bindParam(":direccion", $this->direccion);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }
        public function search() {
            $sql = "SELECT * FROM clientes WHERE activo = 1";
            $stmt = $this->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        public function getByCedula($cedula) {
            $sql = "SELECT * FROM clientes WHERE cedula = :cedula LIMIT 1";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":cedula", $cedula);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }


        public function searchInactive() {
            $sql = "SELECT * FROM clientes WHERE activo = 0";
            $stmt = $this->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        public function update() {
            $sql = "UPDATE clientes SET nombre = :nombre, apellido = :apellido, correo = :correo, telefono = :telefono, direccion = :direccion WHERE id = :id";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":nombre", $this->nombre);
            $stmt->bindParam(":apellido", $this->apellido);
            $stmt->bindParam(":correo", $this->correo);
            $stmt->bindParam(":telefono", $this->telefono);
            $stmt->bindParam(":direccion", $this->direccion);
            $stmt->bindParam(":id", $this->id);

            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }

        public function delete() {
            $sql = "UPDATE clientes SET activo = 0 WHERE id = :id";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id", $this->id);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }

        public function activate() {
            $sql = "UPDATE clientes SET activo = 1 WHERE id = :id";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id", $this->id);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }

    }