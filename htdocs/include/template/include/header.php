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
		<meta name="description" content="<?php echo (isset($this->description) ? $this->out($this->description) : null); ?>" />
		<meta name="keywords" content="<?php echo (isset($this->keywords) ? $this->out($this->keywords) : null); ?>" />
		<link rel="stylesheet" type="text/css" media="screen" href="/css/screen.css" />
		<link rel="stylesheet" type="text/css" media="print" href="/css/print.css" />
		<script type="text/javascript" src="/js/jquery-1.2.6.pack.js"></script>
		<script type="text/javascript" src="/js/library.js"></script>
		<title><?php echo (isset($this->title) ? $this->out($this->title) : 'Sjonsite'); ?></title>
	</head>
	<body>
		<div id="container">
