-- --------------------------------------------------------
-- 主机:                           10.96.70.16
-- 服务器版本:                        5.5.56-MariaDB - MariaDB Server
-- 服务器操作系统:                      Linux
-- HeidiSQL 版本:                  9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- 导出 seal-talk 的数据库结构
DROP DATABASE IF EXISTS `seal-talk`;
CREATE DATABASE IF NOT EXISTS `seal-talk` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_bin */;
USE `seal-talk`;

-- 导出  表 seal-talk.blacklists 结构
DROP TABLE IF EXISTS `blacklists`;
CREATE TABLE IF NOT EXISTS `blacklists` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(10) unsigned NOT NULL,
  `friendId` int(10) unsigned NOT NULL,
  `status` tinyint(1) NOT NULL,
  `timestamp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blacklists_user_id_friend_id` (`userId`,`friendId`),
  KEY `blacklists_user_id_timestamp` (`userId`,`timestamp`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- 正在导出表  seal-talk.blacklists 的数据：~0 rows (大约)
/*!40000 ALTER TABLE `blacklists` DISABLE KEYS */;
/*!40000 ALTER TABLE `blacklists` ENABLE KEYS */;

-- 导出  表 seal-talk.data_versions 结构
DROP TABLE IF EXISTS `data_versions`;
CREATE TABLE IF NOT EXISTS `data_versions` (
  `userId` int(10) unsigned NOT NULL,
  `userVersion` bigint(20) unsigned NOT NULL DEFAULT '0',
  `blacklistVersion` bigint(20) unsigned NOT NULL DEFAULT '0',
  `friendshipVersion` bigint(20) unsigned NOT NULL DEFAULT '0',
  `groupVersion` bigint(20) unsigned NOT NULL DEFAULT '0',
  `groupMemberVersion` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- 正在导出表  seal-talk.data_versions 的数据：~1 rows (大约)
/*!40000 ALTER TABLE `data_versions` DISABLE KEYS */;
INSERT INTO `data_versions` (`userId`, `userVersion`, `blacklistVersion`, `friendshipVersion`, `groupVersion`, `groupMemberVersion`) VALUES
	(2, 0, 0, 0, 0, 0);
/*!40000 ALTER TABLE `data_versions` ENABLE KEYS */;

-- 导出  表 seal-talk.friendships 结构
DROP TABLE IF EXISTS `friendships`;
CREATE TABLE IF NOT EXISTS `friendships` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(10) unsigned NOT NULL,
  `friendId` int(10) unsigned NOT NULL,
  `displayName` varchar(32) COLLATE utf8_bin NOT NULL DEFAULT '',
  `message` varchar(64) COLLATE utf8_bin NOT NULL,
  `status` int(10) unsigned NOT NULL,
  `timestamp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `friendships_user_id_friend_id` (`userId`,`friendId`),
  KEY `friendships_user_id_timestamp` (`userId`,`timestamp`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- 正在导出表  seal-talk.friendships 的数据：~0 rows (大约)
/*!40000 ALTER TABLE `friendships` DISABLE KEYS */;
/*!40000 ALTER TABLE `friendships` ENABLE KEYS */;

-- 导出  表 seal-talk.groups 结构
DROP TABLE IF EXISTS `groups`;
CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) COLLATE utf8_bin NOT NULL,
  `portraitUri` varchar(256) COLLATE utf8_bin NOT NULL DEFAULT '',
  `memberCount` int(10) unsigned NOT NULL DEFAULT '0',
  `maxMemberCount` int(10) unsigned NOT NULL DEFAULT '500',
  `creatorId` int(10) unsigned NOT NULL,
  `bulletin` text COLLATE utf8_bin,
  `timestamp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL,
  `deletedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `groups_id_timestamp` (`id`,`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- 正在导出表  seal-talk.groups 的数据：~0 rows (大约)
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;

-- 导出  表 seal-talk.group_members 结构
DROP TABLE IF EXISTS `group_members`;
CREATE TABLE IF NOT EXISTS `group_members` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `groupId` int(10) unsigned NOT NULL,
  `memberId` int(10) unsigned NOT NULL,
  `displayName` varchar(32) COLLATE utf8_bin NOT NULL DEFAULT '',
  `role` int(10) unsigned NOT NULL,
  `isDeleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_members_group_id_member_id_is_deleted` (`groupId`,`memberId`,`isDeleted`),
  KEY `group_members_member_id_timestamp` (`memberId`,`timestamp`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- 正在导出表  seal-talk.group_members 的数据：~0 rows (大约)
/*!40000 ALTER TABLE `group_members` DISABLE KEYS */;
/*!40000 ALTER TABLE `group_members` ENABLE KEYS */;

-- 导出  表 seal-talk.group_syncs 结构
DROP TABLE IF EXISTS `group_syncs`;
CREATE TABLE IF NOT EXISTS `group_syncs` (
  `groupId` int(10) unsigned NOT NULL DEFAULT '0',
  `syncInfo` tinyint(1) NOT NULL DEFAULT '0',
  `syncMember` tinyint(1) NOT NULL DEFAULT '0',
  `dismiss` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`groupId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- 正在导出表  seal-talk.group_syncs 的数据：~0 rows (大约)
/*!40000 ALTER TABLE `group_syncs` DISABLE KEYS */;
/*!40000 ALTER TABLE `group_syncs` ENABLE KEYS */;

-- 导出  表 seal-talk.login_logs 结构
DROP TABLE IF EXISTS `login_logs`;
CREATE TABLE IF NOT EXISTS `login_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(10) unsigned NOT NULL,
  `ipAddress` int(10) unsigned NOT NULL,
  `os` varchar(64) COLLATE utf8_bin NOT NULL,
  `osVersion` varchar(64) COLLATE utf8_bin NOT NULL,
  `carrier` varchar(64) COLLATE utf8_bin NOT NULL,
  `device` varchar(64) COLLATE utf8_bin DEFAULT NULL,
  `manufacturer` varchar(64) COLLATE utf8_bin DEFAULT NULL,
  `userAgent` varchar(256) COLLATE utf8_bin DEFAULT NULL,
  `createdAt` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- 正在导出表  seal-talk.login_logs 的数据：~0 rows (大约)
/*!40000 ALTER TABLE `login_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `login_logs` ENABLE KEYS */;

-- 导出  表 seal-talk.users 结构
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `region` varchar(5) COLLATE utf8_bin NOT NULL,
  `phone` varchar(11) COLLATE utf8_bin NOT NULL,
  `nickname` varchar(32) COLLATE utf8_bin NOT NULL,
  `portraitUri` varchar(256) COLLATE utf8_bin NOT NULL DEFAULT '',
  `passwordHash` char(40) COLLATE utf8_bin NOT NULL,
  `passwordSalt` char(4) COLLATE utf8_bin NOT NULL,
  `rongCloudToken` varchar(256) COLLATE utf8_bin NOT NULL DEFAULT '',
  `groupCount` int(10) unsigned NOT NULL DEFAULT '0',
  `timestamp` bigint(20) NOT NULL DEFAULT '0',
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL,
  `deletedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_region_phone` (`region`,`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- 导出  表 seal-talk.verification_codes 结构
DROP TABLE IF EXISTS `verification_codes`;
CREATE TABLE IF NOT EXISTS `verification_codes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `region` varchar(5) COLLATE utf8_bin NOT NULL,
  `phone` varchar(11) COLLATE utf8_bin NOT NULL,
  `sessionId` varchar(32) COLLATE utf8_bin NOT NULL,
  `token` char(36) COLLATE utf8_bin NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL,
  PRIMARY KEY (`id`,`region`,`phone`),
  UNIQUE KEY `token` (`token`),
  UNIQUE KEY `verification_codes_token_unique` (`token`),
  UNIQUE KEY `verification_codes_region_phone` (`region`,`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
