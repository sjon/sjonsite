<?php

	/**
	 * SjonSite - Configuration File
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @package SjonSite
	 * @version $Id$
	 */

	/**
	 * Constant
	 */
	define ('SJONSITE_DIR', dirname(dirname(__FILE__)));

	/**
	 * Class sjonsite_cfg
	 */
	final class sjonsite_cfg {
		/**
		 * @var array
		 */
		protected $cfg;

		/**
		 * Constructor
		 *
		 * @param string $uri
		 * @return sjonsite_cfg
		 */
		public function __construct ($uri) {
			$this->cfg = array(
				'dom' => 'example.com',
				'uri' => $uri,
				'dsn' => 'mysql://username:password@localhost:3306/database?persistent=true',
				'dir' => SJONSITE_DIR,
				'var' => realpath(SJONSITE_DIR . '/var'),
				'upi' => false,
			);
		}

		/**
		 * Return a config option
		 *
		 * @param string $name
		 * @return mixed
		 */
		public function get ($name) {
			return (array_key_exists($name, $this->cfg) ? $this->cfg[$name] : null);
		}

		/**
		 * Load an include
		 * Param $name may only consist of a to z, dash and slash
		 *
		 * @param string $name
		 * @return void
		 */
		public function load ($name) {
			require_once SJONSITE_DIR . '/include/' . preg_replace('#[^a-z\-\/]#', '', $name) . '.php';
		}

		/**
		 * Return the full url to $uri
		 *
		 * @param string $uri
		 * @param mixed $secure
		 * @return string
		 */
		public function uri ($uri, $secure = false) {
			$rv = ($secure === true ? 'https' : ($secure === false ? 'http' : $secure));
			$rv .= '://' . $this->get('dom') . $this->get('uri');
			$rv .= ($this->get('upi') ? '' : '?') . $uri;
			return $rv;
		}

	}

	sjonsite_cfg::load('library');

?>