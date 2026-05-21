<?php
    namespace Lenovo\Dalu\Models;
    use Lenovo\Dalu\Models\Conexion;

    class Proveedores extends Conexion {
        private $id;
        private $nombre;
        private $rif;
        private $telefono;
        private $email;
        private $direccion;

        public function __construct($id = null, $nombre = null, $rif = null, $telefono = null, $email = null, $direccion = null) {
            parent::__construct();
            $this->id = $id;
            $this->nombre = $nombre;
            $this->rif = $rif;
            $this->telefono = $telefono;
            $this->email = $email;
            $this->direccion = $direccion;
        }

        public function setId($id) { $this->id = $id; return $this; }
        public function setNombre($nombre) { $this->nombre = $nombre; return $this; }
        public function setRif($rif) { $this->rif = $rif; return $this; }
        public function setTelefono($telefono) { $this->telefono = $telefono; return $this; }
        public function setEmail($email) { $this->email = $email; return $this; }
        public function setDireccion($direccion) { $this->direccion = $direccion; return $this; }

        public function getId() { return $this->id; }
        public function getNombre() { return $this->nombre; }
        public function getRif() { return $this->rif; }
        public function getTelefono() { return $this->telefono; }
        public function getEmail() { return $this->email; }
        public function getDireccion() { return $this->direccion; }

        public function insert() {
            // Mapping to DB columns:
            // rif -> razon_social
            // nombre -> nombre
            // '' -> apellido (NOT NULL)
            // telefono -> telefono_1
            // email -> correo
            // direccion -> direccion
            $sql = "INSERT INTO proveedores (razon_social, nombre, apellido, telefono_1, correo, direccion, active) 
                    VALUES (:rif, :nombre, :apellido, :telefono, :email, :direccion, 1)";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":rif", $this->rif);
            $stmt->bindParam(":nombre", $this->nombre);
            
            $apellido = "-"; // Default to "-" since it's NOT NULL
            $stmt->bindParam(":apellido", $apellido);
            
            $stmt->bindParam(":telefono", $this->telefono);
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":direccion", $this->direccion);

            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }

        public function search() {
            // Selecting DB columns but aliasing them to match view expectations
            $sql = "SELECT id, razon_social as rif, nombre, telefono_1 as telefono, correo as email, direccion 
                    FROM proveedores WHERE active = 1";
            $stmt = $this->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        public function searchInactive() {
            $sql = "SELECT id, razon_social as rif, nombre, telefono_1 as telefono, correo as email, direccion 
                    FROM proveedores WHERE active = 0";
            $stmt = $this->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        public function update() {
            $sql = "UPDATE proveedores 
                    SET razon_social = :rif, nombre = :nombre, telefono_1 = :telefono, correo = :email, direccion = :direccion 
                    WHERE id = :id";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":rif", $this->rif);
            $stmt->bindParam(":nombre", $this->nombre);
            $stmt->bindParam(":telefono", $this->telefono);
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":direccion", $this->direccion);
            $stmt->bindParam(":id", $this->id);

            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }

        public function delete() {
            $sql = "UPDATE proveedores SET active = 0 WHERE id = :id";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id", $this->id);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }

        public function activate() {
            $sql = "UPDATE proveedores SET active = 1 WHERE id = :id";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id", $this->id);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }
    }
