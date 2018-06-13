CREATE DATABASE  IF NOT EXISTS `doothanprod` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `doothanprod`;
-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: doothanprod.chowgu9tw8mo.ap-south-1.rds.amazonaws.com    Database: doothanprod
-- ------------------------------------------------------
-- Server version	5.6.37-log

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
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `doothan_id` int(11) NOT NULL,
  `notification` text NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES (1,11,'This is a test','2018-03-15 23:35:30'),(2,196,'my pickup','2018-04-18 16:14:51'),(3,196,'where is my pickup??','2018-04-19 02:27:25'),(4,196,'hello','2018-04-19 16:06:37'),(5,195,'i didnt get any goods to deliver','2018-04-22 06:45:11'),(6,240,'pls give me a job','2018-04-25 09:46:33'),(7,547,'want cancel my request','2018-05-14 16:39:53'),(8,985,'Hi','2018-05-15 04:11:13'),(9,499,'please hellpme.. my prifile upgrade issu','2018-05-15 04:47:44'),(10,1149,'please cancel my request','2018-05-15 07:57:59'),(11,780,'call me','2018-05-16 08:34:41'),(12,1207,'nilambur. not drop box available','2018-05-16 10:39:09'),(13,1790,'I  would like to start a dropbox (droping point)  from where people can collect the goods. I own a studio at Kanjany, Thrissur.','2018-05-17 01:25:21'),(14,1733,'please upgrade me to doothan','2018-05-17 04:45:56'),(15,263,'I think i missed a work. i got a mail for the work notification. i think the mail notification is not a proper immediate informing medium. An sms notification is more better way to inform us about the works. Then we can immediately respond. so please take it as a request.','2018-05-17 19:36:19'),(16,300,'How I can upgrade to a dropbox','2018-05-18 04:32:06'),(17,575,'OK','2018-05-18 06:29:51'),(18,502,'Upgrade pending...','2018-05-19 14:38:51'),(19,458,'how to use','2018-05-20 11:03:25'),(20,589,'Update my home address:\nPeringayil House, P.O.Manalur-680617\nPh. 8075699620(WhatsApp)','2018-05-20 13:01:30'),(21,2517,'i couldnt to upgrade for','2018-05-21 16:41:07'),(22,2517,'I couldnt to upgrade for dhoothan  &  drop box owner','2018-05-21 16:42:31'),(23,853,'Please give me orders','2018-05-22 13:18:44'),(24,196,'give me my pickup s now iam free','2018-05-23 10:47:28'),(25,2603,'upgrade','2018-05-24 16:44:12'),(26,994,'i have no pickup notifications.','2018-05-25 04:08:38'),(27,1339,'Can i cancel my order','2018-05-28 18:47:42'),(28,1758,'hi','2018-05-29 06:25:24'),(29,1597,'Dear Sir,  I dint receve telephone varification.','2018-05-29 12:18:42'),(30,1540,'howchange  my work from pickup to delivery','2018-05-29 13:47:12');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-05-29  9:52:02
