<?php
    namespace Lenovo\Dalu\Models;
    use Lenovo\Dalu\Models\Conexion;
    use Lenovo\Dalu\Interfaces\IProveedores;

    class Proveedores extends Conexion implements IProveedores {
        private $id;
        private $nombre;
        private $apellido;
        private $razon_social;
        private $documento_identidad;
        private $telefono;
        private $telefono2;
        private $email;
        private $direccion;
        private $rif;

        public function __construct($id = null, $nombre = null, $apellido =null, $razon_social = null, $documento_identidad = null, $telefono = null, $telefono2 = null, $email = null, $direccion = null, $rif = null) {
            parent::__construct();
            $this->id = $id;
            $this->nombre = $nombre;
            $this->apellido =$apellido;
            $this->razon_social = $razon_social;
            $this->documento_identidad = $documento_identidad;
            $this->telefono = $telefono;
            $this->telefono2 = $telefono2;
            $this->email = $email;
            $this->direccion = $direccion;
            $this->rif = $rif;
        }

        public function setId($id) { $this->id = $id; return $this; }
        public function setNombre($nombre) { $this->nombre = $nombre; return $this; }
        public function setApellido($apellido) { $this->apellido = $apellido; return $this; }
        public function setRazonSocial($razon_social) { $this->razon_social = $razon_social; return $this; }
        public function setDocumentoIdentidad($documento_identidad) { $this->documento_identidad = $documento_identidad; return $this; }
        public function setTelefono($telefono) { $this->telefono = $telefono; return $this; }
        public function setTelefono2($telefono2) { $this->telefono2 = $telefono2; return $this; }
        public function setEmail($email) { $this->email = $email; return $this; }
        public function setDireccion($direccion) { $this->direccion = $direccion; return $this; }
        public function setRif($rif) { $this->rif = $rif; return $this; }

        public function getId() { return $this->id; }
        public function getNombre() { return $this->nombre; }
        public function getApellido() { return $this->apellido; }
        public function getRazonSocial() { return $this->razon_social; }
        public function getCedula() { return $this->documento_identidad; }
        public function getTelefono() { return $this->telefono; }
        public function getTelefono2() { return $this->telefono2; }
        public function getEmail() { return $this->email; }
        public function getDireccion() { return $this->direccion; }
        public function getRif() { return $this->rif; }

        public function insert() {
            $sql = "INSERT INTO proveedores (razon_social, rif, documento_identidad, nombre, apellido, telefono_1, telefono_2, correo, direccion, active) 
                    VALUES (:razon_social, :rif, :documento_identidad, :nombre, :apellido, :telefono, :telefono2, :email, :direccion, 1)";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":razon_social", $this->razon_social);
            $stmt->bindParam(":rif", $this->rif);
            $stmt->bindParam(":documento_identidad", $this->documento_identidad);
            $stmt->bindParam(":nombre", $this->nombre);
            $stmt->bindParam(":apellido",$this->apellido);
            $stmt->bindParam(":telefono", $this->telefono);
            $stmt->bindParam(":telefono2", $this->telefono2);
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":direccion", $this->direccion);

            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }

        public function getByDocumentoIdentidad($documentoIdentidad) {
            $sql = "SELECT id, razon_social, rif, documento_identidad, nombre, apellido, telefono_1, telefono_2, correo, direccion 
                    FROM proveedores WHERE documento_identidad = :doc1 OR rif = :doc2 LIMIT 1";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":doc1", $documentoIdentidad);
            $stmt->bindParam(":doc2", $documentoIdentidad);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }

        public function search() {
            $sql = "SELECT id, razon_social as razon_social, rif, documento_identidad, nombre, apellido, telefono_1 as telefono, telefono_2 as telefono2, correo as email, direccion 
                    FROM proveedores WHERE active = 1";
            $stmt = $this->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        public function searchInactive() {
            $sql = "SELECT id, razon_social as razon_social, rif, documento_identidad, nombre, apellido, telefono_1 as telefono, telefono_2 as telefono2, correo as email, direccion 
                    FROM proveedores WHERE active = 0";
            $stmt = $this->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        public function update() {
            $sql = "UPDATE proveedores 
                    SET razon_social = :razon_social, rif = :rif, documento_identidad = :documento_identidad, nombre = :nombre, apellido = :apellido, telefono_1 = :telefono, telefono_2 = :telefono2, correo = :email, direccion = :direccion 
                    WHERE id = :id";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":razon_social", $this->razon_social);
            $stmt->bindParam(":rif", $this->rif);
            $stmt->bindParam(":documento_identidad", $this->documento_identidad);
            $stmt->bindParam(":nombre", $this->nombre);
            $stmt->bindParam(":apellido", $this->apellido);
            $stmt->bindParam(":telefono", $this->telefono);
            $stmt->bindParam(":telefono2", $this->telefono2);
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
