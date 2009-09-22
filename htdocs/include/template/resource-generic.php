<?php

	/**
	 * Sjonsite Template - Resource Generic
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @package Sjonsite_Template
	 * @copyright Sjon's dotCom 2008
	 * @license Mozilla Public License 1.1
	 * @version $Id$
	 */

	include 'include/header.php';
?>
			<div id="main">
				<h1><?php echo Sjonsite::$io->out($this->revision->title); ?></h1>
<?php
	//echo Sjonsite::$io->out($this->revision->content, true);
	$rv = Sjonsite::$io->out(str_replace(array("<h2>\n", "<h3>\n", "<p>\n"), array("</h2>\n", "</h3>\n", "</p>\n"), $this->revision->content), true);
	$rv = explode("\n", $rv);
	foreach ($rv as $s) echo str_repeat("\t", 4) . rtrim($s) . "\n";
?>
			</div>
<?php
	include 'include/menutree-generic.php';
	include 'include/footer.php';
?>