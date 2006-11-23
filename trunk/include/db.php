<?php

	/**
	 * SjonSite - DB Library
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @package SjonSite
	 * @version $Id$
	 */

	/**
	 * Class sjonsite_db
	 */
	class sjonsite_db {
		const INT = 'INT UNSIGNED';
		const TEXT = 'TEXT';
		const BLOB = 'BLOB';
		const DATE = 'DATETIME';
		const STATE = 'ENUM(\'A\', \'S\', \'R\', \'U\') DEFAULT \'U\'';

		public function connect () {}
		public function close () {}
		public function error () {}
		public function escape () {}

		/**
		 * Quote a string, returns a number if string is numeric,
		 * NULL if string is null, or an escaped string enclosed in quotes
		 *
		 * @param string $string
		 * @param mixed $default
		 * @return string
		 */
		function quote ($string) {
			if (is_numeric($string)) {
				return (strpos($string, '.') !== false ? (float) $string : (int) $string);
			}
			elseif (is_null($string)) {
				return 'NULL';
			}
			elseif ($string == 'NOW()') {
				return 'NOW()';
			}
			else {
				return '\'' . mysql_real_escape_string($string) . '\'';
			}
		}

		public function query () {}
		public function fetch () {}
		public function free () {}

	}

?>