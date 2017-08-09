-- CONEXION FEDERADA CONNECTION='mysql://campusvirtualure:oZ{2(Vpks=3D@localhost:3306/campusvi_test0/TABLE

-- MySQL dump 10.16  Distrib 10.1.13-MariaDB, for Win32 (AMD64)
--
-- Host: localhost    Database: test
-- ------------------------------------------------------
-- Server version 10.1.13-MariaDB


--
-- Table structure for table `am_calificaciones_finales`
--

DROP TABLE IF EXISTS `am_calificaciones_finales`;
CREATE TABLE `am_calificaciones_finales` (
  `per_aca` bigint(20) NOT NULL,
  `codigo_curso` bigint(20) NOT NULL,
  `nombre_curso` text NOT NULL,
  `codigo_estudiante` bigint(20) NOT NULL,
  `nota_max` double NOT NULL,
  `nota_obtenida` double NOT NULL,
  `nota_final` double NOT NULL,
  PRIMARY KEY (`per_aca`,`codigo_curso`,`codigo_estudiante`)
) ENGINE=FEDERATED DEFAULT CHARSET=utf8 CONNECTION='mysql://campusvirtualure:oZ{2(Vpks=3D@localhost:3306/campusvi_test/am_calificaciones_finales';

--
-- Dumping data for table `am_calificaciones_finales`
--

LOCK TABLES `am_calificaciones_finales` WRITE;
UNLOCK TABLES;

--
-- Table structure for table `am_campus`
--

DROP TABLE IF EXISTS `am_campus`;
CREATE TABLE `am_campus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_campus` varchar(50) NOT NULL,
  `url_master` varchar(100) NOT NULL,
  `name_db` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=FEDERATED DEFAULT CHARSET=utf8 CONNECTION='mysql://campusvirtualure:oZ{2(Vpks=3D@localhost:3306/campusvi_test/am_campus';

--
-- Dumping data for table `am_campus`
--

LOCK TABLES `am_campus` WRITE;
UNLOCK TABLES;

--
-- Table structure for table `am_cursos`
--

DROP TABLE IF EXISTS `am_cursos`;
CREATE TABLE `am_cursos` (
  `id_curso` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_curso` bigint(20) NOT NULL,
  `n_campus` int(11) NOT NULL DEFAULT '0',
  `per_aca` bigint(20) NOT NULL DEFAULT '0',
  `nombre_curso` text NOT NULL,
  `nombre_corto` varchar(15) NOT NULL,
  `tipo` int(11) NOT NULL DEFAULT '1',
  `id_categoria` int(11) NOT NULL DEFAULT '1',
  `moodle_course_id` int(11) DEFAULT NULL,
  `moodle_contex_id` int(11) DEFAULT NULL,
  `moodle_enrol_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_curso`),
  UNIQUE KEY `codigo_curso` (`codigo_curso`,`per_aca`,`n_campus`)
) ENGINE=FEDERATED DEFAULT CHARSET=utf8 CONNECTION='mysql://campusvirtualure:oZ{2(Vpks=3D@localhost:3306/campusvi_test/am_cursos';

--
-- Dumping data for table `am_cursos`
--

LOCK TABLES `am_cursos` WRITE;
UNLOCK TABLES;

--
-- Table structure for table `am_estados`
--

DROP TABLE IF EXISTS `am_estados`;
CREATE TABLE `am_estados` (
  `id_estado` int(11) NOT NULL AUTO_INCREMENT,
  `estado` char(1) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  PRIMARY KEY (`id_estado`),
  KEY `estado` (`estado`)
) ENGINE=FEDERATED DEFAULT CHARSET=utf8 CONNECTION='mysql://campusvirtualure:oZ{2(Vpks=3D@localhost:3306/campusvi_test/am_estados';

--
-- Dumping data for table `am_estados`
--

LOCK TABLES `am_estados` WRITE;
UNLOCK TABLES;

--
-- Table structure for table `am_facultades`
--

DROP TABLE IF EXISTS `am_facultades`;
CREATE TABLE `am_facultades` (
  `id_facultad` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_facultad` text NOT NULL,
  PRIMARY KEY (`id_facultad`)
) ENGINE=FEDERATED DEFAULT CHARSET=utf8 CONNECTION='mysql://campusvirtualure:oZ{2(Vpks=3D@localhost:3306/campusvi_test/am_facultades';

