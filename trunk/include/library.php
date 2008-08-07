<?php

	/**
	 * SjonSite - Library
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @package Sjonsite
	 * @copyright Sjon's dotCom 2007
	 * @license Mozilla Public License 1.1
	 * @version $Id$
	 */

	/**
	 * Check Configuration
	 */
	if (!defined('SJONSITE_ROOT')) define ('SJONSITE_ROOT', dirname(dirname(__FILE__)));
	if (!defined('SJONSITE_HTDOCS')) define ('SJONSITE_HTDOCS', SJONSITE_ROOT . '/htdocs');
	if (!defined('SJONSITE_INCLUDE')) define ('SJONSITE_INCLUDE', SJONSITE_ROOT . '/include');
	if (!defined('SJONSITE_VAR')) define ('SJONSITE_VAR', SJONSITE_ROOT . '/var');
	if (!defined('SJONSITE_CACHE')) define ('SJONSITE_CACHE', SJONSITE_VAR . '/cache');
	if (!defined('SJONSITE_TEMPLATE')) define ('SJONSITE_TEMPLATE', SJONSITE_VAR . '/template');

	/**
	 * Check PDO Configuration
	 */
	if (!defined('SJONSITE_PDO_DSN')) define ('SJONSITE_PDO_DSN', 'mysql:host=localhost;port=3306;dbname=sjonsite');
	if (!defined('SJONSITE_PDO_USER')) define ('SJONSITE_PDO_USER', 'username');
	if (!defined('SJONSITE_PDO_PASS')) define ('SJONSITE_PDO_PASS', 'password');
	if (!defined('SJONSITE_PDO_PERSISTENT')) define ('SJONSITE_PDO_PERSISTENT', false);
	if (!defined('SJONSITE_PREFIX')) define ('SJONSITE_PREFIX', 'v1_');

	/**
	 * Set starting time and check current environment
	 */
	define ('SJONSITE_START', microtime(true));
	define ('SJONSITE_TIMEZONE', 'Europe/Amsterdam');
	if (version_compare(phpversion(), '5.2', '<')) exit('You are not running PHP 5.2 or higher.');
	if (get_magic_quotes_gpc()) exit('You should disable magic quotes, they\'re evil.');
	if (!class_exists('PDO', false)) exit('You don\'t have the PDO extension enabled.');
	// we only support mysql for now.
	if (!in_array('mysql', PDO::getAvailableDrivers())) exit('You don\'t have the PDO_mysql extension enabled.');

	/**
	 * Class Sjonsite
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @copyright Sjon's dotCom 2007
	 */
	abstract class Sjonsite {

		/**
		 * Constant for the status enums
		 */
		const ACTIVE = 'A';

		/**
		 * Constant for the status enums
		 */
		const HIDDEN = 'H';

		/**
		 * Constant for the status enums
		 */
		const SUSPEND = 'S';

		/**
		 * Constant for the status enums
		 */
		const REMOVED = 'R';

		/**
		 * Constant for the status enums
		 */
		const UNKNOWN = 'U';

		/**
		 * Constant for the boolean enums
		 */
		const YES = 'Y';

		/**
		 * Constant for the boolean enums
		 */
		const NO = 'N';

		/**
		 * Constant for the normalize() method
		 * Change accented chars to un-accented chars
		 */
		const NORM_ACCENTS = 1;

		/**
		 * Constant for the normalize() method
		 * Lower all chars, and trim()
		 */
		const NORM_TOLOWER = 2;

		/**
		 * Constant for the normalize() method
		 * Change spaces et al to dashes
		 */
		const NORM_SPACETODASH = 4;

		/**
		 * Constant for the normalize() method
		 * Make it readable (its vs. it-s)
		 */
		const NORM_READABLE = 8;

		/**
		 * Constant for the normalize() method
		 * Change the string for use in an url
		 */
		const NORM_ALL = 15;

		/**
		 * Our database pointer
		 *
		 * @var PDO
		 */
		protected $db;

		/**
		 * Our exception basket
		 *
		 * @var array
		 */
		protected $ex;

		/**
		 * Our request uri, without query string
		 *
		 * @var string
		 */
		protected $request;

		/**
		 * Our current resource
		 *
		 * @var Sjonsite_ResourceModel
		 */
		protected $resource;

		/**
		 * Constructor
		 *
		 * @param Sjonsite_ResourceModel $resource
		 * @param string $callback
		 */
		public function __construct ($resource, $callback = null) {
			$this->db = null;
			$this->ex = array();
			try {
				$this->db = new PDO(SJONSITE_PDO_DSN, SJONSITE_PDO_USER, SJONSITE_PDO_PASS, array(PDO::ATTR_PERSISTENT => SJONSITE_PDO_PERSISTENT));
				$this->db->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
				$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$this->db->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_NATURAL);
			}
			catch (Exception $e) {
				$this->error($e);
				$this->display('error-template');
				return;
			}
			$this->request = preg_replace('#[^a-z0-9\_\-\/\?]#', '', strtolower($_SERVER['REQUEST_URI']));
			if (strpos($this->request, '?') !== false) {
				$this->request = substr($this->request, 0, strpos($this->request, '?'));
			}
			$this->resource = $resource;
			date_default_timezone_set(SJONSITE_TIMEZONE);
			session_name('Sjonsite');
			session_start();
			$cmd = $this->param('cmd');
			$method = 'handle' . ucfirst(strtolower(preg_replace('#[^a-z]#i', null, $cmd))) . 'Event';
			if (!method_exists($this, $method)) $method = 'handleDefaultEvent';
			$template = null;
			if ($callback && method_exists($this, $callback)) {
				$template = call_user_func(array($this, $callback));
			}
			if (is_null($template) || $template === false) {
				$template = call_user_func(array($this, $method));
			}
			if ($template && is_string($template)) {
				if (!ini_get('zlib.output_compression')) ob_start('ob_gzhandler');
				header('Content-Type: text/html; charset=UTF-8');
				header('Cache-Control: private, no-cache, no-store, must-revalidate, max-age=0, pre-check=0, post-check=0');
				header('Pragma: no-cache');
				header('Expires: ' . gmdate(DATE_RFC1123, 233886300));
				header('Last-Modified: ' . gmdate(DATE_RFC1123));
				$this->display($template);
				ob_flush();
			}
		}

		/**
		 * Display some content
		 *
		 * @param string $template
		 * @return void
		 */
		public function display ($template) {
			$template = preg_replace('#[^a-z0-9\-]#', '', $template);
			if (!file_exists(SJONSITE_TEMPLATE . '/' . $template . '.php')) {
				$this->error('Unable to locate template &ldquo;' . $template . '&rdquo;', 1);
				$template = 'error-template';
			}
			$this->template($template);
		}

		/**
		 * Interface for variables for displaying
		 * Set $isnull to true if you want $name to be null
		 *
		 * @param string $name
		 * @param mixed $value
		 * @param bool $isnull
		 * @return mixed
		 */
		public function displayVar ($name, $value = null, $isnull = false) {
			static $vars;
			if (!is_null($value) || $isnull === true) {
				$vars[$name] = $value;
			}
			else {
				return (array_key_exists($name, $vars) ? $vars[$name] : null);
			}
		}

		/**
		 * Require a template file
		 *
		 * @param string $template
		 * @return void
		 */
		private function template ($template) {
			require SJONSITE_TEMPLATE . '/' . $template . '.php';
		}

		/**
		 * Add a non-fatal error to the exception basket
		 *
		 * @param string $message
		 * @param int $code
		 */
		public function error ($message, $code = null) {
			if ($message instanceof Exception) {
				$this->ex[] = $message;
			}
			else {
				$this->ex[] = new Exception($message, $code);
			}
		}

		/**
		 * Fetch an input argument's value
		 *
		 * @param string $name
		 * @param mixed $default
		 * @return mixed
		 */
		public function param ($name, $default = null) {
			if (isset($_POST[$name]) || isset($_GET[$name])) {
				$rv = (isset($_POST[$name]) ? $_POST[$name] : $_GET[$name]);
				return ($rv === 'null' ? null : ($rv === 'true' ? true : ($rv === 'false' ? false : $rv)));
			}
			return $default;
		}

		/**
		 * Cut the provided string at given length, without breaking words
		 *
		 * @param string $string
		 * @param int $length
		 * @return string
		 */
		public function cutoff ($string, $length = 80) {
			if (strlen($string) > $length) {
				$tmp = explode(' ', $string);
				$rv = array();
				do {
					$length -= (strlen($tmp[0]) + 1);
					$rv[] = array_shift($tmp);
				} while (strlen($tmp[0]) < $length);
				return (implode(' ', $rv) . '&#8230;');
			}
			return $string;
		}

		/**
		 * Return an htmlentities string
		 *
		 * @param string $string
		 * @return string
		 */
		public function entities ($string) {
			return htmlentities($string, ENT_QUOTES, 'utf-8');
		}

		/**
		 * Normalize a string
		 *
		 * The $chars array bit was kindly ripped from wordpress's functions-formatting.php
		 * @param string $string
		 * @param int $options
		 * @return string
		 */
		public function normalize ($string, $options = Sjonsite::NORM_ALL) {
			static $chars, $readable;
			if (empty($chars)) {
				$chars = array(
					// Decompositions for Latin-1 Supplement
					chr(195).chr(128) => 'A', chr(195).chr(129) => 'A', chr(195).chr(130) => 'A', chr(195).chr(131) => 'A', chr(195).chr(132) => 'A', chr(195).chr(133) => 'A', chr(195).chr(135) => 'C', chr(195).chr(136) => 'E', chr(195).chr(137) => 'E', chr(195).chr(138) => 'E', chr(195).chr(139) => 'E', chr(195).chr(140) => 'I', chr(195).chr(141) => 'I', chr(195).chr(142) => 'I', chr(195).chr(143) => 'I', chr(195).chr(145) => 'N', chr(195).chr(146) => 'O', chr(195).chr(147) => 'O', chr(195).chr(148) => 'O', chr(195).chr(149) => 'O', chr(195).chr(150) => 'O', chr(195).chr(153) => 'U', chr(195).chr(154) => 'U', chr(195).chr(155) => 'U', chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y', chr(195).chr(159) => 's', chr(195).chr(160) => 'a', chr(195).chr(161) => 'a', chr(195).chr(162) => 'a', chr(195).chr(163) => 'a', chr(195).chr(164) => 'a', chr(195).chr(165) => 'a', chr(195).chr(167) => 'c', chr(195).chr(168) => 'e', chr(195).chr(169) => 'e', chr(195).chr(170) => 'e', chr(195).chr(171) => 'e', chr(195).chr(172) => 'i', chr(195).chr(173) => 'i', chr(195).chr(174) => 'i', chr(195).chr(175) => 'i', chr(195).chr(177) => 'n', chr(195).chr(178) => 'o', chr(195).chr(179) => 'o', chr(195).chr(180) => 'o', chr(195).chr(181) => 'o', chr(195).chr(182) => 'o', chr(195).chr(182) => 'o', chr(195).chr(185) => 'u', chr(195).chr(186) => 'u', chr(195).chr(187) => 'u', chr(195).chr(188) => 'u', chr(195).chr(189) => 'y', chr(195).chr(191) => 'y',
					// Decompositions for Latin Extended-A
					chr(196).chr(128) => 'A', chr(196).chr(129) => 'a', chr(196).chr(130) => 'A', chr(196).chr(131) => 'a', chr(196).chr(132) => 'A', chr(196).chr(133) => 'a', chr(196).chr(134) => 'C', chr(196).chr(135) => 'c', chr(196).chr(136) => 'C', chr(196).chr(137) => 'c', chr(196).chr(138) => 'C', chr(196).chr(139) => 'c', chr(196).chr(140) => 'C', chr(196).chr(141) => 'c', chr(196).chr(142) => 'D', chr(196).chr(143) => 'd', chr(196).chr(144) => 'D', chr(196).chr(145) => 'd', chr(196).chr(146) => 'E', chr(196).chr(147) => 'e', chr(196).chr(148) => 'E', chr(196).chr(149) => 'e', chr(196).chr(150) => 'E', chr(196).chr(151) => 'e', chr(196).chr(152) => 'E', chr(196).chr(153) => 'e', chr(196).chr(154) => 'E', chr(196).chr(155) => 'e', chr(196).chr(156) => 'G', chr(196).chr(157) => 'g', chr(196).chr(158) => 'G', chr(196).chr(159) => 'g', chr(196).chr(160) => 'G', chr(196).chr(161) => 'g', chr(196).chr(162) => 'G', chr(196).chr(163) => 'g', chr(196).chr(164) => 'H', chr(196).chr(165) => 'h', chr(196).chr(166) => 'H', chr(196).chr(167) => 'h', chr(196).chr(168) => 'I', chr(196).chr(169) => 'i', chr(196).chr(170) => 'I', chr(196).chr(171) => 'i', chr(196).chr(172) => 'I', chr(196).chr(173) => 'i', chr(196).chr(174) => 'I', chr(196).chr(175) => 'i', chr(196).chr(176) => 'I', chr(196).chr(177) => 'i', chr(196).chr(178) => 'IJ', chr(196).chr(179) => 'ij', chr(196).chr(180) => 'J', chr(196).chr(181) => 'j', chr(196).chr(182) => 'K', chr(196).chr(183) => 'k', chr(196).chr(184) => 'k', chr(196).chr(185) => 'L', chr(196).chr(186) => 'l', chr(196).chr(187) => 'L', chr(196).chr(188) => 'l', chr(196).chr(189) => 'L', chr(196).chr(190) => 'l', chr(196).chr(191) => 'L', chr(197).chr(128) => 'l', chr(197).chr(129) => 'L', chr(197).chr(130) => 'l', chr(197).chr(131) => 'N', chr(197).chr(132) => 'n', chr(197).chr(133) => 'N', chr(197).chr(134) => 'n', chr(197).chr(135) => 'N', chr(197).chr(136) => 'n', chr(197).chr(137) => 'N', chr(197).chr(138) => 'n', chr(197).chr(139) => 'N', chr(197).chr(140) => 'O', chr(197).chr(141) => 'o', chr(197).chr(142) => 'O', chr(197).chr(143) => 'o', chr(197).chr(144) => 'O', chr(197).chr(145) => 'o', chr(197).chr(146) => 'OE', chr(197).chr(147) => 'oe', chr(197).chr(148) => 'R', chr(197).chr(149) => 'r', chr(197).chr(150) => 'R', chr(197).chr(151) => 'r', chr(197).chr(152) => 'R', chr(197).chr(153) => 'r', chr(197).chr(154) => 'S', chr(197).chr(155) => 's', chr(197).chr(156) => 'S', chr(197).chr(157) => 's', chr(197).chr(158) => 'S', chr(197).chr(159) => 's', chr(197).chr(160) => 'S', chr(197).chr(161) => 's', chr(197).chr(162) => 'T', chr(197).chr(163) => 't', chr(197).chr(164) => 'T', chr(197).chr(165) => 't', chr(197).chr(166) => 'T', chr(197).chr(167) => 't', chr(197).chr(168) => 'U', chr(197).chr(169) => 'u', chr(197).chr(170) => 'U', chr(197).chr(171) => 'u', chr(197).chr(172) => 'U', chr(197).chr(173) => 'u', chr(197).chr(174) => 'U', chr(197).chr(175) => 'u', chr(197).chr(176) => 'U', chr(197).chr(177) => 'u', chr(197).chr(178) => 'U', chr(197).chr(179) => 'u', chr(197).chr(180) => 'W', chr(197).chr(181) => 'w', chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y', chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z', chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z', chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z', chr(197).chr(190) => 'z', chr(197).chr(191) => 's',
					// Euro Sign
					chr(226).chr(130).chr(172) => 'E',
				);
				$readable = array(
					'\'s' => 's',
					'\'n' => 'n',
					'@' => ' at ',
					'&' => ' and '
				);
			}
			if ($options & Sjonsite::NORM_ACCENTS) {
				$string = strtr($string, $chars);
			}
			if ($options & Sjonsite::NORM_READABLE) {
				$string = strtr($string, $readable);
			}
			if ($options & Sjonsite::NORM_TOLOWER) {
				$string = trim(strtolower($string));
			}
			if ($options & Sjonsite::NORM_SPACETODASH) {
				$string = preg_replace(array('#([^A-Za-z0-9\+\-\.\,\=]+)#U', '#--+#', '#-\.#', '#\.-#', '#^([-]+)([^-]*)#', '#(.*)([-]+)$#'), array('-', '-', '.', '.', '$2', '$1'), $string);
				if ($string && substr($string, -1) == '-') $string = substr($string, 0, -1);
			}
			return $string;
		}

		/**
		 * Redirect to an uri
		 *
		 * @param string $uri
		 */
		public function redirect ($uri) {
			session_write_close();
			header('Location: ' . $uri);
			unset($this->db);
			exit;
		}

		/**
		 * Static Run Handler
		 * Does the actual initialization and execution
		 *
		 * @return void
		 */
		public static function run () {
			$ls = get_declared_classes();
			$mc = null;
			foreach ($ls as $cn) {
				if (is_subclass_of($cn, 'Sjonsite') && $cn != 'Sjonsite_Management') {
					$mc = $cn;
					break;
				}
			}
			if (is_null($mc)) {
				throw new Exception('There is no Runnable class!', 1234);
			}
			return new $mc();
		}

		/**
		 * Default Event Handler
		 *
		 * @return string
		 */
		abstract function handleDefaultEvent ();

		/**
		 * Return the path part of $uri (wich defaults to the current request)
		 *
		 * @param int $idx
		 * @param string $uri
		 * @return string
		 */
		public function getPathPart ($idx, $uri = null) {
			if (is_null($uri)) $uri = $this->request;
			$uri = explode('/', $uri);
			return ((isset($uri[$idx]) && $uri[$idx]) ? $uri[$idx] : null);
		}

		/**
		 * Returns true if $email is a valid address
		 *
		 * @param string $email
		 * @return bool
		 */
		public static function isEmail ($email) {
			return (bool) preg_match('/^[a-z0-9._-]+@[a-z0-9][a-z0-9.-]{0,61}[a-z0-9]\.[a-z.]{2,6}$/i', $email);
		}

		/**
		 * Returns true if this is a post request
		 *
		 * @return bool
		 */
		public static function isPost () {
			return (strtolower($_SERVER['REQUEST_METHOD']) == 'post');
		}

		/**
		 * Returns true if the current request is an https request
		 *
		 * @return bool
		 */
		public static function isSecure () {
			return (array_key_exists('HTTPS', $_SERVER) && (strtolower($_SERVER['HTTPS']) == 'on'));
		}

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
		 * @return void
		 */
		public static function setMessage ($message) {
			$_SESSION['messages'][] = $message;
		}

		/**
		 * Destructor
		 *
		 * @return void
		 */
		public function __destruct () {
			session_write_close();
			$this->db = null;
		}

	}

	/**
	 * Class Sjonsite_Management
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @copyright Sjon's dotCom 2007
	 */
	abstract class Sjonsite_Management extends Sjonsite {

		/**
		 * Constructor
		 *
		 * @param array $resource
		 */
		public function __construct ($resource) {
			parent::__construct($resource, 'checkAuth');
		}

		/**
		 * Check Authentication
		 *
		 * @return string
		 */
		public function checkAuth () {
			if (empty($_SESSION['auth'])) {
				return 'management-login';
			}
			return false;
		}

	}

	/**
	 * Class Sjonsite_Model
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @copyright Sjon's dotCom 2007
	 */
	abstract class Sjonsite_Model {

		/**
		 * Full table name
		 *
		 * @var string
		 */
		protected $_table;

		/**
		 * Field names
		 *
		 * @var array
		 */
		protected $_fields;

		/**
		 * Constructor
		 *
		 * @param string $table
		 * @param array $fields
		 * @param array $values
		 */
		public function __construct ($table, $fields, $values = null) {
			$this->_table = SJONSITE_PREFIX . $table;
			$this->_fields = $fields;
			if (is_array($values)) {
				foreach ($this->_fields as $field) {
					$this->$field = (array_key_exists($field, $values) ? $values[$field] : null);
				}
			}
		}

		/**
		 * Return the table name
		 *
		 * @return string
		 */
		public function getTable () {
			return $this->_table;
		}

		/**
		 * Return the field names
		 *
		 * @return array
		 */
		public function getFields () {
			return $this->_fields;
		}

	}

	/**
	 * Class Sjonsite_ResourceModel
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @copyright Sjon's dotCom 2007
	 */
	final class Sjonsite_ResourceModel {

		/**
		 * Id
		 *
		 * @var int
		 */
		public $id;

		/**
		 * NestedSet Left
		 *
		 * @var int
		 */
		public $ns_left;

		/**
		 * NestedSet Right
		 *
		 * @var int
		 */
		public $ns_right;

		/**
		 * Title
		 *
		 * @var string
		 */
		public $title;

		/**
		 * Description
		 *
		 * @var string
		 */
		public $description;

		/**
		 * Keywords
		 *
		 * @var string
		 */
		public $keywords;

		/**
		 * Module
		 *
		 * @var string
		 */
		public $module;

		/**
		 * State
		 *
		 * @var constant
		 */
		public $state;

		/**
		 * Constructor
		 *
		 * @param array $values
		 */
		public function __construct ($values = null) {
			parent::__construct('resource', array('id', 'ns_left', 'ns_right', 'title', 'description', 'keywords', 'module', 'state'), $values);
		}

	}

	/**
	 * Class Sjonsite_AliasModel
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @copyright Sjon's dotCom 2007
	 */
	final class Sjonsite_AliasModel {

		/**
		 * Uri
		 *
		 * @var string
		 */
		public $uri;

		/**
		 * Resource
		 *
		 * @var int
		 */
		public $resource;

		/**
		 * Constructor
		 *
		 * @param array $values
		 */
		public function __construct ($values = null) {
			parent::__construct('alias', array('uri', 'resource'), $values);
		}

	}

	/**
	 * Class Sjonsite_UserModel
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @copyright Sjon's dotCom 2007
	 */
	final class Sjonsite_UserModel {

		/**
		 * Id
		 *
		 * @var int
		 */
		public $id;

		/**
		 * First name
		 *
		 * @var string
		 */
		public $firstname;

		/**
		 * Last name
		 *
		 * @var string
		 */
		public $lastname;

		/**
		 * Email
		 *
		 * @var string
		 */
		public $email;

		/**
		 * Password
		 *
		 * @var string
		 */
		public $passwd;

		/**
		 * Access level
		 *
		 * @var int
		 */
		public $access;

		/**
		 * State
		 *
		 * @var constant
		 */
		public $state;

		/**
		 * Constructor
		 *
		 * @param array $values
		 */
		public function __construct ($values = null) {
			parent::__construct('user', array('id', 'firstname', 'lastname', 'email', 'passwd', 'access', 'state'), $values);
		}

	}

	/**
	 * Interface Sjonsite_Model
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @copyright Sjon's dotCom 2007
	 */
	interface Sjonsite_Filter {

		public static function process ($string);

	}

	/**
	 * Interface Sjonsite_Model
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @copyright Sjon's dotCom 2007
	 */
	interface Sjonsite_Filter_Config {

		public static function getOption ($name);

	}

?>