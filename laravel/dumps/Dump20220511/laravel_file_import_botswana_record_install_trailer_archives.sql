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
-- Table structure for table `file_import_botswana_record_install_trailer_archives`
--

DROP TABLE IF EXISTS `file_import_botswana_record_install_trailer_archives`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `file_import_botswana_record_install_trailer_archives` (
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
  `BlockCount` char(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `RecordCount` char(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `UserHeaderTrailerCount` char(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `file_import_botswana_record_install_trailer_archives`
--

LOCK TABLES `file_import_botswana_record_install_trailer_archives` WRITE;
/*!40000 ALTER TABLE `file_import_botswana_record_install_trailer_archives` DISABLE KEYS */;
INSERT INTO `file_import_botswana_record_install_trailer_archives` VALUES (1,'94','1001','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','E;;;;;','','0','0','2022-04-04 07:58:26','2022-04-04 07:58:26'),(2,'94','1001','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','E;;;;;','','0','0','2022-04-04 07:58:26','2022-04-04 07:58:26'),(3,'94','1001','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','E;;;;;','','0','0','2022-04-04 07:58:26','2022-04-04 07:58:26'),(4,'94','1001','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','E;;;;;','','0','0','2022-04-04 07:58:26','2022-04-04 07:58:26'),(5,'94','1001','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','E;;;;;','','0','0','2022-04-04 07:58:26','2022-04-04 07:58:26'),(6,'94','1001','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','E;;;;;','','0','0','2022-04-04 07:58:26','2022-04-04 07:58:26'),(7,'94','1001','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','E;;;;;','','0','0','2022-04-04 07:58:26','2022-04-04 07:58:26'),(8,'94','1001','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','E;;;;;','','0','0','2022-04-04 07:58:26','2022-04-04 07:58:26'),(9,'94','1001','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','E;;;;;','','0','0','2022-04-04 07:58:26','2022-04-04 07:58:26'),(10,'94','1001','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','E;;;;;','','0','0','2022-04-04 07:58:26','2022-04-04 07:58:26'),(11,'94','1001','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','E;;;;;','','0','0','2022-04-04 07:58:26','2022-04-04 07:58:26'),(12,'94','1001','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','E;;;;;','','0','0','2022-04-04 07:58:26','2022-04-04 07:58:26'),(13,'94','1001','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','E;;;;;','','0','0','2022-04-04 07:58:26','2022-04-04 07:58:26'),(14,'94','1001','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','E;;;;;','','0','0','2022-04-04 07:58:26','2022-04-04 07:58:26'),(15,'94','1001','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','E;;;;;','','0','0','2022-04-04 07:58:26','2022-04-04 07:58:26'),(16,'94','1001','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','E;;;;;','','0','0','2022-04-04 07:58:26','2022-04-04 07:58:26'),(17,'94','1001','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','E;;;;;','','0','0','2022-04-04 07:58:26','2022-04-04 07:58:26'),(18,'94','1001','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','E;;;;;','','0','0','2022-04-04 07:58:26','2022-04-04 07:58:26'),(19,'94','1001','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','E;;;;;','','0','0','2022-04-04 07:58:26','2022-04-04 07:58:26'),(20,'94','1001','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','E;;;;;','','0','0','2022-04-04 07:58:26','2022-04-04 07:58:26'),(21,'94','1001','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','E;;;;;','','0','0','2022-04-04 07:58:26','2022-04-04 07:58:26'),(22,'94','1001','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','E000003000','300002','900000','4','2022-04-04 07:58:26','2022-04-04 07:58:26'),(23,'94','1001','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','E000003000','300002','900000','4','2022-04-04 07:58:26','2022-04-04 07:58:26'),(24,'94','1001','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','E000003000','300002','900000','4','2022-04-04 07:58:26','2022-04-04 07:58:26'),(25,'94','1001','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','E000003000','300002','900000','4','2022-04-04 07:58:26','2022-04-04 07:58:26'),(26,'94','1001','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','E000003000','300002','900000','4','2022-04-04 07:58:26','2022-04-04 07:58:26'),(27,'94','1001','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','E000003000','300002','900000','4','2022-04-04 07:58:26','2022-04-04 07:58:26'),(28,'94','1001','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','E000003000','300002','900000','4','2022-04-04 07:58:26','2022-04-04 07:58:26'),(29,'94','1001','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','E000003000','300002','900000','4','2022-04-04 07:58:26','2022-04-04 07:58:26'),(30,'94','1001','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','E000003000','300002','900000','4','2022-04-04 07:58:26','2022-04-04 07:58:26'),(31,'94','1001','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','E000003000','300002','900000','4','2022-04-04 07:58:26','2022-04-04 07:58:26'),(32,'94','1001','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','E000003000','300002','900000','4','2022-04-04 07:58:26','2022-04-04 07:58:26'),(33,'94','1001','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','E000003000','300002','900000','4','2022-04-04 07:58:26','2022-04-04 07:58:26'),(34,'94','1001','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','E000003000','300002','900000','4','2022-04-04 07:58:26','2022-04-04 07:58:26'),(35,'94','1001','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','E000003000','300002','900000','4','2022-04-04 07:58:26','2022-04-04 07:58:26'),(36,'94','1001','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','E000003000','300002','900000','4','2022-04-04 07:58:26','2022-04-04 07:58:26'),(37,'94','1001','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','E000003000','300002','900000','4','2022-04-04 07:58:26','2022-04-04 07:58:26'),(38,'94','1001','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','E000003000','300002','900000','4','2022-04-04 07:58:26','2022-04-04 07:58:26'),(39,'94','1001','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','E000003000','300002','900000','4','2022-04-04 07:58:26','2022-04-04 07:58:26'),(40,'94','1001','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','E000003000','300002','900000','4','2022-04-04 07:58:26','2022-04-04 07:58:26'),(41,'94','1001','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','E000003000','300002','900000','4','2022-04-04 07:58:26','2022-04-04 07:58:26'),(42,'94','1001','????????','4055','0021','2022-12-15','2022-08-25','0001','1800','0180','E000003000','300002','900000','4','2022-04-04 07:58:26','2022-04-04 07:58:26');
/*!40000 ALTER TABLE `file_import_botswana_record_install_trailer_archives` ENABLE KEYS */;
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
