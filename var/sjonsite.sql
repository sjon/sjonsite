-- /*
-- Sjonsite MySQL DDL
--
-- @author Sjon <sjonscom@gmail.com>
-- @package Sjonsite
-- @copyright Sjon's dotCom 2007
-- @license Mozilla Public License 1.1
-- @version $Id$
-- */

CREATE DATABASE sjonsite /*!40100 DEFAULT CHARACTER SET utf8 */;

-- core resources

DROP TABLE IF EXISTS v1_resource;
CREATE TABLE v1_resource (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	ns_left INT UNSIGNED NOT NULL,
	ns_right INT UNSIGNED NOT NULL,
	title VARCHAR (255) NOT NULL,
	description TINYTEXT NULL,
	keywords TINYTEXT NULL,
	module VARCHAR (64) NOT NULL,
	state ENUM ('A', 'S', 'R', 'U') NOT NULL DEFAULT 'U',
	PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS v1_alias;
CREATE TABLE v1_alias (
	uri VARCHAR (255) NOT NULL,
	resource INT UNSIGNED NOT NULL,
	UNIQUE KEY uri_idx (uri),
	KEY resource_idx (resource),
	CONSTRAINT resource_fk FOREIGN KEY (resource) REFERENCES v1_resource (id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO v1_resource VALUES (1, 1, 2, 'Homepage', NULL, NULL, 'content', 'A');
INSERT INTO v1_alias VALUES ('/', 1);
INSERT INTO v1_alias VALUES ('/home', 1);
INSERT INTO v1_resource VALUES (2, 0, 0, 'Management', NULL, NULL, 'content', 'A');
INSERT INTO v1_alias VALUES ('/management', 2);

DROP VIEW IF EXISTS sjonsite.v_resource_by_alias;
CREATE VIEW sjonsite.v_resource_by_alias AS SELECT * FROM v1_resource r RIGHT JOIN v1_alias a ON a.resource = r.id WHERE uri = '/';

DROP VIEW IF EXISTS sjonsite.v_resource_cache;
CREATE VIEW sjonsite.v_resource_cache AS SELECT r.id, a.uri, r.module, r.state FROM v1_resource r RIGHT JOIN v1_alias a ON a.resource = r.id;

-- core users

DROP TABLE IF EXISTS v1_user;
CREATE TABLE v1_user (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	firstname VARCHAR (64) NULL,
	lastname VARCHAR (64) NULL,
	email VARCHAR (255) NOT NULL,
	passwd CHAR (32) NOT NULL,
	access TINYINT UNSIGNED NOT NULL DEFAULT '0',
	state ENUM ('A', 'H', 'S', 'R', 'U') NOT NULL DEFAULT 'U',
	PRIMARY KEY (id),
	UNIQUE KEY email_idx (email),
	KEY state_idx (state)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP VIEW IF EXISTS sjonsite.v_user;
CREATE VIEW sjonsite.v_user AS SELECT * FROM v1_user;

-- content module

DROP TABLE IF EXISTS v1_content;
CREATE TABLE v1_content (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	resource INT UNSIGNED NOT NULL,
	title VARCHAR (255) NULL,
	teaser TEXT NULL,
	intro TEXT NULL,
	content MEDIUMTEXT NULL,
	created DATETIME NULL,
	modified DATETIME NULL,
	state ENUM ('A', 'H', 'S', 'R', 'U') NOT NULL DEFAULT 'U',
	PRIMARY KEY (id),
	KEY resource_idx (resource),
	CONSTRAINT resource_fk FOREIGN KEY (resource) REFERENCES v1_resource (id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- assets (images, files)
DROP TABLE IF EXISTS v1_content_asset;
CREATE TABLE v1_content_asset (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	resource INT UNSIGNED NULL,
	title VARCHAR (255),
	filename VARCHAR (255),
	filesize INT UNSIGNED,
	filetype CHAR(3),
	display ENUM ('I', 'A', 'H') DEFAULT 'H' NOT NULL, -- inline, attachment, hidden
	PRIMARY KEY (id),
	KEY resource_idx (resource),
	CONSTRAINT resource_fk FOREIGN KEY (resource) REFERENCES v1_resource (id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

