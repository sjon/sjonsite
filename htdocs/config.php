<?php

/**
 * Sjonsite - Configuration
 *
 * @author Sjon <sjonscom@gmail.com>
 * @package Sjonsite
 * @copyright Sjon's dotCom 2008
 * @license Mozilla Public License 1.1
 * @version $Id$
 */

/**
 * Start of request
 *
 * @var float
 */
define ('SJONSITE_START', microtime(true));

/**
 * Path to the include directory
 *
 * @var string
 */
define ('SJONSITE_INCLUDE', dirname(__FILE__));

/**
 * Set our default timezone
 */
date_default_timezone_set('Europe/Amsterdam');

/**
 * Name our session
 */
session_name('Sjonsite');

/**
 * Check for development configuration
 */
if (file_exists(SJONSITE_INCLUDE . '/config.local.php')) {
    include SJONSITE_INCLUDE . '/config.local.php';
}

/**
 * PDO connection string
 *
 * @var string
 */
if (!defined('SJONSITE_PDO_DSN')) {
    define ('SJONSITE_PDO_DSN', 'mysql:host=localhost;port=3306;dbname=sjonscripts');
}

/**
 * PDO connection username
 *
 * @var string
 */
if (!defined('SJONSITE_PDO_USER')) {
    define ('SJONSITE_PDO_USER', 'username');
}

/**
 * PDO connection password
 *
 * @var string
 */
if (!defined('SJONSITE_PDO_PASS')) {
    define ('SJONSITE_PDO_PASS', 'password');
}

/**
 * PDO table prefix
 *
 * @var string
 */
if (!defined('SJONSITE_PDO_PREFIX')) {
    define ('SJONSITE_PDO_PREFIX', 'sjonsite_');
}

/**
 * Debugflag
 *
 * @var bool
 */
if (!defined('SJONSITE_DEBUG')) {
    define ('SJONSITE_DEBUG', false);
}

/**
 * The global time-to-live, in seconds
 * Low traffic sites can leave this at -1 for full freshness,
 * whereas a higher number introduces more staleness.
 * Set to zero to cache indefinitely
 */
if (!defined('SJONSITE_TTL')) {
    define ('SJONSITE_TTL', -1);
}

/**
 * Autoload
 *
 * @param string $className
 * @return void
 */
function __autoload ($className) {
    if (file_exists(SJONSITE_INCLUDE . '/library/' . $className . '.php')) {
        require_once SJONSITE_INCLUDE . '/library/' . $className . '.php';
    }
    else {
        $className = substr($className, 9);
        if (file_exists(SJONSITE_INCLUDE . '/model/' . $className . '.php')) {
            require_once SJONSITE_INCLUDE . '/model/' . $className . '.php';
        }
        else {
            $className = lcfirst($className);
            if (file_exists(SJONSITE_INCLUDE . '/controller/' . $className . '.php')) {
                require_once SJONSITE_INCLUDE . '/controller/' . $className . '.php';
            }
        }
    }
}

if (!function_exists('lcfirst')) {

    /**
     * Make a string's first character lowercase
     *
     * @param string $string
     * @return string
     */
    function lcfirst ($string) {
        $string{0} = strtolower($string{0});
        return $string;
    }

}

/**
 * Error handler function which throws an ErrorException
 * @param int $errno
 * @param string $errstr
 * @param string $errfile
 * @param int $errline
 * @return void
 * @throws ErrorException
 */
function sjonsite_error_handler ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}

/**
 * Install error handler
 */
set_error_handler('sjonsite_error_handler');