--
-- Dumping data for table `am_facultades`
--

LOCK TABLES `am_facultades` WRITE;
UNLOCK TABLES;

--
-- Table structure for table `am_genero`
--

DROP TABLE IF EXISTS `am_genero`;
CREATE TABLE `am_genero` (
  `id_genero` int(11) NOT NULL AUTO_INCREMENT,
  `genero` text NOT NULL,
  PRIMARY KEY (`id_genero`)
) ENGINE=FEDERATED DEFAULT CHARSET=utf8 CONNECTION='mysql://campusvirtualure:oZ{2(Vpks=3D@localhost:3306/campusvi_test/am_genero';

--
-- Dumping data for table `am_genero`
--

LOCK TABLES `am_genero` WRITE;
UNLOCK TABLES;

--
-- Table structure for table `am_interfaz`
--

DROP TABLE IF EXISTS `am_interfaz`;
CREATE TABLE `am_interfaz` (
  `id_interfaz` int(11) NOT NULL AUTO_INCREMENT,
  `fondo` varchar(15) DEFAULT NULL,
  `menu` varchar(15) DEFAULT NULL,
  `header` varchar(15) DEFAULT NULL,
  `hover_menu` varchar(15) DEFAULT NULL,
  `fuente` varchar(100) DEFAULT NULL,
  `color_fuente` varchar(15) NOT NULL,
  PRIMARY KEY (`id_interfaz`)
) ENGINE=FEDERATED DEFAULT CHARSET=utf8 CONNECTION='mysql://campusvirtualure:oZ{2(Vpks=3D@localhost:3306/campusvi_test/am_interfaz';

--
-- Dumping data for table `am_interfaz`
--

LOCK TABLES `am_interfaz` WRITE;
UNLOCK TABLES;

--
-- Table structure for table `am_notificaciones`
--

DROP TABLE IF EXISTS `am_notificaciones`;
CREATE TABLE `am_notificaciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titular` text NOT NULL,
  `imagen` varchar(20) NOT NULL DEFAULT 'noticia.png',
  `fecha_publicacion` date NOT NULL,
  `fecha_final` date NOT NULL,
  `enlace` text NOT NULL,
  `resumen` varchar(200) DEFAULT NULL,
  `noticia` text,
  `importancia` int(1) NOT NULL DEFAULT '0',
  `per_aca` bigint(20) DEFAULT NULL,
  `id_facultad` int(11) DEFAULT NULL,
  `id_programa` int(11) DEFAULT NULL,
  `codigo_curso` int(11) DEFAULT NULL,
  `id_genero` int(11) DEFAULT NULL,
  `idnumber` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=FEDERATED DEFAULT CHARSET=utf8 CONNECTION='mysql://campusvirtualure:oZ{2(Vpks=3D@localhost:3306/campusvi_test/am_notificaciones';

--
-- Dumping data for table `am_notificaciones`
--

LOCK TABLES `am_notificaciones` WRITE;
UNLOCK TABLES;

--
-- Table structure for table `am_numero_campus`
--

DROP TABLE IF EXISTS `am_numero_campus`;
CREATE TABLE `am_numero_campus` (
  `id_n_campus` int(11) NOT NULL AUTO_INCREMENT,
  `n_campus` int(11) NOT NULL,
  `per_aca` int(11) NOT NULL,
  `codigo_curso` int(11) NOT NULL,
  PRIMARY KEY (`id_n_campus`),
  UNIQUE KEY `n_campus` (`n_campus`,`per_aca`,`codigo_curso`)
) ENGINE=FEDERATED DEFAULT CHARSET=utf8 CONNECTION='mysql://campusvirtualure:oZ{2(Vpks=3D@localhost:3306/campusvi_test/am_numero_campus';

--
-- Dumping data for table `am_numero_campus`
--

LOCK TABLES `am_numero_campus` WRITE;
UNLOCK TABLES;

--
-- Table structure for table `am_per_aca`
--

