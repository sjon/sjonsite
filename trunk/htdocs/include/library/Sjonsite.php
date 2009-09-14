<?php

	/**
	 * Sjonsite - Base Class
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @package Sjonsite
	 * @copyright Sjon's dotCom 2008
	 * @license Mozilla Public License 1.1
	 * @version $Id$
	 */

	/**
	 * Class Sjonsite
	 *
	 * @package Sjonsite
	 */
	class Sjonsite {

		/**
		 * PDO instance
		 *
		 * @var PDO
		 */
		public static $db;

		/**
		 * Sjonsite_Auth instance
		 *
		 * @var Sjonsite_Auth
		 */
		public static $auth;

		/**
		 * Sjonsite_IO instance
		 *
		 * @var Sjonsite_IO
		 */
		public static $io;

		/**
		 * The current request data
		 *
		 * @var Sjonsite_Request
		 */
		public static $request;

		/**
		 * The system's settings
		 *
		 * @var Sjonsite_Settings
		 */
		public static $settings;

		/**
		 * Initialize the current request
		 *
		 * @return void
		 */
		public static function init () {
			session_start();
			if (!array_key_exists('messages', $_SESSION)) {
				$_SESSION['messages'] = array();
			}
			self::$io = new Sjonsite_IO(); // io must be first!
			self::$auth = new Sjonsite_Auth();
			self::$request = new Sjonsite_Request();
		}

		/**
		 * Connect to the database, and load our settings
		 *
		 * @return void
		 * @throws Sjonsite_Exception
		 */
		public static function connect () {
			self::$db = new Sjonsite_PDO(SJONSITE_PDO_DSN, SJONSITE_PDO_USER, SJONSITE_PDO_PASS, array(PDO::ATTR_PERSISTENT => true));
			self::$db->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
			self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			self::$db->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_NATURAL);
			try {
				$sql = 'SELECT `name`, `value` FROM ' . SJONSITE_PDO_PREFIX . 'settings';
				$res = self::$db->query($sql);
				$settings = array();
				while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
					$settings[$row['name']] = unserialize($row['value']);
				}
				$res = null;
				self::$settings = new Sjonsite_Settings($settings);
			}
			catch (Exception $e) { $e;
				throw new Sjonsite_Exception('Unable to load the system\'s settings', 8001/*, $e*/);
			}
		}

		/**
		 * Finalize the current request, and shutdown
		 *
		 * @return void
		 */
		public static function shutdown () {
			Sjonsite::$auth = null;
			Sjonsite::$db = null;
			session_write_close();
			header('Content-Type: ' . Sjonsite::$request->getType() . '; charset=utf-8');
			header('X-Sjonsite: ' . sprintf('T %0.2fms M %dKiB Q %d C %d', ((microtime(true) - SJONSITE_START) * 1000), (memory_get_usage() / 1024), Sjonsite_PDO::getHits(), Sjonsite_Cache::getHits()));
			if (Sjonsite::$request->getRedirect()) {
				header('Location: ' . Sjonsite::$request->getRedirect());
			}
			exit(Sjonsite::$request->getContent());
		}

		/**
		 * Returns the full name to a template file
		 *
		 * @param string $name
		 * @return string
		 */
		public static function template ($name) {
			$rv = SJONSITE_INCLUDE . '/template/' . $name . '.' . Sjonsite::$io->requestType();
			if (!file_exists($rv)) {
				$rv = SJONSITE_INCLUDE . '/template/' . $name . '.php';
			}
			if (!file_exists($rv)) {
				throw new Sjonsite_Exception('Template `' . $name . '` does not exist!', 8002);
			}
			return $rv;
		}

		/**
		 * Informational message class
		 *
		 * @var string
		 * @see Sjonsite_Base::setMessage()
		 */
		const info = 'info';

		/**
		 * Informational message class
		 *
		 * @var string
		 * @see Sjonsite_Base::setMessage()
		 */
		const warning = 'warning';

		/**
		 * Informational message class
		 *
		 * @var string
		 * @see Sjonsite_Base::setMessage()
		 */
		const error = 'error';

		/**
		 * Messaging System - Are there any messages?
		 *
		 * @return bool
		 */
		public static function hasMessage () {
			return (isset($_SESSION['messages']) && count($_SESSION['messages']) > 0);
		}

		/**
		 * Messaging System - Return a message
		 *
		 * @return string
		 */
		public static function getMessage () {
			return array_shift($_SESSION['messages']);
		}

		/**
		 * Messaging System - Add a message
		 *
		 * @param string $message
		 * @param constant $class
		 * @return void
		 */
		public static function setMessage ($message, $class = Sjonsite::info) {
			$_SESSION['messages'][] = array($message, $class);
		}

	}

	/**
	 * Class Sjonsite_Settings
	 *
	 * @package Sjonsite
	 */
	class Sjonsite_Settings {

		/**
		 * Storage
		 *
		 * @var array
		 */
		protected $settings;

		/**
		 * Constructor
		 *
		 * @param array $settings
		 */
		public function __construct ($settings) {
			$this->settings = $settings;
		}

		/**
		 * Return a setting
		 *
		 * @param string $name
		 * @return mixed
		 */
		public function __get ($name) {
			return (array_key_exists($name, $this->settings) ? $this->settings[$name] : null);
		}

		/**
		 * Overloading issetter
		 *
		 * @param string $name
		 * @return bool
		 */
		public function __isset ($name) {
			return array_key_exists($name, $this->settings);
		}

	}

	/**
	 * Class Sjonsite_Exception
	 *
	 * @package Sjonsite
	 */
	class Sjonsite_Exception extends Exception {}

