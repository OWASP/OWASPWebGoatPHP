-- MySQL dump 10.13  Distrib 5.5.35, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: webgoatphp
-- ------------------------------------------------------
-- Server version	5.5.35-0ubuntu0.12.04.2

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
-- Table structure for table `app_user`
--

DROP TABLE IF EXISTS `app_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_user` (
  `Firstname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Lastname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  CONSTRAINT `FK_88BDF3E911D3633A` FOREIGN KEY (`ID`) REFERENCES `jf_users` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_user`
--

LOCK TABLES `app_user` WRITE;
/*!40000 ALTER TABLE `app_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `app_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jf__test_logs`
--

DROP TABLE IF EXISTS `jf__test_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jf__test_logs` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Subject` varchar(256) COLLATE utf8_bin NOT NULL,
  `Data` text COLLATE utf8_bin NOT NULL,
  `Severity` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `SessionID` varchar(64) COLLATE utf8_bin DEFAULT NULL,
  `Timestamp` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jf__test_logs`
--

LOCK TABLES `jf__test_logs` WRITE;
/*!40000 ALTER TABLE `jf__test_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jf__test_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jf__test_options`
--

DROP TABLE IF EXISTS `jf__test_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jf__test_options` (
  `UserID` int(11) NOT NULL,
  `Name` varchar(200) COLLATE utf8_bin NOT NULL,
  `Value` text COLLATE utf8_bin NOT NULL,
  `Expiration` int(11) NOT NULL,
  PRIMARY KEY (`UserID`,`Name`),
  KEY `Expiration` (`Expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jf__test_options`
--

LOCK TABLES `jf__test_options` WRITE;
/*!40000 ALTER TABLE `jf__test_options` DISABLE KEYS */;
/*!40000 ALTER TABLE `jf__test_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jf__test_rbac_permissions`
--

DROP TABLE IF EXISTS `jf__test_rbac_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jf__test_rbac_permissions` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Lft` int(11) NOT NULL,
  `Rght` int(11) NOT NULL,
  `Title` char(64) COLLATE utf8_bin NOT NULL,
  `Description` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `Title` (`Title`),
  KEY `Lft` (`Lft`),
  KEY `Rght` (`Rght`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jf__test_rbac_permissions`
--

LOCK TABLES `jf__test_rbac_permissions` WRITE;
/*!40000 ALTER TABLE `jf__test_rbac_permissions` DISABLE KEYS */;
INSERT INTO `jf__test_rbac_permissions` VALUES (1,1,2,'root','root');
/*!40000 ALTER TABLE `jf__test_rbac_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jf__test_rbac_rolepermissions`
--

DROP TABLE IF EXISTS `jf__test_rbac_rolepermissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jf__test_rbac_rolepermissions` (
  `RoleID` int(11) NOT NULL,
  `PermissionID` int(11) NOT NULL,
  `AssignmentDate` int(11) NOT NULL,
  PRIMARY KEY (`RoleID`,`PermissionID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jf__test_rbac_rolepermissions`
--

LOCK TABLES `jf__test_rbac_rolepermissions` WRITE;
/*!40000 ALTER TABLE `jf__test_rbac_rolepermissions` DISABLE KEYS */;
INSERT INTO `jf__test_rbac_rolepermissions` VALUES (1,1,0);
/*!40000 ALTER TABLE `jf__test_rbac_rolepermissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jf__test_rbac_roles`
--

DROP TABLE IF EXISTS `jf__test_rbac_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jf__test_rbac_roles` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Lft` int(11) NOT NULL,
  `Rght` int(11) NOT NULL,
  `Title` varchar(128) COLLATE utf8_bin NOT NULL,
  `Description` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `Title` (`Title`),
  KEY `Lft` (`Lft`),
  KEY `Rght` (`Rght`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jf__test_rbac_roles`
--

LOCK TABLES `jf__test_rbac_roles` WRITE;
/*!40000 ALTER TABLE `jf__test_rbac_roles` DISABLE KEYS */;
INSERT INTO `jf__test_rbac_roles` VALUES (1,1,2,'root','root');
/*!40000 ALTER TABLE `jf__test_rbac_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jf__test_rbac_userroles`
--

DROP TABLE IF EXISTS `jf__test_rbac_userroles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jf__test_rbac_userroles` (
  `UserID` int(11) NOT NULL,
  `RoleID` int(11) NOT NULL,
  `AssignmentDate` int(11) NOT NULL,
  PRIMARY KEY (`UserID`,`RoleID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jf__test_rbac_userroles`
--

LOCK TABLES `jf__test_rbac_userroles` WRITE;
/*!40000 ALTER TABLE `jf__test_rbac_userroles` DISABLE KEYS */;
INSERT INTO `jf__test_rbac_userroles` VALUES (1,1,0);
/*!40000 ALTER TABLE `jf__test_rbac_userroles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jf__test_session`
--

DROP TABLE IF EXISTS `jf__test_session`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jf__test_session` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `SessionID` char(64) COLLATE utf8_bin NOT NULL,
  `UserID` int(11) NOT NULL,
  `IP` char(15) COLLATE utf8_bin NOT NULL,
  `LoginDate` int(11) NOT NULL,
  `LastAccess` int(11) NOT NULL,
  `AccessCount` int(11) NOT NULL DEFAULT '1',
  `CurrentRequest` varchar(1024) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `SessionID` (`SessionID`),
  KEY `UserID` (`UserID`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jf__test_session`
--

LOCK TABLES `jf__test_session` WRITE;
/*!40000 ALTER TABLE `jf__test_session` DISABLE KEYS */;
/*!40000 ALTER TABLE `jf__test_session` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jf__test_users`
--

DROP TABLE IF EXISTS `jf__test_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jf__test_users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Username` char(128) COLLATE utf8_bin NOT NULL,
  `Password` char(128) COLLATE utf8_bin NOT NULL,
  `Salt` varchar(128) COLLATE utf8_bin NOT NULL,
  `Protocol` float NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Username` (`Username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jf__test_users`
--

LOCK TABLES `jf__test_users` WRITE;
/*!40000 ALTER TABLE `jf__test_users` DISABLE KEYS */;
INSERT INTO `jf__test_users` VALUES (1,'root','09095c2a72470b8a203fb4bc5193c548802177b9f786ce1c5c0f423f68ed2518c8ce4c536240fc25be7cae06fc1860f2ec9c3b469770244837b91689c7a0c9a5','dff41c6629ca83aee37987d3363ce8c9d17b3c3511797956de9abf3535132d9c2daf1a59a32df97e2adafcbe707c989b9957f44ae336f6dc4ada6ef121eeebf4',1);
/*!40000 ALTER TABLE `jf__test_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jf__test_xuser`
--

DROP TABLE IF EXISTS `jf__test_xuser`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jf__test_xuser` (
  `ID` int(11) NOT NULL,
  `Email` varchar(256) COLLATE utf8_bin NOT NULL,
  `PasswordChangeTimestamp` int(11) NOT NULL DEFAULT '0',
  `TemporaryResetPassword` varchar(256) COLLATE utf8_bin NOT NULL DEFAULT '',
  `TemporaryResetPasswordTimeout` int(11) NOT NULL DEFAULT '0',
  `LastLoginTimestamp` int(11) NOT NULL DEFAULT '0',
  `FailedLoginAttempts` int(11) NOT NULL DEFAULT '0',
  `LockTimeout` int(16) NOT NULL DEFAULT '0',
  `Activated` int(11) NOT NULL DEFAULT '0',
  `CreateTimestamp` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Email` (`Email`(128))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jf__test_xuser`
--

LOCK TABLES `jf__test_xuser` WRITE;
/*!40000 ALTER TABLE `jf__test_xuser` DISABLE KEYS */;
INSERT INTO `jf__test_xuser` VALUES (1,'root@localhost',0,'',0,0,0,0,1,0);
/*!40000 ALTER TABLE `jf__test_xuser` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jf_logs`
--

DROP TABLE IF EXISTS `jf_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jf_logs` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Subject` varchar(256) COLLATE utf8_bin NOT NULL,
  `Data` text COLLATE utf8_bin NOT NULL,
  `Severity` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `SessionID` varchar(64) COLLATE utf8_bin DEFAULT NULL,
  `Timestamp` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=139 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jf_logs`
--

LOCK TABLES `jf_logs` WRITE;
/*!40000 ALTER TABLE `jf_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jf_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jf_options`
--

DROP TABLE IF EXISTS `jf_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jf_options` (
  `UserID` int(11) NOT NULL,
  `Name` varchar(200) COLLATE utf8_bin NOT NULL,
  `Value` text COLLATE utf8_bin NOT NULL,
  `Expiration` int(11) NOT NULL,
  PRIMARY KEY (`UserID`,`Name`),
  KEY `Expiration` (`Expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jf_options`
--

LOCK TABLES `jf_options` WRITE;
/*!40000 ALTER TABLE `jf_options` DISABLE KEYS */;
/*!40000 ALTER TABLE `jf_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jf_rbac_permissions`
--

DROP TABLE IF EXISTS `jf_rbac_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jf_rbac_permissions` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Lft` int(11) NOT NULL,
  `Rght` int(11) NOT NULL,
  `Title` char(64) COLLATE utf8_bin NOT NULL,
  `Description` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `Title` (`Title`),
  KEY `Lft` (`Lft`),
  KEY `Rght` (`Rght`)
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jf_rbac_permissions`
--

LOCK TABLES `jf_rbac_permissions` WRITE;
/*!40000 ALTER TABLE `jf_rbac_permissions` DISABLE KEYS */;
INSERT INTO `jf_rbac_permissions` VALUES (51,13,18,'single','Single User Mode'),(52,14,17,'single_chal','Single User Mode Challenges'),(53,15,16,'view_single_chal','View Single Mode Challenges'),(54,19,34,'workshop','Workshop Mode'),(55,20,25,'workshop_chal','Workshop mode challenges'),(56,21,22,'view_workshop_chal','View Workshop mode challenges'),(57,23,24,'edit_workshop_chal','Edit Workshop mode challenges'),(58,26,33,'workshop_users','Workshop mode users'),(59,27,28,'add_workshop_users','Add Workshop mode users'),(60,29,30,'edit_workshop_users','Edit Workshop mode users'),(61,31,32,'delete_workshop_users','Del Workshop mode users'),(62,35,50,'contest','Contest Mode'),(63,36,41,'contest_chal','Contest mode challenges'),(64,37,38,'view_contest_chal','View Contest mode challenges'),(65,39,40,'edit_contest_chal','Edit Contest mode challenges'),(66,42,49,'contest_users','Contest mode users'),(67,43,44,'add_contest_users','Add Contest mode users'),(68,45,46,'edit_contest_users','Edit Contest mode users'),(69,47,48,'delete_contest_users','Del Contest mode users'),(76,0,11,'root_lesson_AccessControlMatrix','Root'),(77,3,4,'public_share','Public Share'),(78,5,6,'time_card_entry','Time Card Entry'),(79,0,1,'performance_review','Performance Review76'),(80,7,8,'site_manager','Site Manager'),(81,9,10,'account_manager','Account Manager');
/*!40000 ALTER TABLE `jf_rbac_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jf_rbac_rolepermissions`
--

DROP TABLE IF EXISTS `jf_rbac_rolepermissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jf_rbac_rolepermissions` (
  `RoleID` int(11) NOT NULL,
  `PermissionID` int(11) NOT NULL,
  `AssignmentDate` int(11) NOT NULL,
  PRIMARY KEY (`RoleID`,`PermissionID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jf_rbac_rolepermissions`
--

LOCK TABLES `jf_rbac_rolepermissions` WRITE;
/*!40000 ALTER TABLE `jf_rbac_rolepermissions` DISABLE KEYS */;
INSERT INTO `jf_rbac_rolepermissions` VALUES (26,50,1400492620),(28,53,1400492620),(29,56,1400492620),(30,64,1400492620),(32,54,1400492620),(33,62,1400492620),(35,71,1401788941),(36,72,1401788941),(36,75,1401788942),(37,73,1401788941),(37,74,1401788942),(38,75,1401788942),(39,76,1402065242),(40,77,1402065242),(41,78,1402065242),(41,81,1402065242),(42,79,1402065242),(42,80,1402065242),(43,81,1402065242);
/*!40000 ALTER TABLE `jf_rbac_rolepermissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jf_rbac_roles`
--

DROP TABLE IF EXISTS `jf_rbac_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jf_rbac_roles` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Lft` int(11) NOT NULL,
  `Rght` int(11) NOT NULL,
  `Title` varchar(128) COLLATE utf8_bin NOT NULL,
  `Description` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `Title` (`Title`),
  KEY `Lft` (`Lft`),
  KEY `Rght` (`Rght`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jf_rbac_roles`
--

LOCK TABLES `jf_rbac_roles` WRITE;
/*!40000 ALTER TABLE `jf_rbac_roles` DISABLE KEYS */;
INSERT INTO `jf_rbac_roles` VALUES (27,17,24,'user','Users of the application'),(28,18,19,'single_user','User of the single user mode'),(29,20,21,'workshop_user','User of the workshop mode'),(30,22,23,'contest_user','User of the contest mode'),(32,11,12,'workshop_admin','Admin of the workshop mode'),(33,13,14,'contest_admin','Admin of the contest mode'),(39,0,9,'root_lesson_AccessControlMatrix','Root'),(40,1,2,'public','Public'),(41,3,4,'user','User'),(42,5,6,'manager','Manager'),(43,7,8,'admin','Administrator');
/*!40000 ALTER TABLE `jf_rbac_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jf_rbac_userroles`
--

DROP TABLE IF EXISTS `jf_rbac_userroles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jf_rbac_userroles` (
  `UserID` int(11) NOT NULL,
  `RoleID` int(11) NOT NULL,
  `AssignmentDate` int(11) NOT NULL,
  PRIMARY KEY (`UserID`,`RoleID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jf_rbac_userroles`
--

LOCK TABLES `jf_rbac_userroles` WRITE;
/*!40000 ALTER TABLE `jf_rbac_userroles` DISABLE KEYS */;
INSERT INTO `jf_rbac_userroles` VALUES (3,28,1400492620),(4,32,1403160626),(100,35,1401788942),(100,40,1402065242),(101,36,1401788942),(101,41,1402065242),(102,37,1401788942),(102,42,1402065242),(103,38,1401788942),(103,43,1402065242);
/*!40000 ALTER TABLE `jf_rbac_userroles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jf_session`
--

DROP TABLE IF EXISTS `jf_session`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jf_session` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `SessionID` char(64) COLLATE utf8_bin NOT NULL,
  `UserID` int(11) NOT NULL,
  `IP` char(15) COLLATE utf8_bin NOT NULL,
  `LoginDate` int(11) NOT NULL,
  `LastAccess` int(11) NOT NULL,
  `AccessCount` int(11) NOT NULL DEFAULT '1',
  `CurrentRequest` varchar(1024) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `SessionID` (`SessionID`),
  KEY `UserID` (`UserID`)
) ENGINE=MEMORY AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jf_session`
--

LOCK TABLES `jf_session` WRITE;
/*!40000 ALTER TABLE `jf_session` DISABLE KEYS */;
INSERT INTO `jf_session` VALUES (2,'og39io9hmacn6d85ato750j1r6',0,'127.0.0.1',1403159128,1403160703,53,'');
/*!40000 ALTER TABLE `jf_session` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jf_stats`
--

DROP TABLE IF EXISTS `jf_stats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jf_stats` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL,
  `SessionID` varchar(128) COLLATE utf8_bin NOT NULL,
  `Timestamp` int(11) NOT NULL,
  `Page` varchar(1024) COLLATE utf8_bin NOT NULL,
  `Query` varchar(1024) COLLATE utf8_bin NOT NULL,
  `IP` char(15) COLLATE utf8_bin NOT NULL,
  `Host` varchar(256) COLLATE utf8_bin NOT NULL,
  `Protocol` varchar(10) COLLATE utf8_bin NOT NULL,
  `UserAgent` varchar(1024) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `UserID` (`UserID`),
  KEY `SessionID` (`SessionID`),
  KEY `Timestamp` (`Timestamp`),
  KEY `Page` (`Page`(255)),
  KEY `IP` (`IP`),
  KEY `Host` (`Host`(255)),
  KEY `Protocol` (`Protocol`)
) ENGINE=InnoDB AUTO_INCREMENT=21079 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jf_stats`
--

LOCK TABLES `jf_stats` WRITE;
/*!40000 ALTER TABLE `jf_stats` DISABLE KEYS */;
/*!40000 ALTER TABLE `jf_stats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jf_users`
--

DROP TABLE IF EXISTS `jf_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jf_users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(255) COLLATE utf8_bin NOT NULL,
  `Password` varchar(255) COLLATE utf8_bin NOT NULL,
  `Salt` varchar(255) COLLATE utf8_bin NOT NULL,
  `Protocol` float NOT NULL,
  `discriminator` varchar(255) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Username` (`Username`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jf_users`
--

LOCK TABLES `jf_users` WRITE;
/*!40000 ALTER TABLE `jf_users` DISABLE KEYS */;
INSERT INTO `jf_users` VALUES (3,'guest','c2fe032175d9a9696028ce1ea748b683da0a31d6796facf3f0ae2659ccea13da9c3eb653e4ae5aee52da3e17a8867fc6b8d4e38f10c404ec8aa8e0ce265bd5df','6739f6d27652e055c03572aec85411a854a0390ca9dba0ec9313e66f1f2d2b3fde7f24e8fd7063d763de58729d4c4b0db171f4a69854324b944513ca8df08900',1,''),(4,'workshop.admin','188c1fdb935ffdec748baa077a73d106723352743d7c74f202486957f3b2780093b7e0ed4030a9438de665ff9b1527b49b032c61f636d1c6f3c0ba2a7f8b4865','424559d8c6b510f9b0e58e4ddf9b738870c17ecf8f9a14a1e2cfd083c013c2b8ec3c494ab2f83b0a81d2b7677e472953925c70cd7f236c9d18cc2192d5898910',1,'');
/*!40000 ALTER TABLE `jf_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jf_xuser`
--

DROP TABLE IF EXISTS `jf_xuser`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jf_xuser` (
  `ID` int(11) NOT NULL,
  `Email` varchar(255) COLLATE utf8_bin NOT NULL,
  `PasswordChangeTimestamp` int(11) NOT NULL,
  `TemporaryResetPassword` varchar(255) COLLATE utf8_bin NOT NULL,
  `TemporaryResetPasswordTimeout` int(11) NOT NULL,
  `LastLoginTimestamp` int(11) NOT NULL,
  `FailedLoginAttempts` int(11) NOT NULL,
  `LockTimeout` int(11) NOT NULL,
  `Activated` tinyint(1) NOT NULL,
  `CreateTimestamp` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  CONSTRAINT `FK_54C202BC11D3633A` FOREIGN KEY (`ID`) REFERENCES `jf_users` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jf_xuser`
--

LOCK TABLES `jf_xuser` WRITE;
/*!40000 ALTER TABLE `jf_xuser` DISABLE KEYS */;
/*!40000 ALTER TABLE `jf_xuser` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lesson_AccessControlMatrix_users`
--

DROP TABLE IF EXISTS `lesson_AccessControlMatrix_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lesson_AccessControlMatrix_users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(30) DEFAULT NULL,
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lesson_AccessControlMatrix_users`
--

LOCK TABLES `lesson_AccessControlMatrix_users` WRITE;
/*!40000 ALTER TABLE `lesson_AccessControlMatrix_users` DISABLE KEYS */;
INSERT INTO `lesson_AccessControlMatrix_users` VALUES (100,'John'),(101,'Shivam'),(102,'Larry'),(103,'Mark');
/*!40000 ALTER TABLE `lesson_AccessControlMatrix_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lesson_BusinessLayerAccessControl_users`
--

DROP TABLE IF EXISTS `lesson_BusinessLayerAccessControl_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lesson_BusinessLayerAccessControl_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(30) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `street` varchar(100) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `salary` int(11) DEFAULT NULL,
  `cc_no` varchar(20) DEFAULT NULL,
  `cc_limit` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lesson_BusinessLayerAccessControl_users`
--

LOCK TABLES `lesson_BusinessLayerAccessControl_users` WRITE;
/*!40000 ALTER TABLE `lesson_BusinessLayerAccessControl_users` DISABLE KEYS */;
INSERT INTO `lesson_BusinessLayerAccessControl_users` VALUES (2,'employee','Curly','Stooge','curly','1112 Crusoe Lane','New York, NY','410-667-6654',50000,'NA','0'),(3,'employee','Eric','Walker','eric','1160 Prescott Rd','New York, NY','410-667-6654',13000,'NA','0'),(6,'manager','David','Giambi','david','5132 DIMM Avenue','New York, NY','610-521-8413',100000,'6981754825018101','10000'),(7,'admin','John','Wayne','john','129 Third St','New York, NY','610-213-1134',200000,'4437334565679921','30000');
/*!40000 ALTER TABLE `lesson_BusinessLayerAccessControl_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lesson_NumericSQLInjection_weather`
--

DROP TABLE IF EXISTS `lesson_NumericSQLInjection_weather`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lesson_NumericSQLInjection_weather` (
  `station` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `state` varchar(5) NOT NULL,
  `min_temp` int(11) NOT NULL,
  `max_temp` int(11) NOT NULL,
  PRIMARY KEY (`station`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lesson_NumericSQLInjection_weather`
--

LOCK TABLES `lesson_NumericSQLInjection_weather` WRITE;
/*!40000 ALTER TABLE `lesson_NumericSQLInjection_weather` DISABLE KEYS */;
INSERT INTO `lesson_NumericSQLInjection_weather` VALUES (1,'Columbia','MD',-10,20),(2,'Seattle','WA',-15,18),(3,'New York','NY',-10,20),(4,'Houston','TX',20,30),(5,'Camp David','MD',-5,20),(6,'Ice Station','NA',-30,0);
/*!40000 ALTER TABLE `lesson_NumericSQLInjection_weather` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lesson_XSS2_messages`
--

DROP TABLE IF EXISTS `lesson_XSS2_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lesson_XSS2_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lesson_XSS2_messages`
--

LOCK TABLES `lesson_XSS2_messages` WRITE;
/*!40000 ALTER TABLE `lesson_XSS2_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `lesson_XSS2_messages` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-06-19 12:29:12
