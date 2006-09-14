<?php

	/**
	 * SjonSite - Library
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @package SjonSite
	 * @version $Id$
	 */

	/**
	 * Class SjonSite_DB
	 */
	class SjonSite_DB {
		const Type_Int = 'INT UNSIGNED';
		const Type_Text = 'TEXT';
		const Type_Blob = 'BLOB';
		const Type_Date = 'DATETIME';
		const Type_State = 'ENUM(\'A\', \'S\', \'R\', \'U\') DEFAULT \'U\'';
	}

	/**
	 * Class SjonSite_Main
	 */
	class SjonSite_Main {

		/**
		 * Constructor
		 *
		 * @return SjonSite_Main
		 */
		public function __construct () {
		}

	}

	/**
	 * Class SjonSite_Model
	 */
	class SjonSite_Model {

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
		 * @return SjonSite_Model
		 */
		public function __construct ($row = null) {
			if (!is_array($row)) {
				$sql = sprintf('SELECT * FROM `%s` WHERE `%s` = %s', $this->table, $this->fields['_primary'], $this->quote($row));
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
	 * Class SjonSite_ExampleModel
	 */
	class SjonSite_ExampleModel {

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
		 * @return SjonSite_ExampleModel
		 */
		public function __construct ($row = null) {
			$this->table = 'example';
			$this->fields = array(
				'id' => array(
					// type, validate regex, default value
					SjonSite_DB::Type_Int, null, null
				),
				'name' => array(SjonSite_DB::Type_Text, '@[^a-z0-9\ ]@Ui', null),
				'email' => array(SjonSite_DB::Type_Text, '@[^a-z0-9\ ]@Ui', null),
				'ts' => array(SjonSite_DB::Type_Date, null, null),
				'state' => array(SjonSite_DB::Type_State, '@[^ASRU]@U', 'U'),
				'_primary' => 'id'
			);
		}

	}

	/**
	 * Interface SjonSite_Controller
	 */
	interface SjonSite_Controller {

		/**
		 * Default Event Handler
		 *
		 * @abstract
		 * @return mixed
		 */
		public abstract function handleEvent();

	}

?>