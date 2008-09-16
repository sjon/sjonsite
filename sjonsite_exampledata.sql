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

INSERT INTO sjonsite_pages (p_id, p_pid, p_uri, p_title, p_sorting, p_state) VALUES (1, NULL, '/', 'Homepage', 1, 'A');
INSERT INTO sjonsite_pages (p_id, p_pid, p_uri, p_title, p_sorting, p_state) VALUES (2, 1, '/about', 'About Us', 1, 'A');
INSERT INTO sjonsite_pages (p_id, p_pid, p_uri, p_title, p_sorting, p_state) VALUES (3, 1, '/contact', 'Contact Us', 2, 'A');
INSERT INTO sjonsite_pages (p_id, p_pid, p_uri, p_title, p_sorting, p_state) VALUES (4, NULL, '/products', 'Our Products', 2, 'A');
INSERT INTO sjonsite_pages (p_id, p_pid, p_uri, p_title, p_sorting, p_state) VALUES (5, 4, '/products/foobar', 'Foobar Product', 1, 'A');
INSERT INTO sjonsite_pages (p_id, p_pid, p_uri, p_title, p_sorting, p_state) VALUES (6, 5, '/products/foobar/specs', 'Foobar Product - Specs', 1, 'A');
INSERT INTO sjonsite_pages (p_id, p_pid, p_uri, p_title, p_gallery, p_sorting, p_state) VALUES (7, 5, '/products/foobar/gallery', 'Foobar Product - Gallery', 1, 2, 'A');
INSERT INTO sjonsite_pages (p_id, p_pid, p_uri, p_title, p_sorting, p_state) VALUES (8, 4, '/products/barbaz', 'Barbaz Product', 2, 'A');
INSERT INTO sjonsite_pages (p_id, p_pid, p_uri, p_title, p_sorting, p_state) VALUES (9, 8, '/products/barbaz/specs', 'Barbaz Product - Specs', 1, 'A');
INSERT INTO sjonsite_pages (p_id, p_pid, p_uri, p_title, p_sorting, p_state) VALUES (10, NULL, '/services', 'Our Services', 3, 'A');
INSERT INTO sjonsite_pages (p_id, p_pid, p_uri, p_title, p_sorting, p_state) VALUES (11, 10, '/services/research', 'Research', 1, 'A');
INSERT INTO sjonsite_pages (p_id, p_pid, p_uri, p_title, p_sorting, p_state) VALUES (12, 10, '/services/development', 'Development', 2, 'A');
INSERT INTO sjonsite_pages (p_id, p_pid, p_uri, p_title, p_sorting, p_state) VALUES (13, 10, '/services/support', 'Support', 3, 'A');
INSERT INTO sjonsite_pages (p_id, p_pid, p_uri, p_title, p_sorting, p_state) VALUES (14, 10, '/services/refurbishing', 'Refurbishing', 4, 'A');
INSERT INTO sjonsite_pages (p_id, p_pid, p_uri, p_title, p_sorting, p_state) VALUES (15, NULL, '/support', 'Get Support', 4, 'A');

INSERT INTO sjonsite_gallery (g_id, g_page, g_title, g_summary) VALUES (1, 7, 'Foobar Product Gallery', NULL);

