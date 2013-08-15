-- MySQL dump 10.13  Distrib 5.5.28, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: ioc
-- ------------------------------------------------------
-- Server version	5.5.28-0ubuntu0.12.04.3

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
-- Table structure for table `P`
--

DROP TABLE IF EXISTS `P`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `P` (
  `pkP` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `mode` bit(1) NOT NULL DEFAULT b'0',
  `dateCreated` int(18) NOT NULL,
  `lastMod` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pkP`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `PLP`
--

DROP TABLE IF EXISTS `PLP`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PLP` (
  `pkPLP` int(11) NOT NULL AUTO_INCREMENT,
  `fkP` int(11) NOT NULL,
  `fkP2` int(11) NOT NULL,
  `mode` bit(1) NOT NULL DEFAULT b'0',
  `dateCreated` int(18) NOT NULL,
  `lastMod` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pkPLP`),
  UNIQUE KEY `Link` (`fkP`,`fkP2`,`mode`),
  KEY `fkP` (`fkP`),
  KEY `fkP2` (`fkP2`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `PLPQual`
--

DROP TABLE IF EXISTS `PLPQual`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PLPQual` (
  `pkPLPQual` int(11) NOT NULL AUTO_INCREMENT,
  `fkP` int(11) NOT NULL,
  `fkPQual` int(11) NOT NULL,
  `value` longblob,
  `mode` bit(1) NOT NULL DEFAULT b'0',
  `dateCreated` int(18) NOT NULL,
  `lastMod` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pkPLPQual`),
  UNIQUE KEY `Link` (`fkP`,`fkPQual`,`mode`),
  KEY `fkP` (`fkP`),
  KEY `fkPQual` (`fkPQual`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `PLPType`
--

DROP TABLE IF EXISTS `PLPType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PLPType` (
  `pkPLPType` int(11) NOT NULL AUTO_INCREMENT,
  `fkP` int(11) NOT NULL,
  `fkPType` int(11) NOT NULL,
  `order` int(2) UNISGNED NOT NULL,
  `mode` bit(1) NOT NULL DEFAULT b'0',
  `dateCreated` int(18) NOT NULL,
  `lastMod` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pkPLPType`),
  UNIQUE KEY `Link` (`fkP`,`fkPType`,`mode`),
  KEY `fkP` (`fkP`),
  KEY `fkPType` (`fkPType`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `PQual`
--

DROP TABLE IF EXISTS `PQual`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PQual` (
  `pkPQual` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `MACHINE` varchar(255) DEFAULT NULL,
  `smDesc` varchar(255) NOT NULL DEFAULT '',
  `mode` bit(1) NOT NULL DEFAULT b'0',
  `dateCreated` int(18) NOT NULL,
  `lastMod` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pkPQual`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `PQualLPType`
--

DROP TABLE IF EXISTS `PQualLPType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PQualLPType` (
  `pkPQualLPType` int(11) NOT NULL AUTO_INCREMENT,
  `fkPQual` int(11) NOT NULL,
  `fkPType` int(11) NOT NULL,
  `mode` bit(1) NOT NULL DEFAULT b'0',
  `value` blob,
  `dateCreated` int(18) NOT NULL,
  `lastMod` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pkPQualLPType`),
  UNIQUE KEY `Link` (`fkPQual`,`fkPType`,`mode`),
  KEY `fkPQual` (`fkPQual`),
  KEY `fkPType` (`fkPType`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `PType`
--

DROP TABLE IF EXISTS `PType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PType` (
  `pkPType` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `MACHINE` varchar(255) DEFAULT NULL,
  `lgDesc` text NOT NULL,
  `isCore` bit(1) NOT NULL DEFAULT b'0',
  `order` int(2) UNISIGNED NOT NULL DEFAULT 1,
  `mode` bit(1) NOT NULL DEFAULT b'0',
  `dateCreated` int(18) NOT NULL,
  `lastMod` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pkPType`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-01-23  4:45:59
