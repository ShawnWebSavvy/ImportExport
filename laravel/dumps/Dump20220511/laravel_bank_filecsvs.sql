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
-- Table structure for table `bank_filecsvs`
--

DROP TABLE IF EXISTS `bank_filecsvs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bank_filecsvs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Bank` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `BankImpoertFileType` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ContractNumber` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ReferenceNumber` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `AccountHolderName` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `AccountHolderSurname` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `AccountHolderInitials` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `OrganizatonName` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `OrganizationCode` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `BranchCode` char(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `BranchSwiftBicCode` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `AccountNumber` char(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `NonStandardAccountNumber` char(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `AccountType` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Amount` decimal(10,2) DEFAULT NULL,
  `ActionDate` timestamp NULL DEFAULT NULL,
  `EntryType` char(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `TransactionType` varchar(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ServiceType` char(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Tracking` char(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `SequenceNumber` char(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `SettlementReferenceTraceCode` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ContractReference` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `CollectionReason` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `RecordIdentifier` char(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `VolumeNumber` char(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `TapeSerialNumber` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `InstallationClientsUserIdCodeFrom` char(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `InstallationClientsUserIdCodeTo` char(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `CreationDate` timestamp NULL DEFAULT NULL,
  `PurgeDate` timestamp NULL DEFAULT NULL,
  `InstallationGenerationNumber` char(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `BlockLength` char(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Service` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `UserCode` char(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `FirstSequenceNumber` char(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `UserGenerationNumber` char(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `HomingRecipientBranch` char(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `HomingRecipientAccountNumber` char(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `HomingRecipientInstitution` char(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `HomingRecipientAccountName` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `EntryClass` char(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `TaxCode` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `UserAccountReference` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `UserContraAccountReference` varchar(35) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `NominatedUserAccountName` varchar(35) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `LastSequenceNumber` char(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `FirstActionDate` timestamp NULL DEFAULT NULL,
  `LastActionDate` timestamp NULL DEFAULT NULL,
  `NumberDebitRecords` char(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `NumberCreditRecords` char(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `NumberContraRecords` char(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `TotalDebitValue` char(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `TotalCreditValue` char(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `HashTotalOfHomingAccountNumbers` char(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `BlockCount` char(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `RecordLength` char(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `RecordCount` char(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `UserHeaderTrailerCount` char(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bank_filecsvs`
--

LOCK TABLES `bank_filecsvs` WRITE;
/*!40000 ALTER TABLE `bank_filecsvs` DISABLE KEYS */;
INSERT INTO `bank_filecsvs` VALUES (1,'ss','FileType',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2022-03-09 15:47:03','2022-03-09 15:47:03');
/*!40000 ALTER TABLE `bank_filecsvs` ENABLE KEYS */;
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
