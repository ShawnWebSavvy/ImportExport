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
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(8,'2022_03_09_094554_create_bank_filecsvs_table',2),(16,'2022_03_16_120218_create_export_fields_table',4),(17,'2022_03_11_104339_create_file_import_namibias_table',5),(18,'2022_03_16_135932_create_file_import_namibia_archives_table',6),(19,'2022_03_28_083142_create_file_import_botswana_record_install_headers_table',7),(20,'2022_03_28_083208_create_file_import_botswana_record_user_headers_table',7),(21,'2022_03_28_083214_create_file_import_botswana_record_transactions_table',7),(22,'2022_03_28_083220_create_file_import_botswana_record_contras_table',7),(23,'2022_03_28_083226_create_file_import_botswana_record_user_trailers_table',7),(24,'2022_03_28_083233_create_file_import_botswana_record_install_trailers_table',7),(25,'2022_03_28_085457_create_file_import_botswana_record_install_header_archives_table',7),(26,'2022_03_28_085504_create_file_import_botswana_record_user_header_archives_table',7),(27,'2022_03_28_085512_create_file_import_botswana_record_transaction_archives_table',7),(28,'2022_03_28_085527_create_file_import_botswana_record_contra_archives_table',7),(29,'2022_03_28_085536_create_file_import_botswana_record_user_trailer_archives_table',7),(30,'2022_03_28_085542_create_file_import_botswana_record_install_trailer_archives_table',7),(33,'2022_04_19_132245_create_file_import_botswanas_table',8),(34,'2022_04_19_132628_create_file_import_botswana_archives_table',8),(36,'2022_04_19_184954_create_generation_numbers_table',9);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-05-11 12:21:08
