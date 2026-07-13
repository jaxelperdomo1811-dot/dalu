<?php
    namespace Lenovo\Dalu\Models;
    use Lenovo\Dalu\Models\Conexion;
    use Lenovo\Dalu\Interfaces\ICategorias;

    class Categorias extends Conexion implements ICategorias {
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
            $sql = "INSERT INTO categorias (nombre, descripcion) VALUES (:nombre, :descripcion)";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":nombre", $this->nombre);
            $stmt->bindParam(":descripcion", $this->descripcion);
            if ($stmt->execute()) {
                return $this->lastInsertId();
            } else {
                return false;
            }
        }
        public function search() {
            $sql = "SELECT * FROM categorias WHERE activo = 1";
            $stmt = $this->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        public function searchInactive() {
            $sql = "SELECT * FROM categorias WHERE activo = 0";
            $stmt = $this->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        public function searchById($id) {
            $this->id = $id;
            $sql = "SELECT * FROM categorias WHERE id = :id";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id", $this->id);
            $stmt->execute();
            return $stmt->fetch();
        }
        public function update() {
            $sql = "UPDATE categorias SET nombre = :nombre, descripcion = :descripcion WHERE id = :id";
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
            $sql = "UPDATE categorias SET activo = 0 WHERE id = :id";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id", $this->id);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }
        public function activate() {
            $sql = "UPDATE categorias SET activo = 1 WHERE id = :id";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id", $this->id);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }

        public function getCampos($id_categoria = null) {
            $id = $id_categoria ?? $this->id;
            $sql = "SELECT cv.*, cc.orden FROM campos_variante cv
                    JOIN categoria_campos cc ON cv.id = cc.id_campo
                    WHERE cc.id_categoria = :id_categoria AND cv.activo = 1
                    ORDER BY cc.orden ASC";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id_categoria", $id);
            $stmt->execute();
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($result as &$row) {
                $row['opciones'] = $row['opciones'] ? json_decode($row['opciones'], true) : null;
            }
            return $result;
        }

        public function setCampos($id_categoria, $campos_ids) {
            $sqlDelete = "DELETE FROM categoria_campos WHERE id_categoria = :id_categoria";
            $stmtDelete = $this->prepare($sqlDelete);
            $stmtDelete->bindParam(":id_categoria", $id_categoria);
            $stmtDelete->execute();

            if (empty($campos_ids)) return true;

            $sqlInsert = "INSERT INTO categoria_campos (id_categoria, id_campo, orden) VALUES (:id_categoria, :id_campo, :orden)";
            $stmtInsert = $this->prepare($sqlInsert);
            
            $orden = 1;
            foreach ($campos_ids as $id_campo) {
                $stmtInsert->bindParam(":id_categoria", $id_categoria);
                $stmtInsert->bindParam(":id_campo", $id_campo);
                $stmtInsert->bindParam(":orden", $orden);
                $stmtInsert->execute();
                $orden++;
            }
            return true;
        }
    }