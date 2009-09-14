<?php

	/**
	 * Sjonsite - Model
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @package Sjonsite
	 * @copyright Sjon's dotCom 2008
	 * @license Mozilla Public License 1.1
	 * @version $Id$
	 */

	/**
	 * Class Sjonsite_Model
	 *
	 * @package Sjonsite
	 */
	class Sjonsite_Model {

		/**
		 * Active row
		 *
		 * @var string
		 */
		const ACTIVE = 'A';

		/**
		 * Suspended row
		 *
		 * @var string
		 */
		const SUSPENDED = 'S';

		/**
		 * Removed row
		 *
		 * @var string
		 */
		const REMOVED = 'R';

		/**
		 * Unknown row
		 *
		 * @var string
		 */
		const UNKNOWN = 'U';

		/**
		 * Yes
		 *
		 * @var string
		 */
		const YES = 'Y';

		/**
		 * No
		 *
		 * @var string
		 */
		const NO = 'N';

		/**
		 * The full name of the table
		 *
		 * @var string
		 */
		protected $_tablename;

		/**
		 * An array with the table structure
		 *
		 * @var array
		 */
		protected $_structure;

		/**
		 * Keeps track of modified properties
		 *
		 * @var array
		 */
		private $_modified = array();

		/**
		 * Constructor
		 *
		 * @param array $data
		 */
		public function __construct ($data = null) {
			if (is_numeric($data)) {
				try {
					$sql = 'SELECT ' . implode(', ', array_keys($this->_structure)) . ' FROM %prefix%' . $this->_tablename . ' WHERE id = ' . Sjonsite::$db->quote($data);
					$res = Sjonsite::$db->query($sql);
					$data = $res->fetch(PDO::FETCH_ASSOC);
					$res->closeCursor();
				} catch (PDOException $e) {
					$data = null;
					throw new Sjonsite_ModelException("Loading model failed.", 200, $e);
				}
			}
			if (is_array($data)) {
				foreach (array_keys($this->_structure) as $name) {
					$this->__set($name, (array_key_exists($name, $data) ? $data[$name] : null));
				}
			}
			$this->isModified(false);
		}

		/**
		 * Property getter
		 *
		 * @param string $name
		 * @return mixed
		 */
		public function __get ($name) {
			if (array_key_exists($name, $this->_structure)) {
				return $this->$name;
			}
			return null;
		}

		/**
		 * Property setter
		 *
		 * @param string $name
		 * @param mixed $value
		 * @return void
		 */
		public function __set ($name, $value) {
			if (array_key_exists($name, $this->_structure)) {
				if (!is_null($value)) {
					list($type, $config) = $this->_structure[$name];
					switch ($type) {
						case 'int':
							$value = filter_var($value, FILTER_VALIDATE_INT, array('options' => array('min_range' => $config['min'], 'max_range' => $config['max'])));
							if ($value === false) {
								throw new Sjonsite_ModelException('Integer value `' . $name . '` is out of bounds.');
							}
							break;
						case 'string':
							if ($config['type'] != 'any') {
								$value = filter_var($value, $config['type'], $config['options']);
								if ($value === false) {
									throw new Sjonsite_ModelException('String value `' . $name . '` does not validate.');
								}
							}
							if (strlen($value) > $config['length']) {
								throw new Sjonsite_ModelException('String value `' . $name . '` is out of bounds.');
							}
							break;
						case 'enum':
							if (!in_array($value, $config['items'])) {
								throw new Sjonsite_ModelException('Enum value `' . $name . '` is out of bounds.');
							}
							break;
						default:
							throw new Sjonsite_ModelException('Invalid type setting in model structure');
					}
				}
				$this->$name = ($value ? $value : (isset($this->_structure[$name][1]['default']) ? $this->_structure[$name][1]['default'] : null));
				$this->_modified[$name] = true;
			}
		}

		/**
		 * Save this model
		 *
		 * @return bool
		 * @todo test bindParam functionality and extend
		 */
		public function save () {
			$rv = false;
			if (isset($this->_structure['id'])) {
				$keys = array_keys($this->_structure);
				if ($this->id) {
					$keys = array_merge(array('id'), array_keys($this->_modified));
					$sql = 'UPDATE %prefix%' . $this->_tablename . ' SET ';
					$prefix = null;
					foreach ($keys as $key) {
						if ($key == 'id') continue;
						$sql .= $prefix . $key . ' = :' . $key;
						$prefix = ', ';
					}
					$sql .= ' WHERE id = :id';
				}
				else {
					$sql = 'INSERT INTO %prefix%' . $this->_tablename . ' (' . implode(', ', $keys) . ') VALUES (:' . implode(', :', $keys) . ')';
				}
				try {
					$res = Sjonsite::$db->prepare($sql);
					foreach ($keys as $key) {
						$res->bindParam(":$key", $this->$key);
					}
					$rv = $res->execute();
					$res = null;
				} catch (PDOException $e) {
					throw new Sjonsite_ModelException("Saving model failed.", 201, $e);
				}
			}
			return $rv;
		}

		/**
		 * Returns true if the model has been modified, false if not.
		 * Modified state can be set or reset with $state
		 *
		 * @param bool $state
		 * @return bool
		 */
		public function isModified ($state = null) {
			if (!is_null($state)) {
				$this->_modified = array();
				if ($state) {
					foreach (array_keys($this->_structure) as $key) {
						$this->_modified[$key] = true;
					}
				}
			}
			return (count($this->_modified) > 0);
		}

	}

	/**
	 * Class Sjonsite_ModelException
	 *
	 * @package Sjonsite
	 */
	class Sjonsite_ModelException extends Sjonsite_Exception {}

