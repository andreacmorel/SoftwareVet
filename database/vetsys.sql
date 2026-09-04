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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auditoria`
--

LOCK TABLES `auditoria` WRITE;
/*!40000 ALTER TABLE `auditoria` DISABLE KEYS */;
INSERT INTO `auditoria` VALUES (1,1,'Mascotas','Modificación',22,'Nombre: chuchi | Fecha nacimiento: 2020-08-10 | Sexo: H | Peso: 15.00 | Color: marrón | Edad: 6 | Unidad edad: años | Especie: 1 | Cliente: 1','Nombre: chuchi | Fecha nacimiento: 2020-08-10 | Sexo: H | Peso: 15.00 | Color: marrón | Edad: 7 | Unidad edad: años | Especie: 1 | Cliente: 1','2026-09-02 17:48:55'),(2,1,'Turnos','Modificación',15,'Fecha: 2026-07-02 | Hora: 18:00:00 | Motivo: prueba | Profesional: manuel brunel | Mascota: Bruni | Estado: pendiente','Fecha: 2026-09-03 | Hora: 18:00:00 | Motivo: holahola | Profesional: manuel brunel | Mascota: Bruni | Estado: pendiente','2026-09-02 19:10:07'),(3,1,'Turnos','Cambio de estado',15,'Estado: confirmado','Estado: en_atencion','2026-09-03 16:05:28'),(4,1,'Turnos','Cambio de estado',15,'','','2026-09-03 16:10:05'),(5,1,'Turnos','Cambio de estado',16,'Estado: Pendiente','Estado: Confirmado','2026-09-03 16:11:43'),(6,1,'Turnos','Cambio de estado',16,'Estado: Confirmado','Estado: En atencion','2026-09-03 16:11:45'),(7,1,'Mascotas','Modificación',24,'Nombre: Bruno | Fecha nacimiento: 2019-06-23 | Sexo: M | Peso: 3500.00 | Color: negro | Edad: 7 | Unidad edad: años | Especie: 3 | Cliente: 10','Nombre: Bruno | Fecha nacimiento: 2019-06-23 | Sexo: M | Peso: 3500.00 | Color: negro | Edad: 8 | Unidad edad: años | Especie: 3 | Cliente: 10','2026-09-04 17:56:08'),(8,1,'Turnos','Cambio de estado',18,'Estado: Pendiente','Estado: Confirmado','2026-09-04 17:56:39');
/*!40000 ALTER TABLE `auditoria` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente`
--

