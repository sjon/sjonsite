<?php

	/**
	 * SjonSite - Model Library
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @package SjonSite
	 * @version $Id$
	 */

	require_once SJONSITE_BASE . '/include/db.php';

	/**
	 * Class sjonsite_model
	 */
	class sjonsite_model {

		/**
		 * @var string
		 */
		private abstract $table;

		/**
		 * @var array
		 */
		private abstract $fields;

		/**
		 * @var array
		 */
		private $row;

		/**
		 * @var bool
		 */
		private $modified;

		/**
		 * Constructor
		 *
		 * @return sjonsite_model
		 */
		public function __construct ($row = null) {
			if (!is_array($row)) {
				$sql = sprintf('SELECT * FROM `%s` WHERE `%s` = %s', $this->table, $this->fields['_primary'], sjonsite_db::quote($row));
			}
			foreach ($this->fields as $name => $cfg) {
				$this->row[$name] = (array_key_exists($name, $row) ? $row[$name] : null);
			}
			$this->modified = false;
		}

		/**
		 * Overload Method
		 *
		 * @param string $name
		 * @return mixed
		 */
		private function __get ($name) {
			return (array_key_exists($name, $this->row) ? $this->row[$name] : null);
		}

		/**
		 * Overload Method
		 *
		 * @param string $name
		 * @param mixed $value
		 * @return void
		 */
		private function __set ($name, $value) {
			if (array_key_exists($name, $this->row)) {
				$this->row[$name] = $value;
				$this->modified = true;
			}
		}

		/**
		 * Overload Method
		 *
		 * @param string $name
		 * @return bool
		 */
		private function __isset ($name) {
			return (array_key_exists($name, $this->row) ? isset($this->row[$name]) : false);
		}

		/**
		 * Overload Method
		 *
		 * @param string $name
		 */
		private function __unset ($name) {
			if (array_key_exists($name, $this->row)) {
				$this->row[$name] = null;
				$this->modified = true;
			}
		}

		/**
		 * Overload Method
		 *
		 * @param string $method
		 * @param array $args
		 * @return mixed
		 */
		private function __call ($method, $args) {
			// handle getId(), setId(2), getIdQuoted()
			if (preg_match('@^getQuoted([A-Za-z]+)$@U', $method, $matches)) {
				$name = $matches[1];
				if (array_key_exists($name, $this->row)) {
					return $this->quote($this->row[$name]);
				}
				return null;
			}
			elseif (preg_match('@^get([A-Za-z]+)$@U', $method, $matches)) {
				$name = $matches[1];
				if (array_key_exists($name, $this->row)) {
					return $this->row[$name];
				}
				return null;
			}
			elseif (preg_match('@^set([A-Za-z]+)$@U', $method, $matches)) {
				$name = $matches[1];
				if (array_key_exists($name, $this->row)) {

				}
			}
		}

		/**
		 * Quote a string for use in an sql query
		 *
		 * @param string $string
		 * @return string
		 */
		public function quote ($string) {
			if (is_numeric($string)) {
				return $string;
			}
			elseif (is_null($string)) {
				return 'NULL';
			}
			elseif ($string == 'NOW()') {
				return $string;
			}
			else {
				return '\'' . mysql_real_escape_string($string) . '\'';
			}
		}
		/**
		 * Destructor
		 *
		 * @return void
		 */
		public function __destruct () {
			return class_exists('foo', true);
		}

	}

	/**
	 * Class sjonsite_examplemodel
	 */
	class sjonsite_examplemodel {

		/**
		 * @var string
		 */
		private $table;

		/**
		 * @var array
		 */
		private $fields;

		/**
		 * Constructor
		 *
		 * @return sjonsite_examplemodel
		 */
		public function __construct ($row = null) {
			$this->table = 'example';
			$this->fields = array(
				'id' => array(
					// type, validate regex, default value
					sjonsite_db::INT, null, null
				),
				'name' => array(sjonsite_db::TEXT, '@[^a-z0-9\ ]@Ui', null),
				'email' => array(sjonsite_db::TEXT, null, null),
				'ts' => array(sjonsite_db::DATE, null, null),
				'state' => array(sjonsite_db::STATE, '@[^ASRU]@U', 'U'),
				'_primary' => 'id'
			);
			parent::__construct($row);
		}

	}

?>