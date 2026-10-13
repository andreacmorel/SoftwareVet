-- MySQL dump 10.13  Distrib 8.0.36, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: vetsys
-- ------------------------------------------------------
-- Server version	8.4.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `auditoria`
--

DROP TABLE IF EXISTS `auditoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auditoria` (
  `id_auditoria` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `modulo` varchar(50) NOT NULL,
  `accion` varchar(30) NOT NULL,
  `id_registro` int NOT NULL,
  `datos_anteriores` text,
  `datos_nuevos` text,
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_auditoria`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cliente`
--

DROP TABLE IF EXISTS `cliente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cliente` (
  `id_cliente` int NOT NULL AUTO_INCREMENT,
  `id_persona` int NOT NULL,
  `activo` tinyint DEFAULT '1',
  PRIMARY KEY (`id_cliente`),
  KEY `id_persona` (`id_persona`),
  CONSTRAINT `cliente_ibfk_1` FOREIGN KEY (`id_persona`) REFERENCES `persona` (`id_persona`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `detalle_historia_clinica`
--

DROP TABLE IF EXISTS `detalle_historia_clinica`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_historia_clinica` (
  `id_detalle_historia_clinica` int NOT NULL AUTO_INCREMENT,
  `id_historia_clinica` int NOT NULL,
  `id_tratamiento` int NOT NULL,
  PRIMARY KEY (`id_detalle_historia_clinica`),
  KEY `id_historia_clinica` (`id_historia_clinica`),
  KEY `id_tratamiento` (`id_tratamiento`),
  CONSTRAINT `detalle_historia_clinica_ibfk_1` FOREIGN KEY (`id_historia_clinica`) REFERENCES `historia_clinica` (`id_historia_clinica`),
  CONSTRAINT `detalle_historia_clinica_ibfk_2` FOREIGN KEY (`id_tratamiento`) REFERENCES `tratamientos` (`id_tratamiento`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `domicilio`
--

DROP TABLE IF EXISTS `domicilio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `domicilio` (
  `id_domicilio` int NOT NULL AUTO_INCREMENT,
  `barrio` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `calle` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `numero_calle` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `manzana` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_cliente` int DEFAULT NULL,
  `id_profesional` int DEFAULT NULL,
  `activo` tinyint DEFAULT '1',
  PRIMARY KEY (`id_domicilio`),
  KEY `id_cliente` (`id_cliente`),
  KEY `fk_domicilio_profesional` (`id_profesional`),
  CONSTRAINT `domicilio_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`),
  CONSTRAINT `fk_domicilio_profesional` FOREIGN KEY (`id_profesional`) REFERENCES `profesional` (`id_profesional`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `especie`
--

DROP TABLE IF EXISTS `especie`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `especie` (
  `id_especie` int NOT NULL AUTO_INCREMENT,
  `nombre_especie` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `raza` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `activo` tinyint DEFAULT '1',
  PRIMARY KEY (`id_especie`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `historia_clinica`
--

DROP TABLE IF EXISTS `historia_clinica`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `historia_clinica` (
  `id_historia_clinica` int NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `descripcion` text COLLATE utf8mb4_general_ci,
  `id_mascota` int NOT NULL,
  `observacion` text COLLATE utf8mb4_general_ci,
  `activo` tinyint DEFAULT '1',
  PRIMARY KEY (`id_historia_clinica`),
  KEY `id_mascota` (`id_mascota`),
  CONSTRAINT `historia_clinica_ibfk_1` FOREIGN KEY (`id_mascota`) REFERENCES `mascota` (`id_mascota`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mascota`
--

DROP TABLE IF EXISTS `mascota`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mascota` (
  `id_mascota` int NOT NULL AUTO_INCREMENT,
  `id_especie` int NOT NULL,
  `id_cliente` int NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `edad` int DEFAULT NULL,
  `unidad_edad` enum('dias','meses','años') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `color` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nombre_mascota` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `peso` decimal(6,2) DEFAULT NULL,
  `sexo` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `activo` tinyint DEFAULT '1',
  PRIMARY KEY (`id_mascota`),
  KEY `id_especie` (`id_especie`),
  KEY `id_cliente` (`id_cliente`),
  CONSTRAINT `mascota_ibfk_1` FOREIGN KEY (`id_especie`) REFERENCES `especie` (`id_especie`),
  CONSTRAINT `mascota_ibfk_2` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `modulo`
--

DROP TABLE IF EXISTS `modulo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `modulo` (
  `id_modulo` int NOT NULL AUTO_INCREMENT,
  `nombre_modulo` varchar(80) COLLATE utf8mb4_general_ci NOT NULL,
  `ruta` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `icono` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `estado` tinyint DEFAULT '1',
  `categoria` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `orden` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_modulo`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perfil`
--

DROP TABLE IF EXISTS `perfil`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `perfil` (
  `id_perfil` int NOT NULL AUTO_INCREMENT,
  `nombre_perfil` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `estado` tinyint DEFAULT '1',
  PRIMARY KEY (`id_perfil`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perfil_modulo`
--

DROP TABLE IF EXISTS `perfil_modulo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `perfil_modulo` (
  `id_perfil_modulo` int NOT NULL AUTO_INCREMENT,
  `id_perfil` int NOT NULL,
  `id_modulo` int NOT NULL,
  PRIMARY KEY (`id_perfil_modulo`),
  KEY `id_perfil` (`id_perfil`),
  KEY `id_modulo` (`id_modulo`),
  CONSTRAINT `perfil_modulo_ibfk_1` FOREIGN KEY (`id_perfil`) REFERENCES `perfil` (`id_perfil`),
  CONSTRAINT `perfil_modulo_ibfk_2` FOREIGN KEY (`id_modulo`) REFERENCES `modulo` (`id_modulo`)
) ENGINE=InnoDB AUTO_INCREMENT=149 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `persona`
--

DROP TABLE IF EXISTS `persona`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `persona` (
  `id_persona` int NOT NULL AUTO_INCREMENT,
  `nombre_persona` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `apellido_persona` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `activo` tinyint DEFAULT '1',
  PRIMARY KEY (`id_persona`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `profesional`
--

DROP TABLE IF EXISTS `profesional`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `profesional` (
  `id_profesional` int NOT NULL AUTO_INCREMENT,
  `id_persona` int NOT NULL,
  `activo` tinyint DEFAULT '1',
  PRIMARY KEY (`id_profesional`),
  KEY `id_persona` (`id_persona`),
  CONSTRAINT `profesional_ibfk_1` FOREIGN KEY (`id_persona`) REFERENCES `persona` (`id_persona`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tipos_turno`
--

DROP TABLE IF EXISTS `tipos_turno`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipos_turno` (
  `id_tipo` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `color` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `duracion_minutos` int DEFAULT '30',
  PRIMARY KEY (`id_tipo`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tratamientos`
--

DROP TABLE IF EXISTS `tratamientos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tratamientos` (
  `id_tratamiento` int NOT NULL AUTO_INCREMENT,
  `duracion` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `dosis` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `descripcion` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id_tratamiento`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `turnos`
--

DROP TABLE IF EXISTS `turnos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `turnos` (
  `id_turno` int NOT NULL AUTO_INCREMENT,
  `id_profesional` int NOT NULL,
  `id_mascota` int NOT NULL,
  `hora` time NOT NULL,
  `fecha` date NOT NULL,
  `motivo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `detalle_atencion` text COLLATE utf8mb4_general_ci,
  `tratamiento` text COLLATE utf8mb4_general_ci,
  `monto_total` decimal(10,2) DEFAULT NULL,
  `estado` varchar(20) COLLATE utf8mb4_general_ci DEFAULT 'pendiente',
  `id_tipo_turno` int DEFAULT NULL,
  `duracion_minutos` int DEFAULT '30',
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_turno`),
  KEY `id_profesional` (`id_profesional`),
  KEY `id_mascota` (`id_mascota`),
  KEY `fk_turnos_tipo` (`id_tipo_turno`),
  CONSTRAINT `fk_turnos_tipo` FOREIGN KEY (`id_tipo_turno`) REFERENCES `tipos_turno` (`id_tipo`),
  CONSTRAINT `turnos_ibfk_1` FOREIGN KEY (`id_profesional`) REFERENCES `profesional` (`id_profesional`),
  CONSTRAINT `turnos_ibfk_2` FOREIGN KEY (`id_mascota`) REFERENCES `mascota` (`id_mascota`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `usuario` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `clave` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `reset_token` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `estado` tinyint(1) DEFAULT NULL,
  `id_perfil` int DEFAULT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `apellido` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-13  0:59:21
