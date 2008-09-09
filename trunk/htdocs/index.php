<?php

	/**
	 * Sjonsite - Launcher
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @package Sjonsite
	 * @copyright Sjon's dotCom 2008
	 * @license Mozilla Public License 1.1
	 * @version $Id$
	 */

	/**
	 * Load configuration
	 */
	require_once 'include/config.php';

	/**
	 * Load library
	 */
	require_once SJONSITE_INCLUDE . '/library.php';

	/**
	 * Class Sjonsite
	 *
	 * @package Sjonsite
	 */
	final class Sjonsite extends Sjonsite_Base {

		/**
		 * Process request
		 *
		 * @return void
		 */
		public function processRequest () {
			switch ($this->pathPart(1)) {
				case 'admin':
					$this->doAdmin();
					break;
				case 'contact':
					$this->doContact();
					break;
				case 'search':
					$this->doSearch();
					break;
				default:
					$this->doPage();
					break;
			}
		}

		/**
		 * Handle admin request
		 *
		 * @return void
		 */
		protected function doAdmin () {}

		/**
		 * Handle contact request
		 *
		 * @return void
		 */
		protected function doContact () {}

		/**
		 * Handle search request
		 *
		 * @return void
		 */
		protected function doSearch () {}

		/**
		 * Handle page request
		 *
		 * @return void
		 */
		protected function doPage () {}

		/**
		 * Display a content page
		 *
		 * @return void
		 */
		protected function displayPage () {}

		/**
		 * Display a gallery page
		 *
		 * @return void
		 */
		protected function displayGallery () {}

	}

	/**
	 * Run
	 */
	new Sjonsite;

?>