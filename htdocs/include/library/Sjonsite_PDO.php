<?php

	/**
	 * Sjonsite - PDO Class
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @package Sjonsite
	 * @copyright Sjon's dotCom 2008
	 * @license Mozilla Public License 1.1
	 * @version $Id$
	 */

	/**
	 * Class Sjonsite_PDO
	 *
	 * @package Sjonsite
	 */
	class Sjonsite_PDO extends PDO {

		/**
		 * Query counter
		 *
		 * @var int
		 */
		protected static $hits = 0;

		/**
		 * Executes an SQL statement, returning a result set as a PDOStatement object
		 *
		 * @param string $statement
		 * @param int $arg2 The FETCH_MODE constant
		 * @param mixed $arg3 A colno, classname or object
		 * @param array $arg4 The ctorargs for classname
		 * @return PDOStatement
		 */
		public function query ($statement, $arg2 = null, $arg3 = null, $arg4 = null) {
			self::$hits++;
			return parent::query(str_replace('%prefix%', SJONSITE_PDO_PREFIX, $statement), $arg2, $arg3, $arg4);
		}

		/**
		 * Prepares a statement for execution and returns a statement object
		 *
		 * @param string $statement
		 * @param array $driver_options
		 * @return PDOStatement
		 */
		public function prepare ($statement, $driver_options = array()) {
			self::$hits++;
			return parent::prepare(str_replace('%prefix%', SJONSITE_PDO_PREFIX, $statement), $driver_options);
		}

		/**
		 * Return the number of hits on the cache
		 * Needs to be called after a possible set() to be accurate
		 *
		 * @return int
		 */
		public static function getHits () {
			return self::$hits;
		}

	}

