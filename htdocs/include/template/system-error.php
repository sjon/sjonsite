<?php

	/**
	 * Sjonsite Template - System Error
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @package Sjonsite
	 * @copyright Sjon's dotCom 2008
	 * @license Mozilla Public License 1.1
	 * @version $Id$
	 */

	$this->template('include/header');
	if (isset($this->ex) && is_array($this->ex)) {
?>
			<h1>Sjonsite Error</h1>
			<ul class="box error">
<?php
		foreach ($this->ex as $e) {
?>
				<li><?php echo $this->out($e instanceof Exception ? $e->getMessage() : $e); ?></li>
<?php
		}
?>
			</ul>
<?php
	}
	elseif (isset($this->ex) && ($this->ex instanceof Exception)) {
?>
			<h1>Sjonsite Error</h1>
			<p class="box error"><?php echo $this->out($this->ex->getMessage()); ?></p>
<?php
	}
	else {
?>
			<h1>Sjonsite Error</h1>
			<p class="box error">Unknown error</p>
<?php
	}
	$this->template('include/footer');
	unset($this);
	exit;
?>