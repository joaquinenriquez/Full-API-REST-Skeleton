-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-03-2020 a las 22:16:54
-- Versión del servidor: 10.1.31-MariaDB
-- Versión de PHP: 7.2.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `comanda`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulos`
--

CREATE TABLE `articulos` (
  `id_articulo` int(11) NOT NULL,
  `descripcion` varchar(255) COLLATE utf8_spanish2_ci NOT NULL,
  `id_sector` int(11) NOT NULL,
  `importe` decimal(10,0) NOT NULL,
  `estado` varchar(255) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `articulos`
--

INSERT INTO `articulos` (`id_articulo`, `descripcion`, `id_sector`, `importe`, `estado`) VALUES
(1, 'Albóndigas con arroz', 1, '100', '1'),
(2, 'Arroz con pollo', 1, '100', '1'),
(3, 'Milanesa con fritas', 1, '110', '1'),
(4, 'Suprema maryland', 1, '145', '1'),
(5, 'Tortilla de papas', 1, '120', '1'),
(6, 'Tortilla de papas a la española', 1, '120', '1'),
(7, 'Postre vigilante', 4, '75', '1'),
(8, 'Flan casero crema y dulce', 4, '80', '1'),
(9, 'Ensalada de frutas', 4, '60', '1'),
(10, 'Tiramisu', 4, '80', '1'),
(11, 'Vino de la casa en pinguino', 2, '85', '1'),
(12, 'Soda en sifon', 2, '50', '1'),
(13, 'Fernet con Coca', 2, '80', '1'),
(14, 'Gin tonnic', 2, '80', '1'),
(15, 'Pinta cerveza artesanal IPA', 3, '75', '1'),
(16, 'Pinta cerveza artesanal APA', 3, '80', '1'),
(17, 'Pinta cerveza artesanal EPA', 3, '85', '1'),
(18, 'Pinta cerveza artesanal UPA', 3, '120', '1'),
(19, 'Empanadas', 1, '30', '1'),
(20, 'Pinta cerveza artesanal DUA LIPA', 2, '120', '1'),
(21, 'Pinta cerveza artesanal DUA LIPA2', 2, '120', '0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cabeceraspedidos`
--

CREATE TABLE `cabeceraspedidos` (
  `id_pedido` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nombre_cliente` varchar(255) COLLATE utf8_spanish2_ci NOT NULL,
  `estado` int(11) NOT NULL,
  `codigo_amigable` varchar(10) COLLATE utf8_spanish2_ci NOT NULL,
  `id_mesa` int(11) NOT NULL,
  `foto` varchar(255) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime DEFAULT NULL,
  `importe` decimal(10,0) NOT NULL,
  `contesto` tinyint(1) NOT NULL,
  `calificacion_mesa` int(11) NOT NULL,
  `calificacion_restaurante` int(11) NOT NULL,
  `calificacion_mozo` int(11) NOT NULL,
  `calificacion_cocinero` int(11) NOT NULL,
  `comentarios` varchar(1024) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `cabeceraspedidos`
--

INSERT INTO `cabeceraspedidos` (`id_pedido`, `id_usuario`, `nombre_cliente`, `estado`, `codigo_amigable`, `id_mesa`, `foto`, `fecha_inicio`, `fecha_fin`, `importe`, `contesto`, `calificacion_mesa`, `calificacion_restaurante`, `calificacion_mozo`, `calificacion_cocinero`, `comentarios`) VALUES
(1, 5, 'Nombre del cliente ASD', 1, '61XYV', 1, NULL, '2003-03-20 13:32:00', NULL, '0', 0, 0, 0, 0, 0, ''),
(2, 5, 'Nombre del cliente ASD', 1, 'M19IN', 4, NULL, '2003-03-20 13:33:00', NULL, '0', 0, 0, 0, 0, 0, ''),
(3, 5, 'Nombre del cliente asd2', 1, '643RU', 5, NULL, '2020-03-03 13:52:40', NULL, '0', 0, 0, 0, 0, 0, ''),
(4, 2, 'Nombre del cliente asd2', 1, 'MP111', 5, NULL, '2020-03-03 15:45:09', NULL, '0', 0, 0, 0, 0, 0, ''),
(5, 2, 'Nombre del cliente asd2', 1, 'CY5QU', 5, NULL, '2020-03-03 15:45:26', NULL, '0', 0, 0, 0, 0, 0, ''),
(6, 2, 'Nombre del cliente asd2', 1, 'RG8PF', 5, NULL, '2020-03-03 15:46:20', NULL, '0', 0, 0, 0, 0, 0, ''),
(7, 2, 'Nombre del cliente asd2', 1, 'PSH4B', 5, NULL, '2020-03-03 15:47:08', NULL, '0', 0, 0, 0, 0, 0, ''),
(8, 2, 'Nombre del cliente asd2', 1, 'EEYPL', 5, NULL, '2020-03-03 16:28:43', NULL, '0', 0, 0, 0, 0, 0, ''),
(9, 2, 'Nombre del cliente asd2', 1, '78KYX', 5, NULL, '2020-03-03 16:39:38', NULL, '0', 0, 0, 0, 0, 0, ''),
(10, 2, 'Nombre del cliente asd2', 1, 'AVXWZ', 5, NULL, '2020-03-03 16:42:17', '2020-03-03 16:50:29', '60', 0, 0, 0, 0, 0, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) COLLATE utf8_spanish2_ci NOT NULL,
  `apellidos` varchar(255) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(255) COLLATE utf8_spanish2_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(255) COLLATE utf8_spanish2_ci NOT NULL,
  `ciudad` varchar(255) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombre`, `apellidos`, `telefono`, `email`, `direccion`, `ciudad`) VALUES
