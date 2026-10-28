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
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auditoria`
--

LOCK TABLES `auditoria` WRITE;
/*!40000 ALTER TABLE `auditoria` DISABLE KEYS */;
INSERT INTO `auditoria` VALUES (1,1,'Mascotas','Modificación',22,'Nombre: chuchi | Fecha nacimiento: 2020-08-10 | Sexo: H | Peso: 15.00 | Color: marrón | Edad: 6 | Unidad edad: años | Especie: 1 | Cliente: 1','Nombre: chuchi | Fecha nacimiento: 2020-08-10 | Sexo: H | Peso: 15.00 | Color: marrón | Edad: 7 | Unidad edad: años | Especie: 1 | Cliente: 1','2026-09-02 17:48:55'),(2,1,'Turnos','Modificación',15,'Fecha: 2026-07-02 | Hora: 18:00:00 | Motivo: prueba | Profesional: manuel brunel | Mascota: Bruni | Estado: pendiente','Fecha: 2026-09-03 | Hora: 18:00:00 | Motivo: holahola | Profesional: manuel brunel | Mascota: Bruni | Estado: pendiente','2026-09-02 19:10:07'),(3,1,'Turnos','Cambio de estado',15,'Estado: confirmado','Estado: en_atencion','2026-09-03 16:05:28'),(4,1,'Turnos','Cambio de estado',15,'','','2026-09-03 16:10:05'),(5,1,'Turnos','Cambio de estado',16,'Estado: Pendiente','Estado: Confirmado','2026-09-03 16:11:43'),(6,1,'Turnos','Cambio de estado',16,'Estado: Confirmado','Estado: En atencion','2026-09-03 16:11:45'),(7,1,'Mascotas','Modificación',24,'Nombre: Bruno | Fecha nacimiento: 2019-06-23 | Sexo: M | Peso: 3500.00 | Color: negro | Edad: 7 | Unidad edad: años | Especie: 3 | Cliente: 10','Nombre: Bruno | Fecha nacimiento: 2019-06-23 | Sexo: M | Peso: 3500.00 | Color: negro | Edad: 8 | Unidad edad: años | Especie: 3 | Cliente: 10','2026-09-04 17:56:08'),(8,1,'Turnos','Cambio de estado',18,'Estado: Pendiente','Estado: Confirmado','2026-09-04 17:56:39'),(9,1,'Mascotas','Modificación',23,'Nombre: Samantha | Fecha nacimiento: 2012-09-01 | Sexo: H | Peso: 30.00 | Color: Blanco | Edad: 14 | Unidad edad: años | Especie: 1 | Cliente: 9','Nombre: Samantha | Fecha nacimiento: 2012-09-10 | Sexo: H | Peso: 20.00 | Color: Blanco | Edad: 14 | Unidad edad: años | Especie: 1 | Cliente: 9','2026-09-04 19:47:14'),(10,1,'Mascotas','Modificación',28,'Nombre: pruebacombo | Fecha nacimiento: 2026-09-01 | Sexo: H | Peso: 20.00 | Color: gris | Edad: 1 | Unidad edad: años | Especie: 13 | Cliente: 10','Nombre: pruebacombo | Fecha nacimiento: 2026-09-01 | Sexo: H | Peso: 20.00 | Color: gris | Edad: 1 | Unidad edad: años | Especie: 2 | Cliente: 10','2026-09-28 17:08:45'),(11,1,'Turnos','Modificación',22,'Fecha: 2026-09-21 | Hora: 12:00:00 | Motivo: Pulgas | Profesional: Rocio Morel | Mascota: Bruni | Estado: pendiente','Fecha: 2026-09-29 | Hora: 12:00:00 | Motivo: Pulgass | Profesional: Rocio Morel | Mascota: Bruni | Estado: pendiente','2026-09-29 00:11:29'),(12,1,'Turnos','Modificación',23,'Fecha: 2026-09-30 | Hora: 08:00:00 | Motivo: ejemplo | Profesional: prueba prueba | Mascota: jrjr | Estado: pendiente','Fecha: 2026-09-30 | Hora: 08:00:00 | Motivo: ejemplo1 | Profesional: prueba prueba | Mascota: jrjr | Estado: pendiente','2026-09-29 00:13:34'),(13,1,'Turnos','Modificación',23,'Fecha: 2026-09-30 | Hora: 08:00:00 | Motivo: ejemplo1 | Profesional: prueba prueba | Mascota: jrjr | Estado: pendiente','Fecha: 2026-09-30 | Hora: 08:00:00 | Motivo: ejemplo1 | Profesional: Rocio Morel | Mascota: jrjr | Estado: pendiente','2026-09-29 00:13:46'),(14,1,'Turnos','Modificación',23,'Fecha: 2026-09-30 | Hora: 08:00:00 | Motivo: ejemplo1 | Profesional: Rocio Morel | Mascota: jrjr | Estado: pendiente','Fecha: 2026-09-30 | Hora: 08:00:00 | Motivo: ejemplo1 | Profesional: Rocio Morel | Mascota: Emily | Estado: pendiente','2026-09-29 00:15:09'),(15,1,'Turnos','Cambio de estado',23,'Estado: Pendiente','Estado: Completado','2026-09-29 00:36:12'),(16,1,'Turnos','Cambio de estado',22,'Estado: Pendiente','Estado: Cancelado','2026-09-29 00:36:18'),(17,1,'Turnos','Cambio de estado',21,'Estado: Pendiente','Estado: Confirmado','2026-09-29 00:39:56'),(18,1,'Turnos','Finalización de consulta',16,'Estado: en_atencion','Estado: Completado | Detalle de atención: control | Tratamiento: antibiotico | Monto total: $14,00','2026-09-30 16:28:28'),(19,1,'Turnos','Cambio de estado',21,'Estado: Confirmado','Estado: En atencion','2026-09-30 16:45:18'),(20,1,'Turnos','Finalización de consulta',21,'Estado: en_atencion','Estado: Completado | Detalle de atención: control | Tratamiento: analgesico | Monto total: $19.999,99','2026-09-30 17:07:16'),(21,1,'Turnos','Cambio de estado',17,'Estado: Pendiente','Estado: Confirmado','2026-10-01 18:23:12'),(22,1,'Turnos','Cambio de estado',18,'Estado: Confirmado','Estado: En atencion','2026-10-01 18:23:16'),(23,1,'Turnos','Cambio de estado',20,'Estado: Pendiente','Estado: Confirmado','2026-10-02 14:33:30'),(24,1,'Turnos','Cambio de estado',20,'Estado: Confirmado','Estado: En atencion','2026-10-02 14:34:01'),(25,1,'Turnos','Finalización de consulta',20,'Estado: en_atencion','Estado: Completado | Detalle de atención: prueba | Tratamiento: prueba | Monto total: $10,00','2026-10-02 14:34:13'),(26,1,'Turnos','Cambio de estado',1,'Estado: Pendiente','Estado: Confirmado','2026-10-02 16:43:48'),(27,1,'Turnos','Cambio de estado',2,'Estado: Pendiente','Estado: Confirmado','2026-10-02 17:09:15'),(28,1,'Turnos','Cambio de estado',1,'Estado: Confirmado','Estado: En atencion','2026-10-02 17:10:54'),(29,1,'Turnos','Finalización de consulta',1,'Estado: en_atencion','Estado: Completado | Detalle de atención: se realizo un examen fisico | Tratamiento: antibiotica cada 12 horas | Monto total: $20,00','2026-10-02 17:15:07'),(30,1,'Turnos','Cambio de estado',2,'Estado: Confirmado','Estado: En atencion','2026-10-02 17:17:10'),(31,1,'Turnos','Cambio de estado',2,'Estado: En atencion','Estado: Confirmado','2026-10-02 17:17:36'),(32,1,'Turnos','Cambio de estado',2,'Estado: Confirmado','Estado: Pendiente','2026-10-02 17:18:56'),(33,1,'Turnos','Cambio de estado',2,'Estado: Pendiente','Estado: Confirmado','2026-10-02 17:19:02'),(34,1,'Turnos','Cambio de estado',2,'Estado: Confirmado','Estado: En atencion','2026-10-02 17:19:18'),(35,1,'Turnos','Finalización de consulta',2,'Estado: en_atencion','Estado: Completado | Detalle de atención: vacuna de parasito | Tratamiento: ejemplo | Monto total: $20.000,00','2026-10-02 17:20:26'),(36,1,'Turnos','Cambio de estado',3,'Estado: Pendiente','Estado: Confirmado','2026-10-02 17:30:56'),(37,1,'Turnos','Cambio de estado',3,'Estado: Confirmado','Estado: En atencion','2026-10-02 17:31:12'),(38,1,'Turnos','Cambio de estado',3,'Estado: En atencion','Estado: Confirmado','2026-10-02 17:31:24'),(39,1,'Turnos','Cambio de estado',3,'Estado: Confirmado','Estado: En atencion','2026-10-02 17:31:27'),(40,1,'Turnos','Cambio de estado',3,'Estado: En atencion','Estado: Confirmado','2026-10-02 17:31:31'),(41,1,'Turnos','Cambio de estado',3,'Estado: Confirmado','Estado: En atencion','2026-10-02 17:31:39'),(42,1,'Turnos','Finalización de consulta',3,'Estado: en_atencion','Estado: Completado | Detalle de atención: ejemplo | Tratamiento: ejemplo | Monto total: $20.000,00','2026-10-02 17:43:47'),(43,1,'Turnos','Cambio de estado',4,'Estado: Pendiente','Estado: Confirmado','2026-10-02 17:44:51'),(44,1,'Turnos','Cambio de estado',4,'Estado: Confirmado','Estado: En atencion','2026-10-02 17:44:58'),(45,1,'Turnos','Modificación',2,'Fecha: 2026-10-03 | Hora: 09:00:00 | Motivo: vacuna | Profesional: Rocio Morel | Mascota: Bruni | Estado: pendiente','Fecha: 2026-10-03 | Hora: 09:00:00 | Motivo: vacuna | Profesional: Rocio Morel | Mascota: Bruno | Estado: pendiente','2026-10-02 17:46:06'),(46,1,'Turnos','Cambio de estado',2,'Estado: Pendiente','Estado: Confirmado','2026-10-02 19:28:57'),(47,1,'Turnos','Cambio de estado',4,'Estado: Pendiente','Estado: Confirmado','2026-10-02 19:44:17'),(48,1,'Turnos','Cambio de estado',4,'Estado: Confirmado','Estado: En atencion','2026-10-02 19:44:21'),(49,1,'Turnos','Finalización de consulta',4,'Estado: en_atencion','Estado: Completado | Detalle de atención: Se realizo control de temperatura, desparasitación, y fluidoterapia. | Tratamiento: Analgésico:(Dipirona) cada 12hs  Antiparasitario (Ivermectina) Fluidoterapia: (Solución Fisiológica Ringer Lactato IV/SC) cada 24hs | Monto total: $150.000,00','2026-10-02 19:53:42'),(50,1,'Turnos','Cambio de estado',5,'Estado: Pendiente','Estado: Confirmado','2026-10-02 20:15:22'),(51,1,'Turnos','Cambio de estado',5,'Estado: Confirmado','Estado: En atencion','2026-10-02 20:15:25'),(52,1,'Turnos','Cambio de estado',6,'Estado: Pendiente','Estado: Confirmado','2026-10-03 20:49:36'),(53,1,'Turnos','Cambio de estado',6,'Estado: Confirmado','Estado: En atencion','2026-10-03 20:49:41'),(54,1,'Turnos','Finalización de consulta',6,'Estado: en_atencion','Estado: Completado | Detalle de atención: control de temperatura | Tratamiento: aintibiotico cada 13 hs | Monto total: $200.000,00','2026-10-03 20:50:55'),(55,1,'Turnos','Finalización de consulta',5,'Estado: en_atencion','Estado: Completado | Detalle de atención: prueba | Tratamiento: prueba | Monto total: $10.000,00','2026-10-06 19:26:10'),(56,1,'Turnos','Cambio de estado',1,'Estado: Pendiente','Estado: Cancelado','2026-10-06 19:27:19'),(57,1,'Turnos','Cambio de estado',2,'Estado: Confirmado','Estado: Cancelado','2026-10-06 19:27:22'),(58,1,'Turnos','Cambio de estado',8,'Estado: Pendiente','Estado: Confirmado','2026-10-06 20:32:28'),(59,1,'Turnos','Cambio de estado',8,'Estado: Confirmado','Estado: En atencion','2026-10-06 20:32:35'),(60,1,'Mascotas','Modificación',34,'Nombre: colita | Fecha nacimiento:  | Sexo: H | Peso: 13.00 | Color: negro | Edad:  | Unidad edad:  | Especie: 20 | Cliente: 18','Nombre: colita | Fecha nacimiento:  | Sexo: H | Peso: 13.00 | Color: negro | Edad:  | Unidad edad:  | Especie: 20 | Cliente: 18','2026-10-12 18:44:07'),(61,1,'Turnos','Finalización de consulta',8,'Estado: en_atencion','Estado: Completado | Detalle de atención: curita | Tratamiento: paracetamol | Monto total: $20.000,00','2026-10-13 20:05:38'),(62,1,'Turnos','Cambio de estado',7,'Estado: Pendiente','Estado: Cancelado','2026-10-13 20:05:42'),(63,1,'Turnos','Cambio de estado',9,'Estado: Pendiente','Estado: Confirmado','2026-10-13 20:21:04'),(64,1,'Turnos','Cambio de estado',9,'Estado: Confirmado','Estado: En atencion','2026-10-13 20:21:10'),(65,1,'Turnos','Finalización de consulta',9,'Estado: en_atencion','Estado: Completado | Detalle de atención: examen fisic | Tratamiento: antibiotico | Monto total: $50.000,00','2026-10-13 20:22:26'),(66,1,'Mascotas','Modificación',36,'Nombre: Bartola | Fecha nacimiento: 2024-11-15 | Sexo: H | Peso: 10.00 | Color: marrón | Edad: 2 | Unidad edad: años | Especie: 16 | Cliente: 20','Nombre: Bartola | Fecha nacimiento: 2024-11-15 | Sexo: H | Peso: 10.00 | Color: marrón | Edad: 4 | Unidad edad: años | Especie: 16 | Cliente: 20','2026-10-16 15:07:07'),(67,1,'Mascotas','Modificación',27,'Nombre: pruebaaa | Fecha nacimiento: 2026-09-22 | Sexo: M | Peso: 2.00 | Color:  | Edad:  | Unidad edad:  | Especie: 1 | Cliente: 13','Nombre: pruebaaa | Fecha nacimiento: 2026-09-22 | Sexo: M | Peso: 2.50 | Color:  | Edad:  | Unidad edad:  | Especie: 1 | Cliente: 13','2026-10-16 15:34:27'),(68,1,'Mascotas','Modificación',36,'Nombre: Bartola | Fecha nacimiento: 2024-11-15 | Sexo: H | Peso: 10.00 | Color: marrón | Edad: 4 | Unidad edad: años | Especie: 16 | Cliente: 20','Nombre: Bartola | Fecha nacimiento: 2024-11-15 | Sexo: H | Peso: 10.00 | Color: marrón | Edad:  | Unidad edad: dias | Especie: 16 | Cliente: 20','2026-10-16 15:50:11'),(69,1,'Mascotas','Modificación',36,'Nombre: Bartola | Fecha nacimiento: 2024-11-15 | Sexo: H | Peso: 10.00 | Color: marrón | Edad:  | Unidad edad: dias | Especie: 16 | Cliente: 20','Nombre: Bartola | Fecha nacimiento: 2024-11-15 | Sexo: H | Peso: 10.00 | Color: marrón | Edad:  | Unidad edad: dias | Especie: 16 | Cliente: 20','2026-10-16 15:50:41'),(70,1,'Mascotas','Modificación',36,'Nombre: Bartola | Fecha nacimiento: 2024-11-15 | Sexo: H | Peso: 10.00 | Color: marrón | Edad:  | Unidad edad: dias | Especie: 16 | Cliente: 20','Nombre: Bartola | Fecha nacimiento: 2024-11-15 | Sexo: H | Peso: 10.00 | Color: marrón | Edad:  | Unidad edad: dias | Especie: 16 | Cliente: 20','2026-10-16 15:52:01'),(71,1,'Mascotas','Modificación',37,'Nombre: Samantha | Fecha nacimiento:  | Sexo: H | Peso: 10.00 | Color: marrón | Edad:  | Unidad edad: dias | Especie: 15 | Cliente: 17','Nombre: Samantha | Fecha nacimiento:  | Sexo: H | Peso: 10.00 | Color: marrón | Edad:  | Unidad edad: dias | Especie: 15 | Cliente: 17','2026-10-16 15:58:17'),(72,1,'Mascotas','Modificación',36,'Nombre: Bartola | Fecha nacimiento: 2024-11-15 | Sexo: H | Peso: 10.00 | Color: marrón | Edad:  | Unidad edad: dias | Especie: 16 | Cliente: 20','Nombre: Bartola | Fecha nacimiento: 2024-11-15 | Sexo: H | Peso: 10.00 | Color: marrón | Edad: 0 | Unidad edad: dias | Especie: 16 | Cliente: 20','2026-10-16 16:02:31'),(73,1,'Mascotas','Modificación',36,'Nombre: Bartola | Fecha nacimiento: 2024-11-15 | Sexo: H | Peso: 10.00 | Color: marrón | Edad: 0 | Unidad edad: dias | Especie: 16 | Cliente: 20','Nombre: Bartola | Fecha nacimiento: 2024-11-15 | Sexo: H | Peso: 10.00 | Color: marrón | Edad:  | Unidad edad:  | Especie: 16 | Cliente: 20','2026-10-16 16:03:35'),(74,1,'Mascotas','Modificación',41,'Nombre: Ponyo | Fecha nacimiento: 2026-05-10 | Sexo: H | Peso: 3.00 | Color: Blanco | Edad: 5 | Unidad edad: meses | Especie: 2 | Cliente: 21','Nombre: Ponyo | Fecha nacimiento: 2026-05-10 | Sexo: H | Peso: 3.00 | Color: Blanco | Edad: 5 | Unidad edad: meses | Especie: 2 | Cliente: 21','2026-10-19 21:55:48'),(75,1,'Mascotas','Modificación',40,'Nombre: Jagger | Fecha nacimiento: 2020-02-01 | Sexo: M | Peso: 15.00 | Color: Blanco | Edad: 6 | Unidad edad: años | Especie: 16 | Cliente: 21','Nombre: Jagger | Fecha nacimiento: 2020-02-01 | Sexo: M | Peso: 15.00 | Color: Blanco | Edad: 6 | Unidad edad: años | Especie: 16 | Cliente: 21','2026-10-19 21:56:26'),(76,1,'Mascotas','Modificación',1,'Nombre: Bruni | Fecha nacimiento:  | Sexo: M | Peso: 15.00 | Color: Blanco | Edad: 3 | Unidad edad: meses | Especie: 2 | Cliente: 1','Nombre: Bruni | Fecha nacimiento: 2021-09-01 | Sexo: M | Peso: 15.00 | Color: Blanco | Edad: 5 | Unidad edad: años | Especie: 20 | Cliente: 1','2026-10-20 16:31:26'),(77,1,'Mascotas','Modificación',1,'Nombre: Bruni | Fecha nacimiento: 2021-09-01 | Sexo: M | Peso: 15.00 | Color: Blanco | Edad: 5 | Unidad edad: años | Especie: 20 | Cliente: 1','Nombre: Bruni | Fecha nacimiento: 2021-09-01 | Sexo: H | Peso: 15.00 | Color: Blanco | Edad: 5 | Unidad edad: años | Especie: 20 | Cliente: 1','2026-10-20 16:33:41'),(78,1,'Mascotas','Modificación',1,'Nombre: Bruni | Fecha nacimiento: 2021-09-01 | Sexo: H | Peso: 15.00 | Color: Blanco | Edad: 5 | Unidad edad: años | Especie: 20 | Cliente: 1','Nombre: Bruni | Fecha nacimiento: 2021-09-01 | Sexo: H | Peso: 15.00 | Color: Blanco | Edad: 5 | Unidad edad: años | Especie: 20 | Cliente: 1','2026-10-20 16:36:48'),(79,1,'Mascotas','Modificación',1,'Nombre: Bruni | Fecha nacimiento: 2021-09-01 | Sexo: H | Peso: 15.00 | Color: Blanco | Edad: 5 | Unidad edad: años | Especie: 20 | Cliente: 1','Nombre: Bruni | Fecha nacimiento: 2021-09-01 | Sexo: H | Peso: 15.00 | Color: Blanco | Edad: 5 | Unidad edad: años | Especie: 20 | Cliente: 1','2026-10-20 16:41:40'),(80,1,'Mascotas','Modificación',1,'Nombre: Bruni | Fecha nacimiento: 2021-09-01 | Sexo: H | Peso: 15.00 | Color: Blanco | Edad: 5 | Unidad edad: años | Especie: 20 | Cliente: 1','Nombre: Bruni | Fecha nacimiento: 2021-09-01 | Sexo: H | Peso: 15.00 | Color: Blanco | Edad: 5 | Unidad edad: años | Especie: 20 | Cliente: 1','2026-10-20 16:44:15'),(81,1,'Mascotas','Modificación',1,'Nombre: Bruni | Fecha nacimiento: 2021-09-01 | Sexo: H | Peso: 15.00 | Color: Blanco | Edad: 5 | Unidad edad: años | Especie: 20 | Cliente: 1','Nombre: Bruni | Fecha nacimiento: 2021-09-01 | Sexo: H | Peso: 15.00 | Color: Blanco | Edad: 5 | Unidad edad: años | Especie: 20 | Cliente: 1','2026-10-20 16:47:01'),(82,1,'Mascotas','Modificación',1,'Nombre: Bruni | Fecha nacimiento: 2021-09-01 | Sexo: H | Peso: 15.00 | Color: Blanco | Edad: 5 | Unidad edad: años | Especie: 20 | Cliente: 1','Nombre: Bruni | Fecha nacimiento: 2021-09-01 | Sexo: H | Peso: 15.00 | Color: Blanco | Edad: 5 | Unidad edad: años | Especie: 20 | Cliente: 1','2026-10-20 16:47:10'),(83,1,'Mascotas','Modificación',1,'Nombre: Bruni | Fecha nacimiento: 2021-09-01 | Sexo: H | Peso: 15.00 | Color: Blanco | Edad: 5 | Unidad edad: años | Especie: 20 | Cliente: 1','Nombre: Bruni | Fecha nacimiento: 2021-09-01 | Sexo: H | Peso: 15.00 | Color: Blanco | Edad: 5 | Unidad edad: años | Especie: 20 | Cliente: 1','2026-10-20 16:47:40'),(84,1,'Mascotas','Modificación',1,'Nombre: Bruni | Fecha nacimiento: 2021-09-01 | Sexo: H | Peso: 15.00 | Color: Blanco | Edad: 5 | Unidad edad: años | Especie: 20 | Cliente: 1','Nombre: Bruni | Fecha nacimiento: 2021-09-01 | Sexo: H | Peso: 15.00 | Color: Blanco | Edad: 5 | Unidad edad: años | Especie: 20 | Cliente: 1','2026-10-20 16:54:42'),(85,1,'Mascotas','Modificación',1,'Nombre: Bruni | Fecha nacimiento: 2021-09-01 | Sexo: H | Peso: 15.00 | Color: Blanco | Edad: 5 | Unidad edad: años | Especie: 20 | Cliente: 1','Nombre: Bruni | Fecha nacimiento: 2021-09-01 | Sexo: H | Peso: 15.00 | Color: Blanco | Edad: 5 | Unidad edad: años | Especie: 20 | Cliente: 1','2026-10-20 16:54:53'),(86,1,'Mascotas','Modificación',42,'Nombre: Coli | Fecha nacimiento: 2024-08-07 | Sexo: H | Peso: 12.00 | Color: Negro | Edad: 3 | Unidad edad: años | Especie: 20 | Cliente: 18','Nombre: Coli | Fecha nacimiento: 2024-08-07 | Sexo: H | Peso: 12.00 | Color: Negro | Edad: 3 | Unidad edad: años | Especie: 20 | Cliente: 18','2026-10-20 17:07:23');
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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente`
--

