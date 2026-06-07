<?php
namespace Lenovo\Dalu\Models;
use Lenovo\Dalu\Models\Conexion;

    class Pedidos extends Conexion {
        private $id;
        private $id_cliente;
        private $tipo; // 'cliente' o 'tienda'
        private $estado;
        private $fecha_registro;
        private $id_proveedor; // Para pedidos tipo 'tienda'
        
        private $detalles = [];

        public function __construct($id = null, $id_cliente = null, $tipo = null, $estado = 'pendiente', $id_proveedor = null) {
            parent::__construct();
            $this->id = $id;
            $this->id_cliente = $id_cliente;
            $this->tipo = $tipo;
            $this->estado = $estado;
            $this->id_proveedor = $id_proveedor;
        }
        
        // ==================== SETTERS ====================
        public function setId($id) { $this->id = $id; return $this; }
        public function setIdCliente($id_cliente) { $this->id_cliente = $id_cliente; return $this; }
        public function setTipo($tipo) { $this->tipo = $tipo; return $this; }
        public function setEstado($estado) { $this->estado = $estado; return $this; }
        public function setIdProveedor($id_proveedor) { $this->id_proveedor = $id_proveedor; return $this; }
        public function setDetalles($detalles) { $this->detalles = $detalles; return $this; }
        
        // ==================== GETTERS ====================
        public function getId() { return $this->id; }
        public function getIdCliente() { return $this->id_cliente; }
        public function getTipo() { return $this->tipo; }
        public function getEstado() { return $this->estado; }
        public function getIdProveedor() { return $this->id_proveedor; }
        public function getFechaRegistro() { return $this->fecha_registro; }
        public function getDetalles() { return $this->detalles; }
        
        // ==================== CRUD PEDIDOS ====================
        
        public function insert() {
            try {
                $sql = "INSERT INTO pedidos (id_cliente, id_proveedor, tipo, estado) 
                        VALUES (:id_cliente, :id_proveedor, :tipo, :estado)";
                
                $stmt = $this->prepare($sql);
                $stmt->bindParam(":id_cliente", $this->id_cliente);
                $stmt->bindParam(":id_proveedor", $this->id_proveedor);
                $stmt->bindParam(":tipo", $this->tipo);
                $stmt->bindParam(":estado", $this->estado);
                
                if (!$stmt->execute()) {
                    return false;
                }
                
                $pedido_id = $this->lastInsertId();
                $this->id = $pedido_id;
                
                if (!empty($this->detalles)) {
                    $this->insertDetalles($pedido_id);
                }
                
                return $pedido_id;
                
            } catch (\PDOException $e) {
                error_log("Error en insert pedido: " . $e->getMessage());
                return false;
            }
        }
        
        private function insertDetalles($pedido_id) {
            $sql = "INSERT INTO detalles_pedido (
                        id_pedido, tipo, imagen, link, 
                        nombre_producto, cantidad, precio_unitario, 
                        descripcion_producto, id_variante, status_inventario
                    ) VALUES (
                        :id_pedido, :tipo, :imagen, :link,
                        :nombre_producto, :cantidad, :precio_unitario,
                        :descripcion_producto, :id_variante, :status_inventario
                    )";
            
            $stmt = $this->prepare($sql);
            
            foreach ($this->detalles as $detalle) {
                $status = !empty($detalle['id_variante']) ? 'vinculado' : 'pendiente';
                $params = [
                    ':id_pedido' => $pedido_id,
                    ':tipo' => $detalle['tipo'] ?? 'proveedor',
                    ':imagen' => $detalle['imagen'] ?? '',
                    ':link' => $detalle['link'] ?? '',
                    ':nombre_producto' => $detalle['nombre_producto'] ?? null,
                    ':cantidad' => !empty($detalle['cantidad']) ? $detalle['cantidad'] : 1,
                    ':precio_unitario' => $detalle['precio_unitario'] ?? null,
                    ':descripcion_producto' => $detalle['descripcion_producto'] ?? null,
                    ':id_variante' => !empty($detalle['id_variante']) ? $detalle['id_variante'] : null,
                    ':status_inventario' => $status,
                ];

                if (!$stmt->execute($params)) {
                    error_log('Error en insertDetalles: ' . json_encode($stmt->errorInfo()));
                }
            }
            
            return true;
        }
        
        /**
         * Buscar pedidos activos (no finalizados)
         */
        public function search() {
            $sql = "SELECT p.*, 
                    COALESCE(NULLIF(pr.razon_social, ''), NULLIF(pr.nombre, ''), 'Proveedor Desconocido') AS nombre_proveedor,
                    c.nombre as cliente_nombre, 
                    c.apellido as cliente_apellido,
                    c.telefono as cliente_telefono
                    FROM pedidos p
                    LEFT JOIN clientes c ON p.id_cliente = c.id
                    LEFT JOIN proveedores pr ON p.id_proveedor = pr.id
                    WHERE p.estado NOT IN ('recibido', 'cancelado')
                    ORDER BY p.fecha_registro DESC";
            
            $stmt = $this->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
        
        /**
         * Buscar pedidos finalizados (recibidos o cancelados)
         */
        public function searchFinished() {
            $sql = "SELECT p.*, 
                    COALESCE(NULLIF(pr.razon_social, ''), NULLIF(pr.nombre, ''), 'Proveedor Desconocido') AS nombre_proveedor,
                    c.nombre as cliente_nombre, 
                    c.apellido as cliente_apellido
                    FROM pedidos p
                    LEFT JOIN clientes c ON p.id_cliente = c.id
                    LEFT JOIN proveedores pr ON p.id_proveedor = pr.id
                    WHERE p.estado IN ('recibido', 'cancelado')
                    ORDER BY p.fecha_registro DESC";
            
            $stmt = $this->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
        
        /**
         * Obtener pedido por ID con sus detalles
         */
        public function getById($id) {
            $sql = "SELECT p.*, 
                    COALESCE(NULLIF(pr.razon_social, ''), NULLIF(pr.nombre, ''), 'Proveedor Desconocido') AS nombre_proveedor,
                    c.nombre as cliente_nombre, 
                    c.apellido as cliente_apellido,
                    c.cedula as cliente_cedula,
                    c.telefono as cliente_telefono,
                    c.direccion as cliente_direccion
                    FROM pedidos p
                    LEFT JOIN clientes c ON p.id_cliente = c.id
                    LEFT JOIN proveedores pr ON p.id_proveedor = pr.id
                    WHERE p.id = :id";
            
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            $pedido = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if ($pedido) {
                $pedido['detalles'] = $this->getDetallesByPedido($id);
            }
            
            return $pedido;
        }
        
        /**
         * Obtener pedidos por tipo (cliente/tienda)
         */
        public function getByTipo($tipo) {
            $sql = "SELECT p.*, 
                    COALESCE(NULLIF(pr.razon_social, ''), NULLIF(pr.nombre, ''), 'Proveedor Desconocido') AS nombre_proveedor,
                    c.nombre as cliente_nombre, 
                    c.apellido as cliente_apellido
                    FROM pedidos p
                    LEFT JOIN clientes c ON p.id_cliente = c.id
                    LEFT JOIN proveedores pr ON p.id_proveedor = pr.id
                    WHERE p.tipo = :tipo
                    ORDER BY p.fecha_registro DESC";
            
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":tipo", $tipo);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
        
        /**
         * Obtener pedidos por cliente
         */
        public function getByCliente($cliente_id) {
            $sql = "SELECT p.*, 
                    COALESCE(NULLIF(pr.razon_social, ''), NULLIF(pr.nombre, ''), 'Proveedor Desconocido') AS nombre_proveedor,
                    c.nombre as cliente_nombre, 
                    c.apellido as cliente_apellido
                    FROM pedidos p
                    LEFT JOIN clientes c ON p.id_cliente = c.id
                    LEFT JOIN proveedores pr ON p.id_proveedor = pr.id
                    WHERE p.id_cliente = :cliente_id
                    ORDER BY p.fecha_registro DESC";
            
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":cliente_id", $cliente_id);
            $stmt->execute();
            
            $pedidos = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            foreach ($pedidos as &$pedido) {
                $pedido['detalles'] = $this->getDetallesByPedido($pedido['id']);
            }
            
            return $pedidos;
        }
        
        /**
         * Actualizar estado del pedido
         */
        public function updateEstado() {
            $updateDates = "";
            if ($this->estado === 'enviado') {
                $updateDates = ", fecha_estimada = DATE_ADD(CURRENT_DATE, INTERVAL 24 DAY)";
            } elseif ($this->estado === 'recibido') {
                $updateDates = ", fecha_recepcion = CURRENT_TIMESTAMP";
            } elseif ($this->estado === 'entregado') {
                $updateDates = ", fecha_entrega = CURRENT_TIMESTAMP";
            }
            
            $sql = "UPDATE pedidos SET estado = :estado {$updateDates} WHERE id = :id";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":estado", $this->estado);
            $stmt->bindParam(":id", $this->id);
            return $stmt->execute();
        }
        
        /**
         * Marcar pedido como recibido parcialmente
         */
        public function marcarParcial() {
            $this->estado = 'parcial';
            return $this->updateEstado();
        }
        
        /**
         * Marcar pedido como recibido completo
         */
        public function marcarRecibido() {
            $this->estado = 'recibido';
            return $this->updateEstado();
        }
        
        /**
         * Cancelar pedido
         */
        public function cancel() {
            $this->estado = 'cancelado';
            return $this->updateEstado();
        }
        
        // ==================== CRUD DETALLES ====================
        
        public function getDetallesByPedido($pedido_id) {
            $sql = "SELECT dp.*, 
                    p.nombre as producto_nombre,
                    p.precio_venta as producto_precio,
                    pv.id_producto,
                    pv.nombre_variante as variante_nombre,
                    pv.atributos as variante_atributos
                    FROM detalles_pedido dp
                    LEFT JOIN producto_variantes pv ON dp.id_variante = pv.id
                    LEFT JOIN productos p ON pv.id_producto = p.id
                    WHERE dp.id_pedido = :id_pedido 
                    ORDER BY dp.id";
            
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id_pedido", $pedido_id);
            $stmt->execute();
            
            $detalles = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            foreach ($detalles as &$detalle) {
                if (!empty($detalle['variante_atributos'])) {
                    $detalle['variante_atributos'] = json_decode($detalle['variante_atributos'], true);
                }
            }
            
            return $detalles;
        }
        
        /**
         * Agregar detalle a pedido existente
         */
        public function addDetalle($pedido_id, $detalle_data) {
            $sql = "INSERT INTO detalles_pedido (
                        id_pedido, tipo, imagen, link, 
                        nombre_producto, cantidad, precio_unitario, 
                        descripcion_producto, id_variante, status_inventario
                    ) VALUES (
                        :id_pedido, :tipo, :imagen, :link,
                        :nombre_producto, :cantidad, :precio_unitario,
                        :descripcion_producto, :id_variante, :status_inventario
                    )";
            
            $stmt = $this->prepare($sql);
            
            $status = !empty($detalle_data['id_variante']) ? 'vinculado' : 'pendiente';
            
            $stmt->bindParam(":id_pedido", $pedido_id);
            $stmt->bindParam(":tipo", $detalle_data['tipo']);
            $stmt->bindParam(":imagen", $detalle_data['imagen']);
            $stmt->bindParam(":link", $detalle_data['link']);
            $stmt->bindParam(":nombre_producto", $detalle_data['nombre_producto']);
            $stmt->bindParam(":cantidad", $detalle_data['cantidad']);
            $stmt->bindParam(":precio_unitario", $detalle_data['precio_unitario']);
            $stmt->bindParam(":descripcion_producto", $detalle_data['descripcion_producto']);
            $stmt->bindParam(":id_variante", $detalle_data['id_variante']);
            $stmt->bindParam(":status_inventario", $status);
            
            if ($stmt->execute()) {
                return $this->lastInsertId();
            }
            return false;
        }
        
        /**
         * Actualizar detalle
         */
        public function updateDetalle($detalle_id, $detalle_data) {
            $sql = "UPDATE detalles_pedido SET 
                        tipo = :tipo,
                        imagen = :imagen,
                        link = :link,
                        nombre_producto = :nombre_producto,
                        cantidad = :cantidad,
                        precio_unitario = :precio_unitario,
                        descripcion_producto = :descripcion_producto,
                        id_variante = :id_variante,
                        status_inventario = :status_inventario
                    WHERE id = :id";
            
            $stmt = $this->prepare($sql);
            
            $status = !empty($detalle_data['id_variante']) ? 'vinculado' : 'pendiente';
            
            $stmt->bindParam(":tipo", $detalle_data['tipo']);
            $stmt->bindParam(":imagen", $detalle_data['imagen']);
            $stmt->bindParam(":link", $detalle_data['link']);
            $stmt->bindParam(":nombre_producto", $detalle_data['nombre_producto']);
            $stmt->bindParam(":cantidad", $detalle_data['cantidad']);
            $stmt->bindParam(":precio_unitario", $detalle_data['precio_unitario']);
            $stmt->bindParam(":descripcion_producto", $detalle_data['descripcion_producto']);
            $stmt->bindParam(":id_variante", $detalle_data['id_variante']);
            $stmt->bindParam(":status_inventario", $status);
            $stmt->bindParam(":id", $detalle_id);
            
            return $stmt->execute();
        }
        
        /**
         * Eliminar detalle
         */
        public function deleteDetalle($detalle_id) {
            $sql = "DELETE FROM detalles_pedido WHERE id = :id";
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id", $detalle_id);
            return $stmt->execute();
        }
        
        // ==================== MÉTODOS PARA PRODUCTOS VAGOS ====================
        
        /**
         * Obtener detalles pendientes de vincular a inventario
         */
        public function getDetallesPendientes() {
            $sql = "SELECT dp.*, p.tipo as pedido_tipo
                    FROM detalles_pedido dp
                    JOIN pedidos p ON dp.id_pedido = p.id
                    WHERE dp.id_pedido = :id_pedido 
                    AND dp.status_inventario = 'pendiente'";
            
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id_pedido", $this->id);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
        
        /**
         * Obtener pedido completo con solo los detalles pendientes
         */
        public function getPedidoConDetallesPendientes($pedido_id) {
            $pedido = $this->getById($pedido_id);
            if (!$pedido) return false;
            
            $this->setId($pedido_id);
            $pedido['detalles_pendientes'] = $this->getDetallesPendientes();
            
            return $pedido;
        }
        
        /**
         * Vincular un detalle vago a un producto existente
         */
        public function vincularDetalleAProducto($detalle_id, $id_variante = null) {
            $sql = "UPDATE detalles_pedido 
                    SET id_variante = :id_variante,
                        status_inventario = 'vinculado'
                    WHERE id = :id";
            
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id_variante", $id_variante);
            $stmt->bindParam(":id", $detalle_id);
            return $stmt->execute();
        }
        
        /**
         * Crear un nuevo producto desde un detalle vago
         * (para pedidos tipo tienda cuando llega mercancía nueva)
         */
        public function crearProductoDesdeDetalle($detalle_id, $producto_data) {
            $productosModel = new Productos();
            
            // Crear el producto
            $producto = new Productos(
                null,
                $producto_data['id_categoria'],
                $producto_data['nombre'],
                $producto_data['descripcion'],
                $producto_data['precio_venta'],
                $producto_data['precio_compra'],
                $producto_data['marca'] ?? null
            );
            
            $producto_id = $producto->insert();
            
            if (!$producto_id) {
                return false;
            }
            
            // Crear variante con el stock del pedido
            $variante_data = [
                'nombre_variante' => $producto_data['nombre'],
                'atributos' => $producto_data['atributos'] ?? [],
                'precio_adicional' => 0,
                'stock' => $producto_data['cantidad'],
                'imagen_variante' => $producto_data['imagen'] ?? null,
                'activo' => 1
            ];
            
            $productosModel->addVariante($producto_id, $variante_data);
            
            // Obtener el ID de la variante recién creada
            $variante_id = $this->lastInsertId();
            
            // Vincular el detalle
            return $this->vincularDetalleAProducto($detalle_id, $variante_id);
        }
        
        /**
         * Crear un nuevo producto desde un detalle usando el objeto Productos completo
         */
        public function crearProductoDesdeDetalleObjeto($detalle_id, Productos $producto) {
            $producto_id = $producto->insert();
            if (!$producto_id) {
                return false;
            }
            
            // Obtener la variante creada (usualmente la primera o principal)
            $variantes = $producto->getVariantesByProducto($producto_id);
            $variante_id = !empty($variantes) ? $variantes[0]['id'] : null;
            
            // Vincular el detalle al nuevo producto y a su primera variante
            return $this->vincularDetalleAProducto($detalle_id, $variante_id);
        }
        
        /**
         * Marcar un detalle como ignorado (no se va a inventariar)
         */
        public function ignorarDetalle($detalle_id) {
            $sql = "UPDATE detalles_pedido 
                    SET status_inventario = 'ignorado'
                    WHERE id = :id";
            
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id", $detalle_id);
            return $stmt->execute();
        }
        
        /**
         * Verificar si todos los detalles de un pedido están resueltos
         */
        public function allDetallesResueltos() {
            $sql = "SELECT COUNT(*) as pendientes 
                    FROM detalles_pedido 
                    WHERE id_pedido = :id_pedido 
                    AND status_inventario = 'pendiente'";
            
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":id_pedido", $this->id);
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            return $result['pendientes'] == 0;
        }
        
        // ==================== MÉTODOS ADICIONALES ====================
        
        /**
         * Obtener pedidos por estado
         */
        public function getByEstado($estado) {
            $sql = "SELECT p.*, 
                    c.nombre as cliente_nombre, 
                    c.apellido as cliente_apellido
                    FROM pedidos p
                    LEFT JOIN clientes c ON p.id_cliente = c.id
                    WHERE p.estado = :estado
                    ORDER BY p.fecha_registro DESC";
            
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":estado", $estado);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
        
        /**
         * Contar pedidos por estado
         */
        public function countByEstado() {
            $sql = "SELECT estado, COUNT(*) as total FROM pedidos GROUP BY estado";
            $stmt = $this->prepare($sql);
            $stmt->execute();
            
            $result = [];
            foreach ($stmt->fetchAll(\PDO::FETCH_ASSOC) as $row) {
                $result[$row['estado']] = $row['total'];
            }
            return $result;
        }
        
        /**
         * Buscar pedidos por rango de fechas
         */
        public function getByFechaRange($fecha_inicio, $fecha_fin) {
            $sql = "SELECT p.*, 
                        c.nombre as cliente_nombre, 
                        c.apellido as cliente_apellido
                    FROM pedidos p
                    LEFT JOIN clientes c ON p.id_cliente = c.id
                    WHERE DATE(p.fecha_registro) BETWEEN :fecha_inicio AND :fecha_fin
                    ORDER BY p.fecha_registro DESC";
            
            $stmt = $this->prepare($sql);
            $stmt->bindParam(":fecha_inicio", $fecha_inicio);
            $stmt->bindParam(":fecha_fin", $fecha_fin);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
    }