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

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT=0;
START TRANSACTION;

TRUNCATE `sjonsite_resources`;

INSERT INTO `sjonsite_resources` (`id`, `parent`, `trail`, `type`, `controller`, `sorting`, `visible`, `state`) VALUES (1, NULL, '1', 'text/html', 'resource', 1, 'Y', 'A');
INSERT INTO `sjonsite_resources` (`id`, `parent`, `trail`, `type`, `controller`, `sorting`, `visible`, `state`) VALUES (2, 1, '1-2', 'text/html', 'resource', 1, 'Y', 'A');
INSERT INTO `sjonsite_resources` (`id`, `parent`, `trail`, `type`, `controller`, `sorting`, `visible`, `state`) VALUES (3, 1, '1-3', 'text/html', 'resource', 1, 'Y', 'A');
INSERT INTO `sjonsite_resources` (`id`, `parent`, `trail`, `type`, `controller`, `sorting`, `visible`, `state`) VALUES (4, NULL, '4', 'text/html', 'resource', 1, 'Y', 'A');
INSERT INTO `sjonsite_resources` (`id`, `parent`, `trail`, `type`, `controller`, `sorting`, `visible`, `state`) VALUES (5, 4, '4-5', 'text/html', 'resource', 1, 'Y', 'A');
INSERT INTO `sjonsite_resources` (`id`, `parent`, `trail`, `type`, `controller`, `sorting`, `visible`, `state`) VALUES (6, 5, '4-5-6', 'text/html', 'resource', 1, 'Y', 'A');
INSERT INTO `sjonsite_resources` (`id`, `parent`, `trail`, `type`, `controller`, `sorting`, `visible`, `state`) VALUES (7, 5, '4-5-7', 'text/html', 'resource', 1, 'Y', 'A');
INSERT INTO `sjonsite_resources` (`id`, `parent`, `trail`, `type`, `controller`, `sorting`, `visible`, `state`) VALUES (8, 4, '4-8', 'text/html', 'resource', 1, 'Y', 'A');
INSERT INTO `sjonsite_resources` (`id`, `parent`, `trail`, `type`, `controller`, `sorting`, `visible`, `state`) VALUES (9, 8, '4-8-9', 'text/html', 'resource', 1, 'Y', 'A');
INSERT INTO `sjonsite_resources` (`id`, `parent`, `trail`, `type`, `controller`, `sorting`, `visible`, `state`) VALUES (10, NULL, '10', 'text/html', 'resource', 1, 'Y', 'A');
INSERT INTO `sjonsite_resources` (`id`, `parent`, `trail`, `type`, `controller`, `sorting`, `visible`, `state`) VALUES (11, 10, '10-11', 'text/html', 'resource', 1, 'Y', 'A');
INSERT INTO `sjonsite_resources` (`id`, `parent`, `trail`, `type`, `controller`, `sorting`, `visible`, `state`) VALUES (12, 10, '10-12', 'text/html', 'resource', 1, 'Y', 'A');
INSERT INTO `sjonsite_resources` (`id`, `parent`, `trail`, `type`, `controller`, `sorting`, `visible`, `state`) VALUES (13, 10, '10-13', 'text/html', 'resource', 1, 'Y', 'A');
INSERT INTO `sjonsite_resources` (`id`, `parent`, `trail`, `type`, `controller`, `sorting`, `visible`, `state`) VALUES (14, 10, '10-14', 'text/html', 'resource', 1, 'Y', 'A');
INSERT INTO `sjonsite_resources` (`id`, `parent`, `trail`, `type`, `controller`, `sorting`, `visible`, `state`) VALUES (15, NULL, '15', 'text/html', 'resource', 1, 'Y', 'A');

TRUNCATE `sjonsite_revisions`;