LOCK TABLES `cliente` WRITE;
/*!40000 ALTER TABLE `cliente` DISABLE KEYS */;
INSERT INTO `cliente` VALUES (1,1,1),(6,13,1),(8,16,0),(9,19,1),(10,20,1),(11,21,1),(12,22,0),(13,23,1),(14,26,1),(15,27,1),(16,28,1),(17,30,1),(18,31,1),(19,32,1),(20,33,1),(21,34,1);
/*!40000 ALTER TABLE `cliente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `condicion_mascota`
--

DROP TABLE IF EXISTS `condicion_mascota`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `condicion_mascota` (
  `id_condicion` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(50) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`id_condicion`),
  UNIQUE KEY `codigo` (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `condicion_mascota`
--

LOCK TABLES `condicion_mascota` WRITE;
/*!40000 ALTER TABLE `condicion_mascota` DISABLE KEYS */;
INSERT INTO `condicion_mascota` VALUES (1,'ceguera','Ceguera'),(2,'sordera','Sordera'),(3,'no_vocaliza','No vocaliza / dificultad para vocalizar'),(4,'movilidad','Movilidad reducida'),(5,'epilepsia','Epilepsia'),(6,'medicacion','Requiere medicación'),(7,'alergias','Alergias'),(8,'temeroso','Se asusta con extraños'),(9,'otra','Otra');
/*!40000 ALTER TABLE `condicion_mascota` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_historia_clinica`
--

LOCK TABLES `detalle_historia_clinica` WRITE;
/*!40000 ALTER TABLE `detalle_historia_clinica` DISABLE KEYS */;
INSERT INTO `detalle_historia_clinica` VALUES (1,1,1),(2,2,2),(3,7,3);
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
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `domicilio`
--

