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
		public function quote () {}
		public function query () {}
		public function fetch () {}
		public function free () {}

	}

?>