<?php

	/**
	 * SjonSite - Configuration File
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @package SjonSite
	 * @version $Id$
	 */

	/**
	 * Constants
	 */
	define ('SJONSITE_DIR', dirname(dirname(__FILE__)));

	/**
	 * Class SjonsiteConfig
	 */
	final class SjonsiteConfig {
		/**
		 * @var array
		 */
		private $cfg;

		/**
		 * Constructor
		 *
		 * @param array $cfg
		 * @return sjonsite_cfg
		 */
		public function __construct (array $cfg) {
			$this->cfg = array();
			$this->cfg['domain'] = (array_key_exists('domain', $cfg) ? $cfg['domain'] : $_SERVER['HTTP_HOST']);
			$this->cfg['uri'] = (array_key_exists('uri', $cfg) ? $cfg['uri'] : '/');
			$this->cfg['dsn'] = (array_key_exists('dsn', $cfg) ? $cfg['dsn'] : null);
			$this->cfg['var'] = (array_key_exists('var', $cfg) ? $cfg['var'] : realpath(SJONSITE_DIR . '/var'));
			$this->cfg['upi'] = (array_key_exists('upi', $cfg) ? $cfg['upi'] : false);
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
		 * Assign a config option
		 *
		 * @param mixed $name
		 * @param mixed $value
		 * @return void
		 */
		public function set ($name, $value = null) {
			if (is_array($name)) {
				$this->cfg = array_merge($this->cfg, $name);
			}
			else {
				$this->cfg[$name] = $value;
			}
		}

		/**
		 * Load an include
		 * Param $name may only consist of a to z, dash and slash
		 *
		 * @param string $name
		 * @return void
		 * @static
		 */
		public static function load ($name) {
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
			$rv .= '://' . $this->get('domain') . $this->get('uri');
			$rv .= ($this->get('upi') ? '' : '?') . $uri;
			return $rv;
		}

	}

	SjonsiteConfig::load('library');

?>