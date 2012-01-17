CREATE DATABASE  IF NOT EXISTS `chamilo` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;
USE `chamilo`;
-- MySQL dump 10.13  Distrib 5.5.9, for Win32 (x86)
--
-- Host: localhost    Database: chamilo
-- ------------------------------------------------------
-- Server version	5.1.53-community-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `discovery_bamaflex_history`
--

DROP TABLE IF EXISTS `discovery_bamaflex_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `discovery_bamaflex_history` (
  `id` int(10) unsigned NOT NULL,
  `history_id` int(10) unsigned NOT NULL,
  `history_source` int(10) unsigned NOT NULL,
  `previous_id` int(10) unsigned NOT NULL,
  `previous_source` int(10) unsigned NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `discovery_bamaflex_history`
--

LOCK TABLES `discovery_bamaflex_history` WRITE;
/*!40000 ALTER TABLE `discovery_bamaflex_history` DISABLE KEYS */;
INSERT INTO `discovery_bamaflex_history` VALUES (1,2,1,18,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(2,1,1,15,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(3,3,1,19,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(4,4,1,20,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(5,9,1,25,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(6,5,1,21,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(7,6,1,22,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(8,7,1,23,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(9,8,1,24,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(10,1,2,8,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(11,1,2,4,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(12,8,2,9,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(13,9,2,10,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(14,10,2,11,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(15,11,2,27,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(16,18,2,84,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(17,84,2,76,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(18,76,2,68,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(19,68,2,60,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(20,60,2,52,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(21,52,2,44,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(22,44,2,36,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(23,36,2,28,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(24,21,2,87,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(25,87,2,79,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(26,79,2,71,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(27,71,2,63,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(28,63,2,55,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(29,55,2,47,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(30,47,2,39,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(31,39,2,31,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(32,37,1,19,1,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(33,37,1,21,1,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(34,63,1,49,1,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(35,64,1,49,1,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(36,25,2,91,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(37,91,2,83,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(38,83,2,75,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(39,75,2,67,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(40,67,2,59,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(41,59,2,51,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(42,51,2,43,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(43,43,2,35,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(44,22,2,88,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(45,88,2,80,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(46,80,2,72,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(47,72,2,64,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(48,64,2,56,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(49,56,2,48,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(50,48,2,40,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(51,40,2,32,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(52,24,2,90,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(53,90,2,82,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(54,82,2,74,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(55,74,2,66,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(56,66,2,58,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(57,58,2,50,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(58,50,2,42,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(59,42,2,34,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(60,23,2,89,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(61,89,2,81,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(62,81,2,73,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(63,73,2,65,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(64,65,2,57,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(65,57,2,49,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(66,49,2,41,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(67,41,2,33,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(68,38,1,20,1,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(69,38,1,22,1,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(70,38,1,25,1,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(71,19,2,85,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(72,85,2,77,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(73,77,2,69,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(74,69,2,61,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(75,61,2,53,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(76,53,2,45,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(77,45,2,37,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(78,37,2,29,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(79,4,2,5,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(80,5,2,6,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(81,6,2,7,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(82,7,2,26,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(83,20,2,86,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(84,86,2,78,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(85,78,2,70,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(86,70,2,62,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(87,62,2,54,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(88,54,2,46,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(89,46,2,38,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex'),(90,38,2,30,2,'application\\discovery\\module\\faculty\\implementation\\bamaflex');
/*!40000 ALTER TABLE `discovery_bamaflex_history` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-01-11 17:43:24