LOCK TABLES `domicilio` WRITE;
/*!40000 ALTER TABLE `domicilio` DISABLE KEYS */;
INSERT INTO `domicilio` VALUES (2,'Mistrol 2','Romulo Amadey','1750','-',1,NULL,1),(6,'ejemplo','Rivadavia','200','',NULL,7,1),(9,'independencia','Cordoba','557','12',6,NULL,1),(13,'independencia','caca','123','',NULL,10,1),(14,'independencia','Cordoba','557','34',9,NULL,1),(15,'independencia','Cordoba','557','',10,NULL,1),(16,'nosenose','nose','1234','',NULL,11,1),(17,'pruebabarrio','prueba','1234','',NULL,12,1),(18,'','Cordoba','557','',15,NULL,1),(19,'Vial','Romulo Amadey','2020','10',16,NULL,1),(20,'Independencia','juan jose silva','100','',NULL,13,1),(21,'Incone','Antartida Argentina','2345','76',17,NULL,1),(22,'Independencia','Cordoba','557','',18,NULL,1),(23,'San Martin','maipu','2390','15',19,NULL,1),(24,'Independencia','Mitre','335','',21,NULL,1);
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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `especie`
--

LOCK TABLES `especie` WRITE;
/*!40000 ALTER TABLE `especie` DISABLE KEYS */;
INSERT INTO `especie` VALUES (1,'Canino','Labrador',1),(2,'Canino','Caniche',1),(3,'Felino','Siames',1),(5,'Canino','Border collie',0),(6,'Aaaa','Aaaa',0),(7,'Aaaa','Asdsada',0),(8,'Aaaaaaaaaaaaa','Aaaaaaaaaaaaaaa',0),(9,'Ddddddd','Ddddddddddddd',0),(10,'Prueba','Prueba',0),(11,'Ave','Loro',0),(12,'Canino','Dachshund',0),(13,'Canino','Pequines',1),(14,'Canino','Rottweiler',1),(15,'Felino','Persa',1),(16,'Canino','Boxer',1),(17,'Felino','Bengali',1),(18,'Canino','Dalmata',1),(19,'Canino','Beagle',1),(20,'Canino','Mestizo',1),(21,'Felino','Ragdoll',1);
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `historia_clinica`
--

