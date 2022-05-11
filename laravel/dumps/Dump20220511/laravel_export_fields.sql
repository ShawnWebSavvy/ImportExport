-- MySQL dump 10.13  Distrib 8.0.28, for macos11 (x86_64)
--
-- Host: 127.0.0.1    Database: laravel
-- ------------------------------------------------------
-- Server version	5.7.34

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
-- Table structure for table `export_fields`
--

DROP TABLE IF EXISTS `export_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `export_fields` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `field_1` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `field_2` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `field_3` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `field_4` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `field_5` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `field_6` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dateField_1` date DEFAULT NULL,
  `dateField_2` date DEFAULT NULL,
  `dateField_3` date DEFAULT NULL,
  `dateField_4` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=531 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `export_fields`
--

LOCK TABLES `export_fields` WRITE;
/*!40000 ALTER TABLE `export_fields` DISABLE KEYS */;
INSERT INTO `export_fields` VALUES (530,NULL,NULL,NULL,NULL,NULL,NULL,'2022-04-01','2022-04-07',NULL,NULL);
/*!40000 ALTER TABLE `export_fields` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-05-11 12:21:06
