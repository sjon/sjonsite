<?php

	/**
	 * SjonSite - Library
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @package SjonSite
	 * @version $Id$
	 */

	/**
	 * @todo integrate db and model into library.php lateron
	 */
	SjonsiteConfig::load('db');
	SjonsiteConfig::load('model');

	/**
	 * Class sjonsite
	 */
	class Sjonsite {

		/**
		 * @var sjonsite_cfg
		 */
		protected $cfg;

		/**
		 * @var sjonsite_db
		 */
		protected $db;

		/**
		 * @var sjonsite_io
		 */
		protected $io;

		/**
		 * Constructor
		 *
		 * @param array $config
		 * @return sjonsite
		 */
		public function __construct (array $config) {
			$this->cfg = new SjonsiteConfig($config);
			$this->db = new SjonsiteDB();
			$this->io = new SjonsiteIO();
		}

		/**
		 * Run a frontend controller
		 *
		 * @param string $suffix
		 * @return void
		 * @throws Exception when $suffix is not a valid class or does not implement controller
		 */
		public static function run ($suffix) {
			$classname = 'Sjonsite_' . ucfirst(strtolower($suffix));
			if (class_exists($classname, false)) {
				$cp = new $classname();
				if ($cp instanceof SjonsiteController) {
					$cp->handleEvent();
				}
				else {
					throw new SjonsiteException($suffix . ' is not a valid controller');
				}
			}
			else {
				throw new SjonsiteException($suffix . ' is not a valid class');
			}
		}

		/**
		 * Default event handler
		 *
		 * Runs the handleXyzEvent method, where Xyz is the value of the cmd argument
		 * Defaults to handleDefaultEvent if an invalid or no cmd is given.
		 *
		 * @return void
		 */
		public function handleEvent () {
			$cmd = ucfirst(strtolower(preg_replace('#[^a-z]#', '', $this->io->param('cmd'))));
			$method = 'handleDefaultEvent';
			if ($cmd && method_exists($this, 'handle' . $cmd . 'Event')) {
				$method = 'handle' . $cmd . 'Event';
			}
			call_user_func(array($this, $method));
		}

	}

	/**
	 * Class SjonsiteIO
	 */
	class SjonsiteIO {

		/**
		 * Fetch an input argument's value
		 *
		 * @param string $name
		 * @param mixed $default
		 * @return mixed
		 */
		public function param ($name, $default = null) {
			$value = (isset($_POST[$name]) ? $_POST[$name] : (isset($_GET[$name]) ? $_GET[$name] : null));
			if (!is_null($value) && get_magic_quotes_gpc()) {
				(is_array($value) ? array_walk($value, array('SjonsiteIO', 'param_stripslashes')) : SjonsiteIO::param_stripslashes($value));
			}
			return (is_null($value) ? $default : ($value === 'null' ? null : ($value === 'true' ? true : ($value === 'false' ? false : $value))));
		}

		/**
		 * Stripslashes
		 *
		 * @access private
		 * @param string $value
		 * @param string $key
		 */
		private static function param_stripslashes (&$value, $key = null) {
			$value = stripslashes($value);
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
		 * Change the string for use in an url
		 */
		const NORM_USEINURL = 7;

		/**
		 * Normalize a string
		 *
		 * The $chars array bit was kindly ripped from wordpress's functions-formatting.php
		 * @param string $string
		 * @param int $options
		 * @return string
		 */
		public function normalize ($string, $options = sjonsite_io::NORM_USEINURL) {
			static $chars;
			if (empty($chars)) {
				$chars = array(
					// Decompositions for Latin-1 Supplement
					chr(195).chr(128) => 'A', chr(195).chr(129) => 'A', chr(195).chr(130) => 'A', chr(195).chr(131) => 'A', chr(195).chr(132) => 'A', chr(195).chr(133) => 'A', chr(195).chr(135) => 'C', chr(195).chr(136) => 'E', chr(195).chr(137) => 'E', chr(195).chr(138) => 'E', chr(195).chr(139) => 'E', chr(195).chr(140) => 'I', chr(195).chr(141) => 'I', chr(195).chr(142) => 'I', chr(195).chr(143) => 'I', chr(195).chr(145) => 'N', chr(195).chr(146) => 'O', chr(195).chr(147) => 'O', chr(195).chr(148) => 'O', chr(195).chr(149) => 'O', chr(195).chr(150) => 'O', chr(195).chr(153) => 'U', chr(195).chr(154) => 'U', chr(195).chr(155) => 'U', chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y', chr(195).chr(159) => 's', chr(195).chr(160) => 'a', chr(195).chr(161) => 'a', chr(195).chr(162) => 'a', chr(195).chr(163) => 'a', chr(195).chr(164) => 'a', chr(195).chr(165) => 'a', chr(195).chr(167) => 'c', chr(195).chr(168) => 'e', chr(195).chr(169) => 'e', chr(195).chr(170) => 'e', chr(195).chr(171) => 'e', chr(195).chr(172) => 'i', chr(195).chr(173) => 'i', chr(195).chr(174) => 'i', chr(195).chr(175) => 'i', chr(195).chr(177) => 'n', chr(195).chr(178) => 'o', chr(195).chr(179) => 'o', chr(195).chr(180) => 'o', chr(195).chr(181) => 'o', chr(195).chr(182) => 'o', chr(195).chr(182) => 'o', chr(195).chr(185) => 'u', chr(195).chr(186) => 'u', chr(195).chr(187) => 'u', chr(195).chr(188) => 'u', chr(195).chr(189) => 'y', chr(195).chr(191) => 'y',
					// Decompositions for Latin Extended-A
					chr(196).chr(128) => 'A', chr(196).chr(129) => 'a', chr(196).chr(130) => 'A', chr(196).chr(131) => 'a', chr(196).chr(132) => 'A', chr(196).chr(133) => 'a', chr(196).chr(134) => 'C', chr(196).chr(135) => 'c', chr(196).chr(136) => 'C', chr(196).chr(137) => 'c', chr(196).chr(138) => 'C', chr(196).chr(139) => 'c', chr(196).chr(140) => 'C', chr(196).chr(141) => 'c', chr(196).chr(142) => 'D', chr(196).chr(143) => 'd', chr(196).chr(144) => 'D', chr(196).chr(145) => 'd', chr(196).chr(146) => 'E', chr(196).chr(147) => 'e', chr(196).chr(148) => 'E', chr(196).chr(149) => 'e', chr(196).chr(150) => 'E', chr(196).chr(151) => 'e', chr(196).chr(152) => 'E', chr(196).chr(153) => 'e', chr(196).chr(154) => 'E', chr(196).chr(155) => 'e', chr(196).chr(156) => 'G', chr(196).chr(157) => 'g', chr(196).chr(158) => 'G', chr(196).chr(159) => 'g', chr(196).chr(160) => 'G', chr(196).chr(161) => 'g', chr(196).chr(162) => 'G', chr(196).chr(163) => 'g', chr(196).chr(164) => 'H', chr(196).chr(165) => 'h', chr(196).chr(166) => 'H', chr(196).chr(167) => 'h', chr(196).chr(168) => 'I', chr(196).chr(169) => 'i', chr(196).chr(170) => 'I', chr(196).chr(171) => 'i', chr(196).chr(172) => 'I', chr(196).chr(173) => 'i', chr(196).chr(174) => 'I', chr(196).chr(175) => 'i', chr(196).chr(176) => 'I', chr(196).chr(177) => 'i', chr(196).chr(178) => 'IJ', chr(196).chr(179) => 'ij', chr(196).chr(180) => 'J', chr(196).chr(181) => 'j', chr(196).chr(182) => 'K', chr(196).chr(183) => 'k', chr(196).chr(184) => 'k', chr(196).chr(185) => 'L', chr(196).chr(186) => 'l', chr(196).chr(187) => 'L', chr(196).chr(188) => 'l', chr(196).chr(189) => 'L', chr(196).chr(190) => 'l', chr(196).chr(191) => 'L', chr(197).chr(128) => 'l', chr(197).chr(129) => 'L', chr(197).chr(130) => 'l', chr(197).chr(131) => 'N', chr(197).chr(132) => 'n', chr(197).chr(133) => 'N', chr(197).chr(134) => 'n', chr(197).chr(135) => 'N', chr(197).chr(136) => 'n', chr(197).chr(137) => 'N', chr(197).chr(138) => 'n', chr(197).chr(139) => 'N', chr(197).chr(140) => 'O', chr(197).chr(141) => 'o', chr(197).chr(142) => 'O', chr(197).chr(143) => 'o', chr(197).chr(144) => 'O', chr(197).chr(145) => 'o', chr(197).chr(146) => 'OE', chr(197).chr(147) => 'oe', chr(197).chr(148) => 'R', chr(197).chr(149) => 'r', chr(197).chr(150) => 'R', chr(197).chr(151) => 'r', chr(197).chr(152) => 'R', chr(197).chr(153) => 'r', chr(197).chr(154) => 'S', chr(197).chr(155) => 's', chr(197).chr(156) => 'S', chr(197).chr(157) => 's', chr(197).chr(158) => 'S', chr(197).chr(159) => 's', chr(197).chr(160) => 'S', chr(197).chr(161) => 's', chr(197).chr(162) => 'T', chr(197).chr(163) => 't', chr(197).chr(164) => 'T', chr(197).chr(165) => 't', chr(197).chr(166) => 'T', chr(197).chr(167) => 't', chr(197).chr(168) => 'U', chr(197).chr(169) => 'u', chr(197).chr(170) => 'U', chr(197).chr(171) => 'u', chr(197).chr(172) => 'U', chr(197).chr(173) => 'u', chr(197).chr(174) => 'U', chr(197).chr(175) => 'u', chr(197).chr(176) => 'U', chr(197).chr(177) => 'u', chr(197).chr(178) => 'U', chr(197).chr(179) => 'u', chr(197).chr(180) => 'W', chr(197).chr(181) => 'w', chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y', chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z', chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z', chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z', chr(197).chr(190) => 'z', chr(197).chr(191) => 's',
					// Euro Sign
					chr(226).chr(130).chr(172) => 'E',
					// extra: symbol to word
					'@' => ' at ', '&' => ' and '
				);
			}
			if ($options & SjonsiteIO::NORM_ACCENTS) {
				$string = strtr($string, $chars);
			}
			if ($options & SjonsiteIO::NORM_TOLOWER) {
				$string = trim(strtolower($string));
			}
			if ($options & SjonsiteIO::NORM_SPACETODASH) {
				$string = preg_replace(array('#([^A-Za-z0-9\+\-\.\,\=]+)#U', '#--+#', '#-\.#', '#\.-#', '#^([-]+)([^-]*)#', '#(.*)([-]+)$#'), array('-', '-', '.', '.', '$2', '$1'), $string);
				if ($string && substr($string, -1) == '-') $string = substr($string, 0, -1);
			}
			return $string;
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
		 * Prints the headers
		 *
		 * @param string $type
		 * @param bool $nocache
		 * @param array $extra
		 * @return void
		 */
		public function headers ($type = false, $nocache = false, $extra = false) {
			if (!$type) {
				$type = (strstr($_SERVER['HTTP_ACCEPT'], 'application/xhtml+xml') !== false) ? 'application/xhtml+xml' : 'text/html';
			}
			header('Content-Type: '.$type.'; charset=UTF-8');
			if ($nocache) {
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: nocache');
			}
			//header('ETag: ssEGJWRUIOJHGUIWOERJHGUIVOWERVNOUIEWNVJOWHENFGUJHEWROOQWJVNSK');
			//header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
			if (is_array($extra)) {
				foreach ($extra as $key => $val) {
					header("$key: $val");
				}
			}
		}

		/**
		 * Redirect to another url
		 *
		 * @param string $url
		 * @return bool
		 */
		public function redirect ($url, $args = null, $exit = false) {
			$qs = null;
			if (is_array($args) && count($args)) {
				$qs = array();
				foreach ($args as $key => $val) {
					$qs[] = urlencode($key).'='.rawurlencode($val);
				}
				$qs = implode('&', $qs);
			}
			$url = str_replace(array("\r", "\n"), '', $url);
			if (preg_match('!^([a-z0-9]+)://!i', $url)) { // already absolute
				$tmp = parse_url($url);
				if (isset($tmp['query']) && $qs) {
					$tmp['query'] .= '&'.$qs;
				}
				$url = $tmp['scheme'].'://'.(isset($tmp['user']) ? $tmp['user'].':'.$tmp['pass'].'@' : '') .
					$tmp['host'].(isset($tmp['port']) && $tmp['port'] != 80 ? ':'.$tmp['port'] : '') .
					$tmp['path'].(isset($tmp['query']) ? '?'.$tmp['query'] : ($qs ? '?'.$qs : '')) .
					(isset($tmp['fragment']) ? '#'.$tmp['fragment'] : '');
			}
			else { // relative
				$host = (empty($_SERVER['HTTP_HOST']) ? (empty($_SERVER['SERVER_NAME']) ? 'localhost' : explode(':', $_SERVER['SERVER_NAME'])) : explode(':', $_SERVER['HTTP_HOST']));
				if (is_array($host)) {
					list($host) = $host;
				}
				$proto = (isset($_SERVER['HTTPS']) && !strcasecmp($_SERVER['HTTPS'], 'on') ? 'https' : 'http');
				$port = (isset($_SERVER['SERVER_PORT']) ? ($proto == 'https' && $_SERVER['SERVER_PORT'] == 443 ? null : $_SERVER['SERVER_PORT']) : 80);
				if ($proto == 'http' && $port == 80) {
					$port = null;
				}
				$server = $proto .'://'. $host . ($port ? ':'. $port : '');
				if (!strlen($url)) {
					$url = (isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF']);
				}
				if ($url{0} == '/') {
					$url = $server . $url;
				}
				else {
					$path = strtr(((isset($_SERVER['PATH_INFO']) && strlen($_SERVER['PATH_INFO']) && $_SERVER['PHP_SELF'] != $_SERVER['PATH_INFO']) ? dirname(substr($_SERVER['PHP_SELF'], 0, -strlen($_SERVER['PATH_INFO']))) : dirname($_SERVER['PHP_SELF'])), '\\', '/');
					if (substr($path, -1) != '/') {
						$path .= '/';
					}
					$url = $server . $path . $url;
				}
				if ($qs) {
					$url .= '?' . $qs;
				}
			}
			if (headers_sent()) {
				echo '<meta http-equiv="Redirect" content="0; URL=' . $url . '" />';
				echo '<script type="text/javascript">';
				echo 'location.href = "' . $url . '";';
				echo '</script>';
				printf('Redirecting to: <a href="%s">%s</a>.', $url, $url);
			}
			else {
				header('Location: ' . $url);
				if (isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) != 'head') {
					printf('Redirecting to: <a href="%s">%s</a>.', $url, $url);
				}
			}
			if ($exit) {
				exit;
			}
			return true;
		}

		/**
		 * Returns true if $date is a valid date
		 *
		 * @param string $date
		 * @return bool
		 */
		public function isDate ($date) {
		}

		/**
		 * Returns true if $email is a valid address
		 *
		 * @param string $email
		 * @return bool
		 */
		public function isEmail ($email) {
			return (bool) preg_match('/^[a-z0-9._-]+@[a-z0-9][a-z0-9.-]{0,61}[a-z0-9]\.[a-z.]{2,6}$/i', $email);
		}

		/**
		 * Returns true if this is a post request
		 *
		 * @return bool
		 */
		public function isPost () {
			return (strtolower($_SERVER['REQUEST_METHOD']) == 'post');
		}

	}

	/**
	 * Interface SjonsiteController
	 */
	interface SjonsiteController {

		/**
		 * Default Event Handler
		 *
		 * @abstract
		 * @return mixed
		 */
		public abstract function handleDefaultEvent();

	}

?>