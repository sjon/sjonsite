<?php

	/**
	 * Sjonsite - Configuration and Loader
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @package Sjonsite
	 * @copyright Sjon's dotCom 2007
	 * @version $Id$
	 */

	/**
	 * Configuration
	 */
	define ('SJONSITE_ROOT', dirname(dirname(__FILE__)));
	define ('SJONSITE_HTDOCS', SJONSITE_ROOT . '/htdocs');
	define ('SJONSITE_INCLUDE', SJONSITE_ROOT . '/include');
	define ('SJONSITE_VAR', SJONSITE_ROOT . '/var');
	define ('SJONSITE_CACHE', SJONSITE_VAR . '/cache');
	define ('SJONSITE_TEMPLATE', SJONSITE_VAR . '/template');

	define ('SJONSITE_PDO_DSN', 'mysql:host=localhost;port=3306;dbname=sjonsite');
	define ('SJONSITE_PDO_USER', 'username');
	define ('SJONSITE_PDO_PASS', 'password');
	define ('SJONSITE_PREFIX', 'v1_');

	/**
	 * Load library
	 */
	require_once '../include/library.php';

	/**
	 * Run
	 */
	Sjonsite::run();

?>