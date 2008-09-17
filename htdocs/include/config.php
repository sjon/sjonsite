<?php

	/**
	 * Sjonsite - Configuration
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @package Sjonsite
	 * @copyright Sjon's dotCom 2008
	 * @license Mozilla Public License 1.1
	 * @version $Id$
	 */

	/**
	 * Start of request
	 *
	 * @var float
	 */
	define ('SJONSITE_START', microtime(true));

	/**
	 * Path to the include directory
	 *
	 * @var string
	 */
	define ('SJONSITE_INCLUDE', dirname(__FILE__));

	/**
	 * PDO connection string
	 *
	 * @var string
	 */
	define ('SJONSITE_PDO_DSN', 'mysql:host=192.168.1.1;port=3306;dbname=test');

	/**
	 * PDO connection username
	 *
	 * @var string
	 */
	define ('SJONSITE_PDO_USER', 'username');

	/**
	 * PDO connection password
	 *
	 * @var string
	 */
	define ('SJONSITE_PDO_PASS', 'password');

	/**
	 * PDO table prefix
	 *
	 * @var string
	 */
	define ('SJONSITE_PDO_PREFIX', 'sjonsite_');

	/**
	 * Set our default timezone
	 */
	date_default_timezone_set('Europe/Amsterdam');

	/**
	 * Name our session
	 */
	session_name('Sjonsite');

?>