LOCK TABLES `historia_clinica` WRITE;
/*!40000 ALTER TABLE `historia_clinica` DISABLE KEYS */;
INSERT INTO `historia_clinica` VALUES (1,'2026-04-30','control de vomitos',1,'se encuentra bien',1),(2,'2026-05-05','control',5,'prueba',0),(4,'2026-09-04','Control de diarrea explosiva',23,'cola sucia',1),(5,'2026-09-17','prueba',1,'',1),(6,'2026-09-17','prueba',24,'',1),(7,'2026-09-18','Revision herida en la pata',1,'El paciente observa una pata rota',1),(8,'2026-09-25','aaaaaaa',26,'',1);
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
  `condicion_especial` text COLLATE utf8mb4_general_ci,
  `cuidados_especiales` text COLLATE utf8mb4_general_ci,
  `token_publico` varchar(64) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `condicion_otra` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ficha_publica` tinyint(1) NOT NULL DEFAULT '0',
  `mostrar_telefono` tinyint(1) NOT NULL DEFAULT '0',
  `consentimiento_qr` tinyint(1) NOT NULL DEFAULT '0',
  `fecha_consentimiento_qr` datetime DEFAULT NULL,
  `mostrar_domicilio` tinyint(1) NOT NULL DEFAULT '0',
  `foto` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id_mascota`),
  UNIQUE KEY `token_publico` (`token_publico`),
  KEY `id_especie` (`id_especie`),
  KEY `id_cliente` (`id_cliente`),
  CONSTRAINT `mascota_ibfk_1` FOREIGN KEY (`id_especie`) REFERENCES `especie` (`id_especie`),
  CONSTRAINT `mascota_ibfk_2` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mascota`
--

LOCK TABLES `mascota` WRITE;
/*!40000 ALTER TABLE `mascota` DISABLE KEYS */;
INSERT INTO `mascota` VALUES (1,20,1,'2021-09-01',5,'años','Blanco','Bruni',15.00,'H',1,'[\"Se asusta con extraños\"]','Responde al nombre de Bruni, suele ser mala con gente desconocida','7fe65b69cf2f35d7418f2d3f25bdca427f314285d88a2c925648d5e5418cc81d',NULL,1,1,1,'2026-10-20 16:31:26',1,'uploads/pets/a37105d74ab0331f6ac69ad904d662df.jpg'),(5,1,1,'2021-11-10',2,'años','Negro','Polo',15.00,'M',1,NULL,NULL,NULL,NULL,0,0,0,NULL,0,NULL),(20,1,6,NULL,NULL,NULL,'','prueba',10.00,'M',0,NULL,NULL,NULL,NULL,0,0,0,NULL,0,NULL),(21,3,1,'2026-07-03',NULL,NULL,'','ejemplo',10.00,'H',0,NULL,NULL,NULL,NULL,0,0,0,NULL,0,NULL),(22,1,1,'2020-08-10',7,'años','marrón','chuchi',15.00,'H',1,NULL,NULL,NULL,NULL,0,0,0,NULL,0,NULL),(23,1,9,'2012-09-10',14,'años','Blanco','Samantha',20.00,'H',1,NULL,NULL,NULL,NULL,0,0,0,NULL,0,NULL),(24,3,10,'2019-06-23',8,'años','negro','Bruno',3500.00,'M',1,NULL,NULL,NULL,NULL,0,0,0,NULL,0,NULL),(25,3,1,'2003-09-01',26,'años','marrón','Emily',10.00,'H',1,NULL,NULL,NULL,NULL,0,0,0,NULL,0,NULL),(26,1,10,'2026-09-01',1,'años','','aaaa',10.00,'M',0,NULL,NULL,NULL,NULL,0,0,0,NULL,0,NULL),(27,1,13,'2026-09-22',NULL,NULL,'','pruebaaa',2.50,'M',0,NULL,NULL,NULL,NULL,0,0,0,NULL,0,NULL),(28,2,10,'2026-09-01',1,'años','gris','pruebacombo',20.00,'H',0,NULL,NULL,NULL,NULL,0,0,0,NULL,0,NULL),(29,2,1,'2015-06-20',11,'años','Blanco','pequeño',11.00,'M',1,NULL,NULL,NULL,NULL,0,0,0,NULL,0,NULL),(30,3,11,'2026-09-24',NULL,NULL,'','jrjr',1.00,'M',0,NULL,NULL,NULL,NULL,0,0,0,NULL,0,NULL),(31,2,13,NULL,NULL,NULL,'','aaaa',11.00,'M',0,NULL,NULL,NULL,NULL,0,0,0,NULL,0,NULL),(32,13,16,'1998-02-14',20,'años','marrón','Aika',5.00,'H',1,NULL,NULL,NULL,NULL,0,0,0,NULL,0,NULL),(33,18,17,'2020-10-10',6,'años','Blanco','Lola',16.00,'H',1,NULL,NULL,NULL,NULL,0,0,0,NULL,0,NULL),(34,20,18,NULL,NULL,NULL,'negro','colita',13.00,'H',0,NULL,NULL,NULL,NULL,0,0,0,NULL,0,NULL),(35,14,19,'2010-10-10',18,'años','Negro','Capuchina',15.00,'H',1,NULL,NULL,NULL,NULL,0,0,0,NULL,0,NULL),(36,16,20,'2024-11-15',NULL,NULL,'marrón','Bartola',10.00,'H',1,NULL,NULL,'68ae0a454ec36d767f907baee5d81b94ecea4b0aa46aa46e9750aa7bde7f3cbc',NULL,0,0,0,NULL,0,NULL),(37,15,17,NULL,NULL,'dias','marrón','Samantha',10.00,'H',0,NULL,NULL,NULL,NULL,0,0,0,NULL,0,NULL),(38,19,19,NULL,0,'dias','','PruebaEdad0',11.00,'H',0,NULL,NULL,NULL,NULL,0,0,0,NULL,0,NULL),(39,1,20,'2026-07-08',7,'años','Marron','Paco',30.00,'M',0,NULL,NULL,NULL,NULL,0,0,0,NULL,0,NULL),(40,16,21,'2020-02-01',6,'años','Blanco','Jagger',15.00,'M',1,'[\"Ceguera\"]',NULL,'3245650100c2127acda9de8a212b49759cdb8629bcd931d64c67336600aa89e7',NULL,1,1,1,'2026-10-19 21:56:26',1,NULL),(41,2,21,'2026-05-10',5,'meses','Blanco','Ponyo',3.00,'H',1,'[\"Ceguera\",\"Requiere medicación\",\"Otra\"]','Acercarse despacio. Necesita medicación diaria','0e8a1e93266cb9d0896f8e8f7683a500ccd1ec21d9612fde525c4de6dce0ce1b','Problemas de equilibrio',1,1,1,'2026-10-19 21:55:48',1,NULL),(42,20,18,'2024-08-07',3,'años','Negro','Coli',12.00,'H',1,'[\"Se asusta con extraños\",\"Otra\"]','Es muy asustadiza, responde al nombre de colita','c076afbdf340f584a21cf7a84fbb51db72805c568e9cfd948b89ebd15bd0a6d7','Tiene un defecto en las partes íntimas',1,1,1,'2026-10-20 17:06:35',1,'uploads/pets/5cfd154ce6e89217191c847e74e05853.jpg');
/*!40000 ALTER TABLE `mascota` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mascota_condicion`
--

