<?php

	/**
	 * SjonSite - Library
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @package SjonSite
	 * @version $Id$
	 */

	require_once SJONSITE_BASE . '/include/model.php';

	/**
	 * Class sjonsite
	 */
	class sjonsite {

		/**
		 * Constructor
		 *
		 * @return sjonsite
		 */
		public function __construct () {
		}

	}

	/**
	 * Interface sjonsite_controller
	 */
	interface sjonsite_controller {

		/**
		 * Default Event Handler
		 *
		 * @abstract
		 * @return mixed
		 */
		public abstract function handleEvent();

	}

?>