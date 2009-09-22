<?php

	/**
	 * Sjonsite - Controller Class
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @package Sjonsite
	 * @copyright Sjon's dotCom 2008
	 * @license Mozilla Public License 1.1
	 * @version $Id$
	 */

	/**
	 * Class Sjonsite_Controller
	 *
	 * @package Sjonsite
	 */
	abstract class Sjonsite_Controller {

		/**
		 * Resource object
		 *
		 * @var Sjonsite_Resource
		 */
		protected $resource;

		/**
		 * Revision object
		 *
		 * @var Sjonsite_Revision
		 */
		protected $revision;

		/**
		 * Constructor
		 *
		 * @param Sjonsite_Resource $resource
		 */
		public function __construct ($resource, $revision) {
			$this->resource = $resource;
			$this->revision = $revision;
		}

		/**
		 * Disables global output caching
		 *
		 * @param bool $disabled;
		 * @return bool
		 */
		public function cacheDisabled ($disabled = null) {
			static $cacheDisabled = false;
			if (!is_null($disabled)) {
				$cacheDisabled = $disabled;
			}
			return $cacheDisabled;
		}

		/**
		 * Process Request
		 * Is responsible for serving the main payload
		 *
		 * @return void
		 */
		abstract public function processRequest ();

		/**
		 * Display a Template file
		 *
		 * @param string $template
		 * @return void
		 */
		protected function displayTemplate ($template) {
			ob_start();
			include $template;
			Sjonsite::$request->setContent(ob_get_clean());
		}

	}

	/**
	 * Class Sjonsite_ControllerException
	 *
	 * @package Sjonsite
	 */
	class Sjonsite_ControllerException extends Sjonsite_Exception {}

