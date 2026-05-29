<?php
    namespace Lenovo\Dalu\Models;
    use PDOException;
    use PDO;

    class Conexion extends PDO {
    private Conexion $pdo;
        function __construct() {
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "tiendadalu";
            try {
                parent::__construct("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
                $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $e) {
                die("Error en la conexión: " . $e->getMessage());
            }
        }
    }