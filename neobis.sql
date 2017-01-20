-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-01-2017 a las 12:38:39
-- Versión del servidor: 10.1.19-MariaDB
-- Versión de PHP: 7.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `neobis`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `campos`
--

CREATE TABLE `campos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `descripcion` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `campos`
--

INSERT INTO `campos` (`id`, `nombre`, `descripcion`) VALUES
(9, 'libelle_charge', 'Nombre del plan tarifario'),
(10, 'montant_charge', 'Monto del plan tarifario'),
(11, 'm_total', 'Valor total de la serie o linea'),
(13, 'm_remises_nondefini', 'Monto de descuentos'),
(14, 'm_autre_nondefini', 'Monto de otros servicios'),
(16, 'm_hors_voix', 'Monto de voz No Incluido Plan Tarifario'),
(17, 'm_hors_data', 'Monto de datos No Incluido Plan Tarifario'),
(18, 'noappel', 'Numero de serie o linea'),
(19, 'centrefacturation', 'En Adessa Enlaces es Operador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `campos_base`
--

CREATE TABLE `campos_base` (
  `id` int(11) NOT NULL,
  `tipo_proveedores_id` int(11) NOT NULL,
  `campos_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `campos_base`
--

INSERT INTO `campos_base` (`id`, `tipo_proveedores_id`, `campos_id`) VALUES
(9, 1, 9),
(10, 1, 10),
(11, 1, 11),
(18, 1, 18),
(19, 2, 9),
(20, 2, 10),
(21, 2, 11),
(22, 2, 18),
(23, 2, 19);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ceco`
--

CREATE TABLE `ceco` (
  `id` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `ceco`
--

INSERT INTO `ceco` (`id`, `nombre`) VALUES
(1, 'PC'),
(2, '420901002'),
(3, 'Soporte'),
(4, 'Enlaces.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombre`) VALUES
(1, 'Falabella'),
(2, 'Walmart');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente_proveedor`
--

CREATE TABLE `cliente_proveedor` (
  `id` int(11) NOT NULL,
  `proveedores_id` int(11) NOT NULL,
  `clientes_id` int(11) NOT NULL,
  `tipo_proveedores_id` int(11) NOT NULL,
  `ceco_id` int(11) NOT NULL,
  `nomcompte_id` int(11) NOT NULL,
  `codedevise_id` int(11) NOT NULL,
  `filestimated` int(11) DEFAULT NULL,
  `col_extra_1` varchar(45) DEFAULT NULL,
  `col_extra_2` varchar(45) DEFAULT NULL,
  `col_extra_3` varchar(45) DEFAULT NULL,
  `col_extra_4` varchar(45) DEFAULT NULL,
  `col_extra_5` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `cliente_proveedor`
--

INSERT INTO `cliente_proveedor` (`id`, `proveedores_id`, `clientes_id`, `tipo_proveedores_id`, `ceco_id`, `nomcompte_id`, `codedevise_id`, `filestimated`, `col_extra_1`, `col_extra_2`, `col_extra_3`, `col_extra_4`, `col_extra_5`) VALUES
(1, 2, 1, 1, 1, 1, 1, 50, NULL, NULL, NULL, NULL, NULL),
(4, 6, 2, 1, 2, 1, 3, 0, NULL, NULL, NULL, NULL, NULL),
(5, 7, 1, 1, 3, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 8, 1, 2, 4, 3, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 9, 1, 1, 3, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(8, 10, 1, 1, 1, 1, 3, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `codedevise`
--

CREATE TABLE `codedevise` (
  `id` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `codedevise`
--

INSERT INTO `codedevise` (`id`, `nombre`) VALUES
(1, 'UF'),
(2, 'CLP'),
(3, 'USD');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `item`
--

CREATE TABLE `item` (
  `id` int(11) NOT NULL,
  `moisfacturation` varchar(45) NOT NULL,
  `datefacturation` varchar(45) NOT NULL,
  `datefacture1` varchar(45) NOT NULL,
  `datefacture2` varchar(45) NOT NULL,
  `codedevise` varchar(45) NOT NULL,
  `idoperateur` varchar(45) NOT NULL,
  `nomcompte` varchar(45) NOT NULL,
  `centrefacturation` varchar(45) NOT NULL,
  `nofacture` varchar(100) NOT NULL,
  `m_total_facture` varchar(45) DEFAULT NULL,
  `m_total_ttc_facture` varchar(45) DEFAULT NULL,
  `noappel` varchar(45) NOT NULL,
  `libelle_charge` varchar(45) NOT NULL,
  `montant_charge` varchar(45) NOT NULL,
  `m_total` varchar(45) NOT NULL,
  `m_hors_voix` varchar(45) DEFAULT NULL,
  `m_hors_data` varchar(45) DEFAULT NULL,
  `m_remises_nondefini` varchar(45) DEFAULT NULL,
  `m_autre_nondefini` varchar(45) DEFAULT NULL,
  `m_tva` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nomcompte`
