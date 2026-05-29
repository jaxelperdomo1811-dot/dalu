<?php
namespace Lenovo\Dalu\Models;

use Lenovo\Dalu\Models\Conexion;
use PDO;
use PDOException;

class Productos extends Conexion {
    private $id;
    private $id_categoria;
    private $nombre;
    private $descripcion;
    private $precio_compra;
    private $precio_venta;
    private $precio_oferta;
    private $stock_total;
    private $stock_minimo;
    private $marca;
    private $imagen_principal;
    private $activo;
    private $ventas_totales;
    private $fecha_registro;
    
    private $variantes = [];

    public function __construct($id = null, $id_categoria = null, $nombre = null, $descripcion = null, $precio_venta = null,$precio_compra = 0.00,$marca = null
    ) {
        parent::__construct();
        $this->id = $id;
        $this->id_categoria = $id_categoria;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->precio_venta = $precio_venta;
        $this->precio_compra = $precio_compra;
        $this->marca = $marca;
        $this->stock_minimo = 3;
        $this->activo = 1;
        $this->ventas_totales = 0;
        $this->stock_total = 0;
    }
    
    public function setId($id) { $this->id = $id; return $this; }
    public function setIdCategoria($id_categoria) { $this->id_categoria = $id_categoria; return $this; }
    public function setNombre($nombre) { $this->nombre = $nombre; return $this; }
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; return $this; }
    public function setPrecioCompra($precio_compra) { $this->precio_compra = $precio_compra; return $this; }
    public function setPrecioVenta($precio_venta) { $this->precio_venta = $precio_venta; return $this; }
    public function setPrecioOferta($precio_oferta) { $this->precio_oferta = $precio_oferta; return $this; }
    public function setStockMinimo($stock_minimo) { $this->stock_minimo = $stock_minimo; return $this; }
    public function setMarca($marca) { $this->marca = $marca; return $this; }
    public function setImagenPrincipal($imagen_principal) { $this->imagen_principal = $imagen_principal; return $this; }
    public function setActivo($activo) { $this->activo = $activo; return $this; }
    public function setVariantes($variantes) { $this->variantes = $variantes; return $this; }
    
    public function getId() { return $this->id; }
    public function getIdCategoria() { return $this->id_categoria; }
    public function getNombre() { return $this->nombre; }
    public function getDescripcion() { return $this->descripcion; }
    public function getPrecioCompra() { return $this->precio_compra; }
    public function getPrecioVenta() { return $this->precio_venta; }
    public function getPrecioOferta() { return $this->precio_oferta; }
    public function getStockTotal() { return $this->stock_total; }
    public function getStockMinimo() { return $this->stock_minimo; }
    public function getMarca() { return $this->marca; }
    public function getImagenPrincipal() { return $this->imagen_principal; }
    public function getActivo() { return $this->activo; }
    public function getVentasTotales() { return $this->ventas_totales; }
    public function getVariantes() { return $this->variantes; }
    
    public function getNombreCategoria($producto_id = null) {
    $id = $producto_id ?? $this->id;
    $sql = "SELECT c.nombre FROM categorias c 
            JOIN productos p ON p.id_categoria = c.id 
            WHERE p.id = :id";
    $stmt = $this->prepare($sql);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? str_replace(' ', '_', strtolower($result['nombre'])) : 'sin_categoria';
    }

    public function generarRutaImagen($categoria_nombre, $nombre_producto, $extension) {
        $nombreLimpio = str_replace(' ', '_', $nombre_producto);
        $categoriaLimpia = str_replace(' ', '_', strtolower($categoria_nombre));
        
        // Sanitizar para evitar path traversal
        $categoriaLimpia = preg_replace('/[^a-zA-Z0-9_-]/', '', $categoriaLimpia);
        $nombreLimpio = preg_replace('/[^a-zA-Z0-9_-]/', '', $nombreLimpio);
        
        $rutaCarpeta = __DIR__ . '/../../assets/img/products/' . $categoriaLimpia . '/';
        
        // Crear carpeta si no existe
        if (!file_exists($rutaCarpeta)) {
            mkdir($rutaCarpeta, 0777, true);
        }
        
        return $rutaCarpeta . $nombreLimpio . '.' . $extension;
    }
    
    public function subirImagen($file, $categoria_nombre, $nombre_producto) {
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
        
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $extension = strtolower($extension);
        
        // Validar extensión
        $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array($extension, $extensionesPermitidas)) {
            throw new \Exception("Formato de imagen no permitido");
        }
        
        $rutaDestino = $this->generarRutaImagen($categoria_nombre, $nombre_producto, $extension);
        
        if (move_uploaded_file($file['tmp_name'], $rutaDestino)) {
            // Retornar la ruta relativa para guardar en BD
            return 'assets/img/products/' . str_replace(' ', '_', strtolower($categoria_nombre)) . '/' . 
                str_replace(' ', '_', $nombre_producto) . '.' . $extension;
        }
        
        return null;
    }
    
    public function insert() {
        try {
            $sql = "INSERT INTO productos (
                        id_categoria, nombre, descripcion, 
                        precio_venta, precio_oferta,
                        stock_minimo, marca, imagen_principal, 
                        activo, ventas_totales, stock_total
                    ) VALUES (
                        :id_categoria, :nombre, :descripcion,
                        :precio_venta, :precio_oferta,
                        :stock_minimo, :marca, :imagen_principal,
                        :activo, :ventas_totales, :stock_total
                    )";
            
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id_categoria", $this->id_categoria);
            $stmt->bindParam(":nombre", $this->nombre);
            $stmt->bindParam(":descripcion", $this->descripcion);
            $stmt->bindParam(":precio_venta", $this->precio_venta);
            $stmt->bindParam(":precio_oferta", $this->precio_oferta);
            $stmt->bindParam(":stock_minimo", $this->stock_minimo);
            $stmt->bindParam(":marca", $this->marca);
            $stmt->bindParam(":imagen_principal", $this->imagen_principal);
            $stmt->bindParam(":activo", $this->activo);
            $stmt->bindParam(":ventas_totales", $this->ventas_totales);
            $stmt->bindParam(":stock_total", $this->stock_total);
            
            if (!$stmt->execute()) {
                return false;
            }
            
            // sacar ID del producto
            $producto_id = $this->lastInsertId();
            $this->id = $producto_id;
            
            // guardar variantes
            if (!empty($this->variantes)) {
                $this->insertVariantes($producto_id);
            }
            
            return $producto_id;
            
        } catch (PDOException $e) {
            error_log("Error en insert producto: " . $e->getMessage());
            return false;
        }
    }
    
    public function search() {
        $sql = "SELECT 
                    p.*, 
                    c.nombre AS categoria_nombre,
                    (SELECT COUNT(*) FROM producto_variantes WHERE id_producto = p.id AND activo = 1) as total_variantes
                FROM productos p 
                JOIN categorias c ON p.id_categoria = c.id 
                WHERE p.activo = 1 
                ORDER BY p.fecha_registro DESC";
        
        $stmt = $this->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function searchInactive() {
        $sql = "SELECT 
                    p.*, 
                    c.nombre AS categoria_nombre 
                FROM productos p 
                JOIN categorias c ON p.id_categoria = c.id 
                WHERE p.activo = 0 
                ORDER BY p.fecha_registro DESC";
        
        $stmt = $this->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getById($id) {
        $sql = "SELECT * FROM productos WHERE id = :id";
        $stmt = $this->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($producto) {
            $producto['variantes'] = $this->getVariantesByProducto($id);
        }
        
        return $producto;
    }
    
    /**
     * Actualizar producto
     */
    public function update() {
        try {
            $sql = "UPDATE productos SET 
                        id_categoria = :id_categoria,
                        nombre = :nombre,
                        descripcion = :descripcion,
                        precio_venta = :precio_venta,
                        precio_oferta = :precio_oferta,
                        stock_minimo = :stock_minimo,
                        marca = :marca,
                        imagen_principal = :imagen_principal
                    WHERE id = :id";
            
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id_categoria", $this->id_categoria);
            $stmt->bindParam(":nombre", $this->nombre);
            $stmt->bindParam(":descripcion", $this->descripcion);
            $stmt->bindParam(":precio_venta", $this->precio_venta);
            $stmt->bindParam(":precio_oferta", $this->precio_oferta);
            $stmt->bindParam(":stock_minimo", $this->stock_minimo);
            $stmt->bindParam(":marca", $this->marca);
            $stmt->bindParam(":imagen_principal", $this->imagen_principal);
            $stmt->bindParam(":id", $this->id);
            
            return $stmt->execute();
            
        } catch (PDOException $e) {
            error_log("Error en update producto: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Eliminar producto (borrado lógico)
     */
    public function delete() {
        $sql = "UPDATE productos SET activo = 0 WHERE id = :id";
        $stmt = $this->prepare($sql);
        $stmt->bindParam(":id", $this->id);
        
        if ($stmt->execute()) {
            // También desactivar variantes
            $this->desactivarVariantesByProducto($this->id);
            return true;
        }
        return false;
    }
    
    /**
     * Activar producto
     */
    public function activate() {
        $sql = "UPDATE productos SET activo = 1 WHERE id = :id";
        $stmt = $this->prepare($sql);
        $stmt->bindParam(":id", $this->id);
        
        if ($stmt->execute()) {
            // También activar variantes
            $this->activarVariantesByProducto($this->id);
            return true;
        }
        return false;
    }
    
    // ==================== CRUD VARIANTES ====================
    
    /**
     * Insertar variantes para un producto
     */
    private function insertVariantes($producto_id) {
        $sql = "INSERT INTO producto_variantes (
                    id_producto, nombre_variante, atributos, 
                    precio_adicional, stock, imagen_variante, activo
                ) VALUES (
                    :id_producto, :nombre_variante, :atributos,
                    :precio_adicional, :stock, :imagen_variante, :activo
                )";
        
        $stmt = $this->prepare($sql);
        $total_stock = 0;
        
        foreach ($this->variantes as $variante) {
            // Validar que atributos sea JSON válido
            $atributos_json = empty($variante['atributos']) ? '{}' : json_encode($variante['atributos'], JSON_FORCE_OBJECT);
            
            $stmt->bindParam(":id_producto", $producto_id);
            $stmt->bindParam(":nombre_variante", $variante['nombre_variante']);
            $stmt->bindParam(":atributos", $atributos_json);
            $stmt->bindParam(":precio_adicional", $variante['precio_adicional']);
            $stmt->bindParam(":stock", $variante['stock']);
            $stmt->bindParam(":imagen_variante", $variante['imagen_variante']);
            $stmt->bindParam(":activo", $variante['activo']);
            
            $stmt->execute();
            $total_stock += $variante['stock'];
        }
        
        // Actualizar stock_total del producto
        $this->actualizarStockTotal($producto_id);
        
        return true;
    }
    
    /**
     * Obtener todas las variantes de un producto
     */
    public function getVariantesByProducto($producto_id) {
        $sql = "SELECT * FROM producto_variantes 
                WHERE id_producto = :id_producto AND activo = 1 
                ORDER BY id";
        
        $stmt = $this->prepare($sql);
        $stmt->bindParam(":id_producto", $producto_id);
        $stmt->execute();
        
        $variantes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Decodificar atributos JSON
        foreach ($variantes as &$variante) {
            $variante['atributos'] = json_decode($variante['atributos'], true);
        }
        
        return $variantes;
    }

    /**
     * Obtener variantes inactivas de un producto
     */
    public function getInactiveVariantesByProducto($producto_id) {
        $sql = "SELECT * FROM producto_variantes 
                WHERE id_producto = :id_producto AND activo = 0 
                ORDER BY id";
        
        $stmt = $this->prepare($sql);
        $stmt->bindParam(":id_producto", $producto_id);
        $stmt->execute();
        
        $variantes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Decodificar atributos JSON
        foreach ($variantes as &$variante) {
            $variante['atributos'] = json_decode($variante['atributos'], true);
        }
        
        return $variantes;
    }
    
    /**
     * Obtener una variante específica por ID
     */
    public function getVarianteById($variante_id) {
        $sql = "SELECT v.*, p.nombre as producto_nombre 
                FROM producto_variantes v
                JOIN productos p ON v.id_producto = p.id
                WHERE v.id = :id";
        
        $stmt = $this->prepare($sql);
        $stmt->bindParam(":id", $variante_id);
        $stmt->execute();
        
        $variante = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($variante) {
            $variante['atributos'] = json_decode($variante['atributos'], true);
        }
        
        return $variante;
    }
    
    /**
     * Agregar variante a producto existente
     */
    public function addVariante($producto_id, $variante_data) {
        try {
            $sql = "INSERT INTO producto_variantes (
                        id_producto, nombre_variante, atributos, 
                        precio_adicional, stock, imagen_variante, activo
                    ) VALUES (
                        :id_producto, :nombre_variante, :atributos,
                        :precio_adicional, :stock, :imagen_variante, 1
                    )";
            
            $stmt = $this->prepare($sql);
            $atributos_json = empty($variante_data['atributos']) ? '{}' : json_encode($variante_data['atributos'], JSON_FORCE_OBJECT);
            
            $stmt->bindParam(":id_producto", $producto_id);
            $stmt->bindParam(":nombre_variante", $variante_data['nombre_variante']);
            $stmt->bindParam(":atributos", $atributos_json);
            $stmt->bindParam(":precio_adicional", $variante_data['precio_adicional']);
            $stmt->bindParam(":stock", $variante_data['stock']);
            $stmt->bindParam(":imagen_variante", $variante_data['imagen_variante']);
            
            if ($stmt->execute()) {
                $this->actualizarStockTotal($producto_id);
                return $this->lastInsertId();
            }
            
            return false;
            
        } catch (PDOException $e) {
            error_log("Error en addVariante: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Actualizar variante completa
     */
    public function updateVariante($variante_id, $variante_data) {
        try {
            $sql = "UPDATE producto_variantes SET 
                        nombre_variante = :nombre_variante,
                        atributos = :atributos,
                        precio_adicional = :precio_adicional,
                        stock = :stock,
                        imagen_variante = :imagen_variante
                    WHERE id = :id";
            
            $stmt = $this->prepare($sql);
            $atributos_json = empty($variante_data['atributos']) ? '{}' : json_encode($variante_data['atributos'], JSON_FORCE_OBJECT);
            
            $stmt->bindParam(":nombre_variante", $variante_data['nombre_variante']);
            $stmt->bindParam(":atributos", $atributos_json);
            $stmt->bindParam(":precio_adicional", $variante_data['precio_adicional']);
            $stmt->bindParam(":stock", $variante_data['stock']);
            $stmt->bindParam(":imagen_variante", $variante_data['imagen_variante']);
            $stmt->bindParam(":id", $variante_id);
            
            if ($stmt->execute()) {
                // Obtener producto_id para actualizar stock total
                $variante = $this->getVarianteById($variante_id);
                if ($variante) {
                    $this->actualizarStockTotal($variante['id_producto']);
                }
                return true;
            }
            
            return false;
            
        } catch (PDOException $e) {
            error_log("Error en updateVariante: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Actualizar stock de una variante
     */
    public function updateVarianteStock($variante_id, $nuevo_stock) {
        try {
            $sql = "UPDATE producto_variantes SET stock = :stock WHERE id = :id";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":stock", $nuevo_stock);
            $stmt->bindParam(":id", $variante_id);
            
            if ($stmt->execute()) {
                // Obtener el producto_id de la variante
                $variante = $this->getVarianteById($variante_id);
                if ($variante) {
                    $this->actualizarStockTotal($variante['id_producto']);
                }
                return true;
            }
            return false;
            
        } catch (PDOException $e) {
            error_log("Error en updateVarianteStock: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Eliminar variante (borrado lógico)
     */
    public function deleteVariante($variante_id) {
        try {
            $sql = "UPDATE producto_variantes SET activo = 0 WHERE id = :id";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id", $variante_id);
            
            if ($stmt->execute()) {
                // Obtener producto_id y actualizar stock total
                $variante = $this->getVarianteById($variante_id);
                if ($variante) {
                    $this->actualizarStockTotal($variante['id_producto']);
                }
                return true;
            }
            return false;
            
        } catch (PDOException $e) {
            error_log("Error en deleteVariante: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Reactivar variante
     */
    public function reactivateVariante($variante_id) {
        try {
            $sql = "UPDATE producto_variantes SET activo = 1 WHERE id = :id";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id", $variante_id);
            
            if ($stmt->execute()) {
                // Actualizar stock total para incluir esta variante nuevamente
                // Nota: getVarianteById no filtra por activo=1, pero si lo hiciera, podriamos usar un query directo.
                $sql_prod = "SELECT id_producto FROM producto_variantes WHERE id = :id";
                $stmt_prod = $this->prepare($sql_prod);
                $stmt_prod->bindParam(":id", $variante_id);
                $stmt_prod->execute();
                $res = $stmt_prod->fetch(PDO::FETCH_ASSOC);
                
                if ($res) {
                    $this->actualizarStockTotal($res['id_producto']);
                }
                return true;
            }
            return false;
            
        } catch (PDOException $e) {
            error_log("Error en reactivateVariante: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Actualizar el stock total del producto (suma de variantes activas)
     */
    private function actualizarStockTotal($producto_id) {
        $sql = "UPDATE productos p
                SET p.stock_total = (
                    SELECT COALESCE(SUM(v.stock), 0)
                    FROM producto_variantes v
                    WHERE v.id_producto = p.id AND v.activo = 1
                )
                WHERE p.id = :producto_id";
        
        $stmt = $this->prepare($sql);
        $stmt->bindParam(":producto_id", $producto_id);
        return $stmt->execute();
    }
    
    /**
     * Desactivar todas las variantes de un producto
     */
    private function desactivarVariantesByProducto($producto_id) {
        $sql = "UPDATE producto_variantes SET activo = 0 WHERE id_producto = :producto_id";
        $stmt = $this->prepare($sql);
        $stmt->bindParam(":producto_id", $producto_id);
        return $stmt->execute();
    }
    
    /**
     * Activar todas las variantes de un producto
     */
    private function activarVariantesByProducto($producto_id) {
        $sql = "UPDATE producto_variantes SET activo = 1 WHERE id_producto = :producto_id";
        $stmt = $this->prepare($sql);
        $stmt->bindParam(":producto_id", $producto_id);
        return $stmt->execute();
    }
    
    // ==================== MÉTODOS ADICIONALES ÚTILES ====================
    
    /**
     * Buscar productos por categoría
     */
    public function getByCategoria($categoria_id) {
        $sql = "SELECT * FROM productos 
                WHERE id_categoria = :categoria_id AND activo = 1 
                ORDER BY nombre";
        
        $stmt = $this->prepare($sql);
        $stmt->bindParam(":categoria_id", $categoria_id);
        $stmt->execute();
        
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Cargar variantes para cada producto
        foreach ($productos as &$producto) {
            $producto['variantes'] = $this->getVariantesByProducto($producto['id']);
        }
        
        return $productos;
    }
    
    /**
     * Buscar productos con bajo stock
     */
    public function getLowStock() {
        $sql = "SELECT p.*, c.nombre as categoria_nombre
                FROM productos p
                JOIN categorias c ON p.id_categoria = c.id
                WHERE p.stock_total <= p.stock_minimo AND p.activo = 1
                ORDER BY p.stock_total ASC";
        
        $stmt = $this->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener productos destacados (más vendidos)
     */
    public function getTopSellers($limit = 10) {
        $sql = "SELECT p.*, c.nombre as categoria_nombre
                FROM productos p
                JOIN categorias c ON p.id_categoria = c.id
                WHERE p.activo = 1 AND p.ventas_totales > 0
                ORDER BY p.ventas_totales DESC
                LIMIT :limit";
        
        $stmt = $this->prepare($sql);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Buscar productos por texto
     */
    public function searchByText($texto) {
        $texto = "%{$texto}%";
        $sql = "SELECT p.*, c.nombre as categoria_nombre
                FROM productos p
                JOIN categorias c ON p.id_categoria = c.id
                WHERE (p.nombre LIKE :texto OR p.descripcion LIKE :texto OR p.marca LIKE :texto)
                AND p.activo = 1
                ORDER BY p.nombre";
        
        $stmt = $this->prepare($sql);
        $stmt->bindParam(":texto", $texto);
        $stmt->execute();
        
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Cargar variantes
        foreach ($productos as &$producto) {
            $producto['variantes'] = $this->getVariantesByProducto($producto['id']);
        }
        
        return $productos;
    }
    
    /**
     * Verificar si hay suficiente stock de una variante
     */
    public function checkStock($variante_id, $cantidad) {
        $sql = "SELECT stock FROM producto_variantes WHERE id = :id AND activo = 1";
        $stmt = $this->prepare($sql);
        $stmt->bindParam(":id", $variante_id);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result && $result['stock'] >= $cantidad;
    }
    
    /**
     * Reducir stock al vender
     */
    public function reducirStock($variante_id, $cantidad) {
        try {
            // Reducir stock de la variante
            $sql = "UPDATE producto_variantes 
                    SET stock = stock - :cantidad 
                    WHERE id = :id AND stock >= :cantidad";
            
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":cantidad", $cantidad);
            $stmt->bindParam(":id", $variante_id);
            
            if (!$stmt->execute() || $stmt->rowCount() == 0) {
                return false;
            }
            
            // Obtener producto_id
            $variante = $this->getVarianteById($variante_id);
            if ($variante) {
                // Actualizar stock total del producto
                $this->actualizarStockTotal($variante['id_producto']);
                
                // Incrementar ventas_totales del producto
                $sql2 = "UPDATE productos SET ventas_totales = ventas_totales + 1 
                         WHERE id = :producto_id";
                $stmt2 = $this->prepare($sql2);
                $stmt2->bindParam(":producto_id", $variante['id_producto']);
                $stmt2->execute();
            }
            
            return true;
            
        } catch (PDOException $e) {
            error_log("Error en reducirStock: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtener todas las variantes con bajo stock
     */
    public function getVariantesLowStock() {
        $sql = "SELECT v.*, p.nombre as producto_nombre, p.stock_minimo
                FROM producto_variantes v
                JOIN productos p ON v.id_producto = p.id
                WHERE v.stock <= p.stock_minimo AND v.activo = 1 AND p.activo = 1
                ORDER BY v.stock ASC";
        
        $stmt = $this->prepare($sql);
        $stmt->execute();
        
        $variantes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Decodificar atributos JSON
        foreach ($variantes as &$variante) {
            $variante['atributos'] = json_decode($variante['atributos'], true);
        }
        
        return $variantes;
    }
}