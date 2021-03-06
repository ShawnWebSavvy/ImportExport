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
-- Table structure for table `file_import_botswana_record_install_headers`
--

DROP TABLE IF EXISTS `file_import_botswana_record_install_headers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `file_import_botswana_record_install_headers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `RecordIdentifier` char(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `VolumeNumber` char(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `TapeSerialNumber` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `InstallationIDfrom` char(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `InstallationIDto` char(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `CreationDate` date DEFAULT NULL,
  `PurgeDate` date DEFAULT NULL,
  `GenerationNumber` char(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `BlockLength` char(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `RecordLength` char(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Service` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `file_import_botswana_record_install_headers`
--

LOCK TABLES `file_import_botswana_record_install_headers` WRITE;
/*!40000 ALTER TABLE `file_import_botswana_record_install_headers` DISABLE KEYS */;
INSERT INTO `file_import_botswana_record_install_headers` VALUES (10,'02','1001','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','MAGTAPE;;;',NULL,'2022-04-19 15:36:04','2022-04-19 15:36:04'),(11,'02','1002','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','MAGTAPE;;;',NULL,'2022-04-19 15:36:04','2022-04-19 15:36:04'),(12,'02','1003','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','MAGTAPE;;;',NULL,'2022-04-19 15:36:04','2022-04-19 15:36:04'),(13,'02','1004','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','MAGTAPE;;;',NULL,'2022-04-19 15:36:04','2022-04-19 15:36:04'),(14,'02','1005','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','MAGTAPE;;;',NULL,'2022-04-19 15:36:04','2022-04-19 15:36:04'),(15,'02','1006','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','MAGTAPE;;;',NULL,'2022-04-19 15:36:04','2022-04-19 15:36:04'),(16,'02','1007','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','MAGTAPE;;;',NULL,'2022-04-19 15:36:04','2022-04-19 15:36:04'),(17,'02','1008','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','MAGTAPE;;;',NULL,'2022-04-19 15:36:04','2022-04-19 15:36:04'),(18,'02','1009','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','MAGTAPE;;;',NULL,'2022-04-19 15:36:04','2022-04-19 15:36:04');
/*!40000 ALTER TABLE `file_import_botswana_record_install_headers` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-05-11 12:21:07
