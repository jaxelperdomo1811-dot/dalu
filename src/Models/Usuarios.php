<?php
    namespace Lenovo\Dalu\Models;
    use Lenovo\Dalu\Models\Conexion;    
    use Lenovo\Dalu\Interfaces\IUsuarios;
    class Usuarios extends Conexion implements IUsuarios {
        private $id;
        private $nombre;
        private $rol;
        private $usuario;
        private $clave;
        private $id_pregunta_s_1;
        private $respuesta_s_1;
        private $id_pregunta_s_2;
        private $respuesta_s_2;
        private $id_pregunta_s_3;
        private $respuesta_s_3;

        public function __construct($id = null, $nombre = null, $rol=null, $usuario = null, $clave = null, $id_pregunta_s_1 = null, $respuesta_s_1 = null, $id_pregunta_s_2 = null, $respuesta_s_2 = null, $id_pregunta_s_3 = null, $respuesta_s_3 = null) {
            parent::__construct();
            $this->id = $id;
            $this->nombre = $nombre;
            $this->rol = $rol;
            $this->usuario = $usuario;
            $this->clave = $clave;
            $this->id_pregunta_s_1 = $id_pregunta_s_1;
            $this->respuesta_s_1 = $respuesta_s_1;
            $this->id_pregunta_s_2 = $id_pregunta_s_2;
            $this->respuesta_s_2 = $respuesta_s_2;
            $this->id_pregunta_s_3 = $id_pregunta_s_3;
            $this->respuesta_s_3 = $respuesta_s_3;
        }

        public function getId() { return $this->id; }
        public function getNombre() { return $this->nombre; }
        public function getRol() { return $this->rol; }
        public function getUsuario() { return $this->usuario; }
        public function getClave() { return $this->clave; }
        public function getIdPreguntaS1() { return $this->id_pregunta_s_1; }
        public function getRespuestaS1() { return $this->respuesta_s_1; }
        public function getIdPreguntaS2() { return $this->id_pregunta_s_2; }
        public function getRespuestaS2() { return $this->respuesta_s_2; }
        public function getIdPreguntaS3() { return $this->id_pregunta_s_3; }
        public function getRespuestaS3() { return $this->respuesta_s_3; }

        public function setId($id) { $this->id = $id; return $this; }
        public function setNombre($nombre) { $this->nombre = $nombre; return $this; }
        public function setRol($rol) { $this->rol = $rol; return $this; }
        public function setUsuario($usuario) { $this->usuario = $usuario; return $this; }
        public function setClave($clave) { $this->clave = $clave; return $this; }
        public function setPreguntaS1($id_pregunta_s_1) { $this->id_pregunta_s_1 = $id_pregunta_s_1; return $this; }
        public function setRespuestaS1($respuesta_s_1) { $this->respuesta_s_1 = $respuesta_s_1; return $this; }
        public function setPreguntaS2($id_pregunta_s_2) { $this->id_pregunta_s_2 = $id_pregunta_s_2; return $this; }
        public function setRespuestaS2($respuesta_s_2) { $this->respuesta_s_2 = $respuesta_s_2; return $this; }
        public function setPreguntaS3($id_pregunta_s_3) { $this->id_pregunta_s_3 = $id_pregunta_s_3; return $this; }
        public function setRespuestaS3($respuesta_s_3) { $this->respuesta_s_3 = $respuesta_s_3; return $this; }

        public function insert() {
            try {
                $sql = "INSERT INTO usuarios (id_rol, nombre, usuario, clave) VALUES (:rol, :nombre, :usuario, :clave)";
                $stmt = $this->prepare($sql);
                $stmt->bindParam(":rol", $this->rol);
                $stmt->bindParam(":nombre", $this->nombre);
                $stmt->bindParam(":usuario", $this->usuario);
                $stmt->bindParam(":clave", $this->clave);
                if (!$stmt->execute()) {
                    return false;
                }

                $this->id = $this->lastInsertId();
                if (!$this->insertRespuestasSeguridad($this->id)) {
                    return false;
                }

                return $this->id;
            } catch (\PDOException $e) {
                return false;
            }
        }

        public function insertRespuestasSeguridad($id_usuario) {
            $sql = "INSERT INTO respuestas_seguridad (id_usuario, id_pregunta, respuesta_hash) VALUES (:id_usuario, :id_pregunta, :respuesta_hash)";
            $stmt = $this->prepare($sql);

            $items = [
                ['pregunta' => $this->id_pregunta_s_1, 'respuesta' => $this->respuesta_s_1],
                ['pregunta' => $this->id_pregunta_s_2, 'respuesta' => $this->respuesta_s_2],
                ['pregunta' => $this->id_pregunta_s_3, 'respuesta' => $this->respuesta_s_3],
            ];

            foreach ($items as $item) {
                if (empty($item['pregunta']) || empty($item['respuesta'])) {
                    return false;
                }

                if (!$stmt->execute([
                    ':id_usuario' => $id_usuario,
                    ':id_pregunta' => $item['pregunta'],
                    ':respuesta_hash' => $item['respuesta'],
                ])) {
                    return false;
                }
            }

            return true;
        }

        public function login($usuario, $clave) {
            $sql = "SELECT u.*, (SELECT nombre FROM roles WHERE id = u.id_rol) AS rol FROM usuarios u WHERE u.usuario = :usuario AND u.clave = :clave AND u.estado = 1";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":usuario", $usuario);
            $stmt->bindParam(":clave", $clave);
            if ($stmt->execute()) {
                return $stmt->fetch(\PDO::FETCH_ASSOC);
            } else {
                return false;
            }
        }
        public function search() {
            $sql = "SELECT id, nombre, id_rol, (SELECT nombre FROM roles WHERE id=usuarios.id_rol) AS rol, usuario, clave FROM usuarios WHERE estado = 1";
            $stmt = $this->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
        public function searchInactive() {
            $sql = "SELECT id, nombre, id_rol, (SELECT nombre FROM roles WHERE id=usuarios.id_rol) AS rol, usuario, clave FROM usuarios WHERE estado = 0";
            $stmt = $this->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        public function searchId($id) {
            $sql = "SELECT * FROM usuarios WHERE id = :id";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }
        public function searchRol($usuario) {
            $sql = "SELECT (SELECT nombre FROM roles WHERE id=usuarios.id_rol) AS rol_nombre FROM usuarios WHERE usuario = :usuario";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":usuario", $usuario);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }
        public function update() {
            $sql = "UPDATE usuarios SET nombre = :nombre, id_rol = :rol, usuario = :usuario, clave = :clave WHERE id = :id";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id", $this->id);
            $stmt->bindParam(":nombre", $this->nombre);
            $stmt->bindParam(":rol", $this->rol);
            $stmt->bindParam(":usuario", $this->usuario);
            $stmt->bindParam(":clave", $this->clave);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }
        public function delete() {
            $sql = "UPDATE usuarios SET estado = 0 WHERE id = :id";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id", $this->id);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }
        public function active() {
            $sql = "UPDATE usuarios SET estado = 1 WHERE id = :id";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id", $this->id);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }

        public function searchIdPregunta_S(){
            $sql = "SELECT * FROM `preguntas_seguridad` WHERE activo = 1";
            $stmt = $this->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        public function searchPregunta_S(){
            return $this->searchIdPregunta_S();
        }
        public function searchPregunta_SById($id){
            $sql = "SELECT id_pregunta, respuesta_hash FROM respuestas_seguridad WHERE id_usuario = :id_usuario";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id_usuario", $id);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        public function findByUsuario($usuario) {
            $sql = "SELECT * FROM usuarios WHERE usuario = :usuario";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":usuario", $usuario);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }

        public function getPreguntaByKey($key) {
            $sql = "SELECT id FROM preguntas_seguridad WHERE activo = 1 AND (pregunta = :key OR pregunta LIKE :likeKey) LIMIT 1";
            $stmt = $this->prepare($sql);
            $likeKey = "%" . $key . "%";
            $stmt->bindParam(":key", $key);
            $stmt->bindParam(":likeKey", $likeKey);
            $stmt->execute();
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $row ? $row['id'] : false;
        }

        public function updatePassword($id, $clave) {
            $sql = "UPDATE usuarios SET clave = :clave WHERE id = :id";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":clave", $clave);
            $stmt->bindParam(":id", $id);
            return $stmt->execute();
        }
    }
