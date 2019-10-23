<?php

/**
 * Sjonsite - Dispatcher
 *
 * @author Sjon <sjonscom@gmail.com>
 * @package Sjonsite
 * @copyright Sjon's dotCom 2008
 * @license Mozilla Public License 1.1
 * @version $Id$
 */

/**
 * Load configuration
 */
require_once 'include/config.php';

/**
 * Run
 */
try {
    Sjonsite::init();
    $guestRequest = null;
    if (!Sjonsite::$io->isPost() && Sjonsite::$auth->isGuest()) {
        $guestRequest = Sjonsite_Cache::get('guest-request-' . Sjonsite::$io->requestUri() . '-' . Sjonsite::$io->requestType(), SJONSITE_TTL);
        if ($guestRequest->isValid()) {
            Sjonsite::$request = $guestRequest->getData();
            Sjonsite::shutdown();
        }
    }
    // build response
    Sjonsite::connect();
    $sql = 'SELECT resource FROM %prefix%revisions WHERE uri = ' . Sjonsite::$db->quote(Sjonsite::$io->requestUri()) . ' AND state = ' . Sjonsite::$db->quote(Sjonsite_Model::ACTIVE) . ' ORDER BY revision DESC LIMIT 0, 1';
    $res = Sjonsite::$db->query($sql);
    $resource = $res->fetchColumn();
    $res = null;
    if (!is_numeric($resource)) {
        // check for system resource
        //$uri = explode('/', Sjonsite::$io->requestUri());
        //if (in_array($uri[1], array('login', 'logout', 'settings', 'system', 'upload', 'user'), true)) {
        //}
        Sjonsite::$io->throwError(404);
    }
    $resource = new Sjonsite_Resource($resource);
    if ($resource->state != Sjonsite_Model::ACTIVE) {
        Sjonsite::$io->throwError(404); // 410 Gone
    }
    $revision = new Sjonsite_Revision();
    $sql = 'SELECT * FROM %prefix%revisions WHERE resource = ' . Sjonsite::$db->quote($resource->id) . ' AND state = ' . Sjonsite::$db->quote(Sjonsite_Model::ACTIVE) . ' ORDER BY revision DESC LIMIT 0, 1';
    $res = Sjonsite::$db->query($sql);
    $res->setFetchMode(PDO::FETCH_INTO, $revision);
    $res->fetch(PDO::FETCH_INTO);
    $res = null;
    $revision->isModified(false);
    if ($revision->resource != $resource->id) {
        Sjonsite::$io->throwError(404); // 204 No Content
    }
    if ($revision->uri != Sjonsite::$io->requestUri()) {
        header('Location: ' . $revision->uri);
        Sjonsite::$io->throwError(301);
    }
    $controller = 'Sjonsite_' . ucfirst($resource->controller) . 'Controller';
    $dispatch = new $controller($resource, $revision);
    $dispatch->processRequest();
    if (!$dispatch->cacheDisabled() && Sjonsite::$auth->isGuest() && $guestRequest instanceof Sjonsite_Cache_Data) {
        $guestRequest->setData(Sjonsite::$request);
        Sjonsite_Cache::set($guestRequest);
    }
    Sjonsite::shutdown();
} catch (Exception $e) {
        $search = dirname(SJONSITE_INCLUDE);
        $replace = '~';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" media="screen" href="/css/exception.css" />
		<title>An Error Occurred - Sjonsite</title>
	</head>
	<body>
		<div id="container">
			<h1>An Error Occurred</h1>
			<h2><?php echo str_replace($search, $replace, ($e->getCode() ? $e->getCode() . ': ' : null) . $e->getMessage()); ?></h2>
<?php
    if (SJONSITE_DEBUG) {
?>
			<p>Stack Trace:</p>
			<ol>
<?php
        $trace = $e->getTrace();
        foreach ($trace as $t) {
            $message = null;
            if (isset($t['class'])) $message .= $t['class'];
            if (isset($t['type'])) $message .= $t['type'];
            if (isset($t['function'])) $message .= $t['function'] . '()';
            if (isset($t['file'])) $message .= ' in file ' . $t['file'];
            if (isset($t['line'])) $message .= ' line ' . $t['line'];
?>
				<li><?php echo str_replace($search, $replace, $message); ?></li>
<?php
        }
?>
				<li>{main}</li>
			</ol>
<?php
    }
?>
		</div>
	</body>
</html>
<?php
}

