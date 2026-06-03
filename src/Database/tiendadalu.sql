-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-06-2026 a las 18:48:43
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
(6, 'Perfumes', '', '2026-05-27 22:24:23', 0),
(7, 'Bisutería', '', '2026-05-27 22:27:01', 1),
(8, 'Zapatos', '', '2026-05-27 22:27:13', 1),
(9, 'Maquillaje', '', '2026-05-27 23:03:42', 0),
(10, 'Accesorios', '', '2026-05-27 23:33:26', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `correo` varchar(150) DEFAULT NULL,
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
(1, 'rollinera', 'perez', 'asasss@gmail.com', 'V-30218994', '+584260563224', '12123133', '2026-05-28 03:58:21', 0),
(2, 'JENNYMAR COROMOTO', 'PEREZ COLMANARES', 'NIUSF@GMAIL.COM', 'V-16238398', '+584126749294', 'jdjdjdjdd', '2026-05-29 03:33:08', 1),
(3, 'JONATHAN JOSE', 'YEPEZ DIAZ', 'DJDJxxLF@gmail.com', 'V-23834152', '+584165546097', 'JKAFDIHjhvdo7-vSd', '2026-05-29 20:18:23', 1),
(4, 'JOSE GUILLERMO', 'PEREZ SOTO', '', 'V-30218990', '', '', '2026-06-03 03:41:27', 1),
(12, 'MARIA CLAUDIA', 'SILVA ALVAREZ', NULL, 'V-30218956', NULL, NULL, '2026-06-03 04:02:28', 1),
(13, 'Luis', 'Gonzales', NULL, 'V-32218992', NULL, NULL, '2026-06-03 04:10:11', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `despachos`
--

CREATE TABLE `despachos` (
  `id` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `numero_despacho` varchar(50) NOT NULL,
  `fecha_despacho` date NOT NULL,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `estado` enum('pendiente','enviado','entregado','cancelado') NOT NULL DEFAULT 'pendiente',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `despachos`
--

INSERT INTO `despachos` (`id`, `id_cliente`, `numero_despacho`, `fecha_despacho`, `total`, `estado`, `fecha_registro`) VALUES
(1, 1, 'DSP-1780022201', '2026-05-29', 20.00, 'entregado', '2026-05-29 02:37:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_despachos`
--

CREATE TABLE `detalles_despachos` (
  `id` int(11) NOT NULL,
  `id_despacho` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_variante` int(11) DEFAULT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalles_despachos`
--

INSERT INTO `detalles_despachos` (`id`, `id_despacho`, `id_producto`, `id_variante`, `cantidad`, `precio_unitario`) VALUES
(1, 1, 2, NULL, 2, 10.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_entrada`
--

CREATE TABLE `detalles_entrada` (
  `id` int(11) NOT NULL,
  `id_entrada` int(11) NOT NULL,
  `id_variante` int(11) DEFAULT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_compra` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalles_entrada`
--

INSERT INTO `detalles_entrada` (`id`, `id_entrada`, `id_variante`, `cantidad`, `precio_compra`) VALUES
(8, 8, 2, 5, 10.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_pedido`
--

CREATE TABLE `detalles_pedido` (
  `id` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `tipo` enum('cliente','proveedor') NOT NULL,
  `imagen` varchar(60) NOT NULL,
  `link` text NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` enum('pendiente','recibido','entregado','') NOT NULL,
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
(1, 3, NULL, 'proveedor', 'assets/img/products/sin_categoria/franela.png', '', '2026-05-28 23:19:45', 'pendiente', 'franela', 1, NULL, NULL, NULL, 'pendiente'),
(2, 3, NULL, 'proveedor', '', 'facebook.com', '2026-05-28 23:19:45', 'pendiente', 'jean', 1, NULL, NULL, NULL, 'pendiente'),
(3, 4, 2, 'proveedor', 'assets/img/products/sin_categoria/franela.png', '', '2026-05-29 00:00:22', 'pendiente', 'franela', 1, NULL, NULL, NULL, 'vinculado'),
(4, 5, NULL, 'proveedor', 'assets/img/products/sin_categoria/jean.png', '', '2026-05-29 01:04:15', 'pendiente', 'jean', 1, NULL, NULL, NULL, 'ignorado'),
(5, 5, 2, 'proveedor', '', 'https://us.shein.com/Women-s-Vest-Brasil-South-America-Flag-Print-Design-Exquisite-Elegant-And-Fashionable-Exuding-Feminine-Charm-Perfect-For-Holiday-Gifts-Mother-s-Day-Coachella-Music-Festival-Memorial-Day-Suitable-For-Spring-And-Summer-Suitable-For-Casual-Sports-Vacation-Travel-Beach-Wear-And-Daily-Wear-Widely-Versatile-Applicable-To-Various-Occasions-Ladies-Elegant-Suits-Blouses-Summer-Outfits-Vacation-Outfits-Women-Travel-Wear-Tank-Top-p-444142921.html?src_identifier=on%3DONE_THIRD_COMPONENT%60cn%3DONE_THIRD_COMPONENT_2%60hz%3D-%60jc%3DsheinPicks_10751%60ps%3D1_4&src_module=all&src_tab_page_id=page_home1780016622648&mallCode=1&pageListType=4&detailBusinessFrom=0-1_444142921%7C0-2', '2026-05-29 01:04:15', 'pendiente', 'franela brasil', 1, NULL, NULL, NULL, 'vinculado'),
(6, 6, NULL, 'proveedor', '', '', '2026-05-29 03:51:47', 'pendiente', 'perfume sabroso', 1, NULL, NULL, NULL, 'ignorado'),
(7, 6, NULL, 'proveedor', '', 'https://us.shein.com/Women-s-Vest-Brasil-South-America-Flag-Print-Design-Exquisite-Elegant-And-Fashionable-Exuding-Feminine-Charm-Perfect-For-Holiday-Gifts-Mother-s-Day-Coachella-Music-Festival-Memorial-Day-Suitable-For-Spring-And-Summer-Suitable-For-Casual-Sports-Vacation-Travel-Beach-Wear-And-Daily-Wear-Widely-Versatile-Applicable-To-Various-Occasions-Ladies-Elegant-Suits-Blouses-Summer-Outfits-Vacation-Outfits-Women-Travel-Wear-Tank-Top-p-444597816.html?src_identifier=on%3DONE_THIRD_COMPONENT%60cn%3DONE_THIRD_COMPONENT_2%60hz%3D-%60jc%3DsheinPicks_10751%60ps%3D1_4&src_module=all&src_tab_page_id=page_home1780016622648&mallCode=1&pageListType=4&detailBusinessFrom=0-1_444142921%7C0-2&main_attr=27_1000112', '2026-05-29 03:51:47', 'pendiente', NULL, 1, NULL, NULL, NULL, 'ignorado'),
(8, 6, NULL, 'proveedor', 'assets/img/products/sin_categoria/detalle_2.png', '', '2026-05-29 03:51:47', 'pendiente', NULL, 1, NULL, NULL, NULL, 'ignorado'),
(9, 7, 25, 'cliente', '', '', '2026-06-03 03:52:03', 'pendiente', NULL, 1, NULL, NULL, NULL, 'vinculado'),
(10, 7, 2, 'cliente', '', '', '2026-06-03 03:52:03', 'pendiente', NULL, 1, NULL, NULL, NULL, 'vinculado'),
(11, 8, 2, 'cliente', '', '', '2026-06-03 04:10:11', 'pendiente', NULL, 1, NULL, NULL, NULL, 'vinculado');

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

--
-- Volcado de datos para la tabla `entradas`
--

INSERT INTO `entradas` (`id`, `id_proveedor`, `numero_lote`, `fecha_ingreso`, `total`, `fecha_registro`) VALUES
(8, 1, '5', '2026-05-29', 50.00, '2026-05-29 20:02:28');

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
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id` int(11) NOT NULL,
  `id_despacho` int(11) DEFAULT NULL,
  `id_metodo_pago` int(11) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `tasa` decimal(10,2) NOT NULL,
  `comprobante` varchar(500) DEFAULT NULL,
  `referencia` varchar(500) DEFAULT NULL,
  `estado` enum('por verificar','verificado','','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

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
(1, NULL, 'prueba', NULL, 'propios', '2026-05-28 04:27:15', 'entregado'),
(2, NULL, NULL, 1, 'cliente', '2026-05-28 04:28:24', 'cancelado'),
(3, NULL, 'shein', NULL, 'propios', '2026-05-28 23:19:45', 'entregado'),
(4, NULL, 'shein', NULL, 'propios', '2026-05-29 00:00:22', 'recibido'),
(5, NULL, 'amazon', NULL, 'propios', '2026-05-29 01:04:15', 'recibido'),
(6, NULL, 'shein', NULL, 'propios', '2026-05-29 03:51:47', 'recibido'),
(7, NULL, NULL, 4, 'cliente', '2026-06-03 03:52:03', 'pendiente'),
(8, NULL, NULL, 13, 'cliente', '2026-06-03 04:10:11', 'pendiente');

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

INSERT INTO `productos` (`id`, `id_categoria`, `nombre`, `descripcion`, `precio_venta`, `precio_oferta`, `stock_minimo`, `marca`, `imagen_principal`, `activo`, `ventas_totales`, `fecha_registro`) VALUES
(1, 2, 'sueter champions', 'sueter champions', 12.00, NULL, 3, NULL, NULL, 1, 0, '2026-05-27 23:18:05'),
(2, 2, 'Franela', 'franelas', 10.00, NULL, 3, NULL, NULL, 1, 0, '2026-05-28 22:57:39'),
(25, 6, 'perfume sabroso', '', 10.00, NULL, 3, 'factory', NULL, 1, 0, '2026-05-29 03:55:06');

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
(1, 1, 'Principal', '{\"talla\":\"s\",\"color\":\"rojo\"}', 0.00, 10, NULL, 1),
(2, 2, 'Franela roja', '{\"talla\":\"xxl\",\"color\":\"Rojo\"}', 0.00, 6, NULL, 1),
(3, 2, 'franela verde', '{\"talla\":\"s\",\"color\":\"verde\"}', 0.00, 1, NULL, 1),
(4, 1, 'Principal', '{\"talla\":\"l\",\"color\":\"gris\"}', 0.00, 1, NULL, 0),
(27, 25, 'perfume test', '{\"volumen_ml\":\"1\",\"fragancia\":\"prueba\"}', 0.00, 1, 'assets/img/products/perfumes/perfume_sabroso_perfume_test.png', 1),
(36, 25, 'perfume rico', '{\"volumen_ml\":\"12\",\"fragancia\":\"test2\"}', 0.00, 2, NULL, 1),
(37, 25, 'perfume pequeno', '{\"volumen_ml\":\"20\",\"fragancia\":\"hallmen\"}', 0.00, 10, 'assets/img/products/perfumes/perfume_sabroso_perfume_pequeno.png', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `id` int(11) NOT NULL,
  `razon_social` varchar(20) NOT NULL,
  `documento_identidad` varchar(15) NOT NULL,
  `rif` varchar(20) DEFAULT NULL,
  `nombre` varchar(50) NOT NULL DEFAULT '0',
  `apellido` varchar(11) NOT NULL,
  `telefono_1` varchar(25) DEFAULT NULL,
  `telefono_2` varchar(25) DEFAULT NULL,
  `correo` varchar(70) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `direccion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id`, `razon_social`, `documento_identidad`, `rif`, `nombre`, `apellido`, `telefono_1`, `telefono_2`, `correo`, `active`, `direccion`) VALUES
(1, 'shein', 'N/A', NULL, 'shein', '', NULL, NULL, 'sin_correo@ejemplo.com', 1, NULL),
(4, 'amazon', 'N/A', NULL, 'amazon', '', NULL, NULL, 'sin_correo@ejemplo.com', 1, NULL);

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
(6, 6, 3, '7184c0a22999aa4e358786be308b128530d15f842763fc691271a3c4d3743138', '2026-05-25 21:49:51', NULL),
(7, 7, 2, '46f377df4d986ea0538967997df1a859d4fe75d53b709dbbca1680cc4771be2d', '2026-05-29 20:32:27', NULL),
(8, 7, 3, '2d35ad37163d185f219edc78d310620510b7737706c3e70c2efbc33fe6fb9939', '2026-05-29 20:32:27', NULL),
(9, 7, 7, '5140e7d01a8ca8bbd8780de4e3878c5ce5cb0f486cbae1bca3ee7025bcf015bc', '2026-05-29 20:32:27', NULL);

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
-- Estructura de tabla para la tabla `tasa`
--

CREATE TABLE `tasa` (
  `id` int(11) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `fecha_actualizacion` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `tasa`
--

INSERT INTO `tasa` (`id`, `valor`, `fecha_actualizacion`) VALUES
(2, 558.64, '2026-06-03 10:26:20');

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
(4, 1, 'Userrrrrt', 'kestico2', 'c775e7b757ede630cd0aa1113bd102661ab38829ca52a6422ab782862f268646', 0),
(5, 1, 'rollinera', 'rollinera', 'adba3cd291eab6784a2ff059fdb770a94d821e92fbbe1fe075649cc90d2e0711', 1),
(6, 3, 'PEPE', 'PEPEM', 'b221d9dbb083a7f33428d7c2a3c3198ae925614d70210e28716ccaa7cd4ddb79', 1),
(7, 1, 'JONATHAN JOSE', 'jjyd', '21f4d881861425c9c7012b2e5e811248851acb61db3815e4566233d32975771d', 1);

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
-- Indices de la tabla `despachos`
--
ALTER TABLE `despachos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Indices de la tabla `detalles_despachos`
--
ALTER TABLE `detalles_despachos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_despacho` (`id_despacho`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `id_variante` (`id_variante`);

--
-- Indices de la tabla `detalles_entrada`
--
ALTER TABLE `detalles_entrada`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_entrada` (`id_entrada`);

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
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idMetodoPago` (`id_metodo_pago`),
  ADD KEY `fk_pagos_despachos` (`id_despacho`);

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
-- Indices de la tabla `tasa`
--
ALTER TABLE `tasa`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `despachos`
--
ALTER TABLE `despachos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `detalles_despachos`
--
ALTER TABLE `detalles_despachos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `detalles_entrada`
--
ALTER TABLE `detalles_entrada`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `detalles_pedido`
--
ALTER TABLE `detalles_pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `entradas`
--
ALTER TABLE `entradas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `metodos_pago`
--
ALTER TABLE `metodos_pago`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `preguntas_seguridad`
--
ALTER TABLE `preguntas_seguridad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla `producto_variantes`
--
ALTER TABLE `producto_variantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `respuestas_seguridad`
--
ALTER TABLE `respuestas_seguridad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tasa`
--
ALTER TABLE `tasa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `despachos`
--
ALTER TABLE `despachos`
  ADD CONSTRAINT `despachos_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalles_despachos`
--
ALTER TABLE `detalles_despachos`
  ADD CONSTRAINT `detalles_despachos_ibfk_1` FOREIGN KEY (`id_despacho`) REFERENCES `despachos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detalles_despachos_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `detalles_despachos_ibfk_3` FOREIGN KEY (`id_variante`) REFERENCES `producto_variantes` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `detalles_entrada`
--
ALTER TABLE `detalles_entrada`
  ADD CONSTRAINT `detalles_entrada_ibfk_1` FOREIGN KEY (`id_entrada`) REFERENCES `entradas` (`id`) ON DELETE CASCADE;

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
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `fk_pagos_despachos` FOREIGN KEY (`id_despacho`) REFERENCES `despachos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`id_metodo_pago`) REFERENCES `metodos_pago` (`id`);

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