DROP TABLE IF EXISTS `mascota_condicion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mascota_condicion` (
  `id_mascota` int NOT NULL,
  `id_condicion` int NOT NULL,
  PRIMARY KEY (`id_mascota`,`id_condicion`),
  KEY `id_condicion` (`id_condicion`),
  CONSTRAINT `mascota_condicion_ibfk_1` FOREIGN KEY (`id_mascota`) REFERENCES `mascota` (`id_mascota`),
  CONSTRAINT `mascota_condicion_ibfk_2` FOREIGN KEY (`id_condicion`) REFERENCES `condicion_mascota` (`id_condicion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mascota_condicion`
--

LOCK TABLES `mascota_condicion` WRITE;
/*!40000 ALTER TABLE `mascota_condicion` DISABLE KEYS */;
/*!40000 ALTER TABLE `mascota_condicion` ENABLE KEYS */;
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
  `categoria` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `orden` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_modulo`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modulo`
--

LOCK TABLES `modulo` WRITE;
/*!40000 ALTER TABLE `modulo` DISABLE KEYS */;
INSERT INTO `modulo` VALUES (1,'Mascotas','/SoftwareVet/modules/pets','fa-paw',1,'Gestión',1),(2,'Clientes','/SoftwareVet/modules/clients','fa-address-book',1,'Gestión',2),(3,'Turnos','/SoftwareVet/modules/appointments','fa-calendar-check',1,'Atención',1),(4,'Usuarios','/SoftwareVet/modules/users','fa-users',1,'Administración',1),(5,'Perfiles','/SoftwareVet/modules/profiles','fa-user-shield',1,'Administración',2),(6,'Historia Clinica','/SoftwareVet/modules/medical_records','fa-stethoscope',1,'Atención',2),(7,'Profesionales','/SoftwareVet/modules/professionals','fa-user-md',1,'Gestión',3),(8,'Especies','/SoftwareVet/modules/species','fa-cat',1,'Gestión',4),(9,'Modulos','/SoftwareVet/modules/system_modules','fa-cubes',1,'Administración',3),(10,'Inicio','/SoftwareVet/php/inicio.php','fa-home',1,'Inicio',1),(14,'Ayuda','/SoftwareVet/modules/help','fa-question-circle',1,'Soporte',1),(15,'Auditoría','/SoftwareVet/modules/audit','fa-history',1,'Administración',4);
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
INSERT INTO `perfil` VALUES (2,'Administrador',1),(7,'Secretaria',1),(8,'Visitante',1);
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
) ENGINE=InnoDB AUTO_INCREMENT=149 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perfil_modulo`
--

LOCK TABLES `perfil_modulo` WRITE;
/*!40000 ALTER TABLE `perfil_modulo` DISABLE KEYS */;
INSERT INTO `perfil_modulo` VALUES (52,8,1),(115,2,15),(116,2,14),(117,2,2),(118,2,8),(119,2,6),(120,2,10),(121,2,1),(122,2,9),(123,2,5),(124,2,7),(125,2,3),(126,2,4),(139,7,15),(140,7,14),(141,7,2),(142,7,8),(143,7,10),(144,7,1),(145,7,9),(146,7,5),(147,7,7),(148,7,4);
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
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `persona`
--

LOCK TABLES `persona` WRITE;
/*!40000 ALTER TABLE `persona` DISABLE KEYS */;
INSERT INTO `persona` VALUES (1,'Andrea','Morel','3704010101','andreacmorel@gmail.com',1),(9,'Rocio','Morel','3704101020','rociomorel@gmail.com',1),(13,'Gabriela','Vera','3704716209','gabrielavera@gmail.com',1),(16,'camila','morel','3704550319','',0),(17,'aaaaaaaaaaaaaa','aaaaaaaaaaa','3704550319','camilamorel@gmail.com',1),(18,'manuel','brunel','3704302101','manu@gmail.com',1),(19,'Daniela','Morel','3704758690','danielaloka@gmail.com',1),(20,'Hector','Villalba','3704567897','hector15v@gmail.com',1),(21,'prueb','prueb','3704030303','',1),(22,'hola','hola','3704939393','',0),(23,'roberto','ayala','3704030303','',1),(24,'Pablo','Morel','37040505050','pablom@gmail.com',1),(25,'prueba','prueba','3704505050','prueba@gmail.com',1),(26,'aaa','aaaa','434234234','',1),(27,'prueba','pruebaa','03704550319','prueba@gmail.com',1),(28,'Milton','Chavez','3704789876','Milton10@gmail.com',1),(29,'Juan Carlos','Peréz','3704789067','Juanca10@gmail.com',1),(30,'Gabriela','Vera','3704565850','gabiiiiiii@gmail.com',1),(31,'Lucia','Morel','3704527431','',1),(32,'Virginia','Wolf','3704787832','virgilokamal@gmail.com',1),(33,'Rocío','Marengo','3704565898','lamarengo@gmail.com',1),(34,'Mariela','Pizzio','3704765387','lamarutop@gmail.com',1);
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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profesional`
--

LOCK TABLES `profesional` WRITE;
/*!40000 ALTER TABLE `profesional` DISABLE KEYS */;
INSERT INTO `profesional` VALUES (7,9,1),(10,18,0),(11,24,0),(12,25,0),(13,29,1);
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tratamientos`
--

LOCK TABLES `tratamientos` WRITE;
/*!40000 ALTER TABLE `tratamientos` DISABLE KEYS */;
INSERT INTO `tratamientos` VALUES (1,'2 dias',NULL,'Medicamento de perro'),(2,'1 dia','1 comprimido cada 6 horas','medicamento'),(3,'10','1 cada 8 hs','Ibuprofeno');
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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `turnos`
--

LOCK TABLES `turnos` WRITE;
/*!40000 ALTER TABLE `turnos` DISABLE KEYS */;
INSERT INTO `turnos` VALUES (1,7,1,'08:00:00','2026-10-03','dolor de ojo',NULL,NULL,NULL,'cancelado',NULL,30,1),(2,7,24,'09:00:00','2026-10-03','vacuna',NULL,NULL,NULL,'cancelado',NULL,30,1),(3,7,5,'16:00:00','2026-10-25','Moquillo',NULL,NULL,NULL,'pendiente',NULL,30,0),(4,13,32,'09:00:00','2026-10-03','Vómitos, diarrea, inapetencia','Se realizo control de temperatura, desparasitación, y fluidoterapia.','Analgésico:(Dipirona) cada 12hs  Antiparasitario (Ivermectina) Fluidoterapia: (Solución Fisiológica Ringer Lactato IV/SC) cada 24hs',150000.00,'completado',NULL,30,1),(5,13,33,'12:00:00','2026-10-03','otitis','prueba','prueba',10000.00,'completado',NULL,30,1),(6,13,33,'10:00:00','2026-10-05','Picazon','control de temperatura','aintibiotico cada 13 hs',200000.00,'completado',NULL,30,1),(7,7,1,'08:00:00','2026-10-07','dolor de ojo',NULL,NULL,NULL,'cancelado',NULL,30,1),(8,7,24,'10:00:00','2026-10-08','pata lastimada','curita','paracetamol',20000.00,'completado',NULL,30,1),(9,7,1,'09:00:00','2026-10-14','pata lastimada','examen fisic','antibiotico',50000.00,'completado',NULL,30,1),(10,13,35,'10:00:00','2026-10-14','Ojo izquierdo enrojecido y con secreciones.',NULL,NULL,NULL,'pendiente',NULL,30,1),(11,13,36,'12:00:00','2026-10-14','Mal aliento y dificultad para masticar.',NULL,NULL,NULL,'pendiente',NULL,30,1),(12,13,40,'15:00:00','2026-10-19','Herida superficial después de una pelea.',NULL,NULL,NULL,'pendiente',NULL,30,1);
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (1,'andrea','$2y$10$haUM4h2yhf9BEqtK96JXPe2qDsz.Mk8jM984iyJ1sfBWsKzB/np12','3a60e3413052e03d79c75af7af2e5c41da38f08739b625b7ad26063af7df32be','andreamorelucp@gmail.com',1,2,'andrea','morel'),(6,'gabriela','$2y$10$WsV/hSScMED7AfbAZtJwT.MUcEW0Fgb0JEuE1Ziv4UgZMU5O2VW3C','9e0363ba668a83f6e981ee2316f48fbb1b8f439fe58dd88d4762e7afa62756f7','gabrielabetianavera@gmail.com',1,7,'gabriela','vera'),(9,'prueba','$2y$10$ijgQGh.LdQvCJJUyHMruT.5gJ08eshnRFBKGxhAlps6GauQs3Wh4.',NULL,'prueba@gmail.com',1,8,'prueba','prueba'),(10,'aaaa','$2y$10$OvL6Tn2Wj80OooDCoxl18OmrvCgjiORRJ43utq8sj/pCw1Tf/zkUC',NULL,'pruebaaa@gmail.com',1,8,'aaa','aaaa');
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

-- Dump completed on 2026-10-27 20:12:50
