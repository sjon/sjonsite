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

-- CREATE DATABASE sjonsite /*!40100 DEFAULT CHARACTER SET utf8 */;

DROP TABLE IF EXISTS sjonsite_pages;
CREATE TABLE sjonsite_pages (
	p_id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
	p_pid MEDIUMINT UNSIGNED NULL,
	p_uri VARCHAR (255) NOT NULL,
	p_title VARCHAR (255) NOT NULL,
	p_summary TEXT NULL,
	p_content MEDIUMTEXT NULL,
	p_gallery MEDIUMINT UNSIGNED NULL,
	p_sorting SMALLINT UNSIGNED NOT NULL,
	p_state ENUM ('A', 'S', 'R', 'U') NOT NULL DEFAULT 'U',
	PRIMARY KEY (p_id),
	UNIQUE KEY p_uri_idx (p_uri),
	KEY p_sorting_idx (p_sorting),
	KEY p_state_idx (p_state)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Some example data

INSERT INTO sjonsite_pages (p_id, p_pid, p_uri, p_title, p_sorting, p_state) VALUES (1, NULL, '/', 'Homepage', 1, 'A');
INSERT INTO sjonsite_pages (p_id, p_pid, p_uri, p_title, p_sorting, p_state) VALUES (2, 1, '/about', 'About Us', 1, 'A');
INSERT INTO sjonsite_pages (p_id, p_pid, p_uri, p_title, p_sorting, p_state) VALUES (3, 1, '/contact', 'Contact Us', 2, 'A');
INSERT INTO sjonsite_pages (p_id, p_pid, p_uri, p_title, p_sorting, p_state) VALUES (4, NULL, '/products', 'Our Products', 2, 'A');
INSERT INTO sjonsite_pages (p_id, p_pid, p_uri, p_title, p_sorting, p_state) VALUES (5, 4, '/products/foobar', 'Foobar Product', 1, 'A');
INSERT INTO sjonsite_pages (p_id, p_pid, p_uri, p_title, p_sorting, p_state) VALUES (6, 5, '/products/foobar/specs', 'Foobar Product - Specs', 1, 'A');
INSERT INTO sjonsite_pages (p_id, p_pid, p_uri, p_title, p_sorting, p_state) VALUES (7, 5, '/products/foobar/reviews', 'Foobar Product - Reviews', 2, 'A');
INSERT INTO sjonsite_pages (p_id, p_pid, p_uri, p_title, p_sorting, p_state) VALUES (8, 4, '/products/barbaz', 'Barbaz Product', 2, 'A');
INSERT INTO sjonsite_pages (p_id, p_pid, p_uri, p_title, p_sorting, p_state) VALUES (9, 8, '/products/barbaz/specs', 'Barbaz Product - Specs', 1, 'A');
INSERT INTO sjonsite_pages (p_id, p_pid, p_uri, p_title, p_sorting, p_state) VALUES (10, NULL, '/services', 'Our Services', 3, 'A');
INSERT INTO sjonsite_pages (p_id, p_pid, p_uri, p_title, p_sorting, p_state) VALUES (11, 10, '/services/research', 'Research', 1, 'A');
INSERT INTO sjonsite_pages (p_id, p_pid, p_uri, p_title, p_sorting, p_state) VALUES (12, 10, '/services/development', 'Development', 2, 'A');
INSERT INTO sjonsite_pages (p_id, p_pid, p_uri, p_title, p_sorting, p_state) VALUES (13, 10, '/services/support', 'Support', 3, 'A');
INSERT INTO sjonsite_pages (p_id, p_pid, p_uri, p_title, p_sorting, p_state) VALUES (14, 10, '/services/refurbishing', 'Refurbishing', 4, 'A');
INSERT INTO sjonsite_pages (p_id, p_pid, p_uri, p_title, p_sorting, p_state) VALUES (15, NULL, '/support', 'Get Support', 4, 'A');

DROP TABLE IF EXISTS sjonsite_gallery;
CREATE TABLE sjonsite_gallery (
	g_id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
	g_page MEDIUMINT UNSIGNED NULL,
	g_title VARCHAR (255) NOT NULL,
	g_summary TEXT NULL,
	PRIMARY KEY (g_id),
	UNIQUE KEY g_page_idx (g_page),
	CONSTRAINT g_page_fk FOREIGN KEY (g_page) REFERENCES sjonsite_pages (p_id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS sjonsite_images;
CREATE TABLE sjonsite_images (
	i_id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
	i_parent ENUM ('P', 'G', 'U') NOT NULL DEFAULT 'U',
	i_parent_id MEDIUMINT UNSIGNED NULL,
	i_uri VARCHAR (255) NOT NULL,
	i_title VARCHAR (255) NOT NULL,
	i_width SMALLINT UNSIGNED NOT NULL,
	i_height SMALLINT UNSIGNED NOT NULL,
	PRIMARY KEY (i_id),
	KEY i_parent_idx (i_parent),
	KEY i_parent_id_idx (i_parent_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS sjonsite_users;
CREATE TABLE sjonsite_users (
	u_id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	u_name VARCHAR (255) NOT NULL,
	u_email VARCHAR (255) NOT NULL,
	u_passwd CHAR (40) NOT NULL,
	u_level SMALLINT UNSIGNED NOT NULL,
	u_state ENUM ('A', 'S', 'R', 'U') NOT NULL DEFAULT 'U',
	PRIMARY KEY (u_id),
	UNIQUE KEY u_email_idx (u_email),
	KEY u_state_idx (u_state)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS sjonsite_settings;
CREATE TABLE sjonsite_settings (
	s_name VARCHAR (128) NOT NULL,
	s_value BLOB NULL,
	PRIMARY KEY (s_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
