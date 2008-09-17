<?php

	/**
	 * Sjonsite - Library
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @package Sjonsite
	 * @copyright Sjon's dotCom 2008
	 * @license Mozilla Public License 1.1
	 * @version $Id$
	 */

	/**
	 * Class Sjonsite_Base
	 *
	 * @package Sjonsite
	 */
	abstract class Sjonsite_Base {

		/**
		 * PDO pointer
		 *
		 * @var PDO
		 */
		public $db;

		/**
		 * Exception pointer
		 *
		 * @var Exception
		 */
		public $ex;

		/**
		 * Request URI
		 *
		 * @var string
		 */
		protected $request;

		/**
		 * System settings
		 *
		 * @var Sjonsite_Settings
		 */
		protected $settings;

		/**
		 * Constructor
		 *
		 */
		public function __construct () {
			if (strpos($_SERVER['REQUEST_URI'], '?') !== false) {
				$_SERVER['REQUEST_URI'] = substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '?'));
			}
			$this->request = str_replace(array('//', '//'), '/', '/' . preg_replace('#[^a-z0-9\_\-\/\.]#', null, strtolower($_SERVER['REQUEST_URI'])));
			if (substr($this->request, -1, 1) == '/' && strlen($this->request) > 1) {
				$this->request = substr($this->request, 0, -1);
			}
			try {
				$this->db = new PDO(SJONSITE_PDO_DSN, SJONSITE_PDO_USER, SJONSITE_PDO_PASS, array(PDO::ATTR_PERSISTENT => true));
				$this->db->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
				$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$this->db->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_NATURAL);
			}
			catch (Exception $e) {
				$this->ex = $e;
				$this->template('system-error');
				return;
			}
			session_start();
			if (!array_key_exists('messages', $_SESSION)) {
				$_SESSION['messages'] = array();
			}
			if (!ini_get('zlib.output_compression')) {
				//ob_start('ob_gzhandler');
			}
			try {
				$this->settings = new Sjonsite_Settings($this->db);
			}
			catch (Exception $e) {
				$this->ex = $e;
				$this->template('system-error');
				return;
			}
			$this->processRequest();
		}

		/**
		 * Process Request
		 * Is responsible for serving the main payload
		 *
		 * @return void
		 */
		abstract public function processRequest ();

		/**
		 * Normalize Accents
		 *
		 * @var int
		 * @see Sjonsite_Base::normalize()
		 */
		const accents = 1;

		/**
		 * Normalize Readable
		 *
		 * @var int
		 * @see Sjonsite_Base::normalize()
		 */
		const readable = 2;

		/**
		 * Normalize NoDots
		 *
		 * @var int
		 * @see Sjonsite_Base::normalize()
		 */
		const nodots = 4;

		/**
		 * Normalize ToLower
		 *
		 * @var int
		 * @see Sjonsite_Base::normalize()
		 */
		const lower = 8;

		/**
		 * Normalize All
		 *
		 * @var int
		 * @see Sjonsite_Base::normalize();
		 */
		const all = 15;

		/**
		 * Normalize Accents
		 *
		 * @var array
		 * @see Sjonsite_Base::normalize()
		 */
		public static $accents = null;

		/**
		 * Normalize Readable
		 *
		 * @var array
		 * @see Sjonsite_Base::normalize()
		 */
		public static $readable = null;

		/**
		 * Normalize a string, for usage in urls and such
		 * The $accents array has been 'borrowed' from Wordpress.
		 *
		 * @param string $string
		 * @return string
		 */
		public static function normalize ($string, $opts = Sjonsite_Base::all) {
			if ($opts & Sjonsite_Base::accents) {
				if (!is_array(Sjonsite_Base::$accents)) {
					Sjonsite_Base::$accents = array(
						// Decompositions for Latin-1 Supplement
						chr(195).chr(128) => 'A', chr(195).chr(129) => 'A', chr(195).chr(130) => 'A', chr(195).chr(131) => 'A', chr(195).chr(132) => 'A', chr(195).chr(133) => 'A', chr(195).chr(135) => 'C', chr(195).chr(136) => 'E', chr(195).chr(137) => 'E', chr(195).chr(138) => 'E', chr(195).chr(139) => 'E', chr(195).chr(140) => 'I', chr(195).chr(141) => 'I', chr(195).chr(142) => 'I', chr(195).chr(143) => 'I', chr(195).chr(145) => 'N', chr(195).chr(146) => 'O', chr(195).chr(147) => 'O', chr(195).chr(148) => 'O', chr(195).chr(149) => 'O', chr(195).chr(150) => 'O', chr(195).chr(153) => 'U', chr(195).chr(154) => 'U', chr(195).chr(155) => 'U', chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y', chr(195).chr(159) => 's', chr(195).chr(160) => 'a', chr(195).chr(161) => 'a', chr(195).chr(162) => 'a', chr(195).chr(163) => 'a', chr(195).chr(164) => 'a', chr(195).chr(165) => 'a', chr(195).chr(167) => 'c', chr(195).chr(168) => 'e', chr(195).chr(169) => 'e', chr(195).chr(170) => 'e', chr(195).chr(171) => 'e', chr(195).chr(172) => 'i', chr(195).chr(173) => 'i', chr(195).chr(174) => 'i', chr(195).chr(175) => 'i', chr(195).chr(177) => 'n', chr(195).chr(178) => 'o', chr(195).chr(179) => 'o', chr(195).chr(180) => 'o', chr(195).chr(181) => 'o', chr(195).chr(182) => 'o', chr(195).chr(182) => 'o', chr(195).chr(185) => 'u', chr(195).chr(186) => 'u', chr(195).chr(187) => 'u', chr(195).chr(188) => 'u', chr(195).chr(189) => 'y', chr(195).chr(191) => 'y',
						// Decompositions for Latin Extended-A
						chr(196).chr(128) => 'A', chr(196).chr(129) => 'a', chr(196).chr(130) => 'A', chr(196).chr(131) => 'a', chr(196).chr(132) => 'A', chr(196).chr(133) => 'a', chr(196).chr(134) => 'C', chr(196).chr(135) => 'c', chr(196).chr(136) => 'C', chr(196).chr(137) => 'c', chr(196).chr(138) => 'C', chr(196).chr(139) => 'c', chr(196).chr(140) => 'C', chr(196).chr(141) => 'c', chr(196).chr(142) => 'D', chr(196).chr(143) => 'd', chr(196).chr(144) => 'D', chr(196).chr(145) => 'd', chr(196).chr(146) => 'E', chr(196).chr(147) => 'e', chr(196).chr(148) => 'E', chr(196).chr(149) => 'e', chr(196).chr(150) => 'E', chr(196).chr(151) => 'e', chr(196).chr(152) => 'E', chr(196).chr(153) => 'e', chr(196).chr(154) => 'E', chr(196).chr(155) => 'e', chr(196).chr(156) => 'G', chr(196).chr(157) => 'g', chr(196).chr(158) => 'G', chr(196).chr(159) => 'g', chr(196).chr(160) => 'G', chr(196).chr(161) => 'g', chr(196).chr(162) => 'G', chr(196).chr(163) => 'g', chr(196).chr(164) => 'H', chr(196).chr(165) => 'h', chr(196).chr(166) => 'H', chr(196).chr(167) => 'h', chr(196).chr(168) => 'I', chr(196).chr(169) => 'i', chr(196).chr(170) => 'I', chr(196).chr(171) => 'i', chr(196).chr(172) => 'I', chr(196).chr(173) => 'i', chr(196).chr(174) => 'I', chr(196).chr(175) => 'i', chr(196).chr(176) => 'I', chr(196).chr(177) => 'i', chr(196).chr(178) => 'IJ', chr(196).chr(179) => 'ij', chr(196).chr(180) => 'J', chr(196).chr(181) => 'j', chr(196).chr(182) => 'K', chr(196).chr(183) => 'k', chr(196).chr(184) => 'k', chr(196).chr(185) => 'L', chr(196).chr(186) => 'l', chr(196).chr(187) => 'L', chr(196).chr(188) => 'l', chr(196).chr(189) => 'L', chr(196).chr(190) => 'l', chr(196).chr(191) => 'L', chr(197).chr(128) => 'l', chr(197).chr(129) => 'L', chr(197).chr(130) => 'l', chr(197).chr(131) => 'N', chr(197).chr(132) => 'n', chr(197).chr(133) => 'N', chr(197).chr(134) => 'n', chr(197).chr(135) => 'N', chr(197).chr(136) => 'n', chr(197).chr(137) => 'N', chr(197).chr(138) => 'n', chr(197).chr(139) => 'N', chr(197).chr(140) => 'O', chr(197).chr(141) => 'o', chr(197).chr(142) => 'O', chr(197).chr(143) => 'o', chr(197).chr(144) => 'O', chr(197).chr(145) => 'o', chr(197).chr(146) => 'OE', chr(197).chr(147) => 'oe', chr(197).chr(148) => 'R', chr(197).chr(149) => 'r', chr(197).chr(150) => 'R', chr(197).chr(151) => 'r', chr(197).chr(152) => 'R', chr(197).chr(153) => 'r', chr(197).chr(154) => 'S', chr(197).chr(155) => 's', chr(197).chr(156) => 'S', chr(197).chr(157) => 's', chr(197).chr(158) => 'S', chr(197).chr(159) => 's', chr(197).chr(160) => 'S', chr(197).chr(161) => 's', chr(197).chr(162) => 'T', chr(197).chr(163) => 't', chr(197).chr(164) => 'T', chr(197).chr(165) => 't', chr(197).chr(166) => 'T', chr(197).chr(167) => 't', chr(197).chr(168) => 'U', chr(197).chr(169) => 'u', chr(197).chr(170) => 'U', chr(197).chr(171) => 'u', chr(197).chr(172) => 'U', chr(197).chr(173) => 'u', chr(197).chr(174) => 'U', chr(197).chr(175) => 'u', chr(197).chr(176) => 'U', chr(197).chr(177) => 'u', chr(197).chr(178) => 'U', chr(197).chr(179) => 'u', chr(197).chr(180) => 'W', chr(197).chr(181) => 'w', chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y', chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z', chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z', chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z', chr(197).chr(190) => 'z', chr(197).chr(191) => 's',
						// Euro Sign
						chr(226).chr(130).chr(172) => 'E'
					);
				}
				$string = strtr($string, Sjonsite_Base::$accents);
			}
			if ($opts & Sjonsite_Base::readable) {
				// it's becomes its
				if (!is_array(Sjonsite_Base::$readable)) {
					Sjonsite_Base::$readable = array(
						'\'s' => 's',
						'\'n' => 'n',
						'&' => 'and'
					);
				}
				$string = strtr($string, Sjonsite_Base::$readable);
			}
			$string = preg_replace(array('#([^A-Za-z0-9\-' . (($opts & Sjonsite_Base::nodots) ? '' : '\.').'\=]+)#U', '#--+#', '#-\.#', '#\.-#', '#^([-]+)([^-]*)#', '#(.*)([-]+)$#'), array('-', '-', '.', '.', '$2', '$1'), $string);
			if ($opts & Sjonsite_Base::lower) {
				$string = trim(strtolower($string));
			}
			return $string;
		}

		/**
		 * Cut the provided string at given length, without breaking words.
		 * Adds three dots at the end. if useEntity is true,
		 * the three dots are represented by the ellipsis entity (#8230)
		 *
		 * @param string $string
		 * @param int $length
		 * @param bool $useEntity
		 * @return string
		 */
		public static function cutoff ($string, $length = 80, $useEntity = false) {
			if (strlen($string) > $length) {
				$tmp = explode(' ', $string);
				$rv = array();
				do {
					$length -= (strlen($tmp[0]) + 1);
					$rv[] = array_shift($tmp);
				} while (isset($tmp[0]) && strlen($tmp[0]) < $length);
				return (implode(' ', $rv) . ($useEntity ? '&#8230;' : '...'));
			}
			return $string;
		}

		/**
		 * Prepares a string for printing
		 * For now, runs entities and returns
		 * Setting $fixamp to null will change all &amp;foo; back to &foo;
		 * Setting $fixamp to true will also change &lt;, &gt; and &quot; back to it's original entities.
		 *
		 * @param string $string
		 * @param bool $fixamp
		 * @return string
		 */
		public function out ($string, $fixamp = false) {
			$rv = htmlentities($string, ENT_QUOTES, 'utf-8');
			if ($fixamp !== false) {
				$rv = preg_replace('/\&amp\;([A-Za-z0-9\#]+)\;/Ui', '&$1;', $rv);
				if ($fixamp === true) {
					$rv = str_replace(array('&lt;', '&gt;', '&quot;'), array('<', '>',  '"'), $rv);
				}
			}
			return $rv;
		}

		/**
		 * Return the value of a parameter
		 *
		 * @param string $name
		 * @param mixed $default
		 * @return mixed
		 */
		public function param ($name, $default = null) {
			$rv = (array_key_exists($name, $_POST) ? $_POST[$name] : (array_key_exists($name, $_GET) ? $_GET[$name] : $default));
			Sjonsite_Base::param__clean($rv);
			return ($rv === 'null' ? null : ($rv === 'true' ? true : ($rv === 'false' ? false : $rv)));
		}

		/**
		 * Clean up the input
		 *
		 * @todo check out the filter extension
		 * @param mixed $rv
		 * @return void
		 */
		private static function param__clean (&$rv) {
			if (is_array($rv)) {
				foreach ($rv as $idx => $val) {
					Sjonsite_Base::param__clean($rv[$idx]);
				}
			}
			elseif (is_string($rv)) {
				if (get_magic_quotes_gpc()) {
					$rv = stripslashes($rv);
				}
				$rv = trim($rv);
			}
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
		public static function setMessage ($message, $class = Sjonsite_Base::info) {
			$_SESSION['messages'][] = array($message, $class);
		}

		/**
		 * Return the path part of request_uri, starting with 1
		 *
		 * @param int $idx
		 * @return string
		 */
		public function pathPart ($idx) {
			$uri = explode('/', $this->request);
			return (isset($uri[$idx]) ? $uri[$idx] : null);
		}

		/**
		 * Returns an array with $url parsed into scheme, user, pass, host, post, path, query
		 *
		 * @param string $url
		 * @return array
		 */
		public function parseUrl ($url) {
			$url = parse_url($url);
			if (count($url) == 1 && isset($url['path'])) {
				// parse_url fix for host-only urls
				$url['host'] = $url['path'];
				$url['path'] = null;
			}
			if (!array_key_exists('scheme', $url)) $url['scheme'] = null;
			if (!array_key_exists('user', $url)) $url['user'] = null;
			if (!array_key_exists('pass', $url)) $url['pass'] = null;
			if (!array_key_exists('host', $url)) $url['host'] = null;
			if (!array_key_exists('port', $url)) $url['port'] = null;
			if (!array_key_exists('path', $url)) $url['path'] = null;
			if (!array_key_exists('query', $url)) $url['query'] = null;
			if (substr($url['path'], 0, 1) == '/') $url['path'] = substr($url['path'], 1);
			return $url;
		}

		/**
		 * Redirect to an uri and exit
		 *
		 * @param string $uri
		 */
		public function redirect ($uri) {
			header('Location: ' . $uri);
			unset($this);
			exit;
		}

		/**
		 * Loads a template
		 *
		 * @param string $name
		 * @param bool $return
		 * @return void
		 */
		public function template ($name, $return = false) {
			$fullname = SJONSITE_INCLUDE . '/template/' . $name . '.php';
			if (file_exists($fullname)) {
				if ($return) {
					return file_get_contents($fullname);
				}
				include $fullname;
			}
			else {
				$this->ex = new Exception('Unknown template &lsquo;' . $this->out($name) . '&rsquo;', 1001);
				include SJONSITE_INCLUDE . '/template/system-error.php';
				unset($this);
			}
		}

		/**
		 * Returns true if $email is a valid address
		 *
		 * @param string $email
		 * @return bool
		 */
		public static function isemail ($email) {
			return (bool) preg_match('/^[a-z0-9\+._-]+@[a-z0-9][a-z0-9.-]{0,61}[a-z0-9]\.[a-z.]{2,6}$/i', $email);
		}

		/**
		 * Returns true if the current request has been posted
		 *
		 * @return bool
		 */
		public static function ispost () {
			return (strtolower($_SERVER['REQUEST_METHOD']) == 'post');
		}

		/**
		 * Desctructor
		 *
		 */
		public function __destruct () {
			if (isset($_SESSION)) @session_write_close();
			unset($this->db);
		}

	}

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
		 * Page parent
		 *
		 * @var string
		 * @see Sjonsite_ImagesModel
		 */
		const PAGE = 'P';

		/**
		 * Gallery parent
		 *
		 * @var string
		 * @see Sjonsite_ImagesModel
		 */
		const GALLERY = 'G';

		/**
		 * The full name of the table
		 *
		 * @var string
		 */
		protected $_table;

		/**
		 * An array with the field names
		 *
		 * @var array
		 */
		protected $_fields;

		/**
		 * An array with the keys/indexes on this table
		 *
		 * @var array
		 */
		protected $_keys;

		/**
		 * Constructor
		 *
		 * @param string $table
		 * @param array $row
		 */
		public function __construct ($table, $row = null) {
			$this->_table = SJONSITE_PDO_PREFIX . $table;
			if (is_array($row)) {
				foreach ($this->_fields as $field) {
					if (array_key_exists($field, $row)) {
						$this->$field = $row[$field];
					}
					else {
						$this->$field = null;
					}
				}
			}
		}

		/**
		 * Return the full name of the table
		 *
		 * @return string
		 */
		public function table () {
			return $this->_table;
		}

	}

	/**
	 * Class Sjonsite_Settings
	 *
	 * @package Sjonsite
	 */
	final class Sjonsite_Settings {

		/**
		 * Settings array
		 *
		 * @var array
		 */
		private $settings;

		/**
		 * Constructor
		 *
		 * @param PDO $db
		 */
		public function __construct ($db) {
			$sql = 'SELECT s_name AS name, s_value AS value FROM ' . SJONSITE_PDO_PREFIX . 'settings';
			$res = $db->query($sql);
			$this->settings = array();
			while ($res && $row = $res->fetch(PDO::FETCH_OBJ)) {
				$this->settings[$row->name] = unserialize($row->value);
			}
			$res = null;
		}

		/**
		 * Insert a setting (object and database)
		 *
		 * @param PDO $db
		 * @param string $name
		 * @param mixed $value
		 * @return bool
		 */
		public function insert ($db, $name, $value) {
			$this->settings[$name] = $value;
			try {
				$db->beginTransaction();
				$sql = 'INSERT ' . SJONSITE_PDO_PREFIX . 'settings (s_name, s_value) VALUES (:name, :value)';
				$res = $db->prepare($sql);
				if ($res->execute(array(
					':name' => $name,
					':value' => serialize($value)
				))) {
					$db->commit();
					$res = null;
					return true;
				}
				else {
					$db->rollBack();
					return false;
				}
			}
			catch (Exception $e) {
				// ? $db->rollBack();
				return $e;
			}
		}

		/**
		 * Update a setting (object and database)
		 *
		 * @param PDO $db
		 * @param string $name
		 * @param mixed $value
		 * @return bool
		 */
		public function update ($db, $name, $value) {
			$this->settings[$name] = $value;
			try {
				$db->beginTransaction();
				$sql = 'UPDATE ' . SJONSITE_PDO_PREFIX . 'settings SET s_value = :value WHERE s_name = :name';
				$res = $db->prepare($sql);
				if ($res->execute(array(
					':name' => $name,
					':value' => serialize($value)
				))) {
					$db->commit();
					$res = null;
					return true;
				}
				else {
					$db->rollBack();
					return false;
				}
			}
			catch (Exception $e) {
				// ? $db->rollBack();
				return $e;
			}
		}

		/**
		 * Return all settings
		 *
		 * @return array
		 */
		public function getAll () {
			return $this->settings;
		}

		/**
		 * Overloading getter
		 *
		 * @param string $name
		 * @return mixed
		 */
		public function __get ($name) {
			return (array_key_exists($name, $this->settings) ? $this->settings[$name] : null);
		}

		/**
		 * Overloading setter
		 *
		 * @param string $name
		 * @param mixed $value
		 * @return void
		 */
		public function __set ($name, $value) {
			$this->settings[$name] = $value;
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

		/**
		 * Overloading unsetter
		 *
		 * @param string $name
		 * @return void
		 */
		public function __unset ($name) {
			unset($this->settings[$name]);
		}

	}

	/**
	 * Class Sjonsite_PagesModel
	 *
	 * @package Sjonsite
	 */
	final class Sjonsite_PagesModel extends Sjonsite_Model {

		/**
		 * Page identifier
		 *
		 * @var int
		 */
		public $p_id;

		/**
		 * Page parent identifier
		 *
		 * @var int
		 */
		public $p_pid;

		/**
		 * Unique URI of this page
		 *
		 * @var string
		 */
		public $p_uri;

		/**
		 * Title of this page
		 *
		 * @var string
		 */
		public $p_title;

		/**
		 * Summary or Introduction of this page
		 *
		 * @var string
		 */
		public $p_summary;

		/**
		 * Main content of this page
		 *
		 * @var string
		 */
		public $p_content;

		/**
		 * Reference to an optional gallery
		 *
		 * @var int
		 */
		public $p_gallery;

		/**
		 * Where to put this page
		 *
		 * @var int
		 */
		public $p_sorting;

		/**
		 * State of this page
		 *
		 * @var constant
		 */
		public $p_state;

		/**
		 * Constructor
		 *
		 * @param array $row
		 */
		public function __construct ($row = null) {
			$this->_fields = array('p_id', 'p_pid', 'p_uri', 'p_title', 'p_summary', 'p_content', 'p_gallery', 'p_sorting', 'p_state');
			$this->_keys = array('p_id', 'p_uri_idx', 'p_sorting_idx', 'p_state_idx');
			parent::__construct('pages', $row);
		}

	}

	/**
	 * Class Sjonsite_GalleryModel
	 *
	 * @package Sjonsite
	 */
	final class Sjonsite_GalleryModel extends Sjonsite_Model {

		/**
		 * Gallery identifier
		 *
		 * @var int
		 */
		public $g_id;

		/**
		 * Reference to a page identifier
		 *
		 * @var int
		 */
		public $g_page;

		/**
		 * Title of gallery
		 *
		 * @var string
		 */
		public $g_title;

		/**
		 * Summary or Description of gallery
		 *
		 * @var string
		 */
		public $g_summary;

		/**
		 * Constructor
		 *
		 * @param array $row
		 */
		public function __construct ($row = null) {
			$this->_fields = array('g_id', 'g_page', 'g_title', 'g_summary');
			$this->_keys = array('g_id', 'g_page_idx');
			parent::__construct('gallery', $row);
		}

	}

	/**
	 * Class Sjonsite_ImagesModel
	 *
	 * @package Sjonsite
	 */
	final class Sjonsite_ImagesModel extends Sjonsite_Model {

		/**
		 * Image identifier
		 *
		 * @var int
		 */
		public $i_id;

		/**
		 * Parent type
		 *
		 * @var constant
		 */
		public $i_parent;

		/**
		 * Parent identifier
		 *
		 * @var int
		 */
		public $i_parent_id;

		/**
		 * URI of image
		 *
		 * @var string
		 */
		public $i_uri;

		/**
		 * Title (or alt-tag) of image
		 *
		 * @var string
		 */
		public $i_title;

		/**
		 * Width of (original) image
		 *
		 * @var int
		 */
		public $i_width;

		/**
		 * Height of (original) image
		 *
		 * @var int
		 */
		public $i_height;

		/**
		 * Constructor
		 *
		 * @param array $row
		 */
		public function __construct ($row = null) {
			$this->_fields = array('i_id', 'i_parent', 'i_parent_id', 'i_uri', 'i_title', 'i_width', 'i_height');
			$this->_keys = array('i_id', 'i_parent_idx', 'i_parent_id_idx');
			parent::__construct('images', $row);
		}

	}

	/**
	 * Class Sjonsite_UsersModel
	 *
	 * @package Sjonsite
	 */
	final class Sjonsite_UsersModel extends Sjonsite_Model {

		/**
		 * User identifier
		 *
		 * @var int
		 */
		public $u_id;

		/**
		 * Full name of user
		 *
		 * @var string
		 */
		public $u_name;

		/**
		 * E-mail address of user
		 *
		 * @var string
		 */
		public $u_email;

		/**
		 * Password of user, sha1 encrypted
		 *
		 * @var string
		 */
		public $u_passwd;

		/**
		 * Authentication level
		 *
		 * @var int
		 */
		public $u_level;

		/**
		 * State of user
		 *
		 * @var constant
		 */
		public $u_state;

		/**
		 * Constructor
		 *
		 * @param array $row
		 */
		public function __construct ($row = null) {
			$this->_fields = array('u_id', 'u_name', 'u_email', 'u_passwd', 'u_level', 'u_state');
			$this->_keys = array('u_id', 'u_email_idx', 'u_state_idx');
			parent::__construct('users', $row);
		}

	}

?>