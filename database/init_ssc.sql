-- MySQL dump 10.13  Distrib 5.7.15, for Linux (x86_64)
--
-- Host: localhost    Database: grab
-- ------------------------------------------------------
-- Server version	5.7.15-0ubuntu0.16.04.1

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
-- Table structure for table `cqssc`
--

DROP TABLE IF EXISTS `cqssc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cqssc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `qishu` char(20) NOT NULL COMMENT '开奖期数',
  `one` tinyint(3) NOT NULL COMMENT '万位 第一位号码',
  `two` tinyint(3) NOT NULL COMMENT '千位 第二位号码',
  `three` tinyint(3) NOT NULL COMMENT '百位 第三位号码',
  `four` tinyint(3) NOT NULL COMMENT '十位 第四位号码',
  `five` tinyint(3) NOT NULL COMMENT '个位 第五位号码',
  `code` char(20) NOT NULL COMMENT '开奖号码',
  `front_three_type` tinyint(1) NOT NULL COMMENT '前三类型\n1=>组6\n2=>组3',
  `center_three_type` tinyint(1) NOT NULL COMMENT '中三类型\n1=>组6\n2=>组3',
  `after_three_type` tinyint(1) NOT NULL COMMENT '后三类型\n1=>组6\n2=>组3',
  `kj_time` char(50) DEFAULT NULL COMMENT '开奖时间',
  `time` int(11) NOT NULL COMMENT '数据记录时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='重庆时时彩';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `analysisCqssc`
--

DROP TABLE IF EXISTS `analysisCqssc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `analysisCqssc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cqssc_id` int(11) NOT NULL COMMENT '重庆时时彩 数据分析表',
  `front_three_lucky_txt` text COMMENT '前三 中奖号码',
  `front_three_regret_txt` text COMMENT '前三 未中奖号码',
  `center_three_lucky_txt` text COMMENT '中三中奖号',
  `center_three_regret_txt` text COMMENT '中三未中奖号',
  `after_three_lucky_txt` text COMMENT '后三中奖号码',
  `after_three_regret_txt` text COMMENT '后三未中奖号码',
  `data_txt` text COMMENT '当前导入的数据',
  `type` int(11) NOT NULL DEFAULT '1' COMMENT '数据包\n1=>重庆时时彩数据包1\n2=>重庆时时彩数据包2',
  `time` int(11) NOT NULL COMMENT '数据创建日期',
  PRIMARY KEY (`id`),
  KEY `fk_analysisCqssc_cqssc1_idx` (`cqssc_id`),
  CONSTRAINT `fk_analysisCqssc_cqssc1` FOREIGN KEY (`cqssc_id`) REFERENCES `cqssc` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='重庆时时彩分析';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tjssc`
--

DROP TABLE IF EXISTS `tjssc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tjssc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `qishu` char(20) NOT NULL COMMENT '开奖期数',
  `one` tinyint(3) NOT NULL COMMENT '万位 第一位号码',
  `two` tinyint(3) NOT NULL COMMENT '千位 第二位号码',
  `three` tinyint(3) NOT NULL COMMENT '百位 第三位号码',
  `four` tinyint(3) NOT NULL COMMENT '十位 第四位号码',
  `five` tinyint(3) NOT NULL COMMENT '个位 第五位号码',
  `code` char(20) NOT NULL COMMENT '开奖号码',
  `front_three_type` tinyint(1) NOT NULL COMMENT '前三类型\n1=>组6\n2=>组3',
  `center_three_type` tinyint(1) NOT NULL COMMENT '中三类型\n1=>组6\n2=>组3',
  `after_three_type` tinyint(1) NOT NULL COMMENT '后三类型\n1=>组6\n2=>组3',
  `kj_time` char(50) DEFAULT NULL COMMENT '开奖时间',
  `time` int(11) NOT NULL COMMENT '数据记录时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='天津时时彩';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `analysisTjssc`
--

