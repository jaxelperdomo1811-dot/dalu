-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-06-2026 a las 01:52:15
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
  `palabra_secreta` varchar(256) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombre`, `apellido`, `correo`, `cedula`, `telefono`, `direccion`, `palabra_secreta`, `fecha_registro`, `activo`) VALUES
(1, 'rollinera', 'perez', 'asasss@gmail.com', 'V-30218994', '+584260563224', '12123133', NULL, '2026-05-28 03:58:21', 1),
(2, 'JENNYMAR COROMOTO', 'PEREZ COLMANARES', 'NIUSF@GMAIL.COM', 'V-16238398', '+584126749294', 'jdjdjdjdd', NULL, '2026-05-29 03:33:08', 1),
(3, 'JONATHAN JOSE', 'YEPEZ DIAZ', 'DJDJxxLF@gmail.com', 'V-23834152', '+584165546097', 'JKAFDIHjhvdo7-vSd', NULL, '2026-05-29 20:18:23', 1),
(4, 'LUIS MANUEL', 'PEREZ ANDRADE', '', 'V-30218990', '', '', NULL, '2026-06-03 03:41:27', 1),
(12, 'MARIA CLAUDIA', 'SILVA ALVAREZ', NULL, 'V-30218956', NULL, NULL, NULL, '2026-06-03 04:02:28', 1),
(13, 'Luis', 'Gonzales', NULL, 'V-32218992', NULL, NULL, NULL, '2026-06-03 04:10:11', 1),
(14, 'Fabrica SA', '', 'NIUaaaSF@GMAIL.COM', 'J-30218990', '+584261289078', 'aqaqaaq1', NULL, '2026-06-03 18:00:40', 1),
(15, 'FRANCISMAR PAOLA', 'ARROYO RODRIGUEZ', 'NI11aUSF@GMAIL.COM', 'V-30218957', '+584241289078', 'dwqdwfde2', NULL, '2026-06-03 18:02:02', 1),
(16, 'ANDRES', 'GONZALEZ GONZALEZ', NULL, 'V-26276726', NULL, NULL, NULL, '2026-06-03 22:31:34', 1),
(17, 'MANUEL RICARDO', 'SANCHEZ TORRES', NULL, 'V-26076726', NULL, NULL, NULL, '2026-06-03 22:31:48', 1),
(18, 'JESUS ALEXANDER', 'PERDOMO PERDOMO', 'yowdwq21n25@gmail', 'V-27388616', '+584125242517', 'el molino ', 'manguangua', '2026-06-04 15:31:41', 1),
(19, 'Maria', 'Perez', NULL, 'V-33551766', NULL, NULL, NULL, '2026-06-04 15:39:28', 1),
(20, 'JESUS GREGORIO', 'PERDOMO ORTIZ', 'aaSF@GMAIL.COM', 'V-12371443', '+584245674001', 'su casa ', NULL, '2026-06-04 20:41:04', 1),
(21, 'DANIELLA VALENTINA', 'LUNA DIAZ', 'NIUSjhbuggvyrfvyF@GMAIL.COM', 'V-30377063', '+584223174606', 'gtybkjgvtuooyt', NULL, '2026-06-04 21:07:36', 1),
(22, 'LIVORIO ANTONIO', 'MORENO GIL', NULL, 'V-30377064', NULL, NULL, NULL, '2026-06-04 21:08:47', 1),
(23, 'HONIS MARIA', 'PEREZ DE SOTO', 'qqdhghh@gmail.com', 'V-12884771', '+573211234567', 'xdawcrwverbvr', NULL, '2026-06-04 21:42:13', 1),
(24, 'YESSIMAR MARIA', 'SOTO ANDRADE', 'NIZAXAXSWDUSF@GMAIL.COM', 'V-19571909', '+584220563224', '123434567', NULL, '2026-06-05 06:48:16', 1),
(25, 'JAYKEL', 'PEREZ', NULL, 'V-31041911', NULL, NULL, NULL, '2026-06-05 12:18:20', 1),
(26, 'jose', 'YEPEZ DIAZ', 'kkjjkhj@gmail.com', 'V-20517658', '+584165589169', 'kkkkkkk', NULL, '2026-06-05 14:12:01', 1),
(28, 'Raul', 'YEPEZ ', 'xxkjjkhj@gmail.com', 'V-15424587', '+584125497898', 'yyyyyyyy', NULL, '2026-06-05 14:14:40', 1),
(30, 'MARIA NELLY', 'MARQUEZ GUERRA', NULL, 'V-11899011', NULL, NULL, NULL, '2026-06-05 18:02:45', 0),
(33, 'VIRGINIA ANTONIA', 'MENDOZA RAMOS', 'yessimarsoto909@gmail.com', 'V-19571908', '+584125244042', 'su casa ', 'arremangala empujala', '2026-06-06 22:25:39', 1),
(38, 'CARMEN JACQUELINE', 'COLMENARES SEQUERA', NULL, 'V-13197426', NULL, NULL, NULL, '2026-06-10 16:14:57', 1),
(39, 'elbimar', 'alvares', NULL, 'V-31367266', NULL, NULL, NULL, '2026-06-10 16:20:01', 1),
(41, 'RAUL', 'SANCHEZ GUILARTE', NULL, 'V-23551758', NULL, NULL, NULL, '2026-06-10 23:50:15', 1),
(46, 'josue', 'garcia', 'asdasd@gmail.com', 'V-99999999', '+580424536587', 'el molino 2', 'nuevo', '2026-06-12 14:08:57', 1),
(48, 'JOSMARI ANDREINA', 'COLMENARES SEQUERA', NULL, 'V-30218991', NULL, NULL, NULL, '2026-06-13 16:42:26', 1);

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
(1, 1, 40, 9.60, 4, 3.60, 'semanal', 'pendiente', '2026-06-03 22:32:14'),
(2, 1, 60, 7.20, 2, 2.40, 'semanal', 'pendiente', '2026-06-04 02:21:50'),
(3, 1, 60, 12.00, 5, 1.60, 'semanal', 'pendiente', '2026-06-04 15:39:28'),
(4, 7, 40, 4.00, 4, 1.50, 'semanal', 'pagado', '2026-06-05 00:00:05'),
(5, 13, 40, 0.82, 2, 0.62, 'semanal', 'pagado', '2026-06-07 04:18:52'),
(6, 14, 40, 20.50, 2, 15.37, 'semanal', 'pendiente', '2026-06-10 16:20:01'),
(7, 15, 40, 25.82, 4, 9.68, 'semanal', 'pendiente', '2026-06-10 23:54:30'),
(8, 18, 40, 40.99, 1, 61.49, 'semanal', 'pendiente', '2026-06-12 00:44:05');

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
(1, 1, 'inicial', 0, 9.60, 9.60, '2026-06-04', 'pendiente', NULL),
(2, 1, 'regular', 1, 3.60, 3.60, '2026-06-11', 'pendiente', NULL),
(3, 1, 'regular', 2, 3.60, 3.60, '2026-06-18', 'pendiente', NULL),
(4, 1, 'regular', 3, 3.60, 3.60, '2026-06-25', 'pendiente', NULL),
(5, 1, 'regular', 4, 3.60, 3.60, '2026-07-02', 'pendiente', NULL),
(6, 2, 'inicial', 0, 7.20, 0.00, '2026-06-04', 'pagado', '2026-06-04 04:21:51'),
(7, 2, 'regular', 1, 2.40, 2.40, '2026-06-11', 'pendiente', NULL),
(8, 2, 'regular', 2, 2.40, 2.40, '2026-06-18', 'pendiente', NULL),
(9, 3, 'inicial', 0, 12.00, 0.00, '2026-06-04', 'pagado', '2026-06-04 17:39:28'),
(10, 3, 'regular', 1, 1.60, 1.60, '2026-06-11', 'pendiente', NULL),
(11, 3, 'regular', 2, 1.60, 1.60, '2026-06-18', 'pendiente', NULL),
(12, 3, 'regular', 3, 1.60, 1.60, '2026-06-25', 'pendiente', NULL),
(13, 3, 'regular', 4, 1.60, 1.60, '2026-07-02', 'pendiente', NULL),
(14, 3, 'regular', 5, 1.60, 1.60, '2026-07-09', 'pendiente', NULL),
(15, 4, 'inicial', 0, 4.00, 0.00, '2026-06-05', 'pagado', '2026-06-05 02:00:05'),
(16, 4, 'regular', 1, 1.50, 0.00, '2026-06-12', 'pagado', '2026-06-05 02:00:52'),
(17, 4, 'regular', 2, 1.50, 0.00, '2026-06-19', 'pagado', '2026-06-05 02:02:09'),
(18, 4, 'regular', 3, 1.50, 0.00, '2026-06-26', 'pagado', '2026-06-05 02:02:09'),
(19, 4, 'regular', 4, 1.50, 0.00, '2026-07-03', 'pagado', '2026-06-05 02:02:42'),
(20, 5, 'inicial', 0, 0.82, 0.00, '2026-06-07', 'pagado', '2026-06-07 06:18:52'),
(21, 5, 'regular', 1, 0.62, 0.00, '2026-06-14', 'pagado', '2026-06-07 06:20:02'),
(22, 5, 'regular', 2, 0.62, 0.00, '2026-06-21', 'pagado', '2026-06-07 06:20:48'),
(23, 6, 'inicial', 0, 20.50, 0.00, '2026-06-10', 'pagado', '2026-06-10 18:20:01'),
(24, 6, 'regular', 1, 15.37, 0.00, '2026-06-17', 'pagado', '2026-06-10 18:21:15'),
(25, 6, 'regular', 2, 15.37, 10.74, '2026-06-24', 'pendiente', NULL),
(26, 7, 'inicial', 0, 25.82, 0.00, '2026-06-11', 'pendiente', NULL),
(27, 7, 'regular', 1, 9.68, 9.68, '2026-06-18', 'pendiente', NULL),
(28, 7, 'regular', 2, 9.68, 9.68, '2026-06-25', 'pendiente', NULL),
(29, 7, 'regular', 3, 9.68, 9.68, '2026-07-02', 'pendiente', NULL),
(30, 7, 'regular', 4, 9.68, 9.68, '2026-07-09', 'pendiente', NULL),
(31, 8, 'inicial', 0, 40.99, 0.00, '2026-06-12', 'pagado', '2026-06-12 02:44:05'),
(32, 8, 'regular', 1, 61.49, 61.49, '2026-06-19', 'pendiente', NULL);

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
(3, 9, 'DSP-1780661915', '2026-06-05', 'entregado', '2026-06-05 12:18:35');

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
(16, 26, 47, 10, 5.00);

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
(1, 3, NULL, 'proveedor', 'assets/img/products/sin_categoria/franela.png', '', '2026-05-28 23:19:45', 'pendiente', 'franela', 1, NULL, NULL, 'pendiente'),
(2, 3, NULL, 'proveedor', '', 'facebook.com', '2026-05-28 23:19:45', 'pendiente', 'jean', 1, NULL, NULL, 'pendiente'),
(3, 4, NULL, 'proveedor', 'assets/img/products/sin_categoria/franela.png', '', '2026-05-29 00:00:22', 'pendiente', 'franela', 1, NULL, NULL, 'vinculado'),
(4, 5, NULL, 'proveedor', 'assets/img/products/sin_categoria/jean.png', '', '2026-05-29 01:04:15', 'pendiente', 'jean', 1, NULL, NULL, 'ignorado'),
(5, 5, NULL, 'proveedor', '', 'https://us.shein.com/Women-s-Vest-Brasil-South-America-Flag-Print-Design-Exquisite-Elegant-And-Fashionable-Exuding-Feminine-Charm-Perfect-For-Holiday-Gifts-Mother-s-Day-Coachella-Music-Festival-Memorial-Day-Suitable-For-Spring-And-Summer-Suitable-For-Casual-Sports-Vacation-Travel-Beach-Wear-And-Daily-Wear-Widely-Versatile-Applicable-To-Various-Occasions-Ladies-Elegant-Suits-Blouses-Summer-Outfits-Vacation-Outfits-Women-Travel-Wear-Tank-Top-p-444142921.html?src_identifier=on%3DONE_THIRD_COMPONENT%60cn%3DONE_THIRD_COMPONENT_2%60hz%3D-%60jc%3DsheinPicks_10751%60ps%3D1_4&src_module=all&src_tab_page_id=page_home1780016622648&mallCode=1&pageListType=4&detailBusinessFrom=0-1_444142921%7C0-2', '2026-05-29 01:04:15', 'pendiente', 'franela brasil', 1, NULL, NULL, 'vinculado'),
(6, 6, NULL, 'proveedor', '', '', '2026-05-29 03:51:47', 'pendiente', 'perfume sabroso', 1, NULL, NULL, 'ignorado'),
(7, 6, NULL, 'proveedor', '', 'https://us.shein.com/Women-s-Vest-Brasil-South-America-Flag-Print-Design-Exquisite-Elegant-And-Fashionable-Exuding-Feminine-Charm-Perfect-For-Holiday-Gifts-Mother-s-Day-Coachella-Music-Festival-Memorial-Day-Suitable-For-Spring-And-Summer-Suitable-For-Casual-Sports-Vacation-Travel-Beach-Wear-And-Daily-Wear-Widely-Versatile-Applicable-To-Various-Occasions-Ladies-Elegant-Suits-Blouses-Summer-Outfits-Vacation-Outfits-Women-Travel-Wear-Tank-Top-p-444597816.html?src_identifier=on%3DONE_THIRD_COMPONENT%60cn%3DONE_THIRD_COMPONENT_2%60hz%3D-%60jc%3DsheinPicks_10751%60ps%3D1_4&src_module=all&src_tab_page_id=page_home1780016622648&mallCode=1&pageListType=4&detailBusinessFrom=0-1_444142921%7C0-2&main_attr=27_1000112', '2026-05-29 03:51:47', 'pendiente', NULL, 1, NULL, NULL, 'ignorado'),
(8, 6, NULL, 'proveedor', 'assets/img/products/sin_categoria/detalle_2.png', '', '2026-05-29 03:51:47', 'pendiente', NULL, 1, NULL, NULL, 'ignorado'),
(12, 9, NULL, 'proveedor', '', 'https://us.shein.com/Women-s-Vest-Brasil-South-America-Flag-Print-Design-Exquisite-Elegant-And-Fashionable-Exuding-Feminine-Charm-Perfect-For-Holiday-Gifts-Mother-s-Day-Coachella-Music-Festival-Memorial-Day-Suitable-For-Spring-And-Summer-Suitable-For-Casual-Sports-Vacation-Travel-Beach-Wear-And-Daily-Wear-Widely-Versatile-Applicable-To-Various-Occasions-Ladies-Elegant-Suits-Blouses-Summer-Outfits-Vacation-Outfits-Women-Travel-Wear-Tank-Top-p-444597816.html?src_identifier=on%3DONE_THIRD_COMPONENT%60cn%3DONE_THIRD_COMPONENT_2%60hz%3D-%60jc%3DsheinPicks_10751%60ps%3D1_4&src_module=all&src_tab_page_id=page_home1780016622648&mallCode=1&pageListType=4&detailBusinessFrom=0-1_444142921%7C0-2&main_attr=27_762', '2026-06-05 00:42:20', 'pendiente', NULL, 1, 10.00, NULL, 'ignorado'),
(13, 9, NULL, 'proveedor', 'assets/img/products/sin_categoria/detalle_1.jpg', '', '2026-06-05 00:42:20', 'pendiente', NULL, 1, 15.00, NULL, 'ignorado'),
(14, 10, NULL, 'proveedor', '', '', '2026-06-05 12:03:51', 'pendiente', 'sueter', 1, NULL, NULL, 'pendiente'),
(15, 11, 1, 'proveedor', '', '', '2026-06-05 13:28:28', 'pendiente', 'sueter', 1, 5.00, NULL, 'vinculado'),
(16, 11, NULL, 'proveedor', '', 'https://us.shein.com/Women-s-Vest-Brasil-South-America-Flag-Print-Design-Exquisite-Elegant-And-Fashionable-Exuding-Feminine-Charm-Perfect-For-Holiday-Gifts-Mother-s-Day-Coachella-Music-Festival-Memorial-Day-Suitable-For-Spring-And-Summer-Suitable-For-Casual-Sports-Vacation-Travel-Beach-Wear-And-Daily-Wear-Widely-Versatile-Applicable-To-Various-Occasions-Ladies-Elegant-Suits-Blouses-Summer-Outfits-Vacation-Outfits-Women-Travel-Wear-Tank-Top-p-444142921.html?src_identifier=on%3DONE_THIRD_COMPONENT%60cn%3DONE_THIRD_COMPONENT_2%60hz%3D-%60jc%3DsheinPicks_10751%60ps%3D1_4&src_module=all&src_tab_page_id=page_home1780016622648&mallCode=1&pageListType=4&detailBusinessFrom=0-1_444142921%7C0-2', '2026-06-05 13:28:28', 'pendiente', NULL, 1, 8.00, NULL, 'pendiente'),
(17, 11, NULL, 'proveedor', 'assets/img/products/sin_categoria/detalle_3.jpg', '', '2026-06-05 13:28:28', 'pendiente', NULL, 2, 6.10, NULL, 'pendiente'),
(18, 12, NULL, 'proveedor', 'assets/img/products/sin_categoria/franela.jpg', '', '2026-06-05 14:19:30', 'pendiente', 'franela', 1, 10.00, NULL, 'pendiente'),
(19, 13, NULL, 'proveedor', 'assets/img/products/sin_categoria/jean.jpg', '', '2026-06-05 14:21:22', 'pendiente', 'jean', 1, 5.00, NULL, 'pendiente'),
(20, 14, NULL, 'proveedor', '', '', '2026-06-07 03:59:35', 'pendiente', 'sueter', 1, 1.00, NULL, 'pendiente'),
(21, 15, 46, 'proveedor', '', '', '2026-06-11 21:09:01', 'pendiente', 'jean', 5, 25.62, NULL, 'vinculado'),
(23, 17, 46, 'cliente', '', '', '2026-06-12 00:40:49', 'pendiente', NULL, 4, 25.62, NULL, 'vinculado'),
(24, 18, 46, 'cliente', '', '', '2026-06-12 00:44:05', 'pendiente', NULL, 4, 25.62, NULL, 'vinculado'),
(25, 19, 47, 'proveedor', '', '', '2026-06-12 14:28:23', 'pendiente', NULL, 20, 80.06, NULL, 'vinculado'),
(26, 19, 47, 'proveedor', '', '', '2026-06-12 14:28:23', 'pendiente', NULL, 20, 10.06, NULL, 'vinculado'),
(27, 19, 47, 'proveedor', '', '', '2026-06-12 14:28:23', 'pendiente', NULL, 20, 10.06, NULL, 'vinculado');

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
(26, 4, '10', '2029-07-12', 50.00, '2026-06-12 14:23:11');

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
(1, 4, '2026-06-03 20:43:22', 'cancelado', 'debito', 20.00, '', '2026-06-03 18:43:22'),
(2, 4, '2026-06-03 21:33:23', 'entregado', 'debito', 10.00, '', '2026-06-03 19:33:23'),
(3, 4, '2026-06-04 00:29:47', 'recibido', 'credito', 30.00, '', '2026-06-03 22:29:47'),
(4, 17, '2026-06-04 00:32:13', 'pendiente', 'credito', 24.00, '', '2026-06-03 22:32:14'),
(5, 4, '2026-06-04 04:21:50', 'pendiente', 'credito', 12.00, '', '2026-06-04 02:21:50'),
(6, 19, '2026-06-04 17:39:28', 'pendiente', 'credito', 20.00, '', '2026-06-04 15:39:28'),
(7, 18, '2026-06-05 02:00:05', 'pendiente', 'credito', 10.00, '', '2026-06-05 00:00:05'),
(8, 18, '2026-06-05 14:14:45', 'entregado', 'debito', 82.92, 'compra', '2026-06-05 12:14:45'),
(9, 25, '2026-06-05 14:18:20', 'entregado', 'debito', 20.50, 'COMPRA', '2026-06-05 12:18:20'),
(10, 18, '2026-06-05 16:41:18', 'entregado', 'debito', 87.66, '', '2026-06-05 14:41:18'),
(12, 4, '2026-06-07 06:16:43', 'pendiente', 'credito', 2.06, '', '2026-06-07 04:16:43'),
(13, 4, '2026-06-07 06:18:52', 'pendiente', 'credito', 2.06, '', '2026-06-07 04:18:52'),
(14, 39, '2026-06-10 18:20:01', 'pendiente', 'credito', 51.24, '', '2026-06-10 16:20:01'),
(15, 41, '2026-06-11 01:54:30', 'pendiente', 'credito', 64.55, '', '2026-06-10 23:54:30'),
(17, 18, '2026-06-12 02:40:49', '', 'debito', 102.48, 'Generada automáticamente desde Pedido de Servicio #17', '2026-06-12 00:40:49'),
(18, 18, '2026-06-12 02:44:05', '', 'debito', 102.48, 'Generada automáticamente desde Pedido de Servicio #18', '2026-06-12 00:44:05'),
(19, 48, '2026-06-13 21:23:57', 'pendiente', 'debito', 10.06, '', '2026-06-13 19:23:57');

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
(1, 1, 36, 1, 10.00, ''),
(2, 1, 2, 1, 10.00, ''),
(3, 2, 27, 1, 10.00, ''),
(4, 3, 37, 3, 10.00, ''),
(5, 4, 1, 2, 12.00, ''),
(6, 5, 1, 1, 12.00, ''),
(7, 6, 37, 2, 10.00, ''),
(8, 7, 27, 1, 10.00, ''),
(9, 8, 2, 2, 41.46, ''),
(10, 9, 1, 1, 20.50, ''),
(12, 10, 40, 1, 23.10, ''),
(13, 10, 2, 1, 41.46, ''),
(15, 12, 44, 1, 2.06, ''),
(16, 13, 44, 1, 2.06, ''),
(17, 14, 46, 2, 25.62, ''),
(18, 15, 38, 5, 12.91, ''),
(19, 19, 47, 1, 10.06, '');

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
(107, 1, 7, 4022.21, 7.20, '2026-06-10 19:09:26', 558.64, NULL, '', 'verificado'),
(108, 1, 7, 1340.74, 2.40, '2026-06-10 19:08:59', 558.64, NULL, '121233', 'verificado'),
(109, 1, 7, 1340.74, 2.40, '2026-06-06 22:40:57', 558.64, NULL, '21212', 'rechazado'),
(110, 1, 10, 6724.56, 12.00, '2026-06-06 22:41:02', 560.38, NULL, '22', 'rechazado'),
(111, 1, 8, 5.00, 0.01, '2026-06-06 22:40:43', 560.38, NULL, '111', 'rechazado'),
(112, 7, 7, 2241.52, 4.00, '2026-06-04 20:00:05', 560.38, NULL, '', 'por verificar'),
(113, 7, 8, 840.57, 1.50, '2026-06-04 20:00:52', 560.38, NULL, '232323', 'por verificar'),
(114, 7, 7, 2241.52, 4.00, '2026-06-04 20:02:09', 560.38, NULL, '21212', 'por verificar'),
(115, 7, 8, 280.19, 0.50, '2026-06-04 20:02:42', 560.38, NULL, '21212', 'por verificar'),
(116, 8, 10, 46708.01, 82.92, '2026-06-10 19:10:42', 563.29, NULL, '185549', 'verificado'),
(117, 9, 9, 11547.45, 20.50, '2026-06-11 14:16:39', 563.29, NULL, '2588811', 'verificado'),
(118, 10, 10, 5632.90, 10.00, '2026-06-06 22:45:02', 563.29, NULL, '456456454', 'verificado'),
(119, 10, 8, 43745.00, 77.66, '2026-06-06 22:45:51', 563.29, NULL, '', 'verificado'),
(121, 1, 7, 13518.96, 24.00, '2026-06-06 22:40:18', 563.29, NULL, 'N/A', 'verificado'),
(122, 13, 8, 464.00, 0.82, '2026-06-07 00:19:28', 563.29, NULL, '', 'verificado'),
(123, 13, 7, 563.29, 1.00, '2026-06-07 00:20:11', 563.29, NULL, 'N/A', 'verificado'),
(124, 13, 8, 135.19, 0.24, '2026-06-07 00:20:48', 563.29, NULL, 'N/A', 'por verificar'),
(125, 14, 7, 11547.45, 20.50, '2026-06-10 12:21:45', 563.29, NULL, '', 'verificado'),
(126, 14, 7, 11265.80, 20.00, '2026-06-10 12:21:52', 563.29, NULL, 'N/A', 'verificado'),
(127, 15, 8, 14544.00, 25.82, '2026-06-10 19:54:30', 563.29, NULL, '', 'por verificar'),
(128, 1, 7, 4506.32, 8.00, '2026-06-11 14:16:10', 563.29, NULL, 'N/A', 'por verificar'),
(129, 1, 10, 924.08, 1.60, '2026-06-11 20:21:42', 577.55, NULL, '567780', 'por verificar'),
(130, 19, 8, 5810.00, 10.06, '2026-06-13 15:23:57', 577.55, NULL, '', 'por verificar');

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
(1, 1, NULL, NULL, 'propios', '2026-05-28 04:27:15', NULL, NULL, NULL, 'entregado'),
(3, 1, NULL, NULL, 'propios', '2026-05-28 23:19:45', NULL, NULL, NULL, 'entregado'),
(4, 1, NULL, NULL, 'propios', '2026-05-29 00:00:22', NULL, NULL, NULL, 'recibido'),
(5, 4, NULL, NULL, 'propios', '2026-05-29 01:04:15', NULL, NULL, NULL, 'recibido'),
(6, 4, NULL, NULL, 'propios', '2026-05-29 03:51:47', NULL, NULL, NULL, 'recibido'),
(9, 1, NULL, NULL, 'propios', '2026-06-05 00:42:20', NULL, NULL, NULL, 'recibido'),
(10, 5, NULL, NULL, 'propios', '2026-06-05 12:03:51', NULL, NULL, NULL, 'pendiente'),
(11, 1, NULL, NULL, 'propios', '2026-06-05 13:28:28', NULL, NULL, NULL, 'recibido'),
(12, 1, NULL, NULL, 'propios', '2026-06-05 14:19:30', NULL, NULL, NULL, 'cancelado'),
(13, 1, NULL, NULL, 'propios', '2026-06-05 14:21:22', NULL, NULL, NULL, 'confirmado'),
(14, 1, NULL, NULL, 'propios', '2026-06-07 03:59:35', '2026-06-30', '2026-06-07 00:00:25', NULL, 'recibido'),
(15, 4, NULL, NULL, 'propios', '2026-06-11 21:09:01', NULL, NULL, NULL, 'cancelado'),
(17, NULL, 18, 17, 'cliente', '2026-06-12 00:40:49', '2026-07-05', '2026-06-11 20:48:00', NULL, 'recibido'),
(18, NULL, 18, 18, 'cliente', '2026-06-12 00:44:05', NULL, NULL, NULL, 'pendiente'),
(19, 4, NULL, NULL, 'propios', '2026-06-12 14:28:23', NULL, NULL, NULL, 'pendiente');

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
(2, 2, 'Franela', 'franelas', 20.00, 41.46, 3, NULL, NULL, 1, 0, '2026-05-28 22:57:39'),
(25, 6, 'perfume sabroso', '', 0.00, 10.00, 3, 'factory', NULL, 0, 0, '2026-05-29 03:55:06'),
(34, 6, 'perfume delicioso', 'fragancia diaria', 5.60, 11.55, 3, NULL, NULL, 1, 0, '2026-06-05 13:13:05'),
(35, 8, 'Zapatos', 'R45-18', 200.00, 140.80, 3, NULL, NULL, 1, 0, '2026-06-05 13:30:56'),
(39, 2, 'sueter gucci', 'tono calido', 0.00, 2.06, 3, NULL, NULL, 1, 0, '2026-06-07 04:01:17'),
(42, 2, 'franela cara', 'franela diaria', 0.00, 10.31, 3, NULL, NULL, 0, 0, '2026-06-08 22:03:17'),
(43, 2, 'pantalon', 'pantalon bota ancha', 10.00, 20.62, 15, NULL, NULL, 1, 0, '2026-06-10 15:59:01'),
(45, 6, 'Tomy', 'fragancia diaria', 0.00, 20.11, 3, NULL, NULL, 1, 0, '2026-06-12 14:14:36'),
(46, 6, 'Dior', 'fragancia diaria', 0.00, 20.11, 3, NULL, NULL, 1, 0, '2026-06-12 14:16:33'),
(47, 6, 'Dior one', 'fragancia diaria', 5.00, 10.06, 3, NULL, NULL, 1, 0, '2026-06-12 14:19:47');

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
(3, 2, NULL, 'franela verde', '{\"talla\":\"s\",\"color\":\"verde\"}', 0.00, 16, NULL, 1),
(4, 1, NULL, 'Principal', '{\"talla\":\"l\",\"color\":\"gris\"}', 0.00, 1, NULL, 0),
(27, 25, NULL, 'perfume test2', '{\"volumen_ml\":\"1\",\"fragancia\":\"prueba\"}', 0.00, 2, 'assets/img/products/perfumes/perfume_sabroso_perfume_test.png', 0),
(36, 25, NULL, 'perfume rico', '{\"volumen_ml\":\"12\",\"fragancia\":\"test2\"}', 0.00, 1, NULL, 0),
(37, 25, NULL, 'perfume pequeno', '{\"volumen_ml\":\"25\",\"fragancia\":\"hallmen\"}', 0.00, 7, 'assets/img/products/perfumes/perfume_sabroso_perfume_pequeno.png', 0),
(38, 34, NULL, 'perfume test', '{\"volumen_ml\":\"150\",\"fragancia\":\"citrica\"}', 1.36, 25, 'assets/img/products/perfumes/perfume_sabroso_perfume_test.jpg', 1),
(39, 34, NULL, 'perfume rico', '{\"volumen_ml\":\"150\",\"fragancia\":\"dulce\"}', 1.20, 7, 'assets/img/products/perfumes/perfume_sabroso_perfume_rico.jpg', 1),
(40, 35, NULL, 'Zapatos', '{}', 0.00, 20, NULL, 1),
(44, 39, NULL, 'sueter gucci', '{}', 0.00, 1, NULL, 1),
(45, 42, NULL, 'Principal', '{\"talla\":\"l\",\"color\":\"BLANCA\"}', 0.00, 2, NULL, 0),
(46, 43, NULL, 'pantalon bota ancha', '{\"talla\":\"34\",\"color\":\"azul\"}', 5.00, 19, NULL, 1),
(47, 47, NULL, 'Principal', '{\"volumen_ml\":\"5\",\"fragancia\":\"jasmin\"}', 0.00, 20, NULL, 1),
(48, 47, NULL, 'dior 500ml', '{\"volumen_ml\":\"500\"}', 0.00, 0, NULL, 1),
(49, 47, NULL, 'dior 200ml', '{\"volumen_ml\":\"200\"}', 0.00, 0, NULL, 1);

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
(5, 'Champions', 'V-27120332', '2161325', 'Pepe', 'Perez', '+584225583005', NULL, 'pepe1112@gmail.com', 1, 's;kndjksbdks');

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
(2, 'BCV', 577.55, '2026-06-11 20:15:03'),
(3, 'Zelle', 744.69, '2026-06-03 23:51:44');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT de la tabla `creditos`
--
ALTER TABLE `creditos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `creditos_cuotas`
--
ALTER TABLE `creditos_cuotas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `despachos`
--
ALTER TABLE `despachos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `detalles_entrada`
--
ALTER TABLE `detalles_entrada`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `detalles_pedido`
--
ALTER TABLE `detalles_pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `entradas`
--
ALTER TABLE `entradas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `metodos_pago`
--
ALTER TABLE `metodos_pago`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `notas_entrega`
--
ALTER TABLE `notas_entrega`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `notas_entrega_detalles`
--
ALTER TABLE `notas_entrega_detalles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `preguntas_seguridad`
--
ALTER TABLE `preguntas_seguridad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de la tabla `producto_variantes`
--
ALTER TABLE `producto_variantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
