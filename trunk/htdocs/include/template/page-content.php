<?php

	/**
	 * Sjonsite Template - Content page
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @package Sjonsite
	 * @copyright Sjon's dotCom 2008
	 * @license Mozilla Public License 1.1
	 * @version $Id$
	 */

	$this->template('include/header');
?>
			<div id="main">
				<h1><?php echo $this->pagePage->p_title; ?></h1>
<?php
	echo $this->pagePage->p_content;
?>
				<p>Lorum ipsum dolor sit amet lorum ipsum dolor sit amet lorum ipsum dolor sit amet lorum ipsum dolor sit amet lorum ipsum dolor sit amet lorum ipsum dolor sit amet lorum ipsum dolor sit amet lorum ipsum dolor sit amet lorum ipsum dolor sit amet lorum ipsum dolor sit amet lorum ipsum dolor sit amet lorum ipsum dolor sit amet lorum ipsum dolor sit amet lorum ipsum dolor sit amet lorum ipsum dolor sit amet lorum ipsum dolor sit amet.</p>
			</div>
<?php
	$this->template('include/footer');
?>