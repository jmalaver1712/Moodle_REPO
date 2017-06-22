-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 22-06-2017 a las 10:05:01
-- Versión del servidor: 5.6.35
-- Versión de PHP: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `campusvi_md_aulas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cap_evaluacion`
--

DROP TABLE IF EXISTS `cap_evaluacion`;
CREATE TABLE `cap_evaluacion` (
  `id_usuario` int(5) NOT NULL,
  `docente` varchar(100) NOT NULL,
  `mundo1` int(1) NOT NULL,
  `mundo2` int(1) NOT NULL,
  `mundo3` int(1) NOT NULL,
  `mundo4` int(1) NOT NULL,
  `mundo5` int(1) NOT NULL,
  `observacion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cap_nivel_user`
--

DROP TABLE IF EXISTS `cap_nivel_user`;
CREATE TABLE `cap_nivel_user` (
  `id_cap_nivel` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nivel` varchar(50) NOT NULL,
  `calificacion` int(11) NOT NULL,
  `docente` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cap_preguntas`
--

DROP TABLE IF EXISTS `cap_preguntas`;
CREATE TABLE `cap_preguntas` (
  `id_pregunta` int(2) NOT NULL,
  `nivel` varchar(12) DEFAULT NULL,
  `pregunta` varchar(246) DEFAULT NULL,
  `opcion_1` varchar(103) DEFAULT NULL,
  `opcion_2` varchar(134) DEFAULT NULL,
  `opcion_3` varchar(218) DEFAULT NULL,
  `opcion_4` varchar(143) DEFAULT NULL,
  `respuesta` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cap_usuarios`
--

DROP TABLE IF EXISTS `cap_usuarios`;
CREATE TABLE `cap_usuarios` (
  `id_docente` varchar(50) NOT NULL,
  `nombre_docente` varchar(100) NOT NULL,
  `nivel_docente` int(1) NOT NULL DEFAULT '1',
  `correo_docente` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cap_evaluacion`
--
ALTER TABLE `cap_evaluacion`
  ADD PRIMARY KEY (`id_usuario`);

--
-- Indices de la tabla `cap_nivel_user`
--
ALTER TABLE `cap_nivel_user`
  ADD PRIMARY KEY (`id_cap_nivel`);

--
-- Indices de la tabla `cap_preguntas`
--
ALTER TABLE `cap_preguntas`
  ADD PRIMARY KEY (`id_pregunta`);

--
-- Indices de la tabla `cap_usuarios`
--
ALTER TABLE `cap_usuarios`
  ADD PRIMARY KEY (`id_docente`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cap_nivel_user`
--
ALTER TABLE `cap_nivel_user`
  MODIFY `id_cap_nivel` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
