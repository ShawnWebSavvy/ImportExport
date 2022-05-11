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
-- Table structure for table `file_import_namibias`
--

DROP TABLE IF EXISTS `file_import_namibias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `file_import_namibias` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ContractNumber` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ReferenceNumber` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `RecipientAccountHolderName` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `RecipientAccountHolderSurname` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `RecipientAccountHolderInitials` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `RecipientAccountHolderAbbreviatedName` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `OrganizatonName` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `OrganizationCode` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `BranchCode` char(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `BranchSwiftBicCode` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `RecipientAccountNumber` char(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `RecipientNonStandardAccountNumber` char(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `RecipientAccountType` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `RecipientAmount` decimal(10,2) DEFAULT NULL,
  `ActionDate` date DEFAULT NULL,
  `EntryType` char(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `TransactionType` varchar(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ServiceType` char(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Tracking` char(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `SequenceNumber` char(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `SettlementReferenceTraceCode` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ContractReference` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `CollectionReason` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `batch_number` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `file_import_namibias`
--

LOCK TABLES `file_import_namibias` WRITE;
/*!40000 ALTER TABLE `file_import_namibias` DISABLE KEYS */;
/*!40000 ALTER TABLE `file_import_namibias` ENABLE KEYS */;
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
