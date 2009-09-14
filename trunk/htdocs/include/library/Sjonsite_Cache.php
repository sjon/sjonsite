<?php

	/**
	 * Sjonsite - Cache Class
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @package Sjonsite
	 * @copyright Sjon's dotCom 2008
	 * @license Mozilla Public License 1.1
	 * @version $Id$
	 */

	/**
	 * Class Sjonsite_Cache
	 *
	 * @package Sjonsite
	 */
	class Sjonsite_Cache {

		/**
		 * Cache Hits counter
		 *
		 * @var int
		 */
		protected static $hits = 0;

		/**
		 * Retrieve cached data
		 *
		 * @param string $token
		 * @param int $ttl
		 * @return Sjonsite_Cache_Data
		 */
		public static function get ($token, $ttl = 0) {
			self::$hits++;
			$rv = new Sjonsite_Cache_Data($token, null, $ttl);
			$filename = SJONSITE_INCLUDE . '/cache/' . sha1($rv->getToken());
			if (file_exists($filename)) {
				$rv->setData(unserialize(file_get_contents($filename)));
			}
			return $rv;
		}

		/**
		 * Store data to the cache
		 *
		 * @param Sjonsite_Cache_Data $cacheData
		 * @return bool
		 */
		public static function set (Sjonsite_Cache_Data $cacheData) {
			self::$hits--;
			$filename = SJONSITE_INCLUDE . '/cache/' . sha1($cacheData->getToken());
			return (bool) file_put_contents($filename, serialize($cacheData->getData()), LOCK_EX);
		}

		/**
		 * Return the number of hits on the cache
		 * Needs to be called after a possible set() to be accurate
		 *
		 * @return int
		 */
		public static function getHits () {
			return self::$hits;
		}

	}

	/**
	 * Class Sjonsite_Cache_Data
	 *
	 * @package Sjonsite
	 */
	class Sjonsite_Cache_Data {

		/**
		 * The unique cache token
		 *
		 * @var string
		 */
		protected $token;

		/**
		 * The cached data
		 *
		 * @var mixed
		 */
		protected $data;

		/**
		 * The time to live, zero for infinite
		 *
		 * @var int
		 */
		protected $ttl;

		/**
		 * Constructor
		 *
		 * @param string $token
		 * @param int $ttl
		 * @return Sjonsite_Cache_Data
		 */
		public function __construct ($token, $ttl = 0) {
			$this->token = $token;
			$this->data = null;
			$this->ttl = $ttl;
		}

		/**
		 * Return the unique cache token
		 *
		 * @return string
		 */
		public function getToken () {
			return $this->token;
		}

		/**
		 * Return the cached data
		 *
		 * @return mixed
		 */
		public function getData () {
			return $this->data;
		}

		/**
		 * Assign the cached data
		 *
		 * @param mixed $data
		 * @return void
		 */
		public function setData ($data) {
			$this->data = $data;
		}

		/**
		 * Returns true if there is any data and it hasnt expired
		 *
		 * @return bool
		 */
		public function isValid () {
			return (!empty($this->data) && !$this->isExpired());
		}

		/**
		 * Returns true if the data has expired its ttl
		 *
		 * @return bool
		 */
		public function isExpired () {
			return (($this->ttl != 0) && ($this->ttl < time()));
		}

	}

	/**
	 * Class Sjonsite_CacheException
	 *
	 * @package Sjonsite
	 */
	class Sjonsite_CacheException extends Sjonsite_Exception {}

