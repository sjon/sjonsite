<?php

	/**
	 * Sjonsite - Error Template
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @package Sjonsite_Template
	 * @copyright Sjon's dotCom 2007
	 * @version $Id: index.php 6 2007-01-22 23:59:58Z sjonscom $
	 */

	$this->template('common-header');
	if (isset($this->ex) && is_array($this->ex)) {
?>
			<h1>Sjonsite Error</h1>
			<ul class="box error">
<?php
		foreach ($this->ex as $e) {
?>
				<li><?php echo ($e instanceof Exception ? $e->getMessage() : $e); ?></li>
<?php
		}
?>
			</ul>
<?php
	}
	else {
?>
			<h1>Sjonsite Error</h1>
			<p class="box error">Unknown error</p>
<?php
	}
	$this->template('common-footer');
?>