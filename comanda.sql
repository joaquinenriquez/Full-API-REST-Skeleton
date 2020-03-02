-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-03-2020 a las 14:37:32
-- Versión del servidor: 10.1.38-MariaDB
-- Versión de PHP: 7.3.4

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
(19, 'Empanadas', 1, '30', '1');

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
  `importe` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `cabeceraspedidos`
--

INSERT INTO `cabeceraspedidos` (`id_pedido`, `id_usuario`, `nombre_cliente`, `estado`, `codigo_amigable`, `id_mesa`, `foto`, `fecha_inicio`, `fecha_fin`, `importe`) VALUES
(1, 3, 'Nombre del cliente asd2', 1, 'HME4C', 3, NULL, '2002-03-20 02:19:00', NULL, '0'),
(2, 3, 'Nombre del cliente asd2', 1, '820TJ', 3, NULL, '2002-03-20 02:19:00', NULL, '0'),
(3, 3, 'Nombre del cliente asd2', 1, 'NSN36', 3, NULL, '2002-03-20 02:23:00', NULL, '0'),
(4, 3, 'Nombre del cliente asd2', 1, 'S6W5F', 3, NULL, '2002-03-20 02:25:00', NULL, '0'),
(5, 3, 'Nombre del cliente asd2', 1, 'U93EI', 3, NULL, '2002-03-20 02:25:00', NULL, '0'),
(6, 3, 'Nombre del cliente asd2', 2, 'YFQ06', 3, NULL, '2002-03-20 02:33:00', NULL, '0'),
(7, 5, 'Nombre del cliente asd2', 2, 'URDIV', 2, NULL, '2002-03-20 03:53:00', NULL, '0');

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
(1, 5, '2020-03-02 02:25:54', '2020-03-02 02:26:00', '2020-03-02 02:27:00', 9, 1, 100, 3, 3, 4),
(2, 6, '2020-03-02 02:34:10', '2020-03-02 02:37:00', '2020-03-02 02:37:00', 9, 1, 100, 3, 3, 4),
(3, 6, '2020-03-02 02:35:49', NULL, NULL, 9, 1, NULL, 3, NULL, 1),
(4, 6, '2020-03-02 02:54:07', NULL, NULL, 9, 1, NULL, 3, NULL, 1),
(5, 6, '2020-03-02 03:00:13', NULL, NULL, 9, 1, NULL, 3, NULL, 1),
(6, 6, '2020-03-02 03:00:15', NULL, NULL, 9, 1, NULL, 3, NULL, 1),
(7, 6, '2020-03-02 03:00:44', NULL, NULL, 9, 1, NULL, 3, NULL, 1),
(8, 6, '2020-03-02 03:01:27', NULL, NULL, 9, 1, NULL, 3, NULL, 1),
(9, 6, '2020-03-02 03:04:56', '2020-03-02 04:22:00', NULL, 9, 1, 10, 3, 5, 2),
(10, 6, '2020-03-02 03:05:13', '2020-03-02 04:21:00', NULL, 9, 1, 5, 3, 5, 2),
(11, 7, '2020-03-02 03:54:29', '2020-03-02 05:35:00', NULL, 9, 1, 5, 3, 5, 2),
(12, 7, '2020-03-02 05:37:47', '2020-03-02 05:38:00', '2020-03-02 05:48:00', 9, 1, 10, 5, 5, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs`
--

CREATE TABLE `logs` (
  `id_registro` int(11) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_sector` int(11) NOT NULL,
  `accion` varchar(255) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `logs`
--

INSERT INTO `logs` (`id_registro`, `fecha_hora`, `id_usuario`, `id_sector`, `accion`) VALUES
(1, '2020-03-02 10:09:37', 5, 4, 'asd'),
(2, '2020-03-02 10:11:09', 5, 4, 'asd'),
(3, '2020-03-02 10:15:24', 5, 4, 'asd'),
(4, '2020-03-02 10:15:57', 5, 4, 'asd'),
(5, '2020-03-02 10:16:57', 5, 4, 'asd'),
(6, '2020-03-02 10:20:16', 5, 4, 'asd'),
(7, '2020-03-02 10:20:52', 5, 4, 'asd'),
(8, '2020-03-02 10:35:50', 5, 4, 'asd');

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
(1, 'LMZYU', 1),
(2, 'X6BXJ', 4),
(3, 'SP2LN', 5);

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
(1, 'admin', '$2y$10$c8vhoeyfibgNJZaATBTHRuI8n3L.W2DZKK4R9TBJTvxXXKrizewtG', 'admin del sistema', 'La comanda', 5, 1),
(2, 'socio1', '$2y$10$C4jlMZ3ZJ49WmvxKzmPuZOa6Gi7Ua3IwB3RtiQmYJzNdMeoYZkG/m', 'El gato', 'Dumas', 5, 1),
(3, 'socio2', '$2y$10$2tnmlfFXrEArcIixq4rLH.BYiCjXYVgGUKNMIHdZ.EBG5niM7qHk.', 'Paulina', 'Cocina', 5, 1),
(4, 'socio3', '$2y$10$H4ibZ2PoSlAZNaQldXGfFO1BIRkASX8LEv4z4Pi/ko9Q.IeZvYBKe', 'Dona', 'Petrona', 5, 1),
(5, 'mozo1', '$2y$10$ddymaXzvsFNA9jSJi6CkUOi8VUps/fgOzS.KLXgpo94l5HVJ4Fj32', 'Karlos', 'Arguinano', 4, 1),
(6, 'mozo2', '$2y$10$pTH0bAzPmyVfYgEdcQ74/OhN9XpD3tUSLZiN9t5TKYs06ZrHR4iaW', 'Lele', 'Cristobal', 4, 1),
(7, 'cocinero1', '$2y$10$8Q4ExHPAiOfwwrDdytymkejAIPE/NGmwkU/5NeFb5Vei5ay6wvXda', 'Christophe', 'Krywonis', 1, 1),
(8, 'cocinera2', '$2y$10$BWzL2TCrE4SI38NBYqqfg.SykfMzK8YIuJ0F9heB8jQJV1MuwA6Ga', 'Narda', 'Lepez', 1, 1),
(9, 'bartender1', '$2y$10$h2jp1l2UpqEB4NWr0lalj.lvG74WE7pTS6VJG4CqgpS0KEfeAlHwS', 'Donato', 'Disantis', 2, 1),
(10, 'bartender2', '$2y$10$CHDmxN7LLIaOmrGsfR8KA.qML7GSwHJn9KjN4u4U69Gu2kRTbN6/e', 'Maru', 'Botana', 2, 1),
(11, 'cervecero1', '$2y$10$p7yY8c3b5BNMS.tBGQ.oCuaPqjOx1OdbxmGUcDRMev4t5w4tZmYam', 'Martiniano', 'Molina', 3, 1),
(12, 'cervecera2', '$2y$10$TiCDLg1bKcuZMfCacN.eRuZuXxGdWaGo/Ln1QH6Wqu/xC3g1BCl5m', 'Blanca', 'Cotta', 3, 1);

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
  MODIFY `id_articulo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `cabeceraspedidos`
--
ALTER TABLE `cabeceraspedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `comandas`
--
ALTER TABLE `comandas`
  MODIFY `idComanda` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `itemspedidos`
--
ALTER TABLE `itemspedidos`
  MODIFY `id_item_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `logs`
--
ALTER TABLE `logs`
  MODIFY `id_registro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id_mesa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
