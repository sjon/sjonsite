--
-- /**
--  * Sjonsite - SQL Structure
--  *
--  * @author Sjon <sjonscom@gmail.com>
--  * @package Sjonsite
--  * @copyright Sjon's dotCom 2008
--  * @license Mozilla Public License 1.1
--  * @version $Id$
--  */
--
SET
  FOREIGN_KEY_CHECKS = 0;
SET
  SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET
  AUTOCOMMIT = 0;

START TRANSACTION;

-- CREATE DATABASE sjonsite /*!40100 DEFAULT CHARACTER SET utf8 */;
  DROP TABLE IF EXISTS `sjonsite_resources`;
CREATE TABLE IF NOT EXISTS `sjonsite_resources` (
    `id` mediumint(8) unsigned NOT NULL auto_increment,
    `parent` mediumint(8) unsigned default NULL,
    `trail` varchar(16) NOT NULL,
    `type` varchar(128) NOT NULL,
    `controller` varchar(16) NOT NULL DEFAULT 'resource',
    `sorting` smallint(5) unsigned NOT NULL,
    `visible` enum('Y', 'N') NOT NULL DEFAULT 'Y',
    `state` enum('A', 'S', 'R', 'U') NOT NULL default 'U',
    PRIMARY KEY (`id`),
    KEY `parent_idx` (`parent`),
    KEY `sorting_idx` (`sorting`),
    KEY `state_idx` (`state`)
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8 AUTO_INCREMENT = 1;

DROP TABLE IF EXISTS `sjonsite_revisions`;
CREATE TABLE IF NOT EXISTS `sjonsite_revisions` (
    `id` int(10) unsigned NOT NULL auto_increment,
    `resource` mediumint(8) unsigned NOT NULL,
    `revision` smallint(5) unsigned NOT NULL,
    `uri` varchar(255) NOT NULL,
    `short` varchar(64) NOT NULL,
    `title` varchar(255) NOT NULL,
    `content` mediumtext,
    `state` enum('A', 'S', 'R', 'U') NOT NULL default 'U',
    PRIMARY KEY (`id`),
    KEY `resource_idx` (`resource`),
    KEY `revision_idx` (`revision`),
    KEY `uri_idx` (`uri`),
    KEY `state_idx` (`state`)
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8 AUTO_INCREMENT = 1;

DROP TABLE IF EXISTS `sjonsite_settings`;
CREATE TABLE IF NOT EXISTS `sjonsite_settings` (
    `name` varchar(128) NOT NULL,
    `value` blob,
    PRIMARY KEY (`name`)
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS `sjonsite_users`;
CREATE TABLE IF NOT EXISTS `sjonsite_users` (
    `id` smallint(5) unsigned NOT NULL auto_increment,
    `name` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL,
    `passwd` char(40) NOT NULL,
    `level` smallint(5) unsigned NOT NULL,
    `state` enum('A', 'S', 'R', 'U') NOT NULL default 'U',
    PRIMARY KEY (`id`),
    UNIQUE KEY `email_idx` (`email`),
    KEY `state_idx` (`state`)
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8 AUTO_INCREMENT = 1;

ALTER TABLE
  `sjonsite_resources`
ADD
  CONSTRAINT `resource_fk` FOREIGN KEY (`parent`) REFERENCES `sjonsite_resources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE
  `sjonsite_revisions`
ADD
  CONSTRAINT `revision_fk` FOREIGN KEY (`resource`) REFERENCES `sjonsite_resources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
SET
  FOREIGN_KEY_CHECKS = 1;

COMMIT;

