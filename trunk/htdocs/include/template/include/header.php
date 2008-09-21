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
<?php
	if ($this->isAdmin()) {
?>
		<link rel="stylesheet" type="text/css" media="screen" href="/css/screen-admin.css" />
		<link rel="stylesheet" type="text/css" media="print" href="/css/print.css" />
		<title><?php echo (isset($this->title) ? $this->out($this->title) : 'Sjonsite Admin'); ?></title>
		<script type="text/javascript" src="/js/jquery-1.2.6.pack.js"></script>
		<script type="text/javascript" src="/js/jquery-wymeditor-0.5.a.pack.js"></script>
		<script type="text/javascript" src="/js/library-admin.js"></script>
		<link rel="icon" type="image/x-icon" href="/img/sjonsite.ico" />
		<link rel="shortcut icon" type="image/x-icon" href="/img/sjonsite.ico" />
<?php
	}
	else {
?>
		<meta name="description" content="<?php echo (isset($this->description) ? $this->out($this->description) : null); ?>" />
		<meta name="keywords" content="<?php echo (isset($this->keywords) ? $this->out($this->keywords) : null); ?>" />
		<link rel="stylesheet" type="text/css" media="screen" href="/css/screen.css" />
		<link rel="stylesheet" type="text/css" media="print" href="/css/print.css" />
		<title><?php echo (isset($this->title) ? $this->out($this->title) : 'Sjonsite'); ?></title>
		<script type="text/javascript" src="/js/jquery-1.2.6.pack.js"></script>
		<script type="text/javascript" src="/js/library.js"></script>
		<link rel="icon" type="image/x-icon" href="/favicon.ico" />
		<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico" />
<?php
	}
?>
	</head>
	<body>
		<div id="container">