(10, 'juan', 'perez', 'asd', 'adsda@gmail.com', 'calelsadasddsasd', 'sadsadsadsa'),
(10, 'juan', 'perez', 'asd', 'adsda@gmail.com', 'calelsadasddsasd', 'sadsadsadsa'),
(10, 'juan', 'perez', 'asd', 'adsda@gmail.com', 'calelsadasddsasd', 'sadsadsadsa'),
(10, 'juan', 'perez', 'asd', 'adsda@gmail.com', 'calelsadasddsasd', 'sadsadsadsa'),
(10, 'juan', 'perez', 'asd', 'adsda@gmail.com', 'calelsadasddsasd', 'sadsadsadsa'),
(10, 'juan', 'perez', 'asd', 'adsda@gmail.com', 'calelsadasddsasd', 'sadsadsadsa'),
(10, 'juan', 'perez', 'asd', 'adsda@gmail.com', 'calelsadasddsasd', 'sadsadsadsa');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comandas`
--

CREATE TABLE `comandas` (
  `idComanda` int(11) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `nombreCliente` varchar(255) COLLATE utf8_spanish2_ci NOT NULL,
  `estado` int(11) NOT NULL,
  `codigoAmigable` varchar(255) COLLATE utf8_spanish2_ci NOT NULL,
  `idMesa` int(11) NOT NULL,
  `foto` varchar(255) COLLATE utf8_spanish2_ci NOT NULL,
  `fechaInicio` datetime NOT NULL,
  `fechaFin` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `itemspedidos`
--

CREATE TABLE `itemspedidos` (
  `id_item_pedido` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `fecha_hora_creacion` datetime NOT NULL,
  `fecha_hora_inicio_preparacion` datetime DEFAULT NULL,
  `fecha_hora_fin_preparacion` datetime DEFAULT NULL,
  `id_articulo` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `tiempo_estimado` int(11) DEFAULT NULL,
  `id_usuario_creador` int(11) NOT NULL,
  `id_usuario_asignado` int(11) DEFAULT NULL,
  `estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `itemspedidos`
--

