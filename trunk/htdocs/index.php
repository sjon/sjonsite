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
	 * Class Sjonsite_Main
	 */
	final class Sjonsite_Main extends Sjonsite implements SjonsiteController {

		public function __construct () {
			parent::__construct(array(
				'dsn' => 'mysql://username:password@localhost:3306/database?persistent=true',
				'upi' => false,
			));
		}

		/**
		 * Contact Event
		 *
		 * @return void
		 */
		public function handleContactEvent () {
		}

		/**
		 * Default Event
		 *
		 * @return void
		 */
		public function handleDefaultEvent () {
		}

	}

	/**
	 * Run
	 */
	Sjonsite::run('main');

?>