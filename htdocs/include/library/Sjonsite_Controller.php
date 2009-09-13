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
	class Sjonsite_Controller {

		/**
		 * Resource object
		 *
		 * @var Sjonsite_Resource
		 */
		protected $resource;

		/**
		 * Constructor
		 *
		 * @param Sjonsite_Resource $resource
		 */
		public function __construct ($resource) {
			$this->resource = $resource;
		}

		/**
		 * Process Request
		 * Is responsible for serving the main payload
		 *
		 * @return void
		 */
		public function processRequest () {
			var_dump($this->resource);
			throw new Sjonsite_ControllerException('Base class cannot process requests ' . $this->resource->controller, 3001);
		}

	}

	/**
	 * Class Sjonsite_ControllerException
	 *
	 * @package Sjonsite
	 */
	class Sjonsite_ControllerException extends Sjonsite_Exception {}