LOCK TABLES `cliente` WRITE;
/*!40000 ALTER TABLE `cliente` DISABLE KEYS */;
INSERT INTO `cliente` VALUES (1,1,1),(6,13,1),(8,16,0),(9,19,1),(10,20,1);
/*!40000 ALTER TABLE `cliente` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_historia_clinica`
--

LOCK TABLES `detalle_historia_clinica` WRITE;
/*!40000 ALTER TABLE `detalle_historia_clinica` DISABLE KEYS */;
INSERT INTO `detalle_historia_clinica` VALUES (1,1,1),(2,2,2);
/*!40000 ALTER TABLE `detalle_historia_clinica` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `domicilio`
--

LOCK TABLES `domicilio` WRITE;
/*!40000 ALTER TABLE `domicilio` DISABLE KEYS */;
INSERT INTO `domicilio` VALUES (2,'ejemplo','ejemplo','1234','12',1,NULL,1),(6,'ejemplo','Rivadavia','200','',NULL,7,1),(9,'independencia','Cordoba','557','12',6,NULL,1),(13,'independencia','caca','123','',NULL,10,1),(14,'independencia','Cordoba','557','34',9,NULL,1),(15,'independencia','Cordoba','557','',10,NULL,1);
/*!40000 ALTER TABLE `domicilio` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `especie`
--

LOCK TABLES `especie` WRITE;
/*!40000 ALTER TABLE `especie` DISABLE KEYS */;
INSERT INTO `especie` VALUES (1,'Canino','Labrador',1),(2,'Canino','Caniche',1),(3,'Felino','Siames',1),(5,'Canino','Border collie',0),(6,'Aaaa','Aaaa',0),(7,'Aaaa','Asdsada',0),(8,'Aaaaaaaaaaaaa','Aaaaaaaaaaaaaaa',0),(9,'Ddddddd','Ddddddddddddd',0),(10,'Prueba','Prueba',0),(11,'Ave','Loro',0);
/*!40000 ALTER TABLE `especie` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `historia_clinica`
--

LOCK TABLES `historia_clinica` WRITE;
/*!40000 ALTER TABLE `historia_clinica` DISABLE KEYS */;
INSERT INTO `historia_clinica` VALUES (1,'2026-04-30','control de vomitos',1,'se encuentra bien',1),(2,'2026-05-05','control',5,'prueba',0),(4,'2026-09-04','Control de diarrea explosiva',23,'cola sucia',1);
/*!40000 ALTER TABLE `historia_clinica` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mascota`
--

LOCK TABLES `mascota` WRITE;
/*!40000 ALTER TABLE `mascota` DISABLE KEYS */;
INSERT INTO `mascota` VALUES (1,2,1,NULL,3,'meses','Blanco','Bruni',15.00,'M',1),(5,1,1,'2021-11-10',2,'años','Negro','Polo',15.00,'M',1),(20,1,6,NULL,NULL,NULL,'','prueba',10.00,'M',0),(21,3,1,'2026-07-03',NULL,NULL,'','ejemplo',10.00,'H',0),(22,1,1,'2020-08-10',7,'años','marrón','chuchi',15.00,'H',1),(23,1,9,'2012-09-01',14,'años','Blanco','Samantha',30.00,'H',1),(24,3,10,'2019-06-23',8,'años','negro','Bruno',3500.00,'M',1);
/*!40000 ALTER TABLE `mascota` ENABLE KEYS */;
UNLOCK TABLES;

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
  PRIMARY KEY (`id_modulo`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modulo`
--

LOCK TABLES `modulo` WRITE;
/*!40000 ALTER TABLE `modulo` DISABLE KEYS */;
INSERT INTO `modulo` VALUES (1,'Mascotas','/SoftwareVet/modules/pets',NULL,1),(2,'Clientes','/SoftwareVet/modules/clients',NULL,1),(3,'Turnos','/SoftwareVet/modules/appointments',NULL,1),(4,'Usuarios','/SoftwareVet/modules/users',NULL,1),(5,'Perfiles','/SoftwareVet/modules/profiles',NULL,1),(6,'Historia Clinica','/SoftwareVet/modules/medical_records',NULL,1),(7,'Profesionales','/SoftwareVet/modules/professionals',NULL,1),(8,'Especies','/SoftwareVet/modules/species',NULL,1),(9,'Modulos','/SoftwareVet/modules/system_modules',NULL,1),(10,'Inicio','/SoftwareVet/php/inicio.php',NULL,1),(11,'Asignacion_Modulos','/SoftwareVet/modules/profiles/assign_modules','',1),(12,'hola','modules/index.php','',1),(13,'Reportes','modules/reports/index.php',NULL,1);
/*!40000 ALTER TABLE `modulo` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `perfil`
--

LOCK TABLES `perfil` WRITE;
/*!40000 ALTER TABLE `perfil` DISABLE KEYS */;
INSERT INTO `perfil` VALUES (2,'Administrador',1),(6,'medicamentos',0),(7,'Secretaria',1),(8,'Visitante',1),(9,'aaaaa',0),(10,'aaaa',0),(11,'aaaaa',0);
/*!40000 ALTER TABLE `perfil` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perfil_modulo`
--

LOCK TABLES `perfil_modulo` WRITE;
/*!40000 ALTER TABLE `perfil_modulo` DISABLE KEYS */;
INSERT INTO `perfil_modulo` VALUES (17,2,2),(18,2,8),(19,2,6),(20,2,10),(21,2,1),(22,2,11),(23,2,9),(24,2,5),(25,2,7),(26,2,3),(27,2,4),(48,7,2),(49,7,10),(50,7,1),(51,7,3),(52,8,1),(53,2,13);
/*!40000 ALTER TABLE `perfil_modulo` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `persona`
--

LOCK TABLES `persona` WRITE;
/*!40000 ALTER TABLE `persona` DISABLE KEYS */;
INSERT INTO `persona` VALUES (1,'Andrea','Morel','3704010101','andreacmorel@gmail.com',1),(9,'Rocio','Morel','3704101020','rociomorel@gmail.com',1),(13,'Gabriela','Vera','3704716209','gabrielavera@gmail.com',1),(16,'camila','morel','3704550319','',0),(17,'aaaaaaaaaaaaaa','aaaaaaaaaaa','3704550319','camilamorel@gmail.com',1),(18,'manuel','brunel','3704302101','manu@gmail.com',1),(19,'Daniela','Morel','3704758690','danielaloka@gmail.com',1),(20,'Hector','Villalba','3704567897','hector15v@gmail.com',1);
/*!40000 ALTER TABLE `persona` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profesional`
--

LOCK TABLES `profesional` WRITE;
/*!40000 ALTER TABLE `profesional` DISABLE KEYS */;
INSERT INTO `profesional` VALUES (7,9,1),(10,18,0);
/*!40000 ALTER TABLE `profesional` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `tipos_turno`
--

LOCK TABLES `tipos_turno` WRITE;
/*!40000 ALTER TABLE `tipos_turno` DISABLE KEYS */;
INSERT INTO `tipos_turno` VALUES (1,'Consulta','#4e73df',30),(2,'Vacunación','#1cc88a',30),(3,'Control','#36b9cc',30),(4,'Cirugía','#e74a3b',30);
/*!40000 ALTER TABLE `tipos_turno` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tratamientos`
--

LOCK TABLES `tratamientos` WRITE;
/*!40000 ALTER TABLE `tratamientos` DISABLE KEYS */;
INSERT INTO `tratamientos` VALUES (1,'2 dias',NULL,'Medicamento de perro'),(2,'1 dia','1 comprimido cada 6 horas','medicamento');
/*!40000 ALTER TABLE `tratamientos` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `turnos`
--

LOCK TABLES `turnos` WRITE;
/*!40000 ALTER TABLE `turnos` DISABLE KEYS */;
INSERT INTO `turnos` VALUES (12,7,1,'09:00:00','2026-06-03','pruebaaa','completado',NULL,30,1),(13,7,1,'07:00:00','2026-06-03','aaaaaaa','completado',NULL,30,1),(14,10,1,'18:00:00','2026-07-02','prueba','cancelado',NULL,30,1),(15,10,1,'18:00:00','2026-09-03','holahola','completado',NULL,30,1),(16,10,21,'20:00:00','2026-09-04','prueba','en_atencion',NULL,30,1),(17,7,23,'08:30:00','2026-09-07','diarrea explosiva','pendiente',NULL,30,1),(18,7,24,'17:00:00','2026-09-08','consulta','confirmado',NULL,30,1);
/*!40000 ALTER TABLE `turnos` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (1,'andrea','$2y$10$haUM4h2yhf9BEqtK96JXPe2qDsz.Mk8jM984iyJ1sfBWsKzB/np12',NULL,'andreamorelucp@gmail.com',1,2,'andrea','morel'),(6,'gabriela','$2y$10$WsV/hSScMED7AfbAZtJwT.MUcEW0Fgb0JEuE1Ziv4UgZMU5O2VW3C','dbcfb853ea3309f3ca58f641bc0ac4fe613548d24129c7d10c97763e7cdd86a6','gabrielabetianavera@gmail.com',1,7,'gabriela','vera');
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-09-04 18:08:54
