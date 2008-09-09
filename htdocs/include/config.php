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
	define ('SJONSITE_PDO_DSN', '');

	/**
	 * PDO connection username
	 *
	 * @var string
	 */
	define ('SJONSITE_PDO_USER', '');

	/**
	 * PDO connection password
	 *
	 * @var string
	 */
	define ('SJONSITE_PDO_PASS', '');

	/**
	 * PDO table prefix
	 *
	 * @var string
	 */
	define ('SJONSITE_PDO_PREFIX', 'sjonsite_');

	/**
	 * Enter description here...
	 *
	 * @var
	 */
	define ('SJONSITE_', '');

?>