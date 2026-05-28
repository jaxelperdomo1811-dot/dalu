-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-05-2026 a las 01:45:08
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tiendadalu`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `descripcion`, `fecha_registro`, `activo`) VALUES
(1, 'Carteras', '', '2026-05-22 00:41:09', 1),
(2, 'Ropa', '', '2026-05-22 01:18:57', 1),
(5, 'Cosméticos', '', '2026-05-27 22:13:00', 1),
(6, 'Perfumes', '', '2026-05-27 22:24:23', 1),
(7, 'Bisutería', '', '2026-05-27 22:27:01', 1),
(8, 'Zapatos', '', '2026-05-27 22:27:13', 1),
(9, 'Maquillaje', '', '2026-05-27 23:03:42', 1),
(10, 'Accesorios', '', '2026-05-27 23:33:26', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `correo` varchar(150) NOT NULL,
  `cedula` varchar(150) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombre`, `apellido`, `correo`, `cedula`, `telefono`, `direccion`, `fecha_registro`, `activo`) VALUES
(1, 'rollinera', 'perez', 'asasss@gmail.com', 'V-30218994', '+584260563224', '12123133', '2026-05-28 03:58:21', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_entrada`
--

CREATE TABLE `detalles_entrada` (
  `id` int(11) NOT NULL,
  `id_entrada` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_compra` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_pedido`
--

CREATE TABLE `detalles_pedido` (
  `id` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `tipo` enum('cliente','proveedor') NOT NULL,
  `imagen` varchar(60) DEFAULT NULL,
  `link` text DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` enum('pendiente','recibido','entregado','') DEFAULT 'pendiente',
  `nombre_producto` varchar(150) DEFAULT NULL COMMENT 'Para productos vagos',
  `cantidad` int(11) NOT NULL DEFAULT 1,
  `precio_unitario` decimal(10,2) DEFAULT NULL,
  `descripcion_producto` text DEFAULT NULL,
  `id_variante` int(11) DEFAULT NULL,
  `status_inventario` enum('pendiente','vinculado','creado','ignorado') DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalles_pedido`
--

INSERT INTO `detalles_pedido` (`id`, `id_pedido`, `id_producto`, `tipo`, `imagen`, `link`, `fecha_registro`, `estado`, `nombre_producto`, `cantidad`, `precio_unitario`, `descripcion_producto`, `id_variante`, `status_inventario`) VALUES
(5, 7, NULL, 'proveedor', 'assets/img/products/sin_categoria/test6.jpeg', '', '2026-05-28 22:03:00', 'pendiente', 'test6', 1, NULL, NULL, NULL, 'pendiente'),
(6, 7, NULL, 'proveedor', '', 'https://chat.deepseek.com/', '2026-05-28 22:03:00', 'pendiente', 'test7', 2, NULL, NULL, NULL, 'pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entradas`
--

CREATE TABLE `entradas` (
  `id` int(11) NOT NULL,
  `id_proveedor` int(11) NOT NULL,
  `numero_lote` varchar(50) NOT NULL,
  `fecha_ingreso` date NOT NULL,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metodos_pago`
--

CREATE TABLE `metodos_pago` (
  `id` int(11) NOT NULL,
  `nombre` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `imagen` varchar(30) NOT NULL,
  `activo` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `id_proveedor` int(11) DEFAULT NULL,
  `nombre proveedor` varchar(11) DEFAULT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `tipo` enum('cliente','proveedor','propios','') NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` enum('pendiente','confirmado','enviado','recibido','cancelado','entregado') NOT NULL DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `id_proveedor`, `nombre proveedor`, `id_cliente`, `tipo`, `fecha_registro`, `estado`) VALUES
(1, NULL, 'prueba', NULL, 'propios', '2026-05-28 04:27:15', 'pendiente'),
(2, NULL, NULL, 1, 'cliente', '2026-05-28 04:28:24', 'pendiente'),
(3, NULL, 'prueba2', NULL, 'propios', '2026-05-28 04:37:19', 'pendiente'),
(4, NULL, 'shein', NULL, 'propios', '2026-05-28 21:43:50', 'pendiente'),
(5, NULL, 'test4', NULL, 'propios', '2026-05-28 21:52:07', 'pendiente'),
(6, NULL, 'test5', NULL, 'propios', '2026-05-28 21:57:44', 'pendiente'),
(7, NULL, 'test6', NULL, 'propios', '2026-05-28 22:03:00', 'pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas_seguridad`
--

CREATE TABLE `preguntas_seguridad` (
  `id` int(11) NOT NULL,
  `pregunta` varchar(255) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `preguntas_seguridad`
--

INSERT INTO `preguntas_seguridad` (`id`, `pregunta`, `activo`, `fecha_registro`) VALUES
(1, '¿Cuál es el nombre de tu primera mascota?', 1, '2026-05-25 00:27:52'),
(2, '¿En qué ciudad naciste?', 1, '2026-05-25 00:27:52'),
(3, '¿Cuál fue tu primer colegio?', 1, '2026-05-25 00:27:52'),
(4, '¿Cuál es tu comida favorita?', 1, '2026-05-25 00:27:52'),
(5, '¿Cuál es tu película favorita?', 1, '2026-05-25 00:27:52'),
(6, '¿Cuál es el nombre de tu hijo/a mayor?', 1, '2026-05-25 00:27:52'),
(7, '¿En qué año te graduaste de bachillerato?', 1, '2026-05-25 00:27:52');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio_venta` decimal(10,2) NOT NULL,
  `precio_oferta` decimal(10,2) DEFAULT NULL,
  `stock_total` int(11) NOT NULL DEFAULT 0,
  `stock_minimo` int(11) DEFAULT 3,
  `marca` varchar(50) DEFAULT NULL,
  `imagen_principal` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `ventas_totales` int(11) DEFAULT 0,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `id_categoria`, `nombre`, `descripcion`, `precio_venta`, `precio_oferta`, `stock_total`, `stock_minimo`, `marca`, `imagen_principal`, `activo`, `ventas_totales`, `fecha_registro`) VALUES
(1, 2, 'sueter champions', 'sueter', 12.00, 0.00, 10, 3, NULL, NULL, 1, 0, '2026-05-27 23:18:05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_variantes`
--

CREATE TABLE `producto_variantes` (
  `id` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `nombre_variante` varchar(100) DEFAULT NULL,
  `atributos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'Ej: {"talla":"M","color":"Blanco","volumen_ml":100,"spf":30}' CHECK (json_valid(`atributos`)),
  `precio_adicional` decimal(10,2) DEFAULT 0.00,
  `stock` int(11) NOT NULL DEFAULT 0,
  `imagen_variante` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto_variantes`
--

INSERT INTO `producto_variantes` (`id`, `id_producto`, `nombre_variante`, `atributos`, `precio_adicional`, `stock`, `imagen_variante`, `activo`) VALUES
(1, 1, 'Principal', '{\"talla\":\"s\",\"color\":\"rojo\"}', 0.00, 10, '', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `id` int(11) NOT NULL,
  `razon_social` varchar(20) NOT NULL,
  `documento_identidad` varchar(15) NOT NULL,
  `nombre` varchar(50) NOT NULL DEFAULT '0',
  `apellido` varchar(11) NOT NULL,
  `telefono_1` varchar(25) DEFAULT NULL,
  `telefono_2` varchar(25) DEFAULT NULL,
  `correo` varchar(70) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `direccion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuestas_seguridad`
--

CREATE TABLE `respuestas_seguridad` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_pregunta` int(11) NOT NULL,
  `respuesta_hash` varchar(255) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `ultima_actualizacion` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `respuestas_seguridad`
--

INSERT INTO `respuestas_seguridad` (`id`, `id_usuario`, `id_pregunta`, `respuesta_hash`, `fecha_registro`, `ultima_actualizacion`) VALUES
(1, 5, 1, 'c775e7b757ede630cd0aa1113bd102661ab38829ca52a6422ab782862f268646', '2026-05-25 02:11:17', '2026-05-25 03:01:31'),
(2, 5, 2, 'c775e7b757ede630cd0aa1113bd102661ab38829ca52a6422ab782862f268646', '2026-05-25 02:11:18', '2026-05-25 03:01:35'),
(3, 5, 3, 'c775e7b757ede630cd0aa1113bd102661ab38829ca52a6422ab782862f268646', '2026-05-25 02:11:18', '2026-05-25 03:01:40'),
(4, 6, 2, '46f377df4d986ea0538967997df1a859d4fe75d53b709dbbca1680cc4771be2d', '2026-05-25 21:49:51', NULL),
(5, 6, 1, 'c5ff177a86e82441f93e3772da700d5f6838157fa1bfdc0bb689d7f7e55e7aba', '2026-05-25 21:49:51', NULL),
(6, 6, 3, '7184c0a22999aa4e358786be308b128530d15f842763fc691271a3c4d3743138', '2026-05-25 21:49:51', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`, `descripcion`, `activo`) VALUES
(1, 'admin', '', 1),
(2, 'ventas', '', 1),
(3, 'cajero', '', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `id_rol` int(11) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `estado` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `id_rol`, `nombre`, `usuario`, `clave`, `estado`) VALUES
(1, 1, 'Administrador', 'admin', 'c775e7b757ede630cd0aa1113bd102661ab38829ca52a6422ab782862f268646', 1),
(2, 2, 'Vendedor', 'ventas', 'c775e7b757ede630cd0aa1113bd102661ab38829ca52a6422ab782862f268646', 0),
(3, NULL, 'Rafael', 'kesto', 'c775e7b757ede630cd0aa1113bd102661ab38829ca52a6422ab782862f268646', 1),
(4, 1, 'Userrrrrt', 'kestico2', 'c775e7b757ede630cd0aa1113bd102661ab38829ca52a6422ab782862f268646', 1),
(5, 1, 'rollinera', 'rollinera', 'adba3cd291eab6784a2ff059fdb770a94d821e92fbbe1fe075649cc90d2e0711', 1),
(6, 3, 'PEPE', 'PEPEM', '8a9bcf1e51e812d0af8465a8dbcc9f741064bf0af3b3d08e6b0246437c19f7fb', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD UNIQUE KEY `telefono` (`telefono`);

--
-- Indices de la tabla `detalles_entrada`
--
ALTER TABLE `detalles_entrada`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_entrada` (`id_entrada`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `detalles_pedido`
--
ALTER TABLE `detalles_pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `id_variante` (`id_variante`);

--
-- Indices de la tabla `entradas`
--
ALTER TABLE `entradas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_proveedor` (`id_proveedor`);

--
-- Indices de la tabla `metodos_pago`
--
ALTER TABLE `metodos_pago`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedidos_ibfk2` (`id_proveedor`),
  ADD KEY `pedidos_ibfk3` (`id_cliente`);

--
-- Indices de la tabla `preguntas_seguridad`
--
ALTER TABLE `preguntas_seguridad`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pregunta_unique` (`pregunta`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_categoria` (`id_categoria`),
  ADD KEY `idx_precio` (`precio_venta`);

--
-- Indices de la tabla `producto_variantes`
--
ALTER TABLE `producto_variantes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD UNIQUE KEY `documento_identidad` (`documento_identidad`),
  ADD UNIQUE KEY `telefono_1` (`telefono_1`),
  ADD UNIQUE KEY `telefono_2` (`telefono_2`);

--
-- Indices de la tabla `respuestas_seguridad`
--
ALTER TABLE `respuestas_seguridad`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario_pregunta_unique` (`id_usuario`,`id_pregunta`),
  ADD KEY `id_pregunta` (`id_pregunta`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD KEY `usuarios_ibfk_1` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `detalles_entrada`
--
ALTER TABLE `detalles_entrada`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalles_pedido`
--
ALTER TABLE `detalles_pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `entradas`
--
ALTER TABLE `entradas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `metodos_pago`
--
ALTER TABLE `metodos_pago`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `preguntas_seguridad`
--
ALTER TABLE `preguntas_seguridad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `producto_variantes`
--
ALTER TABLE `producto_variantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `respuestas_seguridad`
--
ALTER TABLE `respuestas_seguridad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalles_entrada`
--
ALTER TABLE `detalles_entrada`
  ADD CONSTRAINT `detalles_entrada_ibfk_1` FOREIGN KEY (`id_entrada`) REFERENCES `entradas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detalles_entrada_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `detalles_pedido`
--
ALTER TABLE `detalles_pedido`
  ADD CONSTRAINT `detalles_pedido_ibfk_variante` FOREIGN KEY (`id_variante`) REFERENCES `producto_variantes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pedidos_ibfk` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `pedidos_ibfk5` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `entradas`
--
ALTER TABLE `entradas`
  ADD CONSTRAINT `entradas_ibfk_1` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk2` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `pedidos_ibfk4` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `producto_variantes`
--
ALTER TABLE `producto_variantes`
  ADD CONSTRAINT `producto_variantes_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `respuestas_seguridad`
--
ALTER TABLE `respuestas_seguridad`
  ADD CONSTRAINT `respuestas_seguridad_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `respuestas_seguridad_ibfk_2` FOREIGN KEY (`id_pregunta`) REFERENCES `preguntas_seguridad` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
