<?php

/**
 * Sjonsite - IO Class
 *
 * @author Sjon <sjonscom@gmail.com>
 * @package Sjonsite
 * @copyright Sjon's dotCom 2008
 * @license Mozilla Public License 1.1
 * @version $Id$
 */

/**
 * Class Sjonsite_IO
 *
 * @package Sjonsite
 */
class Sjonsite_IO {

    /**
     * The server's protocol
     *
     * @var string
     */
    protected $serverProtocol;

    /**
     * The current request method
     *
     * @var string
     */
    protected $requestMethod;

    /**
     * The current request uri
     *
     * @var string
     */
    protected $requestUri;

    /**
     * The current request type, based on the extension
     *
     * @var string
     */
    protected $requestType;

    /**
     * Constructor
     */
    public function __construct () {
        $this->serverProtocol = filter_input(INPUT_SERVER, 'SERVER_PROTOCOL', FILTER_SANITIZE_STRING);
        $this->requestMethod = strtolower(filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING));
        if (!in_array($this->requestMethod, array('get', 'post', 'head'))) {
            $this->throwError(405);
        }
        $this->requestUri = rawurldecode(filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES));
        if (strpos($this->requestUri, '?') !== false) {
            $this->requestUri = substr($this->requestUri, 0, strpos($this->requestUri, '?'));
        }
        $request = str_replace(array('//', '//'), '/', '/' . preg_replace('#[^a-z0-9\_\-\/\.]#', null, strtolower($this->requestUri)));
        if ($request != $this->requestUri) {
            header('Location: ' . $request);
            $this->throwError(301);
        }
        $this->requestType = pathinfo($this->requestUri, PATHINFO_EXTENSION);
        if (empty($this->requestType)) {
            $this->requestType = 'html';
        }
        $this->requestUri = str_replace('.' . $this->requestType, null, $this->requestUri);
    }

    /**
     * Throw an IO error
     *
     * @param int $code
     * @return void
     * @throws Sjonsite_IOException
     */
    public function throwError ($code) {
        $statuslist = array(
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            204 => 'No Content',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other', // (since HTTP/1.1)
            304 => 'Not Modified',
            307 => 'Temporary Redirect', // (since HTTP/1.1)
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            410 => 'Gone',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            503 => 'Service Unavailable',
        );
        if (!isset($statuslist[$code])) $code = 500;
        header($this->serverProtocol . ' ' . $statuslist[$code], null, $code);
        throw new Sjonsite_IOException($statuslist[$code], $code);
    }

    /**
     * Return the current request method
     *
     * @return string
     */
    public function requestMethod () {
        return $this->requestMethod;
    }

    /**
     * Return the current request uri
     *
     * @return string
     */
    public function requestUri () {
        return $this->requestUri;
    }

    /**
     * Return the current request type, based on extension
     *
     * @return string
     */
    public function requestType () {
        return $this->requestType;
    }

    /**
     * Returns true if the current request method equals POST
     *
     * @return bool
     */
    public function isPost () {
        return ($this->requestMethod == 'post');
    }

    /**
     * Returns true if the current request type equals ATOM
     *
     * @return bool
     */
    public function isAtom () {
        return ($this->requestType == 'atom');
    }

    /**
     * Returns true if the current request type equals JSON
     *
     * @return bool
     */
    public function isJson () {
        return ($this->requestType == 'json');
    }

    /**
     * Returns true if the current request type equals RSS
     *
     * @return bool
     */
    public function isRss () {
        return ($this->requestType == 'rss');
    }

    /**
     * Returns true if the current request type equals XML
     *
     * @return bool
     */
    public function isXml () {
        return ($this->requestType == 'xml');
    }

    /**
     * Return the path part of requestUri, starting with 1
     *
     * @param int $idx
     * @return string
     */
    public function pathPart ($idx) {
        $uri = explode('/', $this->requestUri);
        return (isset($uri[$idx]) ? $uri[$idx] : null);
    }

    /**
     * Normalize Accents
     *
     * @var int
     * @see Sjonsite_IO::normalize()
     */
    const accents = 1;

    /**
     * Normalize Readable
     *
     * @var int
     * @see Sjonsite_IO::normalize()
     */
    const readable = 2;

    /**
     * Normalize NoDots
     *
     * @var int
     * @see Sjonsite_IO::normalize()
     */
    const nodots = 4;

    /**
     * Normalize ToLower
     *
     * @var int
     * @see Sjonsite_IO::normalize()
     */
    const lower = 8;

    /**
     * Normalize All
     *
     * @var int
     * @see Sjonsite_IO::normalize();
     */
    const all = 15;

    /**
     * Normalize Accents
     *
     * @var array
     * @see Sjonsite_IO::normalize()
     */
    protected static $accents = null;

    /**
     * Normalize Readable
     *
     * @var array
     * @see Sjonsite_IO::normalize()
     */
    protected static $readable = null;

    /**
     * Normalize a string, for usage in urls and such
     * The $accents array has been 'borrowed' from Wordpress.
     *
     * @param string $string
     * @return string
     */
    public static function normalize ($string, $opts = Sjonsite_IO::all) {
        if ($opts & Sjonsite_IO::accents) {
            if (!is_array(Sjonsite_IO::$accents)) {
                Sjonsite_IO::$accents = array(
                    // Decompositions for Latin-1 Supplement
                    chr(195).chr(128) => 'A', chr(195).chr(129) => 'A', chr(195).chr(130) => 'A', chr(195).chr(131) => 'A', chr(195).chr(132) => 'A', chr(195).chr(133) => 'A', chr(195).chr(135) => 'C', chr(195).chr(136) => 'E', chr(195).chr(137) => 'E', chr(195).chr(138) => 'E', chr(195).chr(139) => 'E', chr(195).chr(140) => 'I', chr(195).chr(141) => 'I', chr(195).chr(142) => 'I', chr(195).chr(143) => 'I', chr(195).chr(145) => 'N', chr(195).chr(146) => 'O', chr(195).chr(147) => 'O', chr(195).chr(148) => 'O', chr(195).chr(149) => 'O', chr(195).chr(150) => 'O', chr(195).chr(153) => 'U', chr(195).chr(154) => 'U', chr(195).chr(155) => 'U', chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y', chr(195).chr(159) => 's', chr(195).chr(160) => 'a', chr(195).chr(161) => 'a', chr(195).chr(162) => 'a', chr(195).chr(163) => 'a', chr(195).chr(164) => 'a', chr(195).chr(165) => 'a', chr(195).chr(167) => 'c', chr(195).chr(168) => 'e', chr(195).chr(169) => 'e', chr(195).chr(170) => 'e', chr(195).chr(171) => 'e', chr(195).chr(172) => 'i', chr(195).chr(173) => 'i', chr(195).chr(174) => 'i', chr(195).chr(175) => 'i', chr(195).chr(177) => 'n', chr(195).chr(178) => 'o', chr(195).chr(179) => 'o', chr(195).chr(180) => 'o', chr(195).chr(181) => 'o', chr(195).chr(182) => 'o', chr(195).chr(182) => 'o', chr(195).chr(185) => 'u', chr(195).chr(186) => 'u', chr(195).chr(187) => 'u', chr(195).chr(188) => 'u', chr(195).chr(189) => 'y', chr(195).chr(191) => 'y',
                    // Decompositions for Latin Extended-A
                    chr(196).chr(128) => 'A', chr(196).chr(129) => 'a', chr(196).chr(130) => 'A', chr(196).chr(131) => 'a', chr(196).chr(132) => 'A', chr(196).chr(133) => 'a', chr(196).chr(134) => 'C', chr(196).chr(135) => 'c', chr(196).chr(136) => 'C', chr(196).chr(137) => 'c', chr(196).chr(138) => 'C', chr(196).chr(139) => 'c', chr(196).chr(140) => 'C', chr(196).chr(141) => 'c', chr(196).chr(142) => 'D', chr(196).chr(143) => 'd', chr(196).chr(144) => 'D', chr(196).chr(145) => 'd', chr(196).chr(146) => 'E', chr(196).chr(147) => 'e', chr(196).chr(148) => 'E', chr(196).chr(149) => 'e', chr(196).chr(150) => 'E', chr(196).chr(151) => 'e', chr(196).chr(152) => 'E', chr(196).chr(153) => 'e', chr(196).chr(154) => 'E', chr(196).chr(155) => 'e', chr(196).chr(156) => 'G', chr(196).chr(157) => 'g', chr(196).chr(158) => 'G', chr(196).chr(159) => 'g', chr(196).chr(160) => 'G', chr(196).chr(161) => 'g', chr(196).chr(162) => 'G', chr(196).chr(163) => 'g', chr(196).chr(164) => 'H', chr(196).chr(165) => 'h', chr(196).chr(166) => 'H', chr(196).chr(167) => 'h', chr(196).chr(168) => 'I', chr(196).chr(169) => 'i', chr(196).chr(170) => 'I', chr(196).chr(171) => 'i', chr(196).chr(172) => 'I', chr(196).chr(173) => 'i', chr(196).chr(174) => 'I', chr(196).chr(175) => 'i', chr(196).chr(176) => 'I', chr(196).chr(177) => 'i', chr(196).chr(178) => 'IJ', chr(196).chr(179) => 'ij', chr(196).chr(180) => 'J', chr(196).chr(181) => 'j', chr(196).chr(182) => 'K', chr(196).chr(183) => 'k', chr(196).chr(184) => 'k', chr(196).chr(185) => 'L', chr(196).chr(186) => 'l', chr(196).chr(187) => 'L', chr(196).chr(188) => 'l', chr(196).chr(189) => 'L', chr(196).chr(190) => 'l', chr(196).chr(191) => 'L', chr(197).chr(128) => 'l', chr(197).chr(129) => 'L', chr(197).chr(130) => 'l', chr(197).chr(131) => 'N', chr(197).chr(132) => 'n', chr(197).chr(133) => 'N', chr(197).chr(134) => 'n', chr(197).chr(135) => 'N', chr(197).chr(136) => 'n', chr(197).chr(137) => 'N', chr(197).chr(138) => 'n', chr(197).chr(139) => 'N', chr(197).chr(140) => 'O', chr(197).chr(141) => 'o', chr(197).chr(142) => 'O', chr(197).chr(143) => 'o', chr(197).chr(144) => 'O', chr(197).chr(145) => 'o', chr(197).chr(146) => 'OE', chr(197).chr(147) => 'oe', chr(197).chr(148) => 'R', chr(197).chr(149) => 'r', chr(197).chr(150) => 'R', chr(197).chr(151) => 'r', chr(197).chr(152) => 'R', chr(197).chr(153) => 'r', chr(197).chr(154) => 'S', chr(197).chr(155) => 's', chr(197).chr(156) => 'S', chr(197).chr(157) => 's', chr(197).chr(158) => 'S', chr(197).chr(159) => 's', chr(197).chr(160) => 'S', chr(197).chr(161) => 's', chr(197).chr(162) => 'T', chr(197).chr(163) => 't', chr(197).chr(164) => 'T', chr(197).chr(165) => 't', chr(197).chr(166) => 'T', chr(197).chr(167) => 't', chr(197).chr(168) => 'U', chr(197).chr(169) => 'u', chr(197).chr(170) => 'U', chr(197).chr(171) => 'u', chr(197).chr(172) => 'U', chr(197).chr(173) => 'u', chr(197).chr(174) => 'U', chr(197).chr(175) => 'u', chr(197).chr(176) => 'U', chr(197).chr(177) => 'u', chr(197).chr(178) => 'U', chr(197).chr(179) => 'u', chr(197).chr(180) => 'W', chr(197).chr(181) => 'w', chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y', chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z', chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z', chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z', chr(197).chr(190) => 'z', chr(197).chr(191) => 's',
                    // Euro Sign
                    chr(226).chr(130).chr(172) => 'E'
                );
            }
            $string = strtr($string, Sjonsite_IO::$accents);
        }
        if ($opts & Sjonsite_IO::readable) {
            // it's becomes its
            if (!is_array(Sjonsite_IO::$readable)) {
                Sjonsite_IO::$readable = array(
                    '\'s' => 's',
                    '\'t' => 't',
                    '\'n' => 'n',
                    '&' => 'and'
                );
            }
            $string = strtr($string, Sjonsite_IO::$readable);
        }
        $string = preg_replace(array('#([^A-Za-z0-9\-' . (($opts & Sjonsite_IO::nodots) ? '' : '\.').'\=]+)#U', '#--+#', '#-\.#', '#\.-#', '#^([-]+)([^-]*)#', '#(.*)([-]+)$#'), array('-', '-', '.', '.', '$2', '$1'), $string);
        if ($opts & Sjonsite_IO::lower) {
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
    public static function out ($string, $fixamp = false) {
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
     * Return the value from a parameter from POST or GET, or default value
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public static function param ($name, $default = null) {
        $rv = Sjonsite_IO::post($name);
        if (is_null($rv) || $rv === false) {
            $rv = Sjonsite_IO::get($name);
        }
        if (is_null($rv) || $rv === false) {
            $rv = $default;
        }
        return $rv;
    }

    /**
     * Return the value from a parameter from $_GET
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public static function get ($name, $default = null) {
        if (filter_has_var(INPUT_GET, $name)) {
            return filter_input(INPUT_GET, $name, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        }
        return $default;
    }

    /**
     * Return the value from a parameter from $_POST
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public static function post ($name, $default = null) {
        if (filter_has_var(INPUT_POST, $name)) {
            return filter_input(INPUT_POST, $name, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        }
        return $default;
    }

    /**
     * Return the value from a parameter from $_COOKIE
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public static function cookie ($name, $default = null) {
        if (filter_has_var(INPUT_COOKIE, $name)) {
            return filter_input(INPUT_COOKIE, $name, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        }
        return $default;
    }

    /**
     * Return the value from a parameter from $_SERVER
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public static function server ($name, $default = null) {
        if (filter_has_var(INPUT_SERVER, $name)) {
            return filter_input(INPUT_SERVER, $name, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        }
        return $default;
    }

    /**
     * Returns true if $email is a valid address
     *
     * @param string $email
     * @return bool
     */
    public static function isEmail ($email) {
        return (bool) preg_match('/^[a-z0-9\+._-]+@[a-z0-9][a-z0-9.-]{0,61}[a-z0-9]\.[a-z.]{2,6}$/i', $email);
    }

}

/**
 * Class Sjonsite_IOException
 *
 * @package Sjonsite
 */
class Sjonsite_IOException extends Sjonsite_Exception {}

