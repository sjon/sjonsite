<?php

/**
 * Sjonsite Template - Header
 *
 * @author Sjon <sjonscom@gmail.com>
 * @package Sjonsite_Template
 * @copyright Sjon's dotCom 2008
 * @license Mozilla Public License 1.1
 * @version $Id$
 */

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" media="screen" href="/css/screen.css" />
		<link rel="stylesheet" type="text/css" media="print" href="/css/print.css" />
<?php
if (!Sjonsite::$auth->isGuest()) {
?>
		<link rel="stylesheet" type="text/css" media="screen" href="/css/system.css" />
<?php
}
?>
		<link rel="icon" type="image/x-icon" href="/favicon.ico" />
		<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico" />
		<title><?php echo (isset($this->title) ? $this->out($this->title) : 'Sjonsite'); ?></title>
		<script type="text/javascript" src="/js/library.js"></script>
<?php
if (!Sjonsite::$auth->isGuest()) {
?>
		<script type="text/javascript" src="/js/system.js"></script>
<?php
}
?>
	</head>
	<body>
		<div id="container">
<?php
include 'messages.php';
?>