INSERT INTO `itemspedidos` (`id_item_pedido`, `id_pedido`, `fecha_hora_creacion`, `fecha_hora_inicio_preparacion`, `fecha_hora_fin_preparacion`, `id_articulo`, `cantidad`, `tiempo_estimado`, `id_usuario_creador`, `id_usuario_asignado`, `estado`) VALUES
(1, 2, '2020-03-03 13:36:39', '2020-03-03 14:58:00', '2020-03-03 15:00:00', 9, 1, 10, 5, 7, 4),
(2, 1, '2020-03-03 13:39:28', NULL, NULL, 2, 1, NULL, 2, NULL, 0),
(3, 1, '2020-03-03 13:39:28', NULL, NULL, 3, 1, NULL, 2, NULL, 0),
(4, 1, '2020-03-03 13:39:28', NULL, NULL, 11, 1, NULL, 2, NULL, 0),
(5, 1, '2020-03-03 13:39:28', NULL, NULL, 12, 1, NULL, 2, NULL, 0),
(6, 1, '2020-03-03 13:39:28', NULL, NULL, 16, 4, NULL, 2, NULL, 0),
(7, 1, '2020-03-03 13:39:28', NULL, NULL, 17, 4, NULL, 2, NULL, 0),
(8, 1, '2020-03-03 13:39:28', NULL, NULL, 9, 3, NULL, 2, NULL, 0),
(9, 1, '2020-03-03 13:39:28', NULL, NULL, 10, 2, NULL, 2, NULL, 0),
(10, 7, '2020-03-03 15:47:31', '2020-03-03 16:21:00', '2020-03-03 16:21:00', 9, 1, 10, 2, 7, 0),
(11, 7, '2020-03-03 15:50:37', '2020-03-03 16:07:00', '2020-03-03 16:09:00', 9, 1, 10, 2, 7, 0),
(12, 8, '2020-03-03 16:35:47', NULL, NULL, 9, 1, NULL, 2, NULL, 0),
(13, 9, '2020-03-03 16:39:56', NULL, NULL, 9, 1, NULL, 2, NULL, 0),
(14, 10, '2020-03-03 16:42:28', NULL, NULL, 9, 1, NULL, 2, NULL, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs`
--

CREATE TABLE `logs` (
  `id_registro` int(11) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_sector` int(11) DEFAULT NULL,
  `id_accion` int(11) NOT NULL,
  `descripcion_accion` varchar(255) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `logs`
--

INSERT INTO `logs` (`id_registro`, `fecha_hora`, `id_usuario`, `id_sector`, `id_accion`, `descripcion_accion`) VALUES
(1, '2020-03-03 10:12:05', 5, 4, 1, 'Inicio de sesion correcto'),
(2, '2020-03-03 10:12:13', 7, 1, 1, 'Inicio de sesion correcto'),
(3, '2020-03-03 10:12:18', 5, 4, 1, 'Inicio de sesion correcto'),
(4, '2020-03-03 10:12:25', 5, 4, 0, 'E'),
(5, '2020-03-03 10:13:30', 5, 4, 0, 'E'),
(6, '2020-03-03 10:13:34', 5, 4, 0, 'E'),
(7, '2020-03-03 10:13:37', 5, 4, 0, 'E'),
(8, '2020-03-03 10:13:43', 5, 4, 0, 'E'),
(9, '2020-03-03 10:14:13', 5, 4, 0, 'O'),
(10, '2020-03-03 10:14:36', 7, 1, 0, 'O'),
(11, '2020-03-03 10:14:43', 3, 5, 1, 'Inicio de sesion correcto'),
(12, '2020-03-03 10:15:16', 3, 5, 0, 'O'),
(13, '2020-03-03 10:15:42', 3, 5, 0, 'O'),
(14, '2020-03-03 10:15:57', 5, 4, 0, 'E'),
(15, '2020-03-03 10:18:17', 3, 5, 0, 'E'),
(16, '2020-03-03 10:18:29', 5, 4, 0, 'E'),
(17, '2020-03-03 10:18:37', 5, 4, 0, 'E'),
(18, '2020-03-03 10:19:44', 3, 5, 0, 'O'),
(19, '2020-03-03 10:22:03', 3, 5, 0, 'O'),
(20, '2020-03-03 10:29:11', 3, 5, 0, 'O'),
(21, '2020-03-03 10:29:14', 3, 5, 0, 'O'),
(22, '2020-03-03 10:29:21', 5, 4, 0, 'E'),
(23, '2020-03-03 10:29:37', 3, 5, 0, 'O'),
(24, '2020-03-03 10:30:05', 3, 5, 0, 'O'),
(25, '2020-03-03 10:30:22', 3, 5, 0, 'O'),
(26, '2020-03-03 10:31:14', 3, 5, 0, 'O'),
(27, '2020-03-03 10:50:29', 5, 4, 1, 'Inicio de sesion correcto'),
(28, '2020-03-03 10:50:41', 5, 4, 0, 'O'),
(29, '2020-03-03 10:50:49', 5, 4, 0, 'O'),
(30, '2020-03-03 10:50:54', 5, 4, 0, 'O'),
(31, '2020-03-03 10:50:56', 5, 4, 0, 'O'),
(32, '2020-03-03 10:50:57', 5, 4, 0, 'O'),
(33, '2020-03-03 10:51:37', 5, 4, 0, 'E'),
(34, '2020-03-03 10:51:50', 5, 4, 0, 'E'),
(35, '2020-03-03 10:52:31', 5, 4, 0, 'O'),
(36, '2020-03-03 10:52:39', 5, 4, 0, 'O'),
(37, '2020-03-03 10:52:41', 5, 4, 0, 'O'),
(38, '2020-03-03 10:53:08', 5, 4, 0, 'E'),
(39, '2020-03-03 10:53:37', 5, 4, 0, 'E'),
(40, '2020-03-03 10:54:36', 5, 4, 0, 'E'),
(41, '2020-03-03 10:54:45', 5, 4, 0, 'E'),
(42, '2020-03-03 10:54:50', 5, 4, 0, 'E'),
(43, '2020-03-03 10:55:04', 5, 4, 0, 'A'),
(44, '2020-03-03 10:55:09', 5, 4, 0, 'A'),
(45, '2020-03-03 10:58:11', 5, 4, 1, 'Inicio de sesion correcto'),
(46, '2020-03-03 10:58:22', 5, 4, 0, 'O'),
(47, '2020-03-03 11:01:37', 5, 4, 0, 'O'),
(48, '2020-03-03 11:02:17', 3, 5, 1, 'Inicio de sesion correcto'),
(49, '2020-03-03 11:02:31', 3, 5, 0, 'O'),
(50, '2020-03-03 11:02:44', 3, 5, 0, 'O'),
(51, '2020-03-03 11:06:37', 3, 5, 0, 'O'),
(52, '2020-03-03 11:06:50', 3, 5, 0, 'O'),
(53, '2020-03-03 11:07:10', 3, 5, 0, 'O'),
(54, '2020-03-03 11:07:19', 3, 5, 0, 'O'),
(55, '2020-03-03 11:17:50', 5, 4, 0, 'O'),
(56, '2020-03-03 11:18:06', 3, 5, 0, 'O'),
(57, '2020-03-03 11:18:44', 3, 5, 0, 'O'),
(58, '2020-03-03 11:19:04', 3, 5, 0, 'O'),
(59, '2020-03-03 11:20:56', 3, 5, 0, 'E'),
(60, '2020-03-03 11:21:00', 3, 5, 0, 'E'),
(61, '2020-03-03 11:21:02', 3, 5, 0, 'E'),
(62, '2020-03-03 11:21:20', 3, 5, 0, 'E'),
(63, '2020-03-03 11:21:22', 3, 5, 0, 'E'),
(64, '2020-03-03 11:21:23', 3, 5, 0, 'E'),
(65, '2020-03-03 11:24:25', 3, 5, 0, 'E'),
(66, '2020-03-03 11:26:39', 5, 4, 0, 'A'),
(67, '2020-03-03 11:27:27', 5, 4, 0, 'A'),
(68, '2020-03-03 11:27:34', 3, 5, 0, 'E'),
(69, '2020-03-03 11:27:37', 5, 4, 0, 'A'),
(70, '2020-03-03 11:28:24', 5, 4, 0, 'A'),
(71, '2020-03-03 11:28:30', 5, 4, 0, 'A'),
(72, '2020-03-03 11:29:03', 2, 5, 1, 'Inicio de sesion correcto'),
(73, '2020-03-03 11:29:12', 2, 5, 0, 'E'),
(74, '2020-03-03 11:29:17', 2, 5, 0, 'A'),
(75, '2020-03-03 11:30:36', 3, 5, 0, 'O'),
(76, '2020-03-03 12:53:32', 2, 5, 1, 'Inicio de sesion correcto'),
(77, '2020-03-03 12:53:49', 5, 4, 1, 'Inicio de sesion correcto'),
(78, '2020-03-03 12:54:09', 5, 4, 0, 'O'),
(79, '2020-03-03 12:54:17', 2, 5, 1, 'Inicio de sesion correcto'),
(80, '2020-03-03 12:54:26', 2, 5, 0, 'O'),
(81, '2020-03-03 12:54:37', 2, 5, 0, 'O'),
(82, '2020-03-03 12:55:15', 5, 4, 0, 'E'),
(83, '2020-03-03 12:55:47', 5, 4, 0, 'E'),
(84, '2020-03-03 12:56:28', 5, 4, 0, 'E'),
(85, '2020-03-03 12:56:41', 2, 5, 0, 'E'),
(86, '2020-03-03 12:58:37', 5, 4, 0, 'E'),
(87, '2020-03-03 12:58:42', 5, 4, 0, 'E'),
(88, '2020-03-03 12:59:42', 5, 4, 0, 'O'),
(89, '2020-03-03 12:59:58', 2, 5, 0, 'O'),
(90, '2020-03-03 13:00:04', 2, 5, 0, 'O'),
(91, '2020-03-03 13:00:41', 5, 4, 0, 'E'),
(92, '2020-03-03 13:00:45', 5, 4, 0, 'E'),
(93, '2020-03-03 13:01:42', 5, 4, 0, 'E'),
(94, '2020-03-03 13:01:46', 5, 4, 0, 'E'),
(95, '2020-03-03 13:06:25', 5, 4, 0, 'E'),
(96, '2020-03-03 13:06:46', 5, 4, 0, 'E'),
(97, '2020-03-03 13:07:01', 5, 4, 0, 'E'),
(98, '2020-03-03 13:07:06', 5, 4, 0, 'E'),
(99, '2020-03-03 13:07:43', 5, 4, 0, 'E'),
(100, '2020-03-03 13:08:26', 5, 4, 0, 'O'),
(101, '2020-03-03 13:09:11', 5, 4, 0, 'O'),
(102, '2020-03-03 13:09:59', 5, 4, 0, 'O'),
(103, '2020-03-03 13:10:43', 5, 4, 0, 'O'),
(104, '2020-03-03 13:11:27', 5, 4, 0, 'O'),
(105, '2020-03-03 13:11:51', 5, 4, 0, 'O'),
(106, '2020-03-03 13:12:22', 5, 4, 0, 'O'),
(107, '2020-03-03 13:15:01', 2, 5, 0, 'E'),
(108, '2020-03-03 13:15:27', 2, 5, 0, 'E'),
(109, '2020-03-03 13:15:54', 2, 5, 0, 'E'),
(110, '2020-03-03 13:15:58', 2, 5, 0, 'E'),
(111, '2020-03-03 13:16:28', 2, 5, 0, 'E'),
(112, '2020-03-03 13:16:48', 2, 5, 0, 'E'),
(113, '2020-03-03 13:17:10', 2, 5, 0, 'E'),
(114, '2020-03-03 13:18:28', 2, 5, 0, 'E'),
(115, '2020-03-03 13:18:39', 2, 5, 0, 'E'),
(116, '2020-03-03 13:19:01', 2, 5, 0, 'E'),
(117, '2020-03-03 13:24:55', 2, 5, 0, 'E'),
(118, '2020-03-03 13:25:12', 2, 5, 0, 'E'),
(119, '2020-03-03 13:29:10', 5, 4, 1, 'Inicio de sesion correcto'),
(120, '2020-03-03 13:29:19', 5, 4, 0, 'E'),
(121, '2020-03-03 13:29:40', 5, 4, 0, 'O'),
(122, '2020-03-03 13:30:19', 5, 4, 0, 'O'),
(123, '2020-03-03 13:30:27', 2, 5, 1, 'Inicio de sesion correcto'),
(124, '2020-03-03 13:30:35', 2, 5, 0, 'O'),
(125, '2020-03-03 13:30:43', 5, 4, 0, 'E'),
(126, '2020-03-03 13:31:49', 7, 1, 1, 'Inicio de sesion correcto'),
(127, '2020-03-03 13:32:20', 7, 1, 0, 'O'),
(128, '2020-03-03 13:32:57', 5, 4, 0, 'O'),
(129, '2020-03-03 13:33:11', 5, 4, 0, 'O'),
(130, '2020-03-03 13:33:31', 5, 4, 0, 'O'),
(131, '2020-03-03 13:34:04', 5, 4, 0, 'O'),
(132, '2020-03-03 13:36:23', 7, 1, 0, 'O'),
(133, '2020-03-03 13:36:39', 5, 4, 0, 'O'),
(134, '2020-03-03 13:39:28', 2, 5, 0, 'O'),
(135, '2020-03-03 13:42:03', 2, 5, 0, 'E'),
(136, '2020-03-03 13:42:31', 2, 5, 0, 'E'),
(137, '2020-03-03 13:43:22', 7, 1, 0, 'E'),
(138, '2020-03-03 13:45:20', 2, 5, 0, 'E'),
(139, '2020-03-03 13:45:25', 2, 5, 0, 'E'),
(140, '2020-03-03 13:46:35', 5, 4, 0, 'E'),
(141, '2020-03-03 13:47:29', 5, 4, 0, 'E'),
(142, '2020-03-03 13:47:51', 2, 5, 0, 'E'),
(143, '2020-03-03 13:48:06', 5, 4, 0, 'E'),
(144, '2020-03-03 13:52:31', 5, 4, 0, 'O'),
(145, '2020-03-03 13:52:35', 5, 4, 0, 'O'),
(146, '2020-03-03 13:52:37', 5, 4, 0, 'O'),
(147, '2020-03-03 13:52:40', 5, 4, 0, 'O'),
(148, '2020-03-03 13:54:11', 5, 4, 0, 'A'),
(149, '2020-03-03 13:54:22', 2, 5, 1, 'Inicio de sesion correcto'),
(150, '2020-03-03 13:54:31', 2, 5, 0, 'A'),
(151, '2020-03-03 13:54:38', 2, 5, 0, 'A'),
(152, '2020-03-03 13:55:23', 2, 5, 0, 'E'),
(153, '2020-03-03 13:56:20', 2, 5, 0, 'A'),
(154, '2020-03-03 13:56:26', 2, 5, 0, 'A'),
(155, '2020-03-03 13:57:06', 2, 5, 0, 'E'),
(156, '2020-03-03 13:57:12', 2, 5, 0, 'A'),
(157, '2020-03-03 13:57:50', 2, 5, 0, 'A'),
(158, '2020-03-03 13:58:17', 2, 5, 0, 'A'),
(159, '2020-03-03 13:58:38', 2, 5, 0, 'A'),
(160, '2020-03-03 13:58:44', 2, 5, 0, 'E'),
(161, '2020-03-03 14:06:23', 5, 4, 0, 'A'),
(162, '2020-03-03 14:06:29', 5, 4, 0, 'A'),
(163, '2020-03-03 14:07:04', 5, 4, 0, 'O'),
(164, '2020-03-03 14:07:10', 5, 4, 0, 'O'),
(165, '2020-03-03 14:07:29', 5, 4, 0, 'E'),
(166, '2020-03-03 14:10:37', 5, 4, 0, 'E'),
(167, '2020-03-03 14:11:53', 5, 4, 0, 'O'),
(168, '2020-03-03 14:13:19', 5, 4, 0, 'O'),
(169, '2020-03-03 14:26:13', 5, 4, 0, 'E'),
(170, '2020-03-03 14:43:27', 5, 4, 0, 'O'),
(171, '2020-03-03 14:43:31', 5, 4, 0, 'O'),
(172, '2020-03-03 14:43:37', 5, 4, 0, 'O'),
(173, '2020-03-03 14:44:28', 5, 4, 0, 'E'),
(174, '2020-03-03 14:46:00', 5, 4, 0, 'E'),
(175, '2020-03-03 14:46:32', 5, 4, 0, 'E'),
(176, '2020-03-03 14:47:38', 5, 4, 0, 'E'),
(177, '2020-03-03 14:48:09', NULL, NULL, 4, 'Inicio de sesion incorrecto: el usuario esta suspendido'),
(178, '2020-03-03 14:48:25', NULL, NULL, 4, 'Inicio de sesion incorrecto: el usuario esta suspendido'),
(179, '2020-03-03 14:49:05', 5, 4, 0, 'E'),
(180, '2020-03-03 14:49:31', 12, 3, 1, 'Inicio de sesion correcto'),
(181, '2020-03-03 14:49:46', 12, 3, 0, 'O'),
(182, '2020-03-03 14:51:17', 12, 3, 0, 'O'),
(183, '2020-03-03 14:51:32', 12, 3, 0, 'O'),
(184, '2020-03-03 14:51:41', 2, 5, 1, 'Inicio de sesion correcto'),
(185, '2020-03-03 14:51:48', 2, 5, 0, 'O'),
(186, '2020-03-03 14:52:42', 2, 5, 0, 'O'),
(187, '2020-03-03 14:53:14', 5, 4, 1, 'Inicio de sesion correcto'),
(188, '2020-03-03 14:53:23', 5, 4, 0, 'O'),
(189, '2020-03-03 14:53:41', 12, 3, 0, 'O'),
(190, '2020-03-03 14:54:09', 2, 5, 0, 'O'),
(191, '2020-03-03 14:54:28', 7, 1, 1, 'Inicio de sesion correcto'),
(192, '2020-03-03 14:54:35', 7, 1, 0, 'O'),
(193, '2020-03-03 14:56:59', 7, 1, 0, 'O'),
(194, '2020-03-03 14:58:05', 7, 1, 0, 'O'),
(195, '2020-03-03 14:59:15', 7, 1, 0, 'O'),
(196, '2020-03-03 14:59:23', 2, 5, 0, 'A'),
(197, '2020-03-03 14:59:33', 5, 4, 0, 'A'),
(198, '2020-03-03 14:59:37', 5, 4, 0, 'A'),
(199, '2020-03-03 15:00:15', 2, 5, 0, 'A'),
(200, '2020-03-03 15:01:04', 2, 5, 0, 'A'),
(201, '2020-03-03 15:01:29', 2, 5, 0, 'E'),
(202, '2020-03-03 15:02:01', 2, 5, 0, 'E'),
(203, '2020-03-03 15:03:03', 2, 5, 0, 'A'),
(204, '2020-03-03 15:03:37', 2, 5, 0, 'A'),
(205, '2020-03-03 15:04:14', 2, 5, 0, 'A'),
(206, '2020-03-03 15:04:16', 2, 5, 0, 'A'),
(207, '2020-03-03 15:11:32', 12, 3, 0, 'E'),
(208, '2020-03-03 15:11:53', 12, 3, 0, 'E'),
(209, '2020-03-03 15:11:55', 12, 3, 0, 'E'),
(210, '2020-03-03 15:11:58', 12, 3, 0, 'E'),
(211, '2020-03-03 15:18:12', 12, 3, 0, 'E'),
(212, '2020-03-03 15:18:16', 12, 3, 0, 'E'),
(213, '2020-03-03 15:28:22', 12, 3, 0, 'E'),
(214, '2020-03-03 15:32:06', 5, 4, 1, 'Inicio de sesion correcto'),
(215, '2020-03-03 15:32:16', 5, 4, 0, 'E'),
(216, '2020-03-03 15:33:11', 2, 5, 0, 'A'),
(217, '2020-03-03 15:34:07', 5, 4, 0, 'A'),
(218, '2020-03-03 15:34:32', 5, 4, 0, 'A'),
(219, '2020-03-03 15:34:46', 5, 4, 0, 'A'),
(220, '2020-03-03 15:34:55', 2, 5, 1, 'Inicio de sesion correcto'),
(221, '2020-03-03 15:35:25', 2, 5, 0, 'A'),
(222, '2020-03-03 15:36:14', 2, 5, 0, 'A'),
(223, '2020-03-03 15:36:18', 2, 5, 0, 'A'),
(224, '2020-03-03 15:36:20', 2, 5, 0, 'A'),
(225, '2020-03-03 15:36:22', 2, 5, 0, 'A'),
(226, '2020-03-03 15:36:23', 2, 5, 0, 'A'),
(227, '2020-03-03 15:36:27', 2, 5, 0, 'A'),
(228, '2020-03-03 15:37:15', 2, 5, 0, 'A'),
(229, '2020-03-03 15:37:20', 2, 5, 0, 'A'),
(230, '2020-03-03 15:37:22', 2, 5, 0, 'A'),
(231, '2020-03-03 15:37:29', 2, 5, 0, 'A'),
(232, '2020-03-03 15:37:35', 2, 5, 0, 'A'),
(233, '2020-03-03 15:39:01', 5, 4, 0, 'A'),
(234, '2020-03-03 15:39:03', 5, 4, 0, 'A'),
(235, '2020-03-03 15:39:10', 2, 5, 0, 'A'),
(236, '2020-03-03 15:39:33', 2, 5, 0, 'O'),
(237, '2020-03-03 15:41:16', 2, 5, 0, 'O'),
(238, '2020-03-03 15:41:59', 2, 5, 0, 'O'),
(239, '2020-03-03 15:42:03', 2, 5, 0, 'O'),
(240, '2020-03-03 15:42:55', 2, 5, 0, 'O'),
(241, '2020-03-03 15:43:21', 2, 5, 0, 'A'),
(242, '2020-03-03 15:45:03', 2, 5, 0, 'A'),
(243, '2020-03-03 15:45:09', 2, 5, 0, 'O'),
(244, '2020-03-03 15:45:15', 2, 5, 0, 'A'),
(245, '2020-03-03 15:45:23', 2, 5, 0, 'A'),
(246, '2020-03-03 15:45:26', 2, 5, 0, 'O'),
(247, '2020-03-03 15:45:29', 2, 5, 0, 'A'),
(248, '2020-03-03 15:45:46', 2, 5, 0, 'O'),
(249, '2020-03-03 15:46:20', 2, 5, 0, 'O'),
(250, '2020-03-03 15:46:52', 2, 5, 0, 'A'),
(251, '2020-03-03 15:47:08', 2, 5, 0, 'O'),
(252, '2020-03-03 15:47:31', 2, 5, 0, 'O'),
(253, '2020-03-03 15:48:04', 5, 4, 0, 'E'),
(254, '2020-03-03 15:49:53', 2, 5, 0, 'O'),
(255, '2020-03-03 15:49:55', 2, 5, 0, 'O'),
(256, '2020-03-03 15:49:56', 2, 5, 0, 'O'),
(257, '2020-03-03 15:49:59', 2, 5, 0, 'O'),
(258, '2020-03-03 15:50:37', 2, 5, 0, 'O'),
(259, '2020-03-03 15:50:41', 5, 4, 0, 'E'),
(260, '2020-03-03 15:51:25', 2, 5, 0, 'O'),
(261, '2020-03-03 15:51:40', 2, 5, 0, 'O'),
(262, '2020-03-03 15:52:11', 2, 5, 0, 'O'),
(263, '2020-03-03 15:53:52', 2, 5, 0, 'O'),
(264, '2020-03-03 15:53:56', 2, 5, 0, 'O'),
(265, '2020-03-03 15:54:58', 2, 5, 0, 'O'),
(266, '2020-03-03 16:04:55', 5, 4, 0, 'A'),
(267, '2020-03-03 16:05:07', 2, 5, 0, 'O'),
(268, '2020-03-03 16:05:15', 5, 4, 0, 'A'),
(269, '2020-03-03 16:05:57', 5, 4, 0, 'E'),
(270, '2020-03-03 16:06:12', 2, 5, 0, 'O'),
(271, '2020-03-03 16:06:15', 5, 4, 0, 'E'),
(272, '2020-03-03 16:06:27', 2, 5, 0, 'O'),
(273, '2020-03-03 16:06:33', 5, 4, 0, 'E'),
(274, '2020-03-03 16:07:04', 5, 4, 0, 'E'),
(275, '2020-03-03 16:07:34', 7, 1, 0, 'O'),
(276, '2020-03-03 16:07:56', 2, 5, 0, 'O'),
(277, '2020-03-03 16:08:23', 5, 4, 0, 'A'),
(278, '2020-03-03 16:08:39', 5, 4, 0, 'A'),
(279, '2020-03-03 16:09:01', 2, 5, 1, 'Inicio de sesion correcto'),
(280, '2020-03-03 16:09:10', 2, 5, 0, 'A'),
(281, '2020-03-03 16:09:33', 2, 5, 0, 'O'),
(282, '2020-03-03 16:09:35', 2, 5, 0, 'O'),
(283, '2020-03-03 16:09:50', 2, 5, 0, 'A'),
(284, '2020-03-03 16:10:00', 2, 5, 0, 'O'),
(285, '2020-03-03 16:10:02', 2, 5, 0, 'O'),
(286, '2020-03-03 16:10:11', 5, 4, 0, 'E'),
(287, '2020-03-03 16:10:28', 5, 4, 0, 'A'),
(288, '2020-03-03 16:19:47', 5, 4, 0, 'A'),
(289, '2020-03-03 16:20:00', 5, 4, 0, 'A'),
(290, '2020-03-03 16:20:15', 5, 4, 0, 'E'),
(291, '2020-03-03 16:20:40', 2, 5, 0, 'A'),
(292, '2020-03-03 16:21:05', 7, 1, 1, 'Inicio de sesion correcto'),
(293, '2020-03-03 16:21:14', 7, 1, 0, 'O'),
(294, '2020-03-03 16:21:43', 7, 1, 0, 'A'),
(295, '2020-03-03 16:21:58', 5, 4, 0, 'E'),
(296, '2020-03-03 16:22:05', 2, 5, 0, 'A'),
(297, '2020-03-03 16:22:16', 7, 1, 0, 'O'),
(298, '2020-03-03 16:22:30', 7, 1, 0, 'A'),
(299, '2020-03-03 16:22:46', 5, 4, 0, 'A'),
(300, '2020-03-03 16:22:55', 5, 4, 0, 'E'),
(301, '2020-03-03 16:23:13', 5, 4, 0, 'E'),
(302, '2020-03-03 16:23:24', 2, 5, 0, 'A'),
(303, '2020-03-03 16:23:30', 5, 4, 0, 'A'),
(304, '2020-03-03 16:28:43', 2, 5, 0, 'O'),
(305, '2020-03-03 16:28:57', 5, 4, 0, 'A'),
(306, '2020-03-03 16:29:14', 5, 4, 0, 'A'),
(307, '2020-03-03 16:34:00', 5, 4, 0, 'A'),
(308, '2020-03-03 16:35:13', 5, 4, 1, 'Inicio de sesion correcto'),
(309, '2020-03-03 16:35:28', 5, 4, 0, 'E'),
(310, '2020-03-03 16:35:47', 2, 5, 0, 'O'),
(311, '2020-03-03 16:35:53', 5, 4, 0, 'A'),
(312, '2020-03-03 16:36:05', 2, 5, 0, 'O'),
(313, '2020-03-03 16:36:09', 5, 4, 0, 'A'),
(314, '2020-03-03 16:37:11', 5, 4, 0, 'A'),
(315, '2020-03-03 16:37:21', 2, 5, 0, 'O'),
(316, '2020-03-03 16:37:42', 2, 5, 0, 'A'),
(317, '2020-03-03 16:38:02', 2, 5, 0, 'A'),
(318, '2020-03-03 16:38:16', 2, 5, 0, 'O'),
(319, '2020-03-03 16:38:44', 5, 4, 0, 'E'),
(320, '2020-03-03 16:38:53', 2, 5, 0, 'A'),
(321, '2020-03-03 16:39:38', 2, 5, 0, 'O'),
(322, '2020-03-03 16:39:56', 2, 5, 0, 'O'),
(323, '2020-03-03 16:40:05', 5, 4, 0, 'A'),
(324, '2020-03-03 16:41:54', 2, 5, 0, 'A'),
(325, '2020-03-03 16:42:07', 2, 5, 0, 'A'),
(326, '2020-03-03 16:42:12', 2, 5, 0, 'A'),
(327, '2020-03-03 16:42:17', 2, 5, 0, 'O'),
(328, '2020-03-03 16:42:28', 2, 5, 0, 'O'),
(329, '2020-03-03 16:42:36', 5, 4, 0, 'A'),
(330, '2020-03-03 16:50:15', 2, 5, 0, 'A'),
(331, '2020-03-03 16:50:23', 2, 5, 0, 'A'),
(332, '2020-03-03 16:50:25', 2, 5, 0, 'A'),
(333, '2020-03-03 16:53:38', 2, 5, 0, 'A'),
(334, '2020-03-03 16:53:44', 2, 5, 0, 'A'),
(335, '2020-03-03 16:53:48', 2, 5, 0, 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

CREATE TABLE `mesas` (
  `id_mesa` int(11) NOT NULL,
  `codigo_amigable` varchar(255) COLLATE utf8_spanish2_ci NOT NULL,
  `estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`id_mesa`, `codigo_amigable`, `estado`) VALUES
(1, '3CFCX', 1),
(2, '0ALTO', 0),
(3, 'DGNJ7', 0),
(4, 'ZPMGH', 1),
(5, 'WFANV', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `descripcion` varchar(255) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sectores`
--

CREATE TABLE `sectores` (
  `id_sector` int(11) NOT NULL,
  `descripcion` varchar(255) COLLATE utf8_spanish2_ci NOT NULL,
  `estado` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `sectores`
--

INSERT INTO `sectores` (`id_sector`, `descripcion`, `estado`) VALUES
(1, 'Cocina', 1),
(2, 'Barra de vinos y tragos', 1),
(3, 'Barra de cerveza artesanal', 1),
(4, 'Candy bar', 1),
(5, 'Socio', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre_usuario` varchar(255) COLLATE utf8_spanish2_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(255) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido` varchar(255) COLLATE utf8_spanish2_ci NOT NULL,
  `id_rol` int(11) NOT NULL,
  `estado` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre_usuario`, `password`, `nombre`, `apellido`, `id_rol`, `estado`) VALUES
(1, 'admin', '$2y$10$12KZSLSauYwa..ybzJhwt.rX2SdMa9N90T9m2XUhGWoyDqwhnNm4a', 'admin', 'La Comanda', 5, 0),
(2, 'socio1', '$2y$10$C4jlMZ3ZJ49WmvxKzmPuZOa6Gi7Ua3IwB3RtiQmYJzNdMeoYZkG/m', 'El gato', 'Dumas', 5, 1),
(3, 'socio2', '$2y$10$2tnmlfFXrEArcIixq4rLH.BYiCjXYVgGUKNMIHdZ.EBG5niM7qHk.', 'Paulina', 'Cocina', 5, 1),
(4, 'socio3', '$2y$10$H4ibZ2PoSlAZNaQldXGfFO1BIRkASX8LEv4z4Pi/ko9Q.IeZvYBKe', 'Dona', 'Petrona', 5, 1),
(5, 'mozo1', '$2y$10$ddymaXzvsFNA9jSJi6CkUOi8VUps/fgOzS.KLXgpo94l5HVJ4Fj32', 'Karlos', 'Arguinano', 4, 1),
(6, 'mozo2', '$2y$10$pTH0bAzPmyVfYgEdcQ74/OhN9XpD3tUSLZiN9t5TKYs06ZrHR4iaW', 'Lele', 'Cristobal', 4, 1),
(7, 'cocinero1', '$2y$10$8Q4ExHPAiOfwwrDdytymkejAIPE/NGmwkU/5NeFb5Vei5ay6wvXda', 'Christophe', 'Krywonis', 1, 1),
(8, 'cocinera2', '$2y$10$BWzL2TCrE4SI38NBYqqfg.SykfMzK8YIuJ0F9heB8jQJV1MuwA6Ga', 'Narda', 'Lepez', 1, 1),
(9, 'bartender1', '$2y$10$h2jp1l2UpqEB4NWr0lalj.lvG74WE7pTS6VJG4CqgpS0KEfeAlHwS', 'Donato', 'Disantis', 2, 1),
(10, 'bartender2', '$2y$10$CHDmxN7LLIaOmrGsfR8KA.qML7GSwHJn9KjN4u4U69Gu2kRTbN6/e', 'Maru', 'Botana', 2, 1),
(11, 'cervecero1', '$2y$10$p7yY8c3b5BNMS.tBGQ.oCuaPqjOx1OdbxmGUcDRMev4t5w4tZmYam', 'Martiniano', 'Molina', 3, 2),
(12, 'cervecera2', '$2y$10$TiCDLg1bKcuZMfCacN.eRuZuXxGdWaGo/Ln1QH6Wqu/xC3g1BCl5m', 'Blanca', 'Cotta', 3, 1),
(15, 'admin2', '$2y$10$Hi.fIeYWNQF7iO50bgLFkeLrzpU0zk/g42dZgs4/tHn80lxXEW3u.', 'admin del sistema', 'La comanda', 5, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `articulos`
--
ALTER TABLE `articulos`
  ADD PRIMARY KEY (`id_articulo`);

--
-- Indices de la tabla `cabeceraspedidos`
--
ALTER TABLE `cabeceraspedidos`
  ADD PRIMARY KEY (`id_pedido`);

--
-- Indices de la tabla `comandas`
--
ALTER TABLE `comandas`
  ADD PRIMARY KEY (`idComanda`);

--
-- Indices de la tabla `itemspedidos`
--
ALTER TABLE `itemspedidos`
  ADD PRIMARY KEY (`id_item_pedido`);

--
-- Indices de la tabla `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id_registro`);

--
-- Indices de la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`id_mesa`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`),
  ADD UNIQUE KEY `descripcion` (`descripcion`);

--
-- Indices de la tabla `sectores`
--
ALTER TABLE `sectores`
  ADD PRIMARY KEY (`id_sector`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `nombre_usuario` (`nombre_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `articulos`
--
ALTER TABLE `articulos`
  MODIFY `id_articulo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `cabeceraspedidos`
--
ALTER TABLE `cabeceraspedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `comandas`
--
ALTER TABLE `comandas`
  MODIFY `idComanda` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `itemspedidos`
--
ALTER TABLE `itemspedidos`
  MODIFY `id_item_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `logs`
--
ALTER TABLE `logs`
  MODIFY `id_registro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=336;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id_mesa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sectores`
--
ALTER TABLE `sectores`
  MODIFY `id_sector` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
