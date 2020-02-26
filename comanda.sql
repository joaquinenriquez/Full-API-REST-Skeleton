-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-02-2020 a las 14:43:32
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
  `fecha_fin` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `cabeceraspedidos`
--

INSERT INTO `cabeceraspedidos` (`id_pedido`, `id_usuario`, `nombre_cliente`, `estado`, `codigo_amigable`, `id_mesa`, `foto`, `fecha_inicio`, `fecha_fin`) VALUES
(1, 18, 'dfsfsd', 1, '2KKLO', 1, NULL, '2026-02-20 02:14:00', NULL);

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
  `fecha_inicio` datetime DEFAULT NULL,
  `fecha_fin` datetime DEFAULT NULL,
  `id_articulo` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `tiempo_estimado` time DEFAULT NULL,
  `id_usuario_creador` int(11) NOT NULL,
  `id_usuario_asignado` int(11) DEFAULT NULL,
  `estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `itemspedidos`
--

INSERT INTO `itemspedidos` (`id_item_pedido`, `id_pedido`, `fecha_inicio`, `fecha_fin`, `id_articulo`, `cantidad`, `tiempo_estimado`, `id_usuario_creador`, `id_usuario_asignado`, `estado`) VALUES
(1, 1, NULL, NULL, 3, 8, NULL, 18, NULL, 1),
(2, 1, NULL, NULL, 3, 9, NULL, 18, NULL, 1),
(3, 1, NULL, NULL, 1, 0, NULL, 18, NULL, 1),
(4, 1, NULL, NULL, 1, 0, NULL, 18, NULL, 1),
(5, 1, NULL, NULL, 1, 0, NULL, 18, NULL, 1),
(6, 1, NULL, NULL, 1, 1, NULL, 18, NULL, 1),
(7, 1, NULL, NULL, 11, 5, NULL, 18, NULL, 2),
(8, 1, NULL, NULL, 11, 5, NULL, 18, NULL, 1),
(9, 1, NULL, NULL, 11, 5, NULL, 18, NULL, 1),
(10, 1, NULL, NULL, 11, 5, NULL, 18, NULL, 1),
(11, 1, NULL, NULL, 11, 5, NULL, 18, NULL, 1),
(12, 1, NULL, NULL, 11, 5, NULL, 18, NULL, 1),
(13, 1, NULL, NULL, 11, 5, NULL, 18, NULL, 2),
(14, 1, NULL, NULL, 11, 5, NULL, 18, 17, 2),
(15, 1, NULL, NULL, 11, 5, NULL, 18, 17, 2),
(16, 1, NULL, NULL, 11, 5, '00:00:01', 18, 17, 2),
(17, 1, NULL, NULL, 11, 5, '00:00:10', 18, 17, 2),
(18, 1, NULL, NULL, 11, 5, '00:01:00', 18, 17, 2),
(19, 1, NULL, NULL, 11, 5, '00:00:30', 18, 17, 2),
(20, 1, NULL, NULL, 11, 5, '00:30:00', 18, 17, 2),
(21, 1, '2026-02-20 05:12:00', NULL, 11, 5, '00:30:00', 18, 17, 2),
(22, 1, '2026-02-20 05:13:00', NULL, 11, 5, '02:00:00', 18, 17, 2),
(23, 1, '2026-02-20 05:14:00', NULL, 11, 5, '02:00:00', 18, 17, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

CREATE TABLE `mesas` (
  `id_mesa` int(11) NOT NULL,
  `nro_mesa` int(11) NOT NULL,
  `estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`id_mesa`, `nro_mesa`, `estado`) VALUES
(1, 1, 2);

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
(1, 'ernestor', '', '', '', 1, 1),
(6, 'ernestor1', 'asd123', 'asda', 'asda', 1, 1),
(7, 'ernestor3', 'asd123', 'asda', 'asda', 2, 1),
(8, 'ernestor4', 'asd123', 'asda', 'asda', 1, 1),
(9, 'ernestor5', '$2y$10$ue5/pdUN5UU88tSE/1ssU.d/0Htk5DF8SPjLuIkh07.D9D7kMcX7i', 'asda', 'asda', 1, 1),
(10, 'usuario', '$2y$10$J1ujpFcpDqH7oXFSrg3ePOPse6pCtmWiI504OUVxnoXBOwUe.LR6q', 'asda', 'asda', 1, 1),
(11, 'usuario2', '$2y$10$sdfPTEph1kMGakJ7786cO.MMVxIhBMYrfa6YCDa5X6CiYSgji68UG', 'asda', 'asda', 1, 1),
(12, 'usuario3', '$2y$10$6XmbDQ/2xXA7jI/gctVdT.8op0MZKhv7l.j7LyvR7CEZxnuIrTW5i', 'asda', 'asda', 1, 1),
(13, 'usuario4', '$2y$10$dV8vPgj7zDzyTuZFeY.rk.qH9kYOyciINupBveYHePT3lPPGTpIRi', 'asda', 'asda', 1, 1),
(14, 'usuario5', '$2y$10$mqtUFBHPZ3vMajVUMGW01uDde/pwVUd5tR3SJDRWz4wHSC2bqCOdi', 'asda', 'asda', 1, 1),
(15, 'usuario6', '$2y$10$eH2gG687xP0Q3RZd7iZI3u1urbyz1cnJxKzFQLcQqlJsMj3kpcfPe', 'asda', 'asda', 1, 1),
(16, 'usuario7', '$2y$10$hoXZ9Jb7KqCIPNs02EJYt./dj/yBuw7RIQBjr0q3gUXznoN/IRJwu', 'asda', 'asda', 1, 1),
(17, 'BARTENDER_1', '$2y$10$K1.bGLU2wnKtaMGHxJ3NUuQ1gYTUrJHwYbDDFeVNLHvLuZ8Y9HldO', 'Juan', 'Perez', 2, 1),
(18, 'COCINERO_1', '$2y$10$BWfAk1vQxmoe3g93LXZG6OLEXhQADNJw58fm3iv.u4i7ZwRmjKW4G', 'Juan', 'Cocinero', 4, 1),
(19, 'SOCIO_1', '$2y$10$qfxRhnD3CGsrVG/N1tLtgelh.cv8mYwVphZMQhGkZs7H4ilS0mjAS', 'Juan', 'SOCIO', 5, 1),
(20, 'CERVECERO_1', '$2y$10$C8ZyNMqygZQeouW1DUW9zenKK0bQNKt2MwwnyOQP7uZ1DBU0yiEbq', 'Juan', 'APELLIDO CERVECERO', 3, 1),
(21, 'CERVECERO_2', '$2y$10$vgul1it5ClLsJ6k4VpScFubbLmLT4CN/yBMOwv/qgC92ShCUy3Ngi', 'Juan', 'APELLIDO CERVECERO', 3, 1);

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
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `comandas`
--
ALTER TABLE `comandas`
  MODIFY `idComanda` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `itemspedidos`
--
ALTER TABLE `itemspedidos`
  MODIFY `id_item_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id_mesa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
