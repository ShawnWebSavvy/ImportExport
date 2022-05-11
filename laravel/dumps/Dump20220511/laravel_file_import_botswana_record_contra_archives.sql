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
-- Table structure for table `file_import_botswana_record_contra_archives`
--

DROP TABLE IF EXISTS `file_import_botswana_record_contra_archives`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `file_import_botswana_record_contra_archives` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `RecordIdentifier` char(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `UserBranch` char(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `UserCode` char(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `SequenceNumber` char(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `HomingBranch` char(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `HomingAccountNumber` char(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `AccountType` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Amount` char(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ActionDate` date DEFAULT NULL,
  `EntryType` char(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `UserAbbreviatedName` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `UserReference` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `AccountName` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `file_import_botswana_record_contra_archives`
--

LOCK TABLES `file_import_botswana_record_contra_archives` WRITE;
/*!40000 ALTER TABLE `file_import_botswana_record_contra_archives` DISABLE KEYS */;
/*!40000 ALTER TABLE `file_import_botswana_record_contra_archives` ENABLE KEYS */;
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
