-- MySQL dump 10.13  Distrib 5.5.29, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: wubku
-- ------------------------------------------------------
-- Server version	5.5.29-0ubuntu0.12.04.1

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
-- Table structure for table `tblAyarlar`
--

DROP TABLE IF EXISTS `tblAyarlar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblAyarlar` (
  `ayarId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bildirimEposta` tinyint(1) NOT NULL,
  `bildirimWUBKU` tinyint(1) NOT NULL,
  `bildirimMesaj` tinyint(1) NOT NULL,
  `sunucuKokDizin` varchar(1000) NOT NULL,
  `wubkuErisimAdresi` varchar(1000) NOT NULL,
  PRIMARY KEY (`ayarId`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblAyarlar`
--

LOCK TABLES `tblAyarlar` WRITE;
/*!40000 ALTER TABLE `tblAyarlar` DISABLE KEYS */;
INSERT INTO `tblAyarlar` VALUES (1,1,1,0,'/var/www/html/uygulamalar/','http://192.168.32.129/html/wubku/');
/*!40000 ALTER TABLE `tblAyarlar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblDizinler`
--

DROP TABLE IF EXISTS `tblDizinler`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblDizinler` (
  `dizinId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dizinYol` varchar(1000) NOT NULL,
  `FKUygulamaId` int(10) unsigned NOT NULL,
  PRIMARY KEY (`dizinId`),
  KEY `FK_tblDizinler_FKUygulamalar` (`FKUygulamaId`),
  CONSTRAINT `FK_tblDizinler_FKUygulamalar` FOREIGN KEY (`FKUygulamaId`) REFERENCES `tblUygulamalar` (`uygulamaId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblDizinler`
--

LOCK TABLES `tblDizinler` WRITE;
/*!40000 ALTER TABLE `tblDizinler` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblDizinler` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblDosyalar`
--

DROP TABLE IF EXISTS `tblDosyalar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblDosyalar` (
  `dosyaId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dosyaAd` varchar(100) NOT NULL,
  `FKDizinId` int(10) unsigned NOT NULL,
  `dosyaOzetDeger` varchar(512) NOT NULL,
  `dosyaDurum` tinyint(1) NOT NULL,
  `dosya` longblob,
  `FKKullaniciId` int(10) unsigned NOT NULL,
  `FKUygulamaId` int(10) unsigned NOT NULL,
  PRIMARY KEY (`dosyaId`),
  KEY `FK_tblDosyalar_FKKullanici` (`FKKullaniciId`),
  KEY `FK_tblDosyalar_FKUygulamaBilgileri` (`FKUygulamaId`),
  KEY `FK_tblDosyalar_FKDizinId` (`FKDizinId`),
  CONSTRAINT `FK_tblDosyalar_FKDizinId` FOREIGN KEY (`FKDizinId`) REFERENCES `tblDizinler` (`dizinId`),
  CONSTRAINT `FK_tblDosyalar_FKKullanici` FOREIGN KEY (`FKKullaniciId`) REFERENCES `tblKullanici` (`kullaniciId`),
  CONSTRAINT `FK_tblDosyalar_FKUygulamalar` FOREIGN KEY (`FKUygulamaId`) REFERENCES `tblUygulamalar` (`uygulamaId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblDosyalar`
--

LOCK TABLES `tblDosyalar` WRITE;
/*!40000 ALTER TABLE `tblDosyalar` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblDosyalar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblIslemler`
--

DROP TABLE IF EXISTS `tblIslemler`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblIslemler` (
  `islemId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `islemTarih` datetime NOT NULL,
  `islemMesaj` varchar(2000) NOT NULL,
  `FKUygulamaId` int(10) unsigned NOT NULL,
  PRIMARY KEY (`islemId`),
  KEY `FK_tblIslemler_FKUygulamaId` (`FKUygulamaId`),
  CONSTRAINT `FK_tblIslemler_FKUygulamaId` FOREIGN KEY (`FKUygulamaId`) REFERENCES `tblUygulamalar` (`uygulamaId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblIslemler`
--

LOCK TABLES `tblIslemler` WRITE;
/*!40000 ALTER TABLE `tblIslemler` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblIslemler` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblKullanici`
--

DROP TABLE IF EXISTS `tblKullanici`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblKullanici` (
  `kullaniciId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kulAd` varchar(50) NOT NULL,
  `ad` varchar(50) NOT NULL,
  `soyad` varchar(50) NOT NULL,
  `sifre` varchar(128) NOT NULL,
  `eposta` varchar(100) NOT NULL,
  `FKUyeRolId` int(10) unsigned NOT NULL,
  `kullaniciOnay` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kullaniciId`),
  KEY `FK_tblKullanici_FKRol` (`FKUyeRolId`),
  CONSTRAINT `FK_tblKullanici_FKRol` FOREIGN KEY (`FKUyeRolId`) REFERENCES `tblKullaniciRol` (`rolId`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblKullanici`
--

LOCK TABLES `tblKullanici` WRITE;
/*!40000 ALTER TABLE `tblKullanici` DISABLE KEYS */;
INSERT INTO `tblKullanici` VALUES (1,'yönetici','WUBKU','Yöneticisi','81dc9bdb52d04dc20036dbd8313ed055','ornek@eposta.com',1,1);
/*!40000 ALTER TABLE `tblKullanici` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblKullaniciRol`
--

DROP TABLE IF EXISTS `tblKullaniciRol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblKullaniciRol` (
  `rolId` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `rolAd` varchar(30) NOT NULL,
  PRIMARY KEY (`rolId`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblKullaniciRol`
--

LOCK TABLES `tblKullaniciRol` WRITE;
/*!40000 ALTER TABLE `tblKullaniciRol` DISABLE KEYS */;
INSERT INTO `tblKullaniciRol` VALUES (1,'Yönetici'),(2,'Yazýlýmcý'),(3,'Ýzleyici');
/*!40000 ALTER TABLE `tblKullaniciRol` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblSaldirilar`
--

DROP TABLE IF EXISTS `tblSaldirilar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblSaldirilar` (
  `saldiriId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `saldiriTarih` datetime NOT NULL,
  `epostaGonderildiMi` tinyint(1) NOT NULL,
  `saldiriMesaj` varchar(2000) NOT NULL,
  `FKUygulamaId` int(10) unsigned NOT NULL,
  PRIMARY KEY (`saldiriId`),
  KEY `FK_tblSaldirilar_FKUygulamaId` (`FKUygulamaId`),
  CONSTRAINT `FK_tblSaldirilar_FKUygulamaId` FOREIGN KEY (`FKUygulamaId`) REFERENCES `tblUygulamalar` (`uygulamaId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblSaldirilar`
--

LOCK TABLES `tblSaldirilar` WRITE;
/*!40000 ALTER TABLE `tblSaldirilar` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblSaldirilar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblUygulamaBilgileri`
--

DROP TABLE IF EXISTS `tblUygulamaBilgileri`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblUygulamaBilgileri` (
  `degerId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uygulamaOzetDeger` varchar(512) NOT NULL,
  PRIMARY KEY (`degerId`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblUygulamaBilgileri`
--

LOCK TABLES `tblUygulamaBilgileri` WRITE;
/*!40000 ALTER TABLE `tblUygulamaBilgileri` DISABLE KEYS */;
INSERT INTO `tblUygulamaBilgileri` VALUES (1,'30793490651c8010b1b8993291868c87623e2d5649025f11460bf19422b477ee87fa7909373ec2ef728084aa68f369a743e102dab72d3278a6c9cd2acac54760');
/*!40000 ALTER TABLE `tblUygulamaBilgileri` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblUygulamalar`
--

DROP TABLE IF EXISTS `tblUygulamalar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblUygulamalar` (
  `uygulamaId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uygulamaAd` varchar(100) NOT NULL,
  `uygulamaDurum` tinyint(3) unsigned NOT NULL,
  `eklenmeTarihi` datetime NOT NULL,
  `uygulamaOzetDeger` varchar(512) NOT NULL,
  `uygulamaKokDizin` varchar(200) NOT NULL,
  `uygulamaDizinOzetDeger` varchar(512) NOT NULL,
  `uygulamaDosyaOzetDeger` varchar(512) NOT NULL,
  PRIMARY KEY (`uygulamaId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblUygulamalar`
--

LOCK TABLES `tblUygulamalar` WRITE;
/*!40000 ALTER TABLE `tblUygulamalar` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblUygulamalar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblYetkiler`
--

DROP TABLE IF EXISTS `tblYetkiler`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblYetkiler` (
  `yetkiId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `FKKullaniciId` int(10) unsigned NOT NULL,
  `FKUygulamaId` int(10) unsigned NOT NULL,
  PRIMARY KEY (`yetkiId`),
  KEY `FK_tblYetkiler_FKKullaniciId` (`FKKullaniciId`),
  KEY `FK_tblYetkiler_FKUygulamaId` (`FKUygulamaId`),
  CONSTRAINT `FK_tblYetkiler_FKKullaniciId` FOREIGN KEY (`FKKullaniciId`) REFERENCES `tblKullanici` (`kullaniciId`),
  CONSTRAINT `FK_tblYetkiler_FKUygulamaId` FOREIGN KEY (`FKUygulamaId`) REFERENCES `tblUygulamalar` (`uygulamaId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblYetkiler`
--

LOCK TABLES `tblYetkiler` WRITE;
/*!40000 ALTER TABLE `tblYetkiler` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblYetkiler` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-06-11 16:25:43
