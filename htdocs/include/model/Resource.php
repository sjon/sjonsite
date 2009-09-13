<?php

	/**
	 * Sjonsite - ResourceModel
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @package Sjonsite
	 * @copyright Sjon's dotCom 2008
	 * @license Mozilla Public License 1.1
	 * @version $Id: index.php 32 2008-09-20 14:51:49Z sjonscom $
	 */

	/**
	 * ResourceModel
	 *
	 * @package Sjonsite
	 */
	class Sjonsite_Resource extends Sjonsite_Model {

		/**
		 * @var int
		 */
		protected $id;

		/**
		 * @var int
		 */
		protected $parent;

		/**
		 * @var string
		 */
		protected $trail;

		/**
		 * @var string
		 */
		protected $type;

		/**
		 * @var string
		 */
		protected $controller;

		/**
		 * @var int
		 */
		protected $sorting;

		/**
		 * @var constant
		 */
		protected $visible;

		/**
		 * @var constant
		 */
		protected $state;

		/**
		 * @var string
		 */
		protected $_tablename = 'resources';

		/**
		 * @var array
		 */
		protected $_structure = array(
			'id' => array('int', array('min' => 0, 'max' => 1000000)),
			'parent' => array('int', array('min' => 0, 'max' => 1000000)),
			'trail' => array('string', array('type' => 'any', 'length' => 16)),
			'type' => array('string', array('type' => 'any', 'length' => 128)),
			'controller' => array('string', array('type' => 'any', 'length' => 16, 'default' => 'resource')),
			'sorting' => array('int', array('min' => 0, 'max' => 1000)),
			'visible' => array('enum', array('items' => array('Y','N'), 'default' => 'Y')),
			'state' => array('enum', array('items' => array('A','S','R','U'), 'default' => 'U'))
		);

	}

