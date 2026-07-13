-- Catálogo de campos disponibles
CREATE TABLE IF NOT EXISTS `campos_variante` (
  `id`          INT(11) NOT NULL AUTO_INCREMENT,
  `nombre`      VARCHAR(50) NOT NULL,      -- clave interna y etiqueta visible
  `tipo`        ENUM('text','number','select','color') NOT NULL DEFAULT 'text',
  `opciones`    JSON DEFAULT NULL,         -- para tipo "select": ["S","M","L","XL"]
  `requerido`   TINYINT(1) DEFAULT 0,
  `activo`      TINYINT(1) DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Relación N:M entre categoría y campos
CREATE TABLE IF NOT EXISTS `categoria_campos` (
  `id`              INT(11) NOT NULL AUTO_INCREMENT,
  `id_categoria`    INT(11) NOT NULL,
  `id_campo`        INT(11) NOT NULL,
  `orden`           INT(11) DEFAULT 0,    -- para mostrar en el orden correcto
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_categoria_campo` (`id_categoria`, `id_campo`),
  FOREIGN KEY (`id_categoria`) REFERENCES `categorias`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`id_campo`) REFERENCES `campos_variante`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertar campos predefinidos
INSERT IGNORE INTO `campos_variante` (`nombre`, `tipo`) VALUES
('talla',      'text'),
('color',      'text'),
('volumen_ml', 'number'),
('fragancia',  'text'),
('spf',        'number'),
('tipo_piel',  'text');

-- Asignar campos a categorías existentes basado en lógica previa
-- Ropa (id=2), Zapatos (id=8): talla, color
INSERT IGNORE INTO `categoria_campos` (`id_categoria`, `id_campo`, `orden`) 
SELECT c.id, v.id, 1 FROM categorias c JOIN campos_variante v ON v.nombre = 'talla' WHERE c.id IN (2, 8);

INSERT IGNORE INTO `categoria_campos` (`id_categoria`, `id_campo`, `orden`) 
SELECT c.id, v.id, 2 FROM categorias c JOIN campos_variante v ON v.nombre = 'color' WHERE c.id IN (2, 8);

-- Perfumes (id=6): volumen_ml, fragancia
INSERT IGNORE INTO `categoria_campos` (`id_categoria`, `id_campo`, `orden`) 
SELECT c.id, v.id, 1 FROM categorias c JOIN campos_variante v ON v.nombre = 'volumen_ml' WHERE c.id = 6;

INSERT IGNORE INTO `categoria_campos` (`id_categoria`, `id_campo`, `orden`) 
SELECT c.id, v.id, 2 FROM categorias c JOIN campos_variante v ON v.nombre = 'fragancia' WHERE c.id = 6;

-- Cosméticos (id=5), Maquillaje (id=9): spf, tipo_piel, volumen_ml, fragancia
INSERT IGNORE INTO `categoria_campos` (`id_categoria`, `id_campo`, `orden`) 
SELECT c.id, v.id, 1 FROM categorias c JOIN campos_variante v ON v.nombre = 'spf' WHERE c.id IN (5, 9);

INSERT IGNORE INTO `categoria_campos` (`id_categoria`, `id_campo`, `orden`) 
SELECT c.id, v.id, 2 FROM categorias c JOIN campos_variante v ON v.nombre = 'tipo_piel' WHERE c.id IN (5, 9);

INSERT IGNORE INTO `categoria_campos` (`id_categoria`, `id_campo`, `orden`) 
SELECT c.id, v.id, 3 FROM categorias c JOIN campos_variante v ON v.nombre = 'volumen_ml' WHERE c.id IN (5, 9);

INSERT IGNORE INTO `categoria_campos` (`id_categoria`, `id_campo`, `orden`) 
SELECT c.id, v.id, 4 FROM categorias c JOIN campos_variante v ON v.nombre = 'fragancia' WHERE c.id IN (5, 9);