INSERT INTO `sjonsite_revisions` (`id`, `resource`, `revision`, `uri`, `short`, `title`, `content`) VALUES (1, 1, 1, '/', 'Home', 'Homepage', '<h2>Lorem ipsum dolor sit amet, consectetur adipiscing elit.<h2>\n<p>Praesent sit amet odio diam. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nulla pretium, arcu et dignissim bibendum, felis ligula elementum turpis, et interdum nisl ligula id nibh.<p>\n<h3>Praesent eget nulla eget eros adipiscing feugiat.</h3>\n<p>Duis risus urna, pulvinar in fermentum quis, consequat eu mi. In id libero et urna fermentum dignissim varius quis dui. Cras at sem sapien. Duis ultricies, lectus eget venenatis adipiscing, velit augue facilisis ipsum, non pulvinar neque nisl pellentesque ante.<p>\n<h3>Fusce iaculis sit amet adipiscing odio enim in ligula.</h3>\n<p>Cras pharetra congue ipsum at luctus. Nunc sed justo orci. In a turpis erat. Vivamus tincidunt, lacus nec malesuada cursus, nunc nibh bibendum purus, sed lobortis mi neque semper leo. Fusce sagittis mi justo. Mauris ullamcorper, lacus vitae sodales tempus.<p>\n<ul>\n\t<li>Leo massa vitae eros.</li>\n\t<li>Etiam sem est</li>\n\t<li>Hendrerit sit amet</li>\n\t<li>Adipiscing in cursus</li>\n\t<li>Ut risus.</li>\n</ul>');
INSERT INTO `sjonsite_revisions` (`id`, `resource`, `revision`, `uri`, `short`, `title`, `content`) VALUES (2, 2, 1, '/about', 'About', 'About Sjonsite', '<h2>Lorem ipsum dolor sit amet, consectetur adipiscing elit.<h2>\n<p>Praesent sit amet odio diam. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nulla pretium, arcu et dignissim bibendum, felis ligula elementum turpis, et interdum nisl ligula id nibh.<p>\n<h3>Praesent eget nulla eget eros adipiscing feugiat.</h3>\n<p>Duis risus urna, pulvinar in fermentum quis, consequat eu mi. In id libero et urna fermentum dignissim varius quis dui. Cras at sem sapien. Duis ultricies, lectus eget venenatis adipiscing, velit augue facilisis ipsum, non pulvinar neque nisl pellentesque ante.<p>\n<h3>Fusce iaculis sit amet adipiscing odio enim in ligula.</h3>\n<p>Cras pharetra congue ipsum at luctus. Nunc sed justo orci. In a turpis erat. Vivamus tincidunt, lacus nec malesuada cursus, nunc nibh bibendum purus, sed lobortis mi neque semper leo. Fusce sagittis mi justo. Mauris ullamcorper, lacus vitae sodales tempus.<p>\n<ul>\n\t<li>Leo massa vitae eros.</li>\n\t<li>Etiam sem est</li>\n\t<li>Hendrerit sit amet</li>\n\t<li>Adipiscing in cursus</li>\n\t<li>Ut risus.</li>\n</ul>');
INSERT INTO `sjonsite_revisions` (`id`, `resource`, `revision`, `uri`, `short`, `title`, `content`) VALUES (3, 3, 1, '/contact', 'Contact', 'Contact Sjonsite', '<h2>Lorem ipsum dolor sit amet, consectetur adipiscing elit.<h2>\n<p>Praesent sit amet odio diam. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nulla pretium, arcu et dignissim bibendum, felis ligula elementum turpis, et interdum nisl ligula id nibh.<p>\n<h3>Praesent eget nulla eget eros adipiscing feugiat.</h3>\n<p>Duis risus urna, pulvinar in fermentum quis, consequat eu mi. In id libero et urna fermentum dignissim varius quis dui. Cras at sem sapien. Duis ultricies, lectus eget venenatis adipiscing, velit augue facilisis ipsum, non pulvinar neque nisl pellentesque ante.<p>\n<h3>Fusce iaculis sit amet adipiscing odio enim in ligula.</h3>\n<p>Cras pharetra congue ipsum at luctus. Nunc sed justo orci. In a turpis erat. Vivamus tincidunt, lacus nec malesuada cursus, nunc nibh bibendum purus, sed lobortis mi neque semper leo. Fusce sagittis mi justo. Mauris ullamcorper, lacus vitae sodales tempus.<p>\n<ul>\n\t<li>Leo massa vitae eros.</li>\n\t<li>Etiam sem est</li>\n\t<li>Hendrerit sit amet</li>\n\t<li>Adipiscing in cursus</li>\n\t<li>Ut risus.</li>\n</ul>');
INSERT INTO `sjonsite_revisions` (`id`, `resource`, `revision`, `uri`, `short`, `title`, `content`) VALUES (4, 4, 1, '/products', 'Products', 'Our Products', '<h2>Lorem ipsum dolor sit amet, consectetur adipiscing elit.<h2>\n<p>Praesent sit amet odio diam. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nulla pretium, arcu et dignissim bibendum, felis ligula elementum turpis, et interdum nisl ligula id nibh.<p>\n<h3>Praesent eget nulla eget eros adipiscing feugiat.</h3>\n<p>Duis risus urna, pulvinar in fermentum quis, consequat eu mi. In id libero et urna fermentum dignissim varius quis dui. Cras at sem sapien. Duis ultricies, lectus eget venenatis adipiscing, velit augue facilisis ipsum, non pulvinar neque nisl pellentesque ante.<p>\n<h3>Fusce iaculis sit amet adipiscing odio enim in ligula.</h3>\n<p>Cras pharetra congue ipsum at luctus. Nunc sed justo orci. In a turpis erat. Vivamus tincidunt, lacus nec malesuada cursus, nunc nibh bibendum purus, sed lobortis mi neque semper leo. Fusce sagittis mi justo. Mauris ullamcorper, lacus vitae sodales tempus.<p>\n<ul>\n\t<li>Leo massa vitae eros.</li>\n\t<li>Etiam sem est</li>\n\t<li>Hendrerit sit amet</li>\n\t<li>Adipiscing in cursus</li>\n\t<li>Ut risus.</li>\n</ul>');
INSERT INTO `sjonsite_revisions` (`id`, `resource`, `revision`, `uri`, `short`, `title`, `content`) VALUES (5, 5, 1, '/products/foobar', 'Foobar', 'Foobar Product Overview', '<h2>Lorem ipsum dolor sit amet, consectetur adipiscing elit.<h2>\n<p>Praesent sit amet odio diam. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nulla pretium, arcu et dignissim bibendum, felis ligula elementum turpis, et interdum nisl ligula id nibh.<p>\n<h3>Praesent eget nulla eget eros adipiscing feugiat.</h3>\n<p>Duis risus urna, pulvinar in fermentum quis, consequat eu mi. In id libero et urna fermentum dignissim varius quis dui. Cras at sem sapien. Duis ultricies, lectus eget venenatis adipiscing, velit augue facilisis ipsum, non pulvinar neque nisl pellentesque ante.<p>\n<h3>Fusce iaculis sit amet adipiscing odio enim in ligula.</h3>\n<p>Cras pharetra congue ipsum at luctus. Nunc sed justo orci. In a turpis erat. Vivamus tincidunt, lacus nec malesuada cursus, nunc nibh bibendum purus, sed lobortis mi neque semper leo. Fusce sagittis mi justo. Mauris ullamcorper, lacus vitae sodales tempus.<p>\n<ul>\n\t<li>Leo massa vitae eros.</li>\n\t<li>Etiam sem est</li>\n\t<li>Hendrerit sit amet</li>\n\t<li>Adipiscing in cursus</li>\n\t<li>Ut risus.</li>\n</ul>');
INSERT INTO `sjonsite_revisions` (`id`, `resource`, `revision`, `uri`, `short`, `title`, `content`) VALUES (6, 6, 1, '/products/foobar/specs', 'Specs', 'Specifications of Foobar', '<h2>Lorem ipsum dolor sit amet, consectetur adipiscing elit.<h2>\n<p>Praesent sit amet odio diam. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nulla pretium, arcu et dignissim bibendum, felis ligula elementum turpis, et interdum nisl ligula id nibh.<p>\n<h3>Praesent eget nulla eget eros adipiscing feugiat.</h3>\n<p>Duis risus urna, pulvinar in fermentum quis, consequat eu mi. In id libero et urna fermentum dignissim varius quis dui. Cras at sem sapien. Duis ultricies, lectus eget venenatis adipiscing, velit augue facilisis ipsum, non pulvinar neque nisl pellentesque ante.<p>\n<h3>Fusce iaculis sit amet adipiscing odio enim in ligula.</h3>\n<p>Cras pharetra congue ipsum at luctus. Nunc sed justo orci. In a turpis erat. Vivamus tincidunt, lacus nec malesuada cursus, nunc nibh bibendum purus, sed lobortis mi neque semper leo. Fusce sagittis mi justo. Mauris ullamcorper, lacus vitae sodales tempus.<p>\n<ul>\n\t<li>Leo massa vitae eros.</li>\n\t<li>Etiam sem est</li>\n\t<li>Hendrerit sit amet</li>\n\t<li>Adipiscing in cursus</li>\n\t<li>Ut risus.</li>\n</ul>');
INSERT INTO `sjonsite_revisions` (`id`, `resource`, `revision`, `uri`, `short`, `title`, `content`) VALUES (7, 7, 1, '/products/foobar/gallery', 'Gallery', 'Foobar Gallery', '<h2>Lorem ipsum dolor sit amet, consectetur adipiscing elit.<h2>\n<p>Praesent sit amet odio diam. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nulla pretium, arcu et dignissim bibendum, felis ligula elementum turpis, et interdum nisl ligula id nibh.<p>\n<h3>Praesent eget nulla eget eros adipiscing feugiat.</h3>\n<p>Duis risus urna, pulvinar in fermentum quis, consequat eu mi. In id libero et urna fermentum dignissim varius quis dui. Cras at sem sapien. Duis ultricies, lectus eget venenatis adipiscing, velit augue facilisis ipsum, non pulvinar neque nisl pellentesque ante.<p>\n<h3>Fusce iaculis sit amet adipiscing odio enim in ligula.</h3>\n<p>Cras pharetra congue ipsum at luctus. Nunc sed justo orci. In a turpis erat. Vivamus tincidunt, lacus nec malesuada cursus, nunc nibh bibendum purus, sed lobortis mi neque semper leo. Fusce sagittis mi justo. Mauris ullamcorper, lacus vitae sodales tempus.<p>\n<ul>\n\t<li>Leo massa vitae eros.</li>\n\t<li>Etiam sem est</li>\n\t<li>Hendrerit sit amet</li>\n\t<li>Adipiscing in cursus</li>\n\t<li>Ut risus.</li>\n</ul>');
INSERT INTO `sjonsite_revisions` (`id`, `resource`, `revision`, `uri`, `short`, `title`, `content`) VALUES (8, 8, 1, '/products/barbaz', 'Barbaz', 'Barbaz Product Overview', '<h2>Lorem ipsum dolor sit amet, consectetur adipiscing elit.<h2>\n<p>Praesent sit amet odio diam. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nulla pretium, arcu et dignissim bibendum, felis ligula elementum turpis, et interdum nisl ligula id nibh.<p>\n<h3>Praesent eget nulla eget eros adipiscing feugiat.</h3>\n<p>Duis risus urna, pulvinar in fermentum quis, consequat eu mi. In id libero et urna fermentum dignissim varius quis dui. Cras at sem sapien. Duis ultricies, lectus eget venenatis adipiscing, velit augue facilisis ipsum, non pulvinar neque nisl pellentesque ante.<p>\n<h3>Fusce iaculis sit amet adipiscing odio enim in ligula.</h3>\n<p>Cras pharetra congue ipsum at luctus. Nunc sed justo orci. In a turpis erat. Vivamus tincidunt, lacus nec malesuada cursus, nunc nibh bibendum purus, sed lobortis mi neque semper leo. Fusce sagittis mi justo. Mauris ullamcorper, lacus vitae sodales tempus.<p>\n<ul>\n\t<li>Leo massa vitae eros.</li>\n\t<li>Etiam sem est</li>\n\t<li>Hendrerit sit amet</li>\n\t<li>Adipiscing in cursus</li>\n\t<li>Ut risus.</li>\n</ul>');
INSERT INTO `sjonsite_revisions` (`id`, `resource`, `revision`, `uri`, `short`, `title`, `content`) VALUES (9, 9, 1, '/products/barbaz/specs', 'Specs', 'Specifications of Barbaz', '<h2>Lorem ipsum dolor sit amet, consectetur adipiscing elit.<h2>\n<p>Praesent sit amet odio diam. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nulla pretium, arcu et dignissim bibendum, felis ligula elementum turpis, et interdum nisl ligula id nibh.<p>\n<h3>Praesent eget nulla eget eros adipiscing feugiat.</h3>\n<p>Duis risus urna, pulvinar in fermentum quis, consequat eu mi. In id libero et urna fermentum dignissim varius quis dui. Cras at sem sapien. Duis ultricies, lectus eget venenatis adipiscing, velit augue facilisis ipsum, non pulvinar neque nisl pellentesque ante.<p>\n<h3>Fusce iaculis sit amet adipiscing odio enim in ligula.</h3>\n<p>Cras pharetra congue ipsum at luctus. Nunc sed justo orci. In a turpis erat. Vivamus tincidunt, lacus nec malesuada cursus, nunc nibh bibendum purus, sed lobortis mi neque semper leo. Fusce sagittis mi justo. Mauris ullamcorper, lacus vitae sodales tempus.<p>\n<ul>\n\t<li>Leo massa vitae eros.</li>\n\t<li>Etiam sem est</li>\n\t<li>Hendrerit sit amet</li>\n\t<li>Adipiscing in cursus</li>\n\t<li>Ut risus.</li>\n</ul>');
INSERT INTO `sjonsite_revisions` (`id`, `resource`, `revision`, `uri`, `short`, `title`, `content`) VALUES (10, 10, 1, '/services', 'Services', 'Our Services', '<h2>Lorem ipsum dolor sit amet, consectetur adipiscing elit.<h2>\n<p>Praesent sit amet odio diam. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nulla pretium, arcu et dignissim bibendum, felis ligula elementum turpis, et interdum nisl ligula id nibh.<p>\n<h3>Praesent eget nulla eget eros adipiscing feugiat.</h3>\n<p>Duis risus urna, pulvinar in fermentum quis, consequat eu mi. In id libero et urna fermentum dignissim varius quis dui. Cras at sem sapien. Duis ultricies, lectus eget venenatis adipiscing, velit augue facilisis ipsum, non pulvinar neque nisl pellentesque ante.<p>\n<h3>Fusce iaculis sit amet adipiscing odio enim in ligula.</h3>\n<p>Cras pharetra congue ipsum at luctus. Nunc sed justo orci. In a turpis erat. Vivamus tincidunt, lacus nec malesuada cursus, nunc nibh bibendum purus, sed lobortis mi neque semper leo. Fusce sagittis mi justo. Mauris ullamcorper, lacus vitae sodales tempus.<p>\n<ul>\n\t<li>Leo massa vitae eros.</li>\n\t<li>Etiam sem est</li>\n\t<li>Hendrerit sit amet</li>\n\t<li>Adipiscing in cursus</li>\n\t<li>Ut risus.</li>\n</ul>');
INSERT INTO `sjonsite_revisions` (`id`, `resource`, `revision`, `uri`, `short`, `title`, `content`) VALUES (11, 11, 1, '/services/research', 'Research', 'Product Research', '<h2>Lorem ipsum dolor sit amet, consectetur adipiscing elit.<h2>\n<p>Praesent sit amet odio diam. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nulla pretium, arcu et dignissim bibendum, felis ligula elementum turpis, et interdum nisl ligula id nibh.<p>\n<h3>Praesent eget nulla eget eros adipiscing feugiat.</h3>\n<p>Duis risus urna, pulvinar in fermentum quis, consequat eu mi. In id libero et urna fermentum dignissim varius quis dui. Cras at sem sapien. Duis ultricies, lectus eget venenatis adipiscing, velit augue facilisis ipsum, non pulvinar neque nisl pellentesque ante.<p>\n<h3>Fusce iaculis sit amet adipiscing odio enim in ligula.</h3>\n<p>Cras pharetra congue ipsum at luctus. Nunc sed justo orci. In a turpis erat. Vivamus tincidunt, lacus nec malesuada cursus, nunc nibh bibendum purus, sed lobortis mi neque semper leo. Fusce sagittis mi justo. Mauris ullamcorper, lacus vitae sodales tempus.<p>\n<ul>\n\t<li>Leo massa vitae eros.</li>\n\t<li>Etiam sem est</li>\n\t<li>Hendrerit sit amet</li>\n\t<li>Adipiscing in cursus</li>\n\t<li>Ut risus.</li>\n</ul>');
INSERT INTO `sjonsite_revisions` (`id`, `resource`, `revision`, `uri`, `short`, `title`, `content`) VALUES (12, 12, 1, '/services/development', 'Development', 'Product Development', '<h2>Lorem ipsum dolor sit amet, consectetur adipiscing elit.<h2>\n<p>Praesent sit amet odio diam. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nulla pretium, arcu et dignissim bibendum, felis ligula elementum turpis, et interdum nisl ligula id nibh.<p>\n<h3>Praesent eget nulla eget eros adipiscing feugiat.</h3>\n<p>Duis risus urna, pulvinar in fermentum quis, consequat eu mi. In id libero et urna fermentum dignissim varius quis dui. Cras at sem sapien. Duis ultricies, lectus eget venenatis adipiscing, velit augue facilisis ipsum, non pulvinar neque nisl pellentesque ante.<p>\n<h3>Fusce iaculis sit amet adipiscing odio enim in ligula.</h3>\n<p>Cras pharetra congue ipsum at luctus. Nunc sed justo orci. In a turpis erat. Vivamus tincidunt, lacus nec malesuada cursus, nunc nibh bibendum purus, sed lobortis mi neque semper leo. Fusce sagittis mi justo. Mauris ullamcorper, lacus vitae sodales tempus.<p>\n<ul>\n\t<li>Leo massa vitae eros.</li>\n\t<li>Etiam sem est</li>\n\t<li>Hendrerit sit amet</li>\n\t<li>Adipiscing in cursus</li>\n\t<li>Ut risus.</li>\n</ul>');
INSERT INTO `sjonsite_revisions` (`id`, `resource`, `revision`, `uri`, `short`, `title`, `content`) VALUES (13, 13, 1, '/services/support', 'Support', 'Product Support', '<h2>Lorem ipsum dolor sit amet, consectetur adipiscing elit.<h2>\n<p>Praesent sit amet odio diam. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nulla pretium, arcu et dignissim bibendum, felis ligula elementum turpis, et interdum nisl ligula id nibh.<p>\n<h3>Praesent eget nulla eget eros adipiscing feugiat.</h3>\n<p>Duis risus urna, pulvinar in fermentum quis, consequat eu mi. In id libero et urna fermentum dignissim varius quis dui. Cras at sem sapien. Duis ultricies, lectus eget venenatis adipiscing, velit augue facilisis ipsum, non pulvinar neque nisl pellentesque ante.<p>\n<h3>Fusce iaculis sit amet adipiscing odio enim in ligula.</h3>\n<p>Cras pharetra congue ipsum at luctus. Nunc sed justo orci. In a turpis erat. Vivamus tincidunt, lacus nec malesuada cursus, nunc nibh bibendum purus, sed lobortis mi neque semper leo. Fusce sagittis mi justo. Mauris ullamcorper, lacus vitae sodales tempus.<p>\n<ul>\n\t<li>Leo massa vitae eros.</li>\n\t<li>Etiam sem est</li>\n\t<li>Hendrerit sit amet</li>\n\t<li>Adipiscing in cursus</li>\n\t<li>Ut risus.</li>\n</ul>');
INSERT INTO `sjonsite_revisions` (`id`, `resource`, `revision`, `uri`, `short`, `title`, `content`) VALUES (14, 14, 1, '/services/refurbishing', 'Refurbishing', 'Refurbishing Products', '<h2>Lorem ipsum dolor sit amet, consectetur adipiscing elit.<h2>\n<p>Praesent sit amet odio diam. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nulla pretium, arcu et dignissim bibendum, felis ligula elementum turpis, et interdum nisl ligula id nibh.<p>\n<h3>Praesent eget nulla eget eros adipiscing feugiat.</h3>\n<p>Duis risus urna, pulvinar in fermentum quis, consequat eu mi. In id libero et urna fermentum dignissim varius quis dui. Cras at sem sapien. Duis ultricies, lectus eget venenatis adipiscing, velit augue facilisis ipsum, non pulvinar neque nisl pellentesque ante.<p>\n<h3>Fusce iaculis sit amet adipiscing odio enim in ligula.</h3>\n<p>Cras pharetra congue ipsum at luctus. Nunc sed justo orci. In a turpis erat. Vivamus tincidunt, lacus nec malesuada cursus, nunc nibh bibendum purus, sed lobortis mi neque semper leo. Fusce sagittis mi justo. Mauris ullamcorper, lacus vitae sodales tempus.<p>\n<ul>\n\t<li>Leo massa vitae eros.</li>\n\t<li>Etiam sem est</li>\n\t<li>Hendrerit sit amet</li>\n\t<li>Adipiscing in cursus</li>\n\t<li>Ut risus.</li>\n</ul>');
INSERT INTO `sjonsite_revisions` (`id`, `resource`, `revision`, `uri`, `short`, `title`, `content`) VALUES (15, 15, 1, '/support', 'Support', 'Get Support', '<h2>Lorem ipsum dolor sit amet, consectetur adipiscing elit.<h2>\n<p>Praesent sit amet odio diam. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nulla pretium, arcu et dignissim bibendum, felis ligula elementum turpis, et interdum nisl ligula id nibh.<p>\n<h3>Praesent eget nulla eget eros adipiscing feugiat.</h3>\n<p>Duis risus urna, pulvinar in fermentum quis, consequat eu mi. In id libero et urna fermentum dignissim varius quis dui. Cras at sem sapien. Duis ultricies, lectus eget venenatis adipiscing, velit augue facilisis ipsum, non pulvinar neque nisl pellentesque ante.<p>\n<h3>Fusce iaculis sit amet adipiscing odio enim in ligula.</h3>\n<p>Cras pharetra congue ipsum at luctus. Nunc sed justo orci. In a turpis erat. Vivamus tincidunt, lacus nec malesuada cursus, nunc nibh bibendum purus, sed lobortis mi neque semper leo. Fusce sagittis mi justo. Mauris ullamcorper, lacus vitae sodales tempus.<p>\n<ul>\n\t<li>Leo massa vitae eros.</li>\n\t<li>Etiam sem est</li>\n\t<li>Hendrerit sit amet</li>\n\t<li>Adipiscing in cursus</li>\n\t<li>Ut risus.</li>\n</ul>');

TRUNCATE `sjonsite_settings`;

INSERT INTO `sjonsite_settings` (`name`, `value`) VALUES('contactFrom', 's:19:"noreply@example.org";');
INSERT INTO `sjonsite_settings` (`name`, `value`) VALUES('contactSubject', 's:19:"Contact Form E-mail";');
INSERT INTO `sjonsite_settings` (`name`, `value`) VALUES('contactTo', 's:16:"info@example.org";');
INSERT INTO `sjonsite_settings` (`name`, `value`) VALUES('searchEnabled', 'b:1;');
INSERT INTO `sjonsite_settings` (`name`, `value`) VALUES('searchPerPage', 'i:10;');
INSERT INTO `sjonsite_settings` (`name`, `value`) VALUES('secretHash', 's:40:"f96e54d8d8654c9620c51efec8bb596ae33a186f";');

TRUNCATE `sjonsite_users`;

INSERT INTO `sjonsite_users` (`id`, `name`, `email`, `passwd`, `level`, `state`) VALUES (1, 'Administrator', 'info@example.com', SHA1('example'), 15, 'A');

SET FOREIGN_KEY_CHECKS=1;

COMMIT;

