-- MySQL dump 10.13  Distrib 8.0.36, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: produccionpanela
-- ------------------------------------------------------
-- Server version	8.0.36

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
-- Table structure for table `fondada`
--

DROP TABLE IF EXISTS `fondada`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fondada` (
  `id_fondada` int NOT NULL AUTO_INCREMENT,
  `id_molienda` int DEFAULT NULL,
  `cantidad_litros` decimal(10,2) NOT NULL DEFAULT '100.00',
  `fecha_agregada` datetime NOT NULL,
  PRIMARY KEY (`id_fondada`),
  KEY `id_molienda` (`id_molienda`),
  CONSTRAINT `fondada_ibfk_1` FOREIGN KEY (`id_molienda`) REFERENCES `molienda` (`id_molienda`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `labor`
--

DROP TABLE IF EXISTS `labor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `labor` (
  `id_labor` int NOT NULL AUTO_INCREMENT,
  `nombre_labor` varchar(100) NOT NULL,
  `precio_por_fondada` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_labor`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `molienda`
--

DROP TABLE IF EXISTS `molienda`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `molienda` (
  `id_molienda` int NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(255) DEFAULT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime DEFAULT NULL,
  `estado` enum('activa','inactiva') DEFAULT 'activa',
  PRIMARY KEY (`id_molienda`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pago`
--

DROP TABLE IF EXISTS `pago`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pago` (
  `id_pago` int NOT NULL AUTO_INCREMENT,
  `id_participacion` int DEFAULT NULL,
  `monto_total` decimal(10,2) NOT NULL,
  `fecha_pago` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pago`),
  KEY `id_participacion` (`id_participacion`),
  CONSTRAINT `pago_ibfk_1` FOREIGN KEY (`id_participacion`) REFERENCES `participacion` (`id_participacion`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pagoprocesamiento`
--

DROP TABLE IF EXISTS `pagoprocesamiento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pagoprocesamiento` (
  `id_pago_procesamiento` int NOT NULL AUTO_INCREMENT,
  `id_molienda` int DEFAULT NULL,
  `id_persona` int DEFAULT NULL,
  `cantidad_fondadas` int DEFAULT NULL,
  `monto_a_pagar` decimal(10,2) NOT NULL,
  `fecha_pago` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pago_procesamiento`),
  KEY `id_molienda` (`id_molienda`),
  KEY `id_persona` (`id_persona`),
  CONSTRAINT `pagoprocesamiento_ibfk_1` FOREIGN KEY (`id_molienda`) REFERENCES `molienda` (`id_molienda`) ON DELETE CASCADE,
  CONSTRAINT `pagoprocesamiento_ibfk_2` FOREIGN KEY (`id_persona`) REFERENCES `persona` (`id_persona`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `participacion`
--

DROP TABLE IF EXISTS `participacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `participacion` (
  `id_participacion` int NOT NULL AUTO_INCREMENT,
  `id_persona` int DEFAULT NULL,
  `id_molienda` int DEFAULT NULL,
  `id_labor` int DEFAULT NULL,
  `cantidad_fondadas` int NOT NULL,
  `fecha_participacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `es_procesamiento` tinyint(1) DEFAULT '0',
  `monto_total` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_participacion`),
  KEY `id_persona` (`id_persona`),
  KEY `id_molienda` (`id_molienda`),
  KEY `id_labor` (`id_labor`),
  CONSTRAINT `participacion_ibfk_1` FOREIGN KEY (`id_persona`) REFERENCES `persona` (`id_persona`) ON DELETE CASCADE,
  CONSTRAINT `participacion_ibfk_2` FOREIGN KEY (`id_molienda`) REFERENCES `molienda` (`id_molienda`) ON DELETE CASCADE,
  CONSTRAINT `participacion_ibfk_3` FOREIGN KEY (`id_labor`) REFERENCES `labor` (`id_labor`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `persona`
--

DROP TABLE IF EXISTS `persona`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `persona` (
  `id_persona` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `cedula` varchar(20) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_persona`),
  UNIQUE KEY `cedula` (`cedula`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `preciopanela`
--

DROP TABLE IF EXISTS `preciopanela`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `preciopanela` (
  `id_precio` int NOT NULL AUTO_INCREMENT,
  `tipo_panela` enum('grande','mediana','pequeña') NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `fecha_actualizacion` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_precio`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `produccionpanela`
--

DROP TABLE IF EXISTS `produccionpanela`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produccionpanela` (
  `id_produccion` int NOT NULL AUTO_INCREMENT,
  `id_fondada` int DEFAULT NULL,
  `tipo_panela` enum('grande','mediana','pequeña') NOT NULL,
  `cantidad_panela` int NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `id_precio` int DEFAULT NULL,
  PRIMARY KEY (`id_produccion`),
  KEY `id_fondada` (`id_fondada`),
  KEY `id_precio` (`id_precio`),
  CONSTRAINT `produccionpanela_ibfk_1` FOREIGN KEY (`id_fondada`) REFERENCES `fondada` (`id_fondada`) ON DELETE CASCADE,
  CONSTRAINT `produccionpanela_ibfk_2` FOREIGN KEY (`id_precio`) REFERENCES `preciopanela` (`id_precio`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-10-05 14:20:58
