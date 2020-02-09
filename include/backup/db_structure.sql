-- MySQL dump 10.16  Distrib 10.1.38-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: 09source
-- ------------------------------------------------------
-- Server version	10.1.38-MariaDB-0+deb9u1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `ach_bonus`
--

DROP TABLE IF EXISTS `ach_bonus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ach_bonus` (
  `bonus_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `bonus_desc` text CHARACTER SET utf8,
  `bonus_type` tinyint(4) NOT NULL DEFAULT '0',
  `bonus_do` text CHARACTER SET utf8,
  PRIMARY KEY (`bonus_id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `achievementist`
--

DROP TABLE IF EXISTS `achievementist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `achievementist` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `achievname` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `notes` varchar(250) CHARACTER SET utf8 DEFAULT NULL,
  `clienticon` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  UNIQUE KEY `id` (`id`),
  KEY `hostname` (`achievname`)
) ENGINE=MyISAM AUTO_INCREMENT=88 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `achievements`
--

DROP TABLE IF EXISTS `achievements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `achievements` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(5) NOT NULL DEFAULT '0',
  `achievement` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `date` int(11) NOT NULL DEFAULT '0',
  `icon` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `achievementid` int(5) NOT NULL DEFAULT '0',
  UNIQUE KEY `id` (`id`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM AUTO_INCREMENT=12223 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `announcement_main`
--

DROP TABLE IF EXISTS `announcement_main`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `announcement_main` (
  `main_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(10) unsigned NOT NULL DEFAULT '0',
  `created` int(11) NOT NULL DEFAULT '0',
  `expires` int(11) NOT NULL DEFAULT '0',
  `sql_query` text CHARACTER SET utf8,
  `subject` text CHARACTER SET utf8,
  `body` text CHARACTER SET utf8,
  PRIMARY KEY (`main_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `announcement_process`
--

DROP TABLE IF EXISTS `announcement_process`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `announcement_process` (
  `process_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `main_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`process_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=51 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `attachmentdownloads`
--

DROP TABLE IF EXISTS `attachmentdownloads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attachmentdownloads` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `file_id` int(10) NOT NULL DEFAULT '0',
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_id` int(10) NOT NULL DEFAULT '0',
  `date` int(11) NOT NULL DEFAULT '0',
  `times_downloaded` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fileid_userid` (`file_id`,`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `attachments`
--

DROP TABLE IF EXISTS `attachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attachments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `topic_id` int(10) unsigned NOT NULL DEFAULT '0',
  `post_id` int(10) unsigned NOT NULL DEFAULT '0',
  `file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `size` bigint(20) unsigned NOT NULL DEFAULT '0',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `times_downloaded` int(10) unsigned NOT NULL DEFAULT '0',
  `added` int(11) NOT NULL DEFAULT '0',
  `extension` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `topicid` (`topic_id`),
  KEY `postid` (`post_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `attachments_bunny`
--

DROP TABLE IF EXISTS `attachments_bunny`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attachments_bunny` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `file_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `file` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `added` int(11) NOT NULL DEFAULT '0',
  `extension_` enum('zip','rar') CHARACTER SET utf8 NOT NULL DEFAULT 'zip',
  `size` bigint(20) unsigned NOT NULL DEFAULT '0',
  `times_downloaded` int(10) unsigned NOT NULL DEFAULT '0',
  `extension` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `topic_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `avps`
--

DROP TABLE IF EXISTS `avps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `avps` (
  `arg` varchar(20) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `value_s` text CHARACTER SET utf8,
  `value_i` int(11) NOT NULL DEFAULT '0',
  `value_u` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`arg`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bannedemails`
--

DROP TABLE IF EXISTS `bannedemails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bannedemails` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `added` int(11) NOT NULL,
  `addedby` int(10) unsigned NOT NULL DEFAULT '0',
  `comment` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=55 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bans`
--

DROP TABLE IF EXISTS `bans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `added` int(11) NOT NULL,
  `addedby` int(10) unsigned NOT NULL DEFAULT '0',
  `comment` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `first` int(11) DEFAULT NULL,
  `last` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `first_last` (`first`)
) ENGINE=MyISAM AUTO_INCREMENT=47 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `blackjack`
--

DROP TABLE IF EXISTS `blackjack`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blackjack` (
  `userid` int(11) NOT NULL DEFAULT '0',
  `points` int(11) NOT NULL DEFAULT '0',
  `status` enum('playing','waiting') CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'playing',
  `cards` text CHARACTER SET utf8,
  `date` int(11) DEFAULT '0',
  `gameover` enum('yes','no') CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'no',
  PRIMARY KEY (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `blocks`
--

DROP TABLE IF EXISTS `blocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blocks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `blockid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bonus`
--

DROP TABLE IF EXISTS `bonus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bonus` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `bonusname` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `points` decimal(10,1) NOT NULL DEFAULT '0.0',
  `description` text CHARACTER SET utf8,
  `art` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `menge` bigint(20) unsigned NOT NULL DEFAULT '0',
  `pointspool` decimal(10,1) NOT NULL DEFAULT '1.0',
  `enabled` enum('yes','no') CHARACTER SET latin1 NOT NULL DEFAULT 'yes' COMMENT 'This will determined a switch if the bonus is enabled or not! enabled by default',
  `minpoints` decimal(10,1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bonuslog`
--

DROP TABLE IF EXISTS `bonuslog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bonuslog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `donation` decimal(10,1) NOT NULL,
  `type` varchar(44) CHARACTER SET utf8 DEFAULT NULL,
  `added_at` int(11) NOT NULL,
  KEY `id` (`id`),
  KEY `added_at` (`added_at`),
  KEY `type` (`type`)
) ENGINE=MyISAM AUTO_INCREMENT=1805 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='log of contributors towards freeleech etc...';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bookmarks`
--

DROP TABLE IF EXISTS `bookmarks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bookmarks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `torrentid` int(10) unsigned NOT NULL DEFAULT '0',
  `private` enum('yes','no') CHARACTER SET utf8 NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bugs`
--

DROP TABLE IF EXISTS `bugs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bugs` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `sender` int(10) NOT NULL DEFAULT '0',
  `added` int(12) NOT NULL DEFAULT '0',
  `priority` enum('low','high','veryhigh') CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'low',
  `problem` text CHARACTER SET utf8,
  `status` enum('fixed','ignored','na') CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'na',
  `staff` int(10) NOT NULL DEFAULT '0',
  `title` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cards`
--

DROP TABLE IF EXISTS `cards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `points` int(11) NOT NULL DEFAULT '0',
  `pic` text CHARACTER SET utf8,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=53 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `casino`
--

DROP TABLE IF EXISTS `casino`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `casino` (
  `userid` int(10) NOT NULL DEFAULT '0',
  `win` bigint(20) NOT NULL DEFAULT '0',
  `lost` bigint(20) NOT NULL DEFAULT '0',
  `trys` int(11) NOT NULL DEFAULT '0',
  `date` int(11) NOT NULL DEFAULT '0',
  `enableplay` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `deposit` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `casino_bets`
--

DROP TABLE IF EXISTS `casino_bets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `casino_bets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) NOT NULL DEFAULT '0',
  `proposed` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `challenged` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `amount` bigint(20) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL DEFAULT '0',
  `winner` varchar(25) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM AUTO_INCREMENT=328 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `cat_desc` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `parent_id` mediumint(5) NOT NULL DEFAULT '-1',
  `tabletype` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `min_class` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cheaters`
--

DROP TABLE IF EXISTS `cheaters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cheaters` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `added` int(11) NOT NULL,
  `userid` int(10) NOT NULL DEFAULT '0',
  `torrentid` int(10) NOT NULL DEFAULT '0',
  `client` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `rate` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `beforeup` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `upthis` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `timediff` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `userip` varchar(15) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=146 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `class_config`
--

DROP TABLE IF EXISTS `class_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `class_config` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `value` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `classname` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `classcolor` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `classpic` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `class_promo`
--

DROP TABLE IF EXISTS `class_promo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `class_promo` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) CHARACTER SET utf8 DEFAULT NULL,
  `min_ratio` decimal(10,2) NOT NULL,
  `uploaded` bigint(20) NOT NULL,
  `time` int(11) NOT NULL,
  `low_ratio` decimal(10,2) NOT NULL,
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cleanup`
--

DROP TABLE IF EXISTS `cleanup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cleanup` (
  `clean_id` int(10) NOT NULL AUTO_INCREMENT,
  `clean_title` char(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `clean_file` char(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `clean_time` int(11) NOT NULL DEFAULT '0',
  `clean_increment` int(11) NOT NULL DEFAULT '0',
  `clean_cron_key` char(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `clean_log` tinyint(1) NOT NULL DEFAULT '0',
  `clean_desc` text CHARACTER SET utf8,
  `clean_on` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`clean_id`),
  KEY `clean_time` (`clean_time`)
) ENGINE=MyISAM AUTO_INCREMENT=83 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cleanup_log`
--

DROP TABLE IF EXISTS `cleanup_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cleanup_log` (
  `clog_id` int(10) NOT NULL AUTO_INCREMENT,
  `clog_event` char(150) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `clog_time` int(11) NOT NULL DEFAULT '0',
  `clog_ip` char(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `clog_desc` text CHARACTER SET utf8,
  PRIMARY KEY (`clog_id`)
) ENGINE=MyISAM AUTO_INCREMENT=743220 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `coins`
--

DROP TABLE IF EXISTS `coins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `coins` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `torrentid` int(10) unsigned NOT NULL DEFAULT '0',
  `points` int(10) unsigned NOT NULL DEFAULT '0',
  `tf_points` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `torrentid` (`torrentid`)
) ENGINE=MyISAM AUTO_INCREMENT=98 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user` int(10) unsigned NOT NULL DEFAULT '0',
  `torrent` int(10) unsigned NOT NULL DEFAULT '0',
  `added` int(11) NOT NULL,
  `text` text CHARACTER SET utf8,
  `ori_text` text CHARACTER SET utf8,
  `editedby` int(10) unsigned NOT NULL DEFAULT '0',
  `editedat` int(11) NOT NULL,
  `anonymous` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `request` int(10) unsigned NOT NULL DEFAULT '0',
  `offer` int(10) unsigned NOT NULL DEFAULT '0',
  `edit_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `user_likes` text CHARACTER SET utf8 NOT NULL,
  `checked_by` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `checked_when` int(11) NOT NULL,
  `checked` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  KEY `torrent` (`torrent`),
  KEY `scheck` (`edit_name`)
) ENGINE=MyISAM AUTO_INCREMENT=68 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `countries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `flagpic` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=103 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dbbackup`
--

DROP TABLE IF EXISTS `dbbackup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dbbackup` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `added` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2815 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `deathrow`
--

DROP TABLE IF EXISTS `deathrow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `deathrow` (
  `uid` int(10) NOT NULL,
  `username` char(80) CHARACTER SET utf8 NOT NULL,
  `tid` int(10) NOT NULL,
  `torrent_name` char(140) CHARACTER SET utf8 NOT NULL,
  `reason` tinyint(1) NOT NULL,
  `notify` tinyint(1) unsigned NOT NULL DEFAULT '1',
  UNIQUE KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `design`
--

DROP TABLE IF EXISTS `design`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `design` (
  `designid` int(10) unsigned NOT NULL,
  `name` varchar(64) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`designid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `startTime` int(11) NOT NULL,
  `endTime` int(11) NOT NULL,
  `overlayText` text CHARACTER SET utf8,
  `displayDates` tinyint(1) NOT NULL,
  `freeleechEnabled` tinyint(1) NOT NULL,
  `duploadEnabled` tinyint(1) NOT NULL,
  `hdownEnabled` tinyint(1) NOT NULL,
  `oupdated` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `startTime` (`startTime`)
) ENGINE=MyISAM AUTO_INCREMENT=51 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `failedlogins`
--

DROP TABLE IF EXISTS `failedlogins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failedlogins` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(15) CHARACTER SET utf8 DEFAULT NULL,
  `added` int(11) NOT NULL,
  `banned` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `attempts` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2180 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `faq`
--

DROP TABLE IF EXISTS `faq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `faq` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `type` int(3) NOT NULL DEFAULT '0',
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `text` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `faq_cat`
--

DROP TABLE IF EXISTS `faq_cat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `faq_cat` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `shortcut` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `min_view` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `shortcut` (`shortcut`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `files`
--

DROP TABLE IF EXISTS `files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `files` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `torrent` int(10) unsigned NOT NULL DEFAULT '0',
  `filename` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `size` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `torrent` (`torrent`),
  KEY `filename` (`filename`)
) ENGINE=MyISAM AUTO_INCREMENT=14059 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `forum_config`
--

DROP TABLE IF EXISTS `forum_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `forum_config` (
  `id` smallint(1) NOT NULL DEFAULT '1',
  `delete_for_real` smallint(6) NOT NULL DEFAULT '0',
  `min_delete_view_class` smallint(2) unsigned NOT NULL DEFAULT '7',
  `readpost_expiry` smallint(3) NOT NULL DEFAULT '14',
  `min_upload_class` smallint(2) unsigned NOT NULL DEFAULT '2',
  `accepted_file_extension` varchar(80) CHARACTER SET utf8 DEFAULT NULL,
  `accepted_file_types` varchar(280) CHARACTER SET utf8 DEFAULT NULL,
  `max_file_size` int(10) unsigned NOT NULL DEFAULT '2097152',
  `upload_folder` varchar(80) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`readpost_expiry`),
  KEY `delete_for_real` (`delete_for_real`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `forum_poll`
--

DROP TABLE IF EXISTS `forum_poll`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `forum_poll` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `question` varchar(280) CHARACTER SET utf8 DEFAULT NULL,
  `poll_answers` text CHARACTER SET utf8,
  `number_of_options` smallint(2) unsigned NOT NULL DEFAULT '0',
  `poll_starts` int(11) NOT NULL DEFAULT '0',
  `poll_ends` int(11) NOT NULL DEFAULT '0',
  `change_vote` enum('yes','no') CHARACTER SET utf8 NOT NULL DEFAULT 'no',
  `multi_options` smallint(2) unsigned NOT NULL DEFAULT '1',
  `poll_closed` enum('yes','no') CHARACTER SET utf8 DEFAULT 'no',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `forum_poll_votes`
--

DROP TABLE IF EXISTS `forum_poll_votes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `forum_poll_votes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `poll_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `option` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(15) CHARACTER SET utf8 DEFAULT NULL,
  `added` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `poll_id` (`poll_id`)
) ENGINE=MyISAM AUTO_INCREMENT=78 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `forums`
--

DROP TABLE IF EXISTS `forums`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `forums` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `description` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `forum_id` tinyint(4) DEFAULT '0',
  `post_count` int(10) unsigned NOT NULL DEFAULT '0',
  `topic_count` int(10) unsigned NOT NULL DEFAULT '0',
  `min_class_read` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `min_class_write` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `min_class_create` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `place` int(10) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `forums_bunny`
--

DROP TABLE IF EXISTS `forums_bunny`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `forums_bunny` (
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) CHARACTER SET utf8 DEFAULT NULL,
  `description` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `min_class_read` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `min_class_write` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `post_count` int(10) unsigned NOT NULL DEFAULT '0',
  `topic_count` int(10) unsigned NOT NULL DEFAULT '0',
  `min_class_create` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `parent_forum` tinyint(4) NOT NULL DEFAULT '0',
  `forum_id` tinyint(4) DEFAULT '0',
  `place` int(10) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `freeleech`
--

DROP TABLE IF EXISTS `freeleech`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `freeleech` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `var` int(10) NOT NULL DEFAULT '0',
  `description` text CHARACTER SET utf8,
  `type` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `amount` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `type` (`type`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `freepoll`
--

DROP TABLE IF EXISTS `freepoll`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `freepoll` (
  `torrentid` int(11) NOT NULL DEFAULT '0',
  `userid` int(11) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `freeslots`
--

DROP TABLE IF EXISTS `freeslots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `freeslots` (
  `torrentid` int(10) unsigned NOT NULL,
  `userid` int(10) unsigned NOT NULL,
  `doubleup` enum('yes','no') CHARACTER SET utf8 NOT NULL DEFAULT 'no',
  `free` enum('yes','no') CHARACTER SET utf8 NOT NULL DEFAULT 'no',
  `addedup` int(11) NOT NULL DEFAULT '0',
  `addedfree` int(11) NOT NULL DEFAULT '0',
  KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `friends`
--

DROP TABLE IF EXISTS `friends`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `friends` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `friendid` int(10) unsigned NOT NULL DEFAULT '0',
  `confirmed` enum('yes','no') CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=239 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `funds`
--

DROP TABLE IF EXISTS `funds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `funds` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cash` decimal(8,2) NOT NULL DEFAULT '0.00',
  `user` int(10) unsigned NOT NULL DEFAULT '0',
  `added` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=63 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `happyhour`
--

DROP TABLE IF EXISTS `happyhour`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `happyhour` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `userid` int(10) NOT NULL DEFAULT '0',
  `torrentid` int(10) NOT NULL DEFAULT '0',
  `multiplier` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `happylog`
--

DROP TABLE IF EXISTS `happylog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `happylog` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `userid` int(10) NOT NULL DEFAULT '0',
  `torrentid` int(10) NOT NULL DEFAULT '0',
  `multi` float NOT NULL DEFAULT '0',
  `date` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hit_and_run_settings`
--

DROP TABLE IF EXISTS `hit_and_run_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hit_and_run_settings` (
  `name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `value` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `infolog`
--

DROP TABLE IF EXISTS `infolog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `infolog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `added` int(11) DEFAULT '0',
  `txt` text CHARACTER SET utf8,
  PRIMARY KEY (`id`),
  KEY `added` (`added`)
) ENGINE=MyISAM AUTO_INCREMENT=879 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `invite_codes`
--

DROP TABLE IF EXISTS `invite_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invite_codes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sender` int(10) unsigned NOT NULL DEFAULT '0',
  `receiver` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `code` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `invite_added` int(10) NOT NULL,
  `status` enum('Pending','Confirmed') CHARACTER SET latin1 NOT NULL DEFAULT 'Pending',
  `email` varchar(80) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sender` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=130 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ips`
--

DROP TABLE IF EXISTS `ips`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ips` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(60) CHARACTER SET utf8 DEFAULT NULL,
  `userid` int(10) DEFAULT NULL,
  `type` enum('login','announce','browse','like') CHARACTER SET latin1 NOT NULL,
  `seedbox` tinyint(1) NOT NULL DEFAULT '0',
  `lastbrowse` int(11) NOT NULL DEFAULT '0',
  `lastlogin` int(11) NOT NULL DEFAULT '0',
  `lastannounce` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `likes`
--

DROP TABLE IF EXISTS `likes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `likes` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `user_comment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `userip` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lottery_config`
--

DROP TABLE IF EXISTS `lottery_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lottery_config` (
  `name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `value` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `manage_likes`
--

DROP TABLE IF EXISTS `manage_likes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `manage_likes` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `disabled_time` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sender` int(10) unsigned NOT NULL DEFAULT '0',
  `receiver` int(10) unsigned NOT NULL DEFAULT '0',
  `added` int(11) NOT NULL,
  `subject` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `msg` text CHARACTER SET utf8,
  `unread` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `poster` bigint(20) unsigned NOT NULL DEFAULT '0',
  `location` smallint(6) NOT NULL DEFAULT '1',
  `saved` enum('no','yes') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `urgent` enum('no','yes') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `draft` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=MyISAM AUTO_INCREMENT=42650 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mods_control`
--

DROP TABLE IF EXISTS `mods_control`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mods_control` (
  `userid` int(10) unsigned NOT NULL,
  `index_page_mods` int(10) unsigned NOT NULL DEFAULT '585727',
  `global_stdhead_mods` int(10) unsigned NOT NULL DEFAULT '2047',
  `userdetails_page_mods` bigint(20) unsigned NOT NULL DEFAULT '4294967295',
  UNIQUE KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `modscredits`
--

DROP TABLE IF EXISTS `modscredits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `modscredits` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `category` enum('Addon','Forum','Message/Email','Display/Style','Staff/Tools','Browse/Torrent/Details','Misc') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Misc',
  `status` enum('Complete','In-Progress') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Complete',
  `u232lnk` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `credit` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(120) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `moods`
--

DROP TABLE IF EXISTS `moods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `moods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `image` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `bonus` int(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=204 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '0',
  `added` int(11) NOT NULL DEFAULT '0',
  `body` text CHARACTER SET utf8,
  `title` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `sticky` enum('yes','no') CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'no',
  `anonymous` enum('yes','no') CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`),
  KEY `added` (`added`)
) ENGINE=MyISAM AUTO_INCREMENT=58 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `notconnectablepmlog`
--

DROP TABLE IF EXISTS `notconnectablepmlog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notconnectablepmlog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user` int(10) unsigned NOT NULL DEFAULT '0',
  `date` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `now_viewing`
--

DROP TABLE IF EXISTS `now_viewing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `now_viewing` (
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `forum_id` int(10) unsigned NOT NULL DEFAULT '0',
  `topic_id` int(10) unsigned NOT NULL DEFAULT '0',
  `added` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  KEY `forum_id` (`forum_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `offer_votes`
--

DROP TABLE IF EXISTS `offer_votes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `offer_votes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `offer_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `vote` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`id`),
  KEY `user_offer` (`offer_id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `offers`
--

DROP TABLE IF EXISTS `offers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `offers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `offer_name` varchar(120) CHARACTER SET utf8 DEFAULT NULL,
  `image` varchar(180) CHARACTER SET utf8 DEFAULT NULL,
  `description` text CHARACTER SET utf8,
  `category` int(10) unsigned NOT NULL DEFAULT '0',
  `added` int(11) NOT NULL DEFAULT '0',
  `offered_by_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `filled_torrent_id` int(10) NOT NULL DEFAULT '0',
  `vote_yes_count` int(10) unsigned NOT NULL DEFAULT '0',
  `vote_no_count` int(10) unsigned NOT NULL DEFAULT '0',
  `comments` int(10) unsigned NOT NULL DEFAULT '0',
  `link` varchar(240) CHARACTER SET utf8 DEFAULT NULL,
  `status` enum('approved','pending','denied') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`id`),
  KEY `id_added` (`id`),
  KEY `offered_by_name` (`offer_name`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `over_forums`
--

DROP TABLE IF EXISTS `over_forums`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `over_forums` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `description` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `min_class_view` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `forum_id` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `over_forums_bunny`
--

DROP TABLE IF EXISTS `over_forums_bunny`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `over_forums_bunny` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) CHARACTER SET utf8 DEFAULT NULL,
  `description` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `min_class_view` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `forum_id` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pastebin`
--

DROP TABLE IF EXISTS `pastebin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pastebin` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `poster` varchar(16) CHARACTER SET utf8 DEFAULT NULL,
  `posted` datetime DEFAULT NULL,
  `code` text CHARACTER SET utf8,
  `parent_pid` int(11) DEFAULT '0',
  `format` varchar(16) CHARACTER SET utf8 DEFAULT NULL,
  `codefmt` mediumtext CHARACTER SET utf8,
  `codecss` text CHARACTER SET utf8,
  `domain` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `token` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `expires` datetime DEFAULT NULL,
  `expiry_flag` enum('d','m','f') CHARACTER SET latin1 NOT NULL DEFAULT 'm',
  PRIMARY KEY (`pid`),
  KEY `domain` (`domain`),
  KEY `expires` (`expires`),
  KEY `parent_pid` (`parent_pid`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `paypal_config`
--

DROP TABLE IF EXISTS `paypal_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paypal_config` (
  `name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `value` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `peers`
--

DROP TABLE IF EXISTS `peers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `peers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `torrent` int(10) unsigned NOT NULL DEFAULT '0',
  `torrent_pass` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `peer_id` binary(20) NOT NULL,
  `ip` varchar(64) CHARACTER SET utf8 DEFAULT NULL,
  `port` smallint(5) unsigned NOT NULL DEFAULT '0',
  `uploaded` bigint(20) unsigned NOT NULL DEFAULT '0',
  `downloaded` bigint(20) unsigned NOT NULL DEFAULT '0',
  `to_go` bigint(20) unsigned NOT NULL DEFAULT '0',
  `seeder` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `started` int(11) NOT NULL,
  `last_action` int(11) NOT NULL,
  `prev_action` int(11) NOT NULL,
  `connectable` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `agent` varchar(60) CHARACTER SET utf8 DEFAULT NULL,
  `finishedat` int(10) unsigned NOT NULL DEFAULT '0',
  `downloadoffset` bigint(20) unsigned NOT NULL DEFAULT '0',
  `uploadoffset` bigint(20) unsigned NOT NULL DEFAULT '0',
  `corrupt` int(10) NOT NULL DEFAULT '0',
  `compact` varchar(6) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `torrent_peer_id` (`torrent`,`peer_id`,`ip`),
  KEY `torrent` (`torrent`),
  KEY `last_action` (`last_action`),
  KEY `connectable` (`connectable`),
  KEY `userid` (`userid`),
  KEY `torrent_pass` (`torrent_pass`)
) ENGINE=MyISAM AUTO_INCREMENT=839 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pmboxes`
--

DROP TABLE IF EXISTS `pmboxes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pmboxes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `boxnumber` tinyint(4) NOT NULL DEFAULT '2',
  `name` varchar(15) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `poll`
--

DROP TABLE IF EXISTS `poll`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `poll` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `question` varchar(320) CHARACTER SET utf8 DEFAULT NULL,
  `answers` text CHARACTER SET utf8,
  `votes` int(5) NOT NULL DEFAULT '0',
  `multi` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `poll_voters`
--

DROP TABLE IF EXISTS `poll_voters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `poll_voters` (
  `vid` int(10) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(16) CHARACTER SET utf8 DEFAULT NULL,
  `vote_date` int(10) NOT NULL DEFAULT '0',
  `poll_id` int(10) NOT NULL DEFAULT '0',
  `user_id` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`vid`),
  KEY `poll_id` (`poll_id`)
) ENGINE=MyISAM AUTO_INCREMENT=607 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `polls`
--

DROP TABLE IF EXISTS `polls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `polls` (
  `pid` mediumint(8) NOT NULL AUTO_INCREMENT,
  `start_date` int(10) DEFAULT NULL,
  `choices` mediumtext CHARACTER SET utf8,
  `starter_id` mediumint(8) NOT NULL DEFAULT '0',
  `starter_name` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `votes` smallint(5) NOT NULL DEFAULT '0',
  `poll_question` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `postpollanswers`
--

DROP TABLE IF EXISTS `postpollanswers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `postpollanswers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pollid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `selection` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `pollid` (`pollid`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `postpolls`
--

DROP TABLE IF EXISTS `postpolls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `postpolls` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `added` int(11) NOT NULL DEFAULT '0',
  `question` text COLLATE utf8_unicode_ci NOT NULL,
  `option0` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `option1` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `option2` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `option3` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `option4` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `option5` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `option6` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `option7` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `option8` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `option9` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `option10` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `option11` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `option12` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `option13` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `option14` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `option15` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `option16` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `option17` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `option18` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `option19` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sort` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `posts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `topic_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `added` int(22) DEFAULT '0',
  `body` longtext COLLATE utf8_bin,
  `edited_by` int(10) unsigned NOT NULL DEFAULT '0',
  `edit_date` int(11) DEFAULT '0',
  `post_history` mediumtext COLLATE utf8_bin NOT NULL,
  `icon` int(2) NOT NULL DEFAULT '0',
  `anonymous` enum('yes','no') COLLATE utf8_bin NOT NULL DEFAULT 'no',
  `user_likes` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `topicid` (`topic_id`),
  KEY `userid` (`user_id`),
  FULLTEXT KEY `body` (`body`)
) ENGINE=MyISAM AUTO_INCREMENT=145 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `posts_bunny`
--

DROP TABLE IF EXISTS `posts_bunny`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `posts_bunny` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `topic_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `added` int(11) NOT NULL DEFAULT '0',
  `body` text CHARACTER SET utf8,
  `edited_by` int(10) unsigned NOT NULL DEFAULT '0',
  `edit_date` int(11) NOT NULL DEFAULT '0',
  `icon` varchar(80) CHARACTER SET utf8 DEFAULT NULL,
  `post_title` varchar(120) CHARACTER SET utf8 DEFAULT NULL,
  `bbcode` enum('yes','no') CHARACTER SET utf8 NOT NULL DEFAULT 'yes',
  `post_history` text CHARACTER SET utf8,
  `edit_reason` varchar(60) CHARACTER SET utf8 DEFAULT NULL,
  `ip` varchar(15) CHARACTER SET utf8 DEFAULT NULL,
  `status` enum('deleted','recycled','postlocked','ok') CHARACTER SET utf8 NOT NULL DEFAULT 'ok',
  `staff_lock` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `anonymous` enum('yes','no') CHARACTER SET utf8 NOT NULL DEFAULT 'no',
  `user_likes` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `topicid` (`topic_id`),
  KEY `userid` (`user_id`),
  KEY `body` (`post_title`)
) ENGINE=MyISAM AUTO_INCREMENT=322 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `promo`
--

DROP TABLE IF EXISTS `promo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `promo` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(120) CHARACTER SET utf8 DEFAULT NULL,
  `added` int(10) NOT NULL DEFAULT '0',
  `days_valid` int(2) NOT NULL DEFAULT '0',
  `accounts_made` int(3) NOT NULL DEFAULT '0',
  `max_users` int(3) NOT NULL DEFAULT '0',
  `link` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `creator` int(10) NOT NULL DEFAULT '0',
  `users` text CHARACTER SET utf8,
  `bonus_upload` bigint(10) NOT NULL DEFAULT '0',
  `bonus_invites` int(2) NOT NULL DEFAULT '0',
  `bonus_karma` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rating`
--

DROP TABLE IF EXISTS `rating`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rating` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `topic` int(10) NOT NULL DEFAULT '0',
  `torrent` int(10) NOT NULL DEFAULT '0',
  `rating` int(1) NOT NULL DEFAULT '0',
  `user` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=161 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `read_posts`
--

DROP TABLE IF EXISTS `read_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `read_posts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `topic_id` int(10) unsigned NOT NULL DEFAULT '0',
  `last_post_read` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `topic_id` (`topic_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7103 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `readposts`
--

DROP TABLE IF EXISTS `readposts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `readposts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `topicid` int(10) unsigned NOT NULL DEFAULT '0',
  `lastpostread` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `topicid` (`topicid`)
) ENGINE=MyISAM AUTO_INCREMENT=350 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `recent`
--

DROP TABLE IF EXISTS `recent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recent` (
  `domain` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `pid` int(11) NOT NULL,
  `seq_no` int(11) NOT NULL,
  PRIMARY KEY (`domain`,`seq_no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `referrers`
--

DROP TABLE IF EXISTS `referrers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `referrers` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `browser` varchar(500) CHARACTER SET utf8 DEFAULT NULL,
  `ip` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `referer` varchar(500) CHARACTER SET utf8 DEFAULT NULL,
  `page` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `date` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18573 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `releases`
--

DROP TABLE IF EXISTS `releases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `releases` (
  `releasename` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `section` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `nuked` int(11) DEFAULT NULL,
  `nukereason` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nuketime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `releasetime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `reports`
--

DROP TABLE IF EXISTS `reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reports` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `reported_by` int(10) unsigned NOT NULL DEFAULT '0',
  `reporting_what` int(10) unsigned NOT NULL DEFAULT '0',
  `reporting_type` enum('User','Comment','Request_Comment','Offer_Comment','Request','Offer','Torrent','Hit_And_Run','Post') CHARACTER SET utf8 NOT NULL DEFAULT 'Torrent',
  `reason` text CHARACTER SET utf8,
  `who_delt_with_it` int(10) unsigned NOT NULL DEFAULT '0',
  `delt_with` tinyint(1) NOT NULL DEFAULT '0',
  `added` int(11) NOT NULL DEFAULT '0',
  `how_delt_with` text CHARACTER SET utf8,
  `2nd_value` int(10) unsigned NOT NULL DEFAULT '0',
  `when_delt_with` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `delt_with` (`delt_with`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `reputation`
--

DROP TABLE IF EXISTS `reputation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reputation` (
  `reputationid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `reputation` int(10) NOT NULL DEFAULT '0',
  `whoadded` int(10) NOT NULL DEFAULT '0',
  `reason` varchar(250) CHARACTER SET utf8 DEFAULT NULL,
  `dateadd` int(10) NOT NULL DEFAULT '0',
  `locale` enum('posts','comments','torrents','users') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'posts',
  `postid` int(10) NOT NULL DEFAULT '0',
  `userid` mediumint(8) NOT NULL DEFAULT '0',
  PRIMARY KEY (`reputationid`),
  KEY `userid` (`userid`),
  KEY `whoadded` (`whoadded`),
  KEY `multi` (`postid`),
  KEY `dateadd` (`dateadd`),
  KEY `locale` (`locale`)
) ENGINE=MyISAM AUTO_INCREMENT=58 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `reputationlevel`
--

DROP TABLE IF EXISTS `reputationlevel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reputationlevel` (
  `reputationlevelid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `minimumreputation` int(10) NOT NULL DEFAULT '0',
  `level` varchar(250) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`reputationlevelid`),
  KEY `reputationlevel` (`minimumreputation`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `request_votes`
--

DROP TABLE IF EXISTS `request_votes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `request_votes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `request_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `vote` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`id`),
  KEY `user_request` (`request_id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `requests`
--

DROP TABLE IF EXISTS `requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `requests` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `request_name` varchar(120) CHARACTER SET utf8 DEFAULT NULL,
  `image` varchar(180) CHARACTER SET utf8 DEFAULT NULL,
  `description` text CHARACTER SET utf8,
  `category` int(10) unsigned NOT NULL DEFAULT '0',
  `added` int(11) NOT NULL DEFAULT '0',
  `requested_by_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `filled_by_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `filled_torrent_id` int(10) NOT NULL DEFAULT '0',
  `vote_yes_count` int(10) unsigned NOT NULL DEFAULT '0',
  `vote_no_count` int(10) unsigned NOT NULL DEFAULT '0',
  `comments` int(10) unsigned NOT NULL DEFAULT '0',
  `link` varchar(240) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_added` (`id`),
  KEY `requested_by_name` (`request_name`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rules`
--

DROP TABLE IF EXISTS `rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rules` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `type` int(3) NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rules_cat`
--

DROP TABLE IF EXISTS `rules_cat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rules_cat` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `shortcut` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `min_view` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `shortcut` (`shortcut`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `searchcloud`
--

DROP TABLE IF EXISTS `searchcloud`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `searchcloud` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `searchedfor` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `howmuch` int(10) NOT NULL,
  `ip` varchar(15) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `searchedfor` (`searchedfor`)
) ENGINE=MyISAM AUTO_INCREMENT=196 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `shit_list`
--

DROP TABLE IF EXISTS `shit_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shit_list` (
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `suspect` int(10) unsigned NOT NULL DEFAULT '0',
  `shittyness` int(2) unsigned NOT NULL DEFAULT '0',
  `added` int(11) NOT NULL DEFAULT '0',
  `text` text CHARACTER SET utf8,
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `shoutbox`
--

DROP TABLE IF EXISTS `shoutbox`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shoutbox` (
  `id` bigint(40) NOT NULL AUTO_INCREMENT,
  `userid` bigint(6) NOT NULL DEFAULT '0',
  `to_user` int(10) NOT NULL DEFAULT '0',
  `username` varchar(25) CHARACTER SET utf8 DEFAULT NULL,
  `date` int(11) NOT NULL DEFAULT '0',
  `text` text CHARACTER SET utf8,
  `text_parsed` text CHARACTER SET utf8,
  `staff_shout` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `autoshout` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`),
  KEY `for` (`to_user`)
) ENGINE=MyISAM AUTO_INCREMENT=106015 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `site_config`
--

DROP TABLE IF EXISTS `site_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_config` (
  `name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `value` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sitelog`
--

DROP TABLE IF EXISTS `sitelog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sitelog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `added` int(11) NOT NULL,
  `txt` text CHARACTER SET utf8,
  PRIMARY KEY (`id`),
  KEY `added` (`added`)
) ENGINE=MyISAM AUTO_INCREMENT=235155 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `snatched`
--

DROP TABLE IF EXISTS `snatched`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `snatched` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `torrentid` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(15) CHARACTER SET utf8 DEFAULT NULL,
  `port` smallint(5) unsigned NOT NULL DEFAULT '0',
  `connectable` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `agent` varchar(60) CHARACTER SET utf8 DEFAULT NULL,
  `peer_id` binary(20) NOT NULL,
  `uploaded` bigint(20) unsigned NOT NULL DEFAULT '0',
  `upspeed` bigint(20) NOT NULL DEFAULT '0',
  `downloaded` bigint(20) unsigned NOT NULL DEFAULT '0',
  `downspeed` bigint(20) NOT NULL DEFAULT '0',
  `to_go` bigint(20) unsigned NOT NULL DEFAULT '0',
  `seeder` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `seedtime` int(11) unsigned NOT NULL DEFAULT '0',
  `leechtime` int(11) unsigned NOT NULL DEFAULT '0',
  `start_date` int(11) NOT NULL,
  `last_action` int(11) NOT NULL,
  `complete_date` int(11) NOT NULL,
  `timesann` int(10) unsigned NOT NULL DEFAULT '0',
  `hit_and_run` int(11) NOT NULL,
  `mark_of_cain` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `finished` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`),
  KEY `tr_usr` (`torrentid`)
) ENGINE=MyISAM AUTO_INCREMENT=83 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `staffmessages`
--

DROP TABLE IF EXISTS `staffmessages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staffmessages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sender` int(10) unsigned NOT NULL DEFAULT '0',
  `added` int(11) DEFAULT '0',
  `msg` text CHARACTER SET utf8,
  `subject` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `answeredby` int(10) unsigned NOT NULL DEFAULT '0',
  `answered` int(1) NOT NULL DEFAULT '0',
  `answer` text CHARACTER SET utf8,
  PRIMARY KEY (`id`),
  KEY `answeredby` (`answeredby`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `staffpanel`
--

DROP TABLE IF EXISTS `staffpanel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staffpanel` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `page_name` varchar(80) CHARACTER SET utf8 DEFAULT NULL,
  `file_name` varchar(80) CHARACTER SET utf8 DEFAULT NULL,
  `description` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `type` enum('user','settings','stats','other') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'user',
  `av_class` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `added_by` int(10) unsigned NOT NULL DEFAULT '0',
  `added` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `file_name` (`file_name`),
  KEY `av_class` (`av_class`)
) ENGINE=MyISAM AUTO_INCREMENT=118 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `stats`
--

DROP TABLE IF EXISTS `stats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stats` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `regusers` int(10) unsigned NOT NULL DEFAULT '0',
  `unconusers` int(10) unsigned NOT NULL DEFAULT '0',
  `torrents` int(10) unsigned NOT NULL DEFAULT '0',
  `seeders` int(10) unsigned NOT NULL DEFAULT '0',
  `leechers` int(10) unsigned NOT NULL DEFAULT '0',
  `torrentstoday` int(10) unsigned NOT NULL DEFAULT '0',
  `donors` int(10) unsigned NOT NULL DEFAULT '0',
  `unconnectables` int(10) unsigned NOT NULL DEFAULT '0',
  `forumtopics` int(10) unsigned NOT NULL DEFAULT '0',
  `forumposts` int(10) unsigned NOT NULL DEFAULT '0',
  `numactive` int(10) unsigned NOT NULL DEFAULT '0',
  `torrentsmonth` int(10) unsigned NOT NULL DEFAULT '0',
  `gender_na` int(10) unsigned NOT NULL DEFAULT '1',
  `gender_male` int(10) unsigned NOT NULL DEFAULT '1',
  `gender_female` int(10) unsigned NOT NULL DEFAULT '1',
  `powerusers` int(10) unsigned NOT NULL DEFAULT '1',
  `disabled` int(10) unsigned NOT NULL DEFAULT '1',
  `uploaders` int(10) unsigned NOT NULL DEFAULT '1',
  `moderators` int(10) unsigned NOT NULL DEFAULT '1',
  `administrators` int(10) unsigned NOT NULL DEFAULT '1',
  `sysops` int(10) unsigned NOT NULL DEFAULT '1',
  `vip` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `stylesheets`
--

DROP TABLE IF EXISTS `stylesheets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stylesheets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uri` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `name` varchar(64) CHARACTER SET utf8 DEFAULT NULL,
  `design_id` int(5) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `design_id` (`design_id`)
) ENGINE=MyISAM AUTO_INCREMENT=107 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `subscriptions`
--

DROP TABLE IF EXISTS `subscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subscriptions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `topic_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `subscriptions_orig`
--

DROP TABLE IF EXISTS `subscriptions_orig`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subscriptions_orig` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `topic_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `subtitles`
--

DROP TABLE IF EXISTS `subtitles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subtitles` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `filename` varchar(36) CHARACTER SET utf8 DEFAULT NULL,
  `imdb` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `lang` varchar(3) CHARACTER SET utf8 DEFAULT NULL,
  `comment` text CHARACTER SET utf8,
  `fps` varchar(6) CHARACTER SET utf8 DEFAULT NULL,
  `poster` varchar(120) CHARACTER SET utf8 DEFAULT NULL,
  `cds` int(3) NOT NULL DEFAULT '0',
  `hits` int(10) NOT NULL DEFAULT '0',
  `added` int(11) NOT NULL DEFAULT '0',
  `owner` int(10) NOT NULL DEFAULT '0',
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `thanks`
--

DROP TABLE IF EXISTS `thanks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `thanks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `torrentid` int(11) NOT NULL DEFAULT '0',
  `userid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=224 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `thankyou`
--

DROP TABLE IF EXISTS `thankyou`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `thankyou` (
  `tid` bigint(10) NOT NULL AUTO_INCREMENT,
  `uid` bigint(10) NOT NULL DEFAULT '0',
  `torid` bigint(10) NOT NULL DEFAULT '0',
  `thank_date` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`tid`)
) ENGINE=MyISAM AUTO_INCREMENT=139 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `thumbsup`
--

DROP TABLE IF EXISTS `thumbsup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `thumbsup` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` enum('torrents','posts','comments','users') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'torrents',
  `torrentid` int(10) NOT NULL DEFAULT '0',
  `userid` int(11) NOT NULL DEFAULT '0',
  `commentid` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=155 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tmdb`
--

DROP TABLE IF EXISTS `tmdb`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tmdb` (
  `movie_id` int(10) unsigned NOT NULL DEFAULT '0',
  `movie_likes` text CHARACTER SET utf8 NOT NULL,
  `tv_id` int(10) unsigned NOT NULL DEFAULT '0',
  `tv_likes` text CHARACTER SET utf8 NOT NULL,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `topics`
--

DROP TABLE IF EXISTS `topics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `topics` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `topic_name` varchar(40) COLLATE utf8_bin DEFAULT NULL,
  `locked` enum('yes','no') COLLATE utf8_bin NOT NULL DEFAULT 'no',
  `forum_id` int(10) unsigned NOT NULL DEFAULT '0',
  `last_post` int(10) unsigned NOT NULL DEFAULT '0',
  `sticky` enum('yes','no') COLLATE utf8_bin NOT NULL DEFAULT 'no',
  `views` int(10) unsigned NOT NULL DEFAULT '0',
  `poll_id` int(10) unsigned NOT NULL DEFAULT '0',
  `anonymous` enum('yes','no') COLLATE utf8_bin NOT NULL DEFAULT 'no',
  `num_ratings` int(10) unsigned NOT NULL DEFAULT '0',
  `rating_sum` int(10) unsigned NOT NULL DEFAULT '0',
  `user_likes` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`user_id`),
  KEY `subject` (`topic_name`),
  KEY `lastpost` (`last_post`),
  KEY `locked_sticky` (`locked`,`sticky`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `topics_bunny`
--

DROP TABLE IF EXISTS `topics_bunny`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `topics_bunny` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `topic_name` varchar(120) CHARACTER SET utf8 DEFAULT NULL,
  `locked` enum('yes','no') CHARACTER SET utf8 NOT NULL DEFAULT 'no',
  `forum_id` int(10) unsigned NOT NULL DEFAULT '0',
  `last_post` int(10) unsigned NOT NULL DEFAULT '0',
  `sticky` enum('yes','no') CHARACTER SET utf8 NOT NULL DEFAULT 'no',
  `views` int(10) unsigned NOT NULL DEFAULT '0',
  `poll_id` int(10) unsigned NOT NULL DEFAULT '0',
  `num_ratings` int(10) unsigned NOT NULL DEFAULT '0',
  `rating_sum` int(10) unsigned NOT NULL DEFAULT '0',
  `topic_desc` varchar(120) CHARACTER SET utf8 DEFAULT NULL,
  `post_count` int(10) unsigned NOT NULL DEFAULT '0',
  `first_post` int(10) unsigned NOT NULL DEFAULT '0',
  `status` enum('deleted','recycled','ok') CHARACTER SET utf8 NOT NULL DEFAULT 'ok',
  `main_forum_id` int(10) unsigned NOT NULL DEFAULT '0',
  `anonymous` enum('yes','no') CHARACTER SET utf8 NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`),
  KEY `userid` (`user_id`),
  KEY `subject` (`topic_name`),
  KEY `lastpost` (`last_post`)
) ENGINE=MyISAM AUTO_INCREMENT=89 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `torrents`
--

DROP TABLE IF EXISTS `torrents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `torrents` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `info_hash` binary(20) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `filename` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `save_as` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `search_text` text CHARACTER SET utf8,
  `descr` longtext CHARACTER SET utf8,
  `ori_descr` longtext CHARACTER SET utf8,
  `category` int(10) unsigned NOT NULL DEFAULT '0',
  `size` bigint(20) unsigned NOT NULL DEFAULT '0',
  `added` int(11) NOT NULL,
  `type` enum('single','multi') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'single',
  `numfiles` int(10) unsigned NOT NULL DEFAULT '0',
  `comments` int(10) unsigned NOT NULL DEFAULT '0',
  `views` int(10) unsigned NOT NULL DEFAULT '0',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `times_completed` int(10) unsigned NOT NULL DEFAULT '0',
  `leechers` int(10) unsigned NOT NULL DEFAULT '0',
  `seeders` int(10) unsigned NOT NULL DEFAULT '0',
  `last_action` int(11) NOT NULL,
  `visible` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `banned` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `owner` int(10) unsigned NOT NULL DEFAULT '0',
  `num_ratings` int(10) unsigned NOT NULL DEFAULT '0',
  `rating_sum` int(10) unsigned NOT NULL DEFAULT '0',
  `nfo` text CHARACTER SET utf8,
  `client_created_by` char(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'unknown',
  `free` int(11) unsigned NOT NULL DEFAULT '0',
  `sticky` enum('yes','fly','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `anonymous` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `url` varchar(80) CHARACTER SET utf8 DEFAULT NULL,
  `checked_by` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `points` int(10) NOT NULL DEFAULT '0',
  `allow_comments` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `poster` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `nuked` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `nukereason` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `last_reseed` int(11) NOT NULL DEFAULT '0',
  `release_group` enum('scene','p2p','none') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'none',
  `subs` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `vip` enum('1','0') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `newgenre` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `pretime` int(11) NOT NULL DEFAULT '0',
  `bump` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `request` int(10) unsigned NOT NULL DEFAULT '0',
  `offer` int(10) unsigned NOT NULL DEFAULT '0',
  `username` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `thanks` int(10) NOT NULL DEFAULT '0',
  `description` varchar(120) CHARACTER SET utf8 DEFAULT NULL,
  `youtube` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `tags` text CHARACTER SET utf8,
  `recommended` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `silver` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_when` int(11) NOT NULL,
  `flags` int(11) NOT NULL,
  `mtime` int(11) NOT NULL,
  `ctime` int(11) NOT NULL,
  `freetorrent` tinyint(4) NOT NULL DEFAULT '0',
  `user_likes` text CHARACTER SET utf8 NOT NULL,
  `Snatched` int(10) unsigned NOT NULL DEFAULT '0',
  `balance` bigint(20) NOT NULL DEFAULT '0',
  `f_points` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `info_hash` (`info_hash`),
  KEY `owner` (`owner`),
  KEY `visible` (`visible`),
  KEY `category_visible` (`category`),
  KEY `newgenre` (`newgenre`)
) ENGINE=MyISAM AUTO_INCREMENT=280 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `uploadapp`
--

DROP TABLE IF EXISTS `uploadapp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `uploadapp` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) NOT NULL DEFAULT '0',
  `applied` int(11) NOT NULL DEFAULT '0',
  `speed` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `offer` longtext CHARACTER SET utf8,
  `reason` longtext CHARACTER SET utf8,
  `sites` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `sitenames` varchar(150) CHARACTER SET utf8 DEFAULT NULL,
  `scene` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `creating` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `seeding` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `connectable` enum('yes','no','pending') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending',
  `status` enum('accepted','rejected','pending') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending',
  `moderator` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `comment` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users` (`userid`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_blocks`
--

DROP TABLE IF EXISTS `user_blocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_blocks` (
  `userid` int(10) unsigned NOT NULL,
  `index_page` int(10) unsigned NOT NULL DEFAULT '585727',
  `global_stdhead` int(10) unsigned NOT NULL DEFAULT '2047',
  `userdetails_page` bigint(20) unsigned NOT NULL DEFAULT '4294967295',
  UNIQUE KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `usercomments`
--

DROP TABLE IF EXISTS `usercomments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usercomments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `added` int(11) NOT NULL DEFAULT '0',
  `text` text CHARACTER SET utf8,
  `ori_text` text CHARACTER SET utf8,
  `editedby` int(10) unsigned NOT NULL DEFAULT '0',
  `editedat` int(11) NOT NULL DEFAULT '0',
  `edit_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `user_likes` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM AUTO_INCREMENT=62 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `userhits`
--

DROP TABLE IF EXISTS `userhits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `userhits` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `hitid` int(10) unsigned NOT NULL DEFAULT '0',
  `number` int(10) unsigned NOT NULL DEFAULT '0',
  `added` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `added` (`added`),
  KEY `hitid` (`hitid`)
) ENGINE=MyISAM AUTO_INCREMENT=10131 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `passhash` varchar(80) CHARACTER SET utf8 DEFAULT NULL,
  `loginhash` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `secret` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `passkey` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `email` varchar(180) CHARACTER SET utf8 DEFAULT NULL,
  `status` enum('pending','confirmed') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending',
  `added` int(11) NOT NULL,
  `last_login` int(11) NOT NULL,
  `last_access` int(11) NOT NULL,
  `curr_ann_last_check` int(10) unsigned NOT NULL DEFAULT '0',
  `curr_ann_id` int(10) unsigned NOT NULL DEFAULT '0',
  `editsecret` varchar(80) CHARACTER SET utf8 DEFAULT NULL,
  `privacy` enum('strong','normal','low') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'normal',
  `stylesheet` int(10) NOT NULL DEFAULT '1',
  `info` text CHARACTER SET utf8,
  `acceptpms` enum('yes','friends','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `ip` varchar(15) CHARACTER SET utf8 DEFAULT NULL,
  `class` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `override_class` tinyint(3) unsigned NOT NULL DEFAULT '255',
  `language` int(11) NOT NULL DEFAULT '1',
  `avatar` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `av_w` smallint(3) unsigned NOT NULL DEFAULT '0',
  `av_h` smallint(3) unsigned NOT NULL DEFAULT '0',
  `uploaded` bigint(20) unsigned NOT NULL DEFAULT '0',
  `downloaded` bigint(20) unsigned NOT NULL DEFAULT '0',
  `title` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `country` int(10) unsigned NOT NULL DEFAULT '0',
  `notifs` varchar(300) CHARACTER SET utf8 DEFAULT NULL,
  `modcomment` text CHARACTER SET utf8,
  `enabled` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `donor` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `warned` int(11) NOT NULL DEFAULT '0',
  `torrentsperpage` int(3) unsigned NOT NULL DEFAULT '0',
  `topicsperpage` int(3) unsigned NOT NULL DEFAULT '0',
  `postsperpage` int(3) unsigned NOT NULL DEFAULT '0',
  `deletepms` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `savepms` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `reputation` int(10) NOT NULL DEFAULT '10',
  `time_offset` varchar(5) CHARACTER SET utf8 DEFAULT NULL,
  `dst_in_use` tinyint(1) NOT NULL DEFAULT '0',
  `auto_correct_dst` tinyint(1) NOT NULL DEFAULT '1',
  `show_shout` enum('yes','no') CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'yes',
  `shoutboxbg` enum('1','2','3','4') CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '1',
  `chatpost` int(11) NOT NULL DEFAULT '1',
  `smile_until` int(10) NOT NULL DEFAULT '0',
  `seedbonus` decimal(10,1) NOT NULL DEFAULT '200.0',
  `bonuscomment` text CHARACTER SET utf8,
  `vip_added` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `vip_until` int(10) NOT NULL DEFAULT '0',
  `freeslots` int(11) unsigned NOT NULL DEFAULT '5',
  `free_switch` int(11) unsigned NOT NULL DEFAULT '0',
  `invites` int(10) unsigned NOT NULL DEFAULT '1',
  `invitedby` int(10) unsigned NOT NULL DEFAULT '0',
  `invite_rights` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `anonymous` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `uploadpos` int(11) NOT NULL DEFAULT '1',
  `forumpost` int(11) NOT NULL DEFAULT '1',
  `downloadpos` int(11) NOT NULL DEFAULT '1',
  `immunity` int(11) NOT NULL DEFAULT '0',
  `leechwarn` int(11) NOT NULL DEFAULT '0',
  `disable_reason` text CHARACTER SET utf8,
  `clear_new_tag_manually` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `last_browse` int(11) NOT NULL DEFAULT '0',
  `sig_w` smallint(3) unsigned NOT NULL DEFAULT '0',
  `sig_h` smallint(3) unsigned NOT NULL DEFAULT '0',
  `signatures` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `signature` varchar(225) CHARACTER SET utf8 DEFAULT NULL,
  `forum_access` int(11) NOT NULL DEFAULT '0',
  `highspeed` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `hnrwarn` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `hit_and_run_total` int(9) DEFAULT '0',
  `donoruntil` int(11) unsigned NOT NULL DEFAULT '0',
  `donated` int(3) NOT NULL DEFAULT '0',
  `total_donated` decimal(8,2) NOT NULL DEFAULT '0.00',
  `vipclass_before` int(10) NOT NULL DEFAULT '0',
  `parked` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `passhint` int(10) unsigned NOT NULL,
  `hintanswer` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `avatarpos` int(11) NOT NULL DEFAULT '1',
  `support` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `supportfor` text CHARACTER SET utf8,
  `language_new` int(11) NOT NULL DEFAULT '1',
  `sendpmpos` int(11) NOT NULL DEFAULT '1',
  `invitedate` int(11) NOT NULL DEFAULT '0',
  `invitees` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `invite_on` enum('yes','no') CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'yes',
  `subscription_pm` enum('yes','no') CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'no',
  `gender` enum('Male','Female','NA') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'NA',
  `anonymous_until` int(10) NOT NULL DEFAULT '0',
  `viewscloud` enum('yes','no') CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'yes',
  `tenpercent` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `avatars` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `offavatar` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `pirate` int(11) unsigned NOT NULL DEFAULT '0',
  `king` int(11) unsigned NOT NULL DEFAULT '0',
  `hidecur` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `ssluse` int(1) NOT NULL DEFAULT '1',
  `signature_post` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `forum_post` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `avatar_rights` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `offensive_avatar` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `view_offensive_avatar` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `paranoia` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `google_talk` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `msn` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `aim` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `yahoo` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `website` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `icq` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `show_email` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `parked_until` int(10) NOT NULL DEFAULT '0',
  `gotgift` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `hash1` varchar(96) CHARACTER SET utf8 DEFAULT NULL,
  `suspended` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `bjwins` int(10) NOT NULL DEFAULT '0',
  `bjlosses` int(10) NOT NULL DEFAULT '0',
  `warn_reason` text CHARACTER SET utf8,
  `onirc` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `irctotal` bigint(20) unsigned NOT NULL DEFAULT '0',
  `birthday` date DEFAULT '0000-00-00',
  `got_blocks` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `last_access_numb` bigint(30) NOT NULL DEFAULT '0',
  `onlinetime` bigint(30) NOT NULL DEFAULT '0',
  `pm_on_delete` enum('yes','no') CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'no',
  `commentpm` enum('yes','no') CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'no',
  `split` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `browser` text CHARACTER SET utf8,
  `hits` int(10) NOT NULL DEFAULT '0',
  `comments` int(10) unsigned NOT NULL DEFAULT '0',
  `categorie_icon` int(10) DEFAULT '1',
  `perms` int(11) NOT NULL DEFAULT '0',
  `mood` int(10) NOT NULL DEFAULT '1',
  `got_moods` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `pms_per_page` tinyint(3) unsigned DEFAULT '20',
  `show_pm_avatar` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `watched_user` int(11) NOT NULL DEFAULT '0',
  `watched_user_reason` text CHARACTER SET utf8,
  `staff_notes` text CHARACTER SET utf8,
  `game_access` int(11) NOT NULL DEFAULT '1',
  `where_is` text CHARACTER SET utf8,
  `show_staffshout` enum('yes','no') CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'yes',
  `request_uri` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `logout` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `browse_icons` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `numuploads` int(10) NOT NULL DEFAULT '0',
  `corrupt` int(10) NOT NULL DEFAULT '0',
  `ignore_list` text CHARACTER SET utf8,
  `opt1` int(11) NOT NULL DEFAULT '182927957',
  `opt2` int(11) NOT NULL DEFAULT '224',
  `sidebar` enum('yes','no') CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'no',
  `torrent_pass_version` int(11) NOT NULL,
  `torrent_pass` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `can_leech` tinyint(4) NOT NULL DEFAULT '1',
  `wait_time` int(11) NOT NULL,
  `peers_limit` int(11) DEFAULT '1000',
  `torrents_limit` int(11) DEFAULT '1000',
  `forum_mod` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `forums_mod` varchar(320) CHARACTER SET utf8 DEFAULT NULL,
  `altnick` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `forum_sort` enum('ASC','DESC') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'DESC',
  `pm_forced` enum('yes','no') CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'no',
  `lastactivity` int(11) DEFAULT '0',
  `trial` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `pin_code` int(4) NOT NULL,
  `snow` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `design` int(25) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `ip` (`ip`),
  KEY `uploaded` (`uploaded`),
  KEY `downloaded` (`downloaded`),
  KEY `country` (`country`),
  KEY `last_access` (`last_access`),
  KEY `enabled` (`enabled`),
  KEY `warned` (`warned`),
  KEY `free_switch` (`free_switch`),
  KEY `T_Pass` (`torrent_pass`)
) ENGINE=MyISAM AUTO_INCREMENT=2139 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users_freeleeches`
--

DROP TABLE IF EXISTS `users_freeleeches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_freeleeches` (
  `UserID` int(10) NOT NULL,
  `TorrentID` int(10) NOT NULL,
  `Time` datetime NOT NULL,
  `Expired` tinyint(1) NOT NULL DEFAULT '0',
  `Downloaded` bigint(20) NOT NULL DEFAULT '0',
  `Uses` int(10) NOT NULL DEFAULT '1',
  PRIMARY KEY (`UserID`,`TorrentID`),
  KEY `Time` (`Time`),
  KEY `Expired_Time` (`Expired`,`Time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `usersachiev`
--

DROP TABLE IF EXISTS `usersachiev`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usersachiev` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `totalshoutlvl` tinyint(2) NOT NULL DEFAULT '0',
  `username` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `snatchmaster` tinyint(1) NOT NULL DEFAULT '0',
  `invited` int(3) NOT NULL DEFAULT '0',
  `bday` tinyint(1) NOT NULL DEFAULT '0',
  `ul` tinyint(1) NOT NULL DEFAULT '0',
  `inviterach` tinyint(1) NOT NULL DEFAULT '0',
  `forumposts` int(10) NOT NULL DEFAULT '0',
  `postachiev` tinyint(2) NOT NULL DEFAULT '0',
  `avatarset` tinyint(1) NOT NULL DEFAULT '0',
  `avatarach` tinyint(1) NOT NULL DEFAULT '0',
  `stickyup` int(5) NOT NULL DEFAULT '0',
  `stickyachiev` tinyint(1) NOT NULL DEFAULT '0',
  `sigset` tinyint(1) NOT NULL DEFAULT '0',
  `sigach` tinyint(1) NOT NULL DEFAULT '0',
  `corrupt` tinyint(1) NOT NULL DEFAULT '0',
  `dayseed` tinyint(3) NOT NULL DEFAULT '0',
  `sheepyset` tinyint(1) NOT NULL DEFAULT '0',
  `sheepyach` tinyint(1) NOT NULL DEFAULT '0',
  `spentpoints` int(3) NOT NULL DEFAULT '0',
  `achpoints` int(3) NOT NULL DEFAULT '1',
  `forumtopics` int(10) NOT NULL DEFAULT '0',
  `topicachiev` tinyint(2) NOT NULL DEFAULT '0',
  `bonus` tinyint(2) NOT NULL DEFAULT '0',
  `bonusspent` decimal(10,2) NOT NULL DEFAULT '0.00',
  `christmas` tinyint(1) NOT NULL DEFAULT '0',
  `xmasdays` int(2) NOT NULL DEFAULT '0',
  `reqfilled` int(5) NOT NULL DEFAULT '0',
  `reqlvl` tinyint(2) NOT NULL DEFAULT '0',
  `dailyshouts` int(5) NOT NULL DEFAULT '0',
  `dailyshoutlvl` tinyint(2) NOT NULL DEFAULT '0',
  `weeklyshouts` int(5) NOT NULL DEFAULT '0',
  `weeklyshoutlvl` tinyint(2) NOT NULL DEFAULT '0',
  `monthlyshouts` int(5) NOT NULL DEFAULT '0',
  `monthlyshoutlvl` tinyint(2) NOT NULL DEFAULT '0',
  `totalshouts` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=2139 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ustatus`
--

DROP TABLE IF EXISTS `ustatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ustatus` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `userid` int(10) NOT NULL DEFAULT '0',
  `last_status` varchar(140) CHARACTER SET utf8 DEFAULT NULL,
  `last_update` int(11) NOT NULL DEFAULT '0',
  `archive` text CHARACTER SET utf8,
  PRIMARY KEY (`id`),
  UNIQUE KEY `userid` (`userid`)
) ENGINE=MyISAM AUTO_INCREMENT=74 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `wiki`
--

DROP TABLE IF EXISTS `wiki`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wiki` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `body` longtext CHARACTER SET utf8,
  `userid` int(10) unsigned DEFAULT '0',
  `time` int(11) NOT NULL,
  `lastedit` int(10) unsigned DEFAULT NULL,
  `lastedituser` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xbt_announce_log`
--

DROP TABLE IF EXISTS `xbt_announce_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xbt_announce_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ipa` int(10) unsigned NOT NULL,
  `port` int(11) NOT NULL DEFAULT '0',
  `event` int(11) NOT NULL DEFAULT '0',
  `info_hash` blob NOT NULL,
  `peer_id` blob NOT NULL,
  `downloaded` bigint(20) NOT NULL DEFAULT '0',
  `left0` bigint(20) NOT NULL DEFAULT '0',
  `uploaded` bigint(20) NOT NULL DEFAULT '0',
  `uid` int(11) NOT NULL DEFAULT '0',
  `mtime` int(11) NOT NULL DEFAULT '0',
  `useragent` varchar(51) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xbt_client_whitelist`
--

DROP TABLE IF EXISTS `xbt_client_whitelist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xbt_client_whitelist` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `peer_id` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `vstring` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `peer_id` (`peer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xbt_config`
--

DROP TABLE IF EXISTS `xbt_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xbt_config` (
  `name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `value` varchar(255) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xbt_deny_from_hosts`
--

DROP TABLE IF EXISTS `xbt_deny_from_hosts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xbt_deny_from_hosts` (
  `begin` int(11) NOT NULL DEFAULT '0',
  `end` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xbt_files`
--

DROP TABLE IF EXISTS `xbt_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xbt_files` (
  `fid` int(11) NOT NULL AUTO_INCREMENT,
  `info_hash` blob NOT NULL,
  `leechers` int(11) NOT NULL DEFAULT '0',
  `seeders` int(11) NOT NULL DEFAULT '0',
  `completed` int(11) NOT NULL DEFAULT '0',
  `announced_http` int(11) NOT NULL DEFAULT '0',
  `announced_http_compact` int(11) NOT NULL DEFAULT '0',
  `announced_http_no_peer_id` int(11) NOT NULL DEFAULT '0',
  `announced_udp` int(11) NOT NULL DEFAULT '0',
  `scraped_http` int(11) NOT NULL DEFAULT '0',
  `scraped_udp` int(11) NOT NULL DEFAULT '0',
  `started` int(11) NOT NULL DEFAULT '0',
  `stopped` int(11) NOT NULL DEFAULT '0',
  `flags` int(11) NOT NULL DEFAULT '0',
  `mtime` int(11) NOT NULL DEFAULT '0',
  `ctime` int(11) NOT NULL DEFAULT '0',
  `balance` int(11) NOT NULL DEFAULT '0',
  `freetorrent` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xbt_files_users`
--

DROP TABLE IF EXISTS `xbt_files_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xbt_files_users` (
  `fid` int(11) NOT NULL DEFAULT '0',
  `uid` int(11) NOT NULL DEFAULT '0',
  `active` tinyint(4) NOT NULL DEFAULT '0',
  `announced` int(11) NOT NULL DEFAULT '0',
  `completed` int(11) NOT NULL DEFAULT '0',
  `downloaded` bigint(20) unsigned NOT NULL DEFAULT '0',
  `left` bigint(20) unsigned NOT NULL DEFAULT '0',
  `uploaded` bigint(20) unsigned NOT NULL DEFAULT '0',
  `mtime` int(11) NOT NULL DEFAULT '0',
  `leechtime` bigint(20) unsigned NOT NULL DEFAULT '0',
  `seedtime` bigint(20) unsigned NOT NULL DEFAULT '0',
  `upspeed` int(10) unsigned NOT NULL DEFAULT '0',
  `downspeed` int(10) unsigned NOT NULL DEFAULT '0',
  `peer_id` char(8) COLLATE utf8_unicode_ci NOT NULL,
  `completedtime` int(11) unsigned NOT NULL DEFAULT '0',
  `ipa` int(11) unsigned NOT NULL DEFAULT '0',
  `connectable` tinyint(4) NOT NULL DEFAULT '1',
  `mark_of_cain` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `hit_and_run` int(11) NOT NULL DEFAULT '0',
  `started` int(11) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(39) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `corrupt` bigint(20) NOT NULL DEFAULT '0',
  `useragent` varchar(51) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  UNIQUE KEY `fid` (`fid`,`uid`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=FIXED;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xbt_scrape_log`
--

DROP TABLE IF EXISTS `xbt_scrape_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xbt_scrape_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ipa` int(11) NOT NULL DEFAULT '0',
  `info_hash` blob,
  `uid` int(11) NOT NULL DEFAULT '0',
  `mtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5783 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xbt_snatched`
--

DROP TABLE IF EXISTS `xbt_snatched`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xbt_snatched` (
  `uid` int(11) NOT NULL DEFAULT '0',
  `tstamp` int(11) NOT NULL,
  `fid` int(11) NOT NULL,
  `IP` varchar(15) NOT NULL,
  KEY `fid` (`fid`),
  KEY `tstamp` (`tstamp`),
  KEY `uid_tstamp` (`uid`,`tstamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `xbt_users`
--

DROP TABLE IF EXISTS `xbt_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xbt_users` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `can_leech` tinyint(4) NOT NULL DEFAULT '1',
  `wait_time` int(11) NOT NULL DEFAULT '0',
  `peers_limit` int(11) NOT NULL DEFAULT '0',
  `torrents_limit` int(11) NOT NULL DEFAULT '0',
  `torrent_pass` char(32) CHARACTER SET utf8 NOT NULL,
  `torrent_pass_version` int(11) NOT NULL DEFAULT '0',
  `downloaded` bigint(20) unsigned NOT NULL DEFAULT '0',
  `uploaded` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-08-14 12:58:13
