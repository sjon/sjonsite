<?php

	/**
	 * Sjonsite - Auth Class
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @package Sjonsite
	 * @copyright Sjon's dotCom 2008
	 * @license Mozilla Public License 1.1
	 * @version $Id$
	 */

	/**
	 * Class Sjonsite_Auth
	 *
	 * @package Sjonsite
	 */
	class Sjonsite_Auth {


		public function __construct () {

		}

		public function checkAuth () {

		}

		public function isGuest () {
			return false;
		}

	}

	/**
	 * Class Sjonsite_AuthException
	 *
	 * @package Sjonsite
	 */
	class Sjonsite_AuthException extends Sjonsite_Exception {}

