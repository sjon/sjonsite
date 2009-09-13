<?php

	/**
	 * Sjonsite - ResourceController
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @package Sjonsite
	 * @copyright Sjon's dotCom 2008
	 * @license Mozilla Public License 1.1
	 * @version $Id$
	 */

	/**
	 * Class Sjonsite_ResourceController
	 *
	 * @package Sjonsite
	 */
	class Sjonsite_ResourceController extends Sjonsite_Controller {

		public function processRequest () {
			$this->resource->sorting = 99999;
			echo '<pre>', var_export($this->resource, true), '</pre>';
			echo '<pre>', var_export(Sjonsite::$io, true), '</pre>';
		}

	}