DROP TABLE IF EXISTS `am_per_aca`;
CREATE TABLE `am_per_aca` (
  `id_per_aca` int(11) NOT NULL AUTO_INCREMENT,
  `id` bigint(11) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_final` date NOT NULL,
  `estado` varchar(5) NOT NULL,
  PRIMARY KEY (`id_per_aca`),
  UNIQUE KEY `id` (`id`)
) ENGINE=FEDERATED DEFAULT CHARSET=utf8 CONNECTION='mysql://campusvirtualure:oZ{2(Vpks=3D@localhost:3306/campusvi_test/am_per_aca';

--
-- Dumping data for table `am_per_aca`
--

LOCK TABLES `am_per_aca` WRITE;
UNLOCK TABLES;

--
-- Table structure for table `am_programa`
--

DROP TABLE IF EXISTS `am_programa`;
CREATE TABLE `am_programa` (
  `id_programa` int(11) NOT NULL AUTO_INCREMENT,
  `id_facultad` int(11) NOT NULL,
  `programa` text NOT NULL,
  PRIMARY KEY (`id_programa`),
  KEY `facultad` (`id_facultad`)
) ENGINE=FEDERATED DEFAULT CHARSET=utf8 CONNECTION='mysql://campusvirtualure:oZ{2(Vpks=3D@localhost:3306/campusvi_test/am_programa';

--
-- Dumping data for table `am_programa`
--

LOCK TABLES `am_programa` WRITE;
UNLOCK TABLES;

--
-- Table structure for table `am_tipo_curso`
--

DROP TABLE IF EXISTS `am_tipo_curso`;
CREATE TABLE `am_tipo_curso` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` int(11) NOT NULL,
  `modulo` int(11) NOT NULL,
  `semanas` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=FEDERATED DEFAULT CHARSET=utf8 CONNECTION='mysql://campusvirtualure:oZ{2(Vpks=3D@localhost:3306/campusvi_test/am_tipo_curso';

--
-- Dumping data for table `am_tipo_curso`
--

LOCK TABLES `am_tipo_curso` WRITE;
UNLOCK TABLES;

--
-- Table structure for table `am_usuario`
--

DROP TABLE IF EXISTS `am_usuario`;
CREATE TABLE `am_usuario` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `idnumber` bigint(20) NOT NULL,
  `firstname` varchar(100) NOT NULL DEFAULT '',
  `lastname` varchar(100) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `city` varchar(120) NOT NULL DEFAULT '',
  `lang` varchar(30) NOT NULL DEFAULT 'es',
  `calendartype` varchar(255) NOT NULL DEFAULT 'gregorian',
  `n_identidad` bigint(20) NOT NULL,
  `id_genero` int(11) NOT NULL,
  `id_programa` int(11) NOT NULL,
  `id_moodle` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `idnumber` (`idnumber`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `n_identidad` (`n_identidad`),
  KEY `genero` (`id_genero`),
  KEY `programa` (`id_programa`)
) ENGINE=FEDERATED DEFAULT CHARSET=utf8 CONNECTION='mysql://campusvirtualure:oZ{2(Vpks=3D@localhost:3306/campusvi_test/am_usuario';

--
-- Dumping data for table `am_usuario`
--

LOCK TABLES `am_usuario` WRITE;
UNLOCK TABLES;

--
-- Table structure for table `am_usuario_curso`
--

DROP TABLE IF EXISTS `am_usuario_curso`;
CREATE TABLE `am_usuario_curso` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_curso` int(11) NOT NULL,
  `n_campus` int(11) NOT NULL DEFAULT '0',
  `per_aca` int(11) NOT NULL,
  `idnumber` int(11) NOT NULL,
  `moodle_rol_id` int(11) NOT NULL,
  `estado` char(1) NOT NULL,
  `grupo` int(11) NOT NULL DEFAULT '0',
  `fecha` int(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `llave_unica` (`codigo_curso`,`per_aca`,`idnumber`,`moodle_rol_id`,`estado`,`grupo`),
  KEY `n_campus` (`n_campus`),
  KEY `grupo` (`grupo`),
  KEY `idnumber` (`idnumber`),
  KEY `per_aca` (`per_aca`),
  KEY `codigo_curso` (`codigo_curso`)
) ENGINE=FEDERATED DEFAULT CHARSET=utf8 CONNECTION='mysql://campusvirtualure:oZ{2(Vpks=3D@localhost:3306/campusvi_test/am_usuario_curso';

--
-- Dumping data for table `am_usuario_curso`
--

LOCK TABLES `am_usuario_curso` WRITE;
UNLOCK TABLES;


-- Dump completed on 2017-06-15 16:26:01

