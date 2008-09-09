<?php

	/**
	 * Sjonsite - Main
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
		 * Handle contact request
		 *
		 * @return void
		 */
		protected function doContact () {
			try {
				// prepare data
				$this->template('page-contact');
			}
			catch (Exception $e) {
				$this->ex = $e;
				$this->template('system-error');
			}
		}

		/**
		 * Handle search request
		 *
		 * @return void
		 */
		protected function doSearch () {
			try {
				// prepare data
				$this->template('page-search');
			}
			catch (Exception $e) {
				$this->ex = $e;
				$this->template('system-error');
			}
		}

		/**
		 * Handle page request
		 *
		 * @return void
		 */
		protected function doPage () {
			try {
				// prepare data
				$this->template('page-content'); // page-gallery
			}
			catch (Exception $e) {
				$this->ex = $e;
				$this->template('system-error');
			}
		}

	}

	/**
	 * Run
	 */
	new Sjonsite;

?>