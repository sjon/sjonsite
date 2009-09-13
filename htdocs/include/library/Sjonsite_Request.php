<?php

	/**
	 * Sjonsite - Request Class
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @package Sjonsite
	 * @copyright Sjon's dotCom 2008
	 * @license Mozilla Public License 1.1
	 * @version $Id$
	 */

	/**
	 * Class Sjonsite_Request
	 *
	 * @package Sjonsite
	 */
	class Sjonsite_Request {

		/**
		 * The Content-Type of the current request
		 *
		 * @var string
		 */
		protected $type;

		/**
		 * The URI to redirect to
		 *
		 * @var string
		 */
		protected $redirect;

		/**
		 * The content
		 *
		 * @var string
		 */
		protected $content;

		/**
		 * Constructor
		 */
		public function __construct () {
			$this->type = 'text/html';
			$this->redirect = null;
			$this->content = null;
		}

		/**
		 * Return the Content-Type
		 *
		 * @return string
		 */
		public function getType () {
			return $this->type;
		}

		/**
		 * Change the Content-Type
		 *
		 * @param string $type
		 * @return Sjonsite_Request
		 */
		public function setType ($type) {
			$this->type = $type;
			return $this;
		}

		/**
		 * Return the content
		 *
		 * @return string
		 */
		public function getContent () {
			return $this->content;
		}

		/**
		 * Change the content
		 *
		 * @param string $content
		 * @return Sjonsite_Request
		 */
		public function setContent ($content) {
			$this->content = $content;
			return $this;
		}

		/**
		 * Return the redirect-to
		 *
		 * @return string
		 */
		public function getRedirect () {
			return $this->redirect;
		}

		/**
		 * Change the redirect-to
		 *
		 * @param string $redirect
		 * @return Sjonsite_Request
		 */
		public function setRedirect ($redirect) {
			$this->redirect = $redirect;
			return $this;
		}

	}

	/**
	 * Class Sjonsite_ControllerException
	 *
	 * @package Sjonsite
	 */
	class Sjonsite_RequestException extends Sjonsite_Exception {}

