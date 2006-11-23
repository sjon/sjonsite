<?php

	/**
	 * SjonSite - Frontend Controller
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @package SjonSite
	 * @version $Id$
	 */

	require_once '../include/config.php';

	/**
	 * Class sjonsite_main
	 */
	final class sjonsite_main extends sjonsite implements sjonsite_controller {

		public function __construct () {
			parent::__construct('/');
		}

		function handleEvent () {

		}

	}

	/**
	 * Run
	 */
	sjonsite::run('main');

?>