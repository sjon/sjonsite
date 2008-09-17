<?php


	/**
	 * Sjonsite - Install
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
	 * Class Sjonsite_Install
	 *
	 * @package Sjonsite
	 */
	final class Sjonsite_Install extends Sjonsite_Base {

		/**
		 * Process request
		 *
		 * @return void
		 */
		public function processRequest () {
			$this->settings->insert($this->db, 'secretHash', sha1(mt_rand() . serialize($_SERVER)));
			$this->settings->insert($this->db, 'contactTo', 'info@example.org');
			$this->settings->insert($this->db, 'contactFrom', 'noreply@example.org');
			$this->settings->insert($this->db, 'contactSubject', 'Contact Form E-mail');
			$this->settings->insert($this->db, 'searchPerPage', 10);
		}

	}

	/**
	 * Run
	 */
	new Sjonsite_Install();

?>