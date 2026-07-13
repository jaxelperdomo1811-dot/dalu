-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-07-2026 a las 18:16:37
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
-- Estructura de tabla para la tabla `ajustes`
--

CREATE TABLE `ajustes` (
  `id` int(11) NOT NULL,
  `clave` varchar(50) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ajustes`
--

INSERT INTO `ajustes` (`id`, `clave`, `valor`, `descripcion`) VALUES
(1, 'porcentaje_envio', 20.00, 'Porcentaje de recargo por envío (%)'),
(2, 'porcentaje_ganancia', 30.00, 'Porcentaje de margen de ganancia (%)');

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
  `apellido` varchar(100) DEFAULT NULL,
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
(1, 'rollinera', 'perez', 'asasss@gmail.com', 'V-30218994', '+584260563224', '12123133', '2026-05-28 03:58:21', 1),
(2, 'JENNYMAR COROMOTO', 'PEREZ COLMANARES', 'NIUSF@GMAIL.COM', 'V-16238398', '+584126749294', 'jdjdjdjdd', '2026-05-29 03:33:08', 0),
(3, 'JONATHAN JOSE', 'YEPEZ DIAZ', 'DJDJxxLF@gmail.com', 'V-23834152', '+584165546097', 'JKAFDIHjhvdo7-vSd', '2026-05-29 20:18:23', 1),
(4, 'LUIS MANUEL', 'PEREZ ANDRADE', '', 'V-30218990', '', '', '2026-06-03 03:41:27', 1),
(12, 'MARIA CLAUDIA', 'SILVA ALVAREZ', NULL, 'V-30218956', NULL, NULL, '2026-06-03 04:02:28', 1),
(13, 'Luis', 'Gonzales', NULL, 'V-32218992', NULL, NULL, '2026-06-03 04:10:11', 1),
(14, 'Fabrica SA', '', 'NIUaaaSF@GMAIL.COM', 'J-30218990', '+584261289078', 'aqaqaaq1', '2026-06-03 18:00:40', 0),
(15, 'FRANCISMAR PAOLA', 'ARROYO RODRIGUEZ', 'NI11aUSF@GMAIL.COM', 'V-30218957', '+584241289078', 'dwqdwfde2', '2026-06-03 18:02:02', 1),
(16, 'ANDRES', 'GONZALEZ GONZALEZ', NULL, 'V-26276726', NULL, NULL, '2026-06-03 22:31:34', 1),
(17, 'MANUEL RICARDO', 'SANCHEZ TORRES', NULL, 'V-26076726', NULL, NULL, '2026-06-03 22:31:48', 1),
(18, 'JESUS ALEXANDER', 'PERDOMO PERDOMO', 'yowdwq21n25@gmail', 'V-27388616', '+584125242517', 'el molino ', '2026-06-04 15:31:41', 1),
(19, 'Maria', 'Perez', NULL, 'V-33551766', NULL, NULL, '2026-06-04 15:39:28', 1),
(20, 'JESUS GREGORIO', 'PERDOMO ORTIZ', 'aaSF@GMAIL.COM', 'V-12371443', '+584245674001', 'su casa ', '2026-06-04 20:41:04', 1),
(21, 'DANIELLA VALENTINA', 'LUNA DIAZ', 'NIUSjhbuggvyrfvyF@GMAIL.COM', 'V-30377063', '+584223174606', 'gtybkjgvtuooyt', '2026-06-04 21:07:36', 1),
(22, 'LIVORIO ANTONIO', 'MORENO GIL', NULL, 'V-30377064', NULL, NULL, '2026-06-04 21:08:47', 1),
(23, 'HONIS MARIA', 'PEREZ DE SOTO', 'qqdhghh@gmail.com', 'V-12884771', '+573211234567', 'xdawcrwverbvr', '2026-06-04 21:42:13', 1),
(24, 'YESSIMAR MARIA', 'SOTO ANDRADE', 'NIZAXAXSWDUSF@GMAIL.COM', 'V-19571909', '+584220563224', '123434567', '2026-06-05 06:48:16', 1),
(25, 'JAYKEL', 'PEREZ', NULL, 'V-31041911', NULL, NULL, '2026-06-05 12:18:20', 1),
(26, 'jose', 'YEPEZ DIAZ', 'kkjjkhj@gmail.com', 'V-20517658', '+584165589169', 'kkkkkkk', '2026-06-05 14:12:01', 1),
(28, 'Raul', 'YEPEZ ', 'xxkjjkhj@gmail.com', 'V-15424587', '+584125497898', 'yyyyyyyy', '2026-06-05 14:14:40', 1),
(30, 'MARIA NELLY', 'MARQUEZ GUERRA', NULL, 'V-11899011', NULL, NULL, '2026-06-05 18:02:45', 0),
(33, 'VIRGINIA ANTONIA', 'MENDOZA RAMOS', 'yessimarsoto909@gmail.com', 'V-19571908', '+584125244042', 'su casa ', '2026-06-06 22:25:39', 1),
(38, 'CARMEN JACQUELINE', 'COLMENARES SEQUERA', NULL, 'V-13197426', NULL, NULL, '2026-06-10 16:14:57', 1),
(39, 'elbimar', 'alvares', NULL, 'V-31367266', NULL, NULL, '2026-06-10 16:20:01', 1),
(41, 'RAUL', 'SANCHEZ GUILARTE', NULL, 'V-23551758', NULL, NULL, '2026-06-10 23:50:15', 1),
(46, 'josue', 'garcia', 'asdasd@gmail.com', 'V-99999999', '+580424536587', 'el molino 2', '2026-06-12 14:08:57', 1),
(48, 'JOSMARI ANDREINA', 'COLMENARES SEQUERA', NULL, 'V-30218991', NULL, NULL, '2026-06-13 16:42:26', 1),
(49, 'ana', 'yepez', 'sgyuf@gmail.com', 'V-23834156', '+584165546902', 'ngqfucd', '2026-07-06 16:06:53', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `creditos`
--

CREATE TABLE `creditos` (
  `id` int(11) NOT NULL,
  `id_nota_entrega` int(11) NOT NULL,
  `porcentaje_inicial` int(11) NOT NULL,
  `monto_cuota_inicial` decimal(10,2) NOT NULL,
  `nro_cuotas` int(11) NOT NULL,
  `monto_por_cuota` decimal(10,2) NOT NULL,
  `frecuencia` enum('semanal','quincenal','mensual') NOT NULL,
  `estado` enum('pendiente','pagado') NOT NULL DEFAULT 'pendiente',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `creditos`
--

INSERT INTO `creditos` (`id`, `id_nota_entrega`, `porcentaje_inicial`, `monto_cuota_inicial`, `nro_cuotas`, `monto_por_cuota`, `frecuencia`, `estado`, `fecha_registro`) VALUES
(14, 26, 40, 61.68, 1, 92.52, 'semanal', 'pendiente', '2026-07-08 03:47:22'),
(15, 28, 40, 92.52, 1, 138.78, 'semanal', 'pagado', '2026-07-08 13:46:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `creditos_cuotas`
--

CREATE TABLE `creditos_cuotas` (
  `id` int(11) NOT NULL,
  `id_credito` int(11) NOT NULL,
  `tipo_cuota` enum('inicial','regular') NOT NULL,
  `nro_cuota` int(11) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `monto_restante` decimal(10,2) NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `estado` enum('pendiente','pagado','retrasado') NOT NULL DEFAULT 'pendiente',
  `fecha_pago` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `creditos_cuotas`
--

INSERT INTO `creditos_cuotas` (`id`, `id_credito`, `tipo_cuota`, `nro_cuota`, `monto`, `monto_restante`, `fecha_vencimiento`, `estado`, `fecha_pago`) VALUES
(45, 14, 'inicial', 0, 61.68, 0.00, '2026-07-08', 'pagado', '2026-07-08 05:47:22'),
(46, 14, 'regular', 1, 92.52, 0.00, '2026-07-15', 'pendiente', NULL),
(47, 15, 'inicial', 0, 92.52, 0.00, '2026-07-08', 'pagado', '2026-07-08 15:46:26'),
(48, 15, 'regular', 1, 138.78, 0.00, '2026-07-15', 'pagado', '2026-07-08 15:47:42');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `despachos`
--

CREATE TABLE `despachos` (
  `id` int(11) NOT NULL,
  `id_nota_entrega` int(11) NOT NULL,
  `numero_despacho` varchar(50) NOT NULL,
  `fecha_despacho` date NOT NULL,
  `estado` enum('pendiente','enviado','entregado','cancelado') NOT NULL DEFAULT 'pendiente',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `despachos`
--

INSERT INTO `despachos` (`id`, `id_nota_entrega`, `numero_despacho`, `fecha_despacho`, `estado`, `fecha_registro`) VALUES
(1, 1, 'DSP-1780022201', '2026-05-29', 'entregado', '2026-05-29 02:37:19'),
(3, 9, 'DSP-1780661915', '2026-06-05', 'entregado', '2026-06-05 12:18:35'),
(4, 19, 'DSP-1783451229', '2026-07-07', 'enviado', '2026-07-07 19:07:09'),
(5, 27, 'DSP-1783512773', '2026-07-08', 'enviado', '2026-07-08 12:12:53');

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
(8, 8, 2, 5, 10.00),
(9, 14, 2, 5, 5.00),
(10, 16, 3, 15, 20.00),
(11, 17, 1, 5, 14.50),
(12, 19, 38, 14, 5.60),
(13, 21, 1, 40, 10.00),
(14, 22, 46, 3, 10.00),
(15, 23, 40, 20, 200.00),
(16, 26, 47, 10, 5.00),
(17, 28, 50, 20, 80.00),
(18, 32, 54, 10, 20.00),
(19, 38, 46, 40, 7.00),
(20, 40, 55, 10, 40.00),
(21, 40, 56, 10, 40.00),
(22, 41, 57, 10, 50.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_pedido`
--

CREATE TABLE `detalles_pedido` (
  `id` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_variante` int(11) DEFAULT NULL,
  `tipo` enum('cliente','proveedor') NOT NULL,
  `imagen` varchar(60) NOT NULL,
  `link` text NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` enum('pendiente','recibido','entregado','') NOT NULL,
  `nombre_producto` varchar(150) DEFAULT NULL COMMENT 'Para productos vagos',
  `cantidad` int(11) NOT NULL DEFAULT 1,
  `precio_unitario` decimal(10,2) DEFAULT NULL,
  `descripcion_producto` text DEFAULT NULL,
  `status_inventario` enum('pendiente','vinculado','creado','ignorado') DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalles_pedido`
--

INSERT INTO `detalles_pedido` (`id`, `id_pedido`, `id_variante`, `tipo`, `imagen`, `link`, `fecha_registro`, `estado`, `nombre_producto`, `cantidad`, `precio_unitario`, `descripcion_producto`, `status_inventario`) VALUES
(39, 31, 56, 'proveedor', '', '', '2026-07-08 03:54:02', 'pendiente', NULL, 100, 20.00, NULL, 'vinculado');

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
(8, 1, '5', '2026-05-29', 50.00, '2026-05-29 20:02:28'),
(14, 1, '56', '2026-06-05', 25.00, '2026-06-04 23:23:34'),
(16, 4, 'e23re23rt4', '2026-06-05', 300.00, '2026-06-05 06:52:43'),
(17, 4, '56', '2026-06-05', 72.50, '2026-06-05 12:10:00'),
(19, 1, '12', '2026-06-05', 78.40, '2026-06-05 13:15:16'),
(21, 1, '14', '2026-06-08', 400.00, '2026-06-08 14:04:02'),
(22, 1, '55', '2026-06-10', 30.00, '2026-06-10 16:11:43'),
(23, 5, 'dsp ', '2026-06-11', 4000.00, '2026-06-11 18:14:37'),
(26, 4, '10', '2029-07-12', 50.00, '2026-06-12 14:23:11'),
(28, 4, '53523', '2026-06-15', 1600.00, '2026-06-15 12:00:13'),
(32, 4, '5555', '2026-07-06', 200.00, '2026-07-06 18:14:42'),
(38, 5, '5465', '2026-07-07', 280.00, '2026-07-07 16:49:17'),
(40, 6, '9505', '2026-07-08', 800.00, '2026-07-08 00:37:19'),
(41, 6, '3434', '2026-07-08', 500.00, '2026-07-08 00:44:09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metodos_pago`
--

CREATE TABLE `metodos_pago` (
  `id` int(11) NOT NULL,
  `nombre` varchar(15) NOT NULL,
  `descripcion` text NOT NULL,
  `imagen` varchar(30) NOT NULL,
  `activo` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `metodos_pago`
--

INSERT INTO `metodos_pago` (`id`, `nombre`, `descripcion`, `imagen`, `activo`) VALUES
(6, 'Pago Móvil', 'Pago Móvil (Bs)', '', 1),
(7, 'Efectivo Divisa', 'Efectivo en Dólares', '', 1),
(8, 'Efectivo Bs', 'Efectivo en Bolívares', '', 1),
(9, 'Zelle', 'Pago vía Zelle', '', 1),
(10, 'Binance', 'Pago vía Binance Pay', '', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notas_entrega`
--

CREATE TABLE `notas_entrega` (
  `id` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `fecha_pedido` datetime NOT NULL,
  `estado` enum('pendiente','confirmado','enviado','recibido','entregado','cancelado') NOT NULL DEFAULT 'pendiente',
  `tipo` enum('debito','credito') NOT NULL DEFAULT 'debito',
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `observaciones` text DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `notas_entrega`
--

INSERT INTO `notas_entrega` (`id`, `id_cliente`, `fecha_pedido`, `estado`, `tipo`, `total`, `observaciones`, `fecha_registro`) VALUES
(26, 2, '2026-07-08 05:47:22', 'entregado', 'credito', 154.20, '', '2026-07-08 03:47:22'),
(27, 2, '2026-07-08 14:12:37', 'cancelado', 'debito', 77.10, '', '2026-07-08 12:12:37'),
(28, 18, '2026-07-08 15:46:26', 'confirmado', 'credito', 231.30, '', '2026-07-08 13:46:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notas_entrega_detalles`
--

CREATE TABLE `notas_entrega_detalles` (
  `id` int(11) NOT NULL,
  `id_nota_entrega` int(11) NOT NULL,
  `id_variante` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL DEFAULT 0.00,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `notas_entrega_detalles`
--

INSERT INTO `notas_entrega_detalles` (`id`, `id_nota_entrega`, `id_variante`, `cantidad`, `precio_unitario`, `descripcion`) VALUES
(21, 26, 56, 2, 77.10, ''),
(22, 27, 56, 1, 77.10, ''),
(23, 28, 55, 3, 77.10, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id` int(11) NOT NULL,
  `id_nota_entrega` int(11) NOT NULL,
  `id_metodo_pago` int(11) NOT NULL,
  `monto_bs` decimal(10,2) NOT NULL,
  `monto_usd` decimal(10,2) DEFAULT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `tasa` decimal(10,2) NOT NULL,
  `comprobante` varchar(500) DEFAULT NULL,
  `referencia` varchar(500) DEFAULT NULL,
  `estado` enum('por verificar','verificado','rechazado','') NOT NULL DEFAULT 'por verificar'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`id`, `id_nota_entrega`, `id_metodo_pago`, `monto_bs`, `monto_usd`, `fecha`, `tasa`, `comprobante`, `referencia`, `estado`) VALUES
(139, 26, 7, 41629.68, 61.68, '2026-07-07 23:49:18', 674.93, NULL, '', 'verificado'),
(140, 27, 7, 52037.10, 77.10, '2026-07-08 09:49:19', 674.93, NULL, '', 'verificado'),
(141, 26, 8, 92.52, 0.14, '2026-07-08 12:08:47', 674.93, NULL, 'N/A', 'rechazado'),
(142, 26, 8, 62350.03, 92.38, '2026-07-08 12:08:48', 674.93, NULL, 'N/A', 'rechazado'),
(143, 28, 7, 62444.52, 92.52, '2026-07-08 09:50:03', 674.93, NULL, '', 'verificado'),
(144, 28, 8, 93666.79, 138.78, '2026-07-08 09:50:02', 674.93, NULL, 'N/A', 'verificado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `id_proveedor` int(11) DEFAULT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `id_nota_entrega` int(11) DEFAULT NULL,
  `tipo` enum('cliente','proveedor','propios','') NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_estimada` date DEFAULT NULL,
  `fecha_recepcion` datetime DEFAULT NULL,
  `fecha_entrega` datetime DEFAULT NULL,
  `estado` enum('pendiente','confirmado','enviado','recibido','cancelado','entregado') NOT NULL DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `id_proveedor`, `id_cliente`, `id_nota_entrega`, `tipo`, `fecha_registro`, `fecha_estimada`, `fecha_recepcion`, `fecha_entrega`, `estado`) VALUES
(31, 6, NULL, NULL, 'propios', '2026-07-08 03:54:02', '2026-07-31', '2026-07-07 23:54:13', NULL, 'recibido');

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
  `precio_compra` decimal(10,2) NOT NULL DEFAULT 0.00,
  `precio_venta` decimal(10,2) NOT NULL,
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

INSERT INTO `productos` (`id`, `id_categoria`, `nombre`, `descripcion`, `precio_compra`, `precio_venta`, `stock_minimo`, `marca`, `imagen_principal`, `activo`, `ventas_totales`, `fecha_registro`) VALUES
(1, 2, 'sueter champions', 'sueter champions', 10.00, 20.62, 3, NULL, NULL, 1, 0, '2026-05-27 23:18:05'),
(2, 2, 'Franela', 'franelas', 20.00, 38.55, 3, NULL, NULL, 1, 0, '2026-05-28 22:57:39'),
(25, 6, 'perfume sabroso', '', 0.00, 10.00, 3, 'factory', NULL, 1, 0, '2026-05-29 03:55:06'),
(34, 6, 'perfume delicioso', 'fragancia diaria', 5.60, 11.55, 3, NULL, NULL, 1, 0, '2026-06-05 13:13:05'),
(35, 8, 'Zapatos', 'R45-18', 200.00, 140.80, 3, NULL, NULL, 1, 0, '2026-06-05 13:30:56'),
(39, 2, 'sueter gucci', 'tono calido', 0.00, 2.06, 3, NULL, NULL, 1, 0, '2026-06-07 04:01:17'),
(42, 2, 'franela cara', 'franela diaria', 0.00, 10.31, 3, NULL, NULL, 0, 0, '2026-06-08 22:03:17'),
(43, 2, 'pantalon', 'pantalon bota ancha', 7.00, 13.49, 15, NULL, NULL, 1, 0, '2026-06-10 15:59:01'),
(45, 6, 'Tomy', 'fragancia diaria', 80.00, 160.92, 3, NULL, NULL, 1, 0, '2026-06-12 14:14:36'),
(46, 6, 'Dior', 'fragancia diaria', 0.00, 20.11, 3, NULL, NULL, 1, 0, '2026-06-12 14:16:33'),
(47, 6, 'Dior one', 'fragancia diaria', 5.00, 10.06, 3, NULL, NULL, 1, 0, '2026-06-12 14:19:47'),
(48, 2, 'camisa overside', 'camisa ancha tipo oversid', 0.00, 28.91, 3, NULL, NULL, 1, 0, '2026-07-06 16:54:44'),
(49, 10, 'lentes', 'lentes clasicos', 20.00, 38.55, 3, NULL, NULL, 1, 0, '2026-07-06 17:27:25'),
(51, 8, 'asics', 'zapatos de voley', 50.00, 77.10, 7, NULL, NULL, 1, 0, '2026-07-08 00:17:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_variantes`
--

CREATE TABLE `producto_variantes` (
  `id` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `codigo_producto` varchar(15) DEFAULT NULL,
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

INSERT INTO `producto_variantes` (`id`, `id_producto`, `codigo_producto`, `nombre_variante`, `atributos`, `precio_adicional`, `stock`, `imagen_variante`, `activo`) VALUES
(1, 1, NULL, 'Principal', '{\"talla\":\"s\",\"color\":\"rojo\"}', 0.00, 40, NULL, 1),
(2, 2, NULL, 'Franela roja', '{\"talla\":\"xxl\",\"color\":\"Rojo\"}', 0.00, 7, NULL, 1),
(3, 2, NULL, 'franela verde', '{\"talla\":\"s\",\"color\":\"verde\"}', 0.00, 15, NULL, 1),
(4, 1, NULL, 'Principal', '{\"talla\":\"l\",\"color\":\"gris\"}', 0.00, 1, NULL, 0),
(27, 25, NULL, 'perfume test2', '{\"volumen_ml\":\"1\",\"fragancia\":\"prueba\"}', 0.00, 2, 'assets/img/products/perfumes/perfume_sabroso_perfume_test.png', 1),
(36, 25, NULL, 'perfume rico', '{\"volumen_ml\":\"12\",\"fragancia\":\"test2\"}', 0.00, 1, NULL, 1),
(37, 25, NULL, 'perfume pequeno', '{\"volumen_ml\":\"25\",\"fragancia\":\"hallmen\"}', 0.00, 7, 'assets/img/products/perfumes/perfume_sabroso_perfume_pequeno.png', 1),
(38, 34, NULL, 'perfume test', '{\"volumen_ml\":\"150\",\"fragancia\":\"citrica\"}', 1.36, 25, 'assets/img/products/perfumes/perfume_sabroso_perfume_test.jpg', 1),
(39, 34, NULL, 'perfume rico', '{\"volumen_ml\":\"150\",\"fragancia\":\"dulce\"}', 1.20, 7, 'assets/img/products/perfumes/perfume_sabroso_perfume_rico.jpg', 1),
(40, 35, NULL, 'Zapatos', '{}', 0.00, 20, NULL, 1),
(44, 39, NULL, 'sueter gucci', '{}', 0.00, 1, NULL, 1),
(45, 42, NULL, 'Principal', '{\"talla\":\"l\",\"color\":\"BLANCA\"}', 0.00, 2, NULL, 0),
(46, 43, NULL, 'pantalon bota ancha', '{\"talla\":\"34\",\"color\":\"azul\"}', 5.00, 59, NULL, 1),
(47, 47, NULL, 'Principal', '{\"volumen_ml\":\"5\",\"fragancia\":\"jasmin\"}', 0.00, 19, NULL, 1),
(48, 47, NULL, 'dior 500ml', '{\"volumen_ml\":\"500\"}', 0.00, 0, NULL, 1),
(49, 47, NULL, 'dior 200ml', '{\"volumen_ml\":\"200\"}', 0.00, 0, NULL, 1),
(50, 45, NULL, 'tommy12 ', '{\"volumen_ml\":\"100\"}', 0.00, 28, NULL, 1),
(51, 2, NULL, 'franela azul', '{\"talla\":\"xs\",\"color\":\"azul\"}', 0.00, 8, NULL, 1),
(52, 48, '455', 'beige', '{\"talla\":\"L\",\"color\":\"negro\"}', 0.00, 4, NULL, 1),
(53, 48, '955', 'overside roja', '{\"talla\":\"XL\",\"color\":\"roja\"}', 0.00, 6, NULL, 1),
(54, 49, NULL, 'lentes', '{}', 0.00, 11, NULL, 1),
(55, 51, '276', 'Sky', '{\"talla\":\"42\",\"color\":\"blaca\"}', 0.00, 17, NULL, 1),
(56, 51, NULL, 'metarice', '{\"talla\":\"38\",\"color\":\"verde\"}', 0.00, 13, NULL, 1),
(57, 51, NULL, 'tokyo', '{\"talla\":\"43\",\"color\":\"rojo\"}', 0.00, 10, NULL, 1);

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
  `correo` varchar(70) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `direccion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id`, `razon_social`, `documento_identidad`, `rif`, `nombre`, `apellido`, `telefono_1`, `telefono_2`, `correo`, `active`, `direccion`) VALUES
(1, 'shein', 'N/A', NULL, 'shein', '', NULL, NULL, NULL, 1, NULL),
(4, 'amazon', 'N/A', NULL, 'amazon', '', NULL, NULL, NULL, 1, NULL),
(5, 'Champions', 'V-27120332', '2161325', 'Pepe', 'Perez', '+584225583005', NULL, 'pepe1112@gmail.com', 1, 's;kndjksbdks'),
(6, 'alibaba', 'V-30218992', 'V302189923', 'ROSA LINDA', 'GIMENEZ MEN', '+584247669087', NULL, 'rosalinda@gmail.com', 1, 'villa concepcion');

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
  `nombre` varchar(50) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `fecha_actualizacion` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `tasa`
--

INSERT INTO `tasa` (`id`, `nombre`, `valor`, `fecha_actualizacion`) VALUES
(2, 'BCV', 674.93, '2026-07-07 20:55:07'),
(3, 'Zelle', 750.45, '2026-06-20 12:16:06');

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
(7, 1, 'JONATHAN JOSE', 'jjyd', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ajustes`
--
ALTER TABLE `ajustes`
  ADD PRIMARY KEY (`id`);

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
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD UNIQUE KEY `telefono` (`telefono`);

--
-- Indices de la tabla `creditos`
--
ALTER TABLE `creditos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_nota_entrega` (`id_nota_entrega`);

--
-- Indices de la tabla `creditos_cuotas`
--
ALTER TABLE `creditos_cuotas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_credito` (`id_credito`);

--
-- Indices de la tabla `despachos`
--
ALTER TABLE `despachos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_nota_entrega` (`id_nota_entrega`);

--
-- Indices de la tabla `detalles_entrada`
--
ALTER TABLE `detalles_entrada`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_entrada` (`id_entrada`),
  ADD KEY `detalles_entrada_ibfk_variante` (`id_variante`);

--
-- Indices de la tabla `detalles_pedido`
--
ALTER TABLE `detalles_pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pedido` (`id_pedido`),
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
-- Indices de la tabla `notas_entrega`
--
ALTER TABLE `notas_entrega`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Indices de la tabla `notas_entrega_detalles`
--
ALTER TABLE `notas_entrega_detalles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_nota_entrega` (`id_nota_entrega`),
  ADD KEY `id_variante` (`id_variante`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idMetodoPago` (`id_metodo_pago`),
  ADD KEY `id_nota_entrega` (`id_nota_entrega`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedidos_ibfk2` (`id_proveedor`),
  ADD KEY `pedidos_ibfk3` (`id_cliente`),
  ADD KEY `pedidos_ibfk_nota_entrega` (`id_nota_entrega`);

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
  ADD UNIQUE KEY `nombre` (`nombre`),
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
-- AUTO_INCREMENT de la tabla `ajustes`
--
ALTER TABLE `ajustes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT de la tabla `creditos`
--
ALTER TABLE `creditos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `creditos_cuotas`
--
ALTER TABLE `creditos_cuotas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT de la tabla `despachos`
--
ALTER TABLE `despachos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `detalles_entrada`
--
ALTER TABLE `detalles_entrada`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `detalles_pedido`
--
ALTER TABLE `detalles_pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de la tabla `entradas`
--
ALTER TABLE `entradas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT de la tabla `metodos_pago`
--
ALTER TABLE `metodos_pago`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `notas_entrega`
--
ALTER TABLE `notas_entrega`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `notas_entrega_detalles`
--
ALTER TABLE `notas_entrega_detalles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=145;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `preguntas_seguridad`
--
ALTER TABLE `preguntas_seguridad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT de la tabla `producto_variantes`
--
ALTER TABLE `producto_variantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `creditos`
--
ALTER TABLE `creditos`
  ADD CONSTRAINT `creditos_ibfk_1` FOREIGN KEY (`id_nota_entrega`) REFERENCES `notas_entrega` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `creditos_cuotas`
--
ALTER TABLE `creditos_cuotas`
  ADD CONSTRAINT `creditos_cuotas_ibfk_1` FOREIGN KEY (`id_credito`) REFERENCES `creditos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `despachos`
--
ALTER TABLE `despachos`
  ADD CONSTRAINT `despachos_ibfk_1` FOREIGN KEY (`id_nota_entrega`) REFERENCES `notas_entrega` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalles_entrada`
--
ALTER TABLE `detalles_entrada`
  ADD CONSTRAINT `detalles_entrada_ibfk_1` FOREIGN KEY (`id_entrada`) REFERENCES `entradas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detalles_entrada_ibfk_variante` FOREIGN KEY (`id_variante`) REFERENCES `producto_variantes` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalles_pedido`
--
ALTER TABLE `detalles_pedido`
  ADD CONSTRAINT `detalles_pedido_ibfk_variante` FOREIGN KEY (`id_variante`) REFERENCES `producto_variantes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pedidos_ibfk` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `entradas`
--
ALTER TABLE `entradas`
  ADD CONSTRAINT `entradas_ibfk_1` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `notas_entrega`
--
ALTER TABLE `notas_entrega`
  ADD CONSTRAINT `notas_entrega_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id`);

--
-- Filtros para la tabla `notas_entrega_detalles`
--
ALTER TABLE `notas_entrega_detalles`
  ADD CONSTRAINT `notas_entrega_detalles_ibfk_1` FOREIGN KEY (`id_nota_entrega`) REFERENCES `notas_entrega` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notas_entrega_detalles_ibfk_2` FOREIGN KEY (`id_variante`) REFERENCES `producto_variantes` (`id`);

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `fk_pagos_notas_entrega` FOREIGN KEY (`id_nota_entrega`) REFERENCES `notas_entrega` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`id_metodo_pago`) REFERENCES `metodos_pago` (`id`);

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk2` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `pedidos_ibfk4` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `pedidos_ibfk_nota_entrega` FOREIGN KEY (`id_nota_entrega`) REFERENCES `notas_entrega` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

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
