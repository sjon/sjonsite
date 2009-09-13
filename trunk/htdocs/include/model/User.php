<?php

	/**
	 * Sjonsite - UserModel
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @package Sjonsite
	 * @copyright Sjon's dotCom 2008
	 * @license Mozilla Public License 1.1
	 * @version $Id: index.php 32 2008-09-20 14:51:49Z sjonscom $
	 */

	/**
	 * UserModel
	 *
	 * @package Sjonsite
	 */
	class Sjonsite_User extends Sjonsite_Model {

		/**
		 * @var int
		 */
		protected $id;

		/**
		 * @var string
		 */
		protected $name;

		/**
		 * @var string
		 */
		protected $email;

		/**
		 * @var string
		 */
		protected $passwd;

		/**
		 * @var int
		 */
		protected $level;

		/**
		 * @var constant
		 */
		protected $state;

		/**
		 * @var string
		 */
		protected $_tablename = 'users';

		/**
		 * @var array
		 */
		protected $_structure = array(
			'id' => array('int', array('min' => 0, 'max' => 1000)),
			'name' => array('string', array('type' => 'any', 'length' => 255)),
			'email' => array('string', array('type' => 'email', 'length' => 255)),
			'passwd' => array('string', array('type' => 'any', 'length' => 40)),
			'level' => array('int', array('min' => 0, 'max' => 128)),
			'state' => array('enum', array('items' => array('A','S','R','U'), 'default' => 'U'))
		);

	}

