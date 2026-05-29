USE tiendadalu;

CREATE TABLE IF NOT EXISTS despachos (
  id int(11) NOT NULL AUTO_INCREMENT,
  id_cliente int(11) NOT NULL,
  numero_despacho varchar(50) NOT NULL,
  fecha_despacho date NOT NULL,
  total decimal(10,2) NOT NULL DEFAULT 0.00,
  estado enum('pendiente','enviado','entregado','cancelado') NOT NULL DEFAULT 'pendiente',
  fecha_registro timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (id),
  FOREIGN KEY (id_cliente) REFERENCES clientes(id) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS detalles_despachos (
  id int(11) NOT NULL AUTO_INCREMENT,
  id_despacho int(11) NOT NULL,
  id_producto int(11) NOT NULL,
  id_variante int(11) DEFAULT NULL,
  cantidad int(11) NOT NULL,
  precio_unitario decimal(10,2) NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (id_despacho) REFERENCES despachos(id) ON DELETE CASCADE,
  FOREIGN KEY (id_producto) REFERENCES productos(id) ON UPDATE CASCADE,
  FOREIGN KEY (id_variante) REFERENCES producto_variantes(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Agregar fk a pagos si no existe
SET @exist := (SELECT count(*) FROM information_schema.columns WHERE table_schema = 'tiendadalu' AND table_name = 'pagos' AND column_name = 'id_despacho');
SET @sqlstmt := IF(@exist = 0, 'ALTER TABLE pagos ADD COLUMN id_despacho int(11) DEFAULT NULL AFTER id', 'SELECT 1');
PREPARE stmt FROM @sqlstmt;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Eliminar y agregar llave foránea de forma segura (ignora errores si existe)
ALTER TABLE pagos ADD CONSTRAINT fk_pagos_despachos FOREIGN KEY (id_despacho) REFERENCES despachos(id) ON DELETE CASCADE ON UPDATE CASCADE;
