<?php

	/**
	 * SjonSite - Configuration File
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @package SjonSite
	 * @version $Id$
	 */

	define ('SJONSITE_URL', '');
	define ('SJONSITE_DSN', '');
	define ('SJONSITE_BASE', dirname(__FILE__));
	define ('SJONSITE_VAR', realpath(SJONSITE_BASE . '/../var'));

	require_once SJONSITE_BASE . '/library.php';

?>