--

CREATE TABLE `nomcompte` (
  `id` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `nomcompte`
--

INSERT INTO `nomcompte` (`id`, `nombre`) VALUES
(1, 'PC'),
(2, 'Telefonia.IP'),
(3, 'Enlaces');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `idoperateur` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id`, `nombre`, `idoperateur`) VALUES
(2, 'Adessa PC', '272'),
(6, 'HP', '282'),
(7, 'Adessa IP', '437'),
(8, 'Adessa Enlaces', '438'),
(9, 'Quintec Soporte', '271'),
(10, 'Quintec Arriendo', '271');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_proveedores`
--

CREATE TABLE `tipo_proveedores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tipo_proveedores`
--

INSERT INTO `tipo_proveedores` (`id`, `nombre`) VALUES
(1, 'PC'),
(2, 'Adessa Enlaces');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `campos`
--
ALTER TABLE `campos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `campos_base`
--
ALTER TABLE `campos_base`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tipo_proveedores_has_campos_campos1_idx` (`campos_id`),
  ADD KEY `fk_tipo_proveedores_has_campos_tipo_proveedores1_idx` (`tipo_proveedores_id`);

--
-- Indices de la tabla `ceco`
--
ALTER TABLE `ceco`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cliente_proveedor`
--
ALTER TABLE `cliente_proveedor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_proveedores_has_clientes_clientes1_idx` (`clientes_id`),
  ADD KEY `fk_proveedores_has_clientes_proveedores_idx` (`proveedores_id`),
  ADD KEY `fk_proveedores_has_clientes_tipo_proveedores1_idx` (`tipo_proveedores_id`);

--
-- Indices de la tabla `codedevise`
--
ALTER TABLE `codedevise`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `nomcompte`
--
ALTER TABLE `nomcompte`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_proveedores`
--
ALTER TABLE `tipo_proveedores`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `campos`
--
ALTER TABLE `campos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT de la tabla `campos_base`
--
ALTER TABLE `campos_base`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT de la tabla `ceco`
--
ALTER TABLE `ceco`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `cliente_proveedor`
--
ALTER TABLE `cliente_proveedor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT de la tabla `codedevise`
--
ALTER TABLE `codedevise`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `item`
--
ALTER TABLE `item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `nomcompte`
--
ALTER TABLE `nomcompte`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT de la tabla `tipo_proveedores`
--
ALTER TABLE `tipo_proveedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `campos_base`
--
ALTER TABLE `campos_base`
  ADD CONSTRAINT `fk_tipo_proveedores_has_campos_campos1` FOREIGN KEY (`campos_id`) REFERENCES `campos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tipo_proveedores_has_campos_tipo_proveedores1` FOREIGN KEY (`tipo_proveedores_id`) REFERENCES `tipo_proveedores` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `cliente_proveedor`
--
ALTER TABLE `cliente_proveedor`
  ADD CONSTRAINT `fk_proveedores_has_clientes_clientes1` FOREIGN KEY (`clientes_id`) REFERENCES `clientes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_proveedores_has_clientes_proveedores` FOREIGN KEY (`proveedores_id`) REFERENCES `proveedores` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_proveedores_has_clientes_tipo_proveedores1` FOREIGN KEY (`tipo_proveedores_id`) REFERENCES `tipo_proveedores` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
