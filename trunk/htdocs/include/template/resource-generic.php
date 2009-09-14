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
	if (count($this->menuTree) || (Sjonsite::$settings->searchEnabled && Sjonsite::$auth->isGuest())) {
?>
			<div id="menu">
<?php
		if (Sjonsite::$settings->searchEnabled) {
?>
				<div class="form">
					<form action="/search" method="get">
						<div id="search-box">
							<input class="text" size="10" type="text" name="q" id="field-q" value="<?php if (isset($this->searchQuery)) echo Sjonsite::$io->out($this->searchQuery); ?>" />
							<button type="submit">Search</button>
						</div>
					</form>
				</div>
				<br />
<?php
		}
		if (count($this->menuTree)) {

			function __tree ($tree, $trail, $indent = 3) {
				$rv = null;
				if (isset($tree['uri'])) $rv .= str_repeat("\t", $indent) . '<li' . (in_array($tree['resource'], $trail) ? ' class="current"' : null) . '><a href="'.$tree['uri'].'" title="'.$tree['title'].'"' . (in_array($tree['resource'], $trail) ? ' class="current"' : null) . '>'.$tree['short'].'</a>';
				if (count($tree['children'])) {
					$rv .= "\n" . str_repeat("\t", $indent + 1) . "<ul>\n";
					foreach ($tree['children'] as $child) $rv .= __tree($child, $trail, $indent + 2);
					$rv .= str_repeat("\t", $indent + 1) . "</ul>\n" . str_repeat("\t", $indent);
									}
				if (isset($tree['uri'])) $rv .= "</li>\n";
				return $rv;
			}
			echo str_repeat("\t", 4) . trim(__tree(array('children' => $this->menuTree), explode('-', $this->resource->trail))) . "\n";

		}
?>
			</div>
<?php
	}
	include 'include/footer.php';
?>