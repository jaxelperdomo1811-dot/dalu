<?php
    namespace Lenovo\Dalu\Models;
    use Lenovo\Dalu\Models\Conexion;

    class Productos extends Conexion {
        private $id;
        private $id_categoria;
        private $nombre;
        private $descripcion;
        private $precio;
        private $stock;
        

        public function __construct($id = null, $id_categoria = null, $nombre = null, $descripcion = null, $precio = null, $stock = null) {
            parent::__construct();
            $this->id = $id;
            $this->id_categoria = $id_categoria;
            $this->nombre = $nombre;
            $this->descripcion = $descripcion;
            $this->precio = $precio;
            $this->stock = $stock;
        }
        
        public function setId($id) { $this->id = $id; return $this; }
        public function setIdCategoria($id_categoria) { $this->id_categoria = $id_categoria; return $this; }
        public function setNombre($nombre) { $this->nombre = $nombre; return $this; }
        public function setDescripcion($descripcion) { $this->descripcion = $descripcion; return $this; }
        public function setPrecio($precio) { $this->precio = $precio; return $this; }
        public function setStock($stock) { $this->stock = $stock; return $this; }

        public function getId() { return $this->id; }
        public function getIdCategoria() { return $this->id_categoria; }
        public function getNombre() { return $this->nombre; }
        public function getDescripcion() { return $this->descripcion; }
        public function getPrecio() { return $this->precio; }
        public function getStock() { return $this->stock; }

        public function insert() {
            $sql = "INSERT INTO productos (id_categoria, nombre, descripcion, precio) VALUES (:id_categoria, :nombre, :descripcion, :precio)";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id_categoria", $this->id_categoria);
            $stmt->bindParam(":nombre", $this->nombre);
            $stmt->bindParam(":descripcion", $this->descripcion);
            $stmt->bindParam(":precio", $this->precio);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }
        public function search() {
            $sql = "(SELECT p.*, c.nombre AS categoria FROM productos p JOIN categorias c ON p.id_categoria = c.id WHERE p.activo = 1)";
            $stmt = $this->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        public function searchInactive() {
            $sql = "(SELECT p.*, c.nombre AS categoria FROM productos p JOIN categorias c ON p.id_categoria = c.id WHERE p.activo = 0)";
            $stmt = $this->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        public function update() {
            $sql = "UPDATE productos SET id_categoria = :id_categoria, nombre = :nombre, descripcion = :descripcion, precio = :precio WHERE id = :id";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id_categoria", $this->id_categoria);
            $stmt->bindParam(":nombre", $this->nombre);
            $stmt->bindParam(":descripcion", $this->descripcion);
            $stmt->bindParam(":precio", $this->precio);
            $stmt->bindParam(":id", $this->id);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }
        public function delete() {
            $sql = "UPDATE productos SET activo = 0 WHERE id = :id";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id", $this->id);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }
        public function activate() {
            $sql = "UPDATE productos SET activo = 1 WHERE id = :id";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id", $this->id);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }   
        }
    }   