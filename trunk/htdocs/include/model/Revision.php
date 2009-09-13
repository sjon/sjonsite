<?php

	/**
	 * Sjonsite - RevisionModel
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @package Sjonsite
	 * @copyright Sjon's dotCom 2008
	 * @license Mozilla Public License 1.1
	 * @version $Id$
	 */

	/**
	 * RevisionModel
	 *
	 * @package Sjonsite
	 */
	class Sjonsite_Revision extends Sjonsite_Model {

		/**
		 * @var int
		 */
		protected $id;

		/**
		 * @var int
		 */
		protected $resource;

		/**
		 * @var int
		 */
		protected $revision;

		/**
		 * @var string
		 */
		protected $uri;

		/**
		 * @var string
		 */
		protected $short;

		/**
		 * @var string
		 */
		protected $title;

		/**
		 * @var string
		 */
		protected $content;

		/**
		 * @var string
		 */
		protected $_tablename = 'revisions';

		/**
		 * @var array
		 */
		protected $_structure = array(
			'id' => array('int', array('min' => 0, 'max' => 100000000)),
			'resource' => array('int', array('min' => 0, 'max' => 1000000)),
			'revision' => array('int', array('min' => 0, 'max' => 1000)),
			'uri' => array('string', array('type' => 'any', 'length' => 255)),
			'short' => array('string', array('type' => 'any', 'length' => 64)),
			'title' => array('string', array('type' => 'any', 'length' => 255)),
			'content' => array('string', array('type' => 'any', 'length' => 65536)),
		);

	}