DROP TABLE IF EXISTS `analysisTjssc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `analysisTjssc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tjssc_id` int(11) NOT NULL COMMENT '天津时时彩开奖表主键id',
  `front_three_lucky_txt` text COMMENT '前三 中奖号码',
  `front_three_regret_txt` text COMMENT '前三 未中奖号码',
  `center_three_lucky_txt` text COMMENT '中三中奖号',
  `center_three_regret_txt` text COMMENT '中三未中奖号',
  `after_three_lucky_txt` text COMMENT '后三中奖号码',
  `after_three_regret_txt` text COMMENT '后三未中奖号码',
  `data_txt` text COMMENT '当前导入的数据',
  `type` int(11) NOT NULL DEFAULT '1' COMMENT '数据包\n1=>天津时时彩数据包1\n2=>天津时时彩数据包2',
  `time` int(11) NOT NULL COMMENT '数据创建日期',
  PRIMARY KEY (`id`),
  KEY `fk_analysisTjssc_tjssc1_idx` (`tjssc_id`),
  CONSTRAINT `fk_analysisTjssc_tjssc1` FOREIGN KEY (`tjssc_id`) REFERENCES `tjssc` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='天津时时彩分析';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xjssc`
--

DROP TABLE IF EXISTS `xjssc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xjssc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `qishu` char(20) NOT NULL COMMENT '开奖期数',
  `one` tinyint(3) NOT NULL COMMENT '万位 第一位号码',
  `two` tinyint(3) NOT NULL COMMENT '千位 第二位号码',
  `three` tinyint(3) NOT NULL COMMENT '百位 第三位号码',
  `four` tinyint(3) NOT NULL COMMENT '十位 第四位号码',
  `five` tinyint(3) NOT NULL COMMENT '个位 第五位号码',
  `code` char(20) NOT NULL COMMENT '开奖号码',
  `front_three_type` tinyint(1) NOT NULL COMMENT '前三类型\n1=>组6\n2=>组3',
  `center_three_type` tinyint(1) NOT NULL COMMENT '中三类型\n1=>组6\n2=>组3',
  `after_three_type` tinyint(1) NOT NULL COMMENT '后三类型\n1=>组6\n2=>组3',
  `kj_time` char(50) DEFAULT NULL COMMENT '开奖时间',
  `time` int(11) NOT NULL COMMENT '数据记录时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='新疆时时彩';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `analysisXjssc`
--

DROP TABLE IF EXISTS `analysisXjssc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `analysisXjssc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `xjssc_id` int(11) NOT NULL COMMENT '新疆时时彩开奖表主键ID',
  `front_three_lucky_txt` text COMMENT '前三 中奖号码',
  `front_three_regret_txt` text COMMENT '前三 未中奖号码',
  `center_three_lucky_txt` text COMMENT '中三中奖号',
  `center_three_regret_txt` text COMMENT '中三未中奖号',
  `after_three_lucky_txt` text COMMENT '后三中奖号码',
  `after_three_regret_txt` text COMMENT '后三未中奖号码',
  `data_txt` text COMMENT '当前导入的数据',
  `type` int(11) NOT NULL DEFAULT '1' COMMENT '数据包\n1=>重庆时时彩数据包1\n2=>重庆时时彩数据包2',
  `time` int(11) NOT NULL COMMENT '数据包\n1=>新疆时时彩数据包1\n2=>新疆时时彩数据包2',
  PRIMARY KEY (`id`),
  KEY `fk_analysisXjssc_xjssc1_idx` (`xjssc_id`),
  CONSTRAINT `fk_analysisXjssc_xjssc1` FOREIGN KEY (`xjssc_id`) REFERENCES `xjssc` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='新疆时时彩数据分析';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-10-08 19:56:27


ALTER TABLE `grab`.`analysisTjssc`
CHANGE COLUMN `type` `type` INT(11) NOT NULL COMMENT '天津数据包id' ,
ADD INDEX `fk_analysisTjssc_tjssc1_idx` (`tjssc_id` ASC),
DROP INDEX `fk_analysisTjssc_tjssc1_idx` ;

ALTER TABLE `grab`.`analysisXjssc`
CHANGE COLUMN `type` `type` INT(11) NOT NULL COMMENT '新疆数据包id' ,
ADD INDEX `fk_analysisXjssc_xjssc1_idx` (`xjssc_id` ASC),
DROP INDEX `fk_analysisXjssc_xjssc1_idx` ;

ALTER TABLE `grab`.`analysisCqssc`
CHANGE COLUMN `type` `type` INT(11) NOT NULL COMMENT '重庆数据包id' ,
ADD INDEX `fk_analysisCqssc_cqssc1_idx` (`cqssc_id` ASC),
DROP INDEX `fk_analysisCqssc_cqssc1_idx` ;