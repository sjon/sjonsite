<?php

	/**
	 * Sjonsite - Auth Class
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @package Sjonsite
	 * @copyright Sjon's dotCom 2008
	 * @license Mozilla Public License 1.1
	 * @version $Id$
	 */

	/**
	 * Class Sjonsite_Auth
	 *
	 * @package Sjonsite
	 */
	class Sjonsite_Auth {

		/**
		 * User Role
		 * @var int
		 */
		const ROLE_USER = 1;

		/**
		 * Editor Role
		 * @var int
		 */
		const ROLE_EDITOR = 3;

		/**
		 * Moderator Role
		 * @var int
		 */
		const ROLE_MODERATOR = 7;

		/**
		 * Administrator Role
		 * @var int
		 */
		const ROLE_ADMINISTRATOR = 15;

		/**
		 * View revisions
		 * @var int
		 */
		const ACT_VIEW = 16;

		/**
		 * Edit revisions
		 * @var int
		 */
		const ACT_EDIT = 32;

		/**
		 * Delete revisions
		 * @var int
		 */
		const ACT_DELETE = 64;

		/**
		 * List resources
		 * @var int
		 */
		const ACT_LIST = 128;

		/**
		 * Add resources
		 * @var int
		 */
		const ACT_ADD = 256;

		/**
		 * Remove resources
		 * @var int
		 */
		const ACT_REMOVE = 512;

		/**
		 * Sjonsite_User object
		 *
		 * @var Sjonsite_User
		 */
		protected $user;

		/**
		 * Security token
		 *
		 * @var string
		 */
		protected $token;

		/**
		 * Constructor
		 */
		public function __construct () {
			$this->user = new Sjonsite_User(isset($_SESSION['authUser']) ? $_SESSION['authUser'] : null);
			$this->token = (isset($_SESSION['authToken']) ? $_SESSION['authToken'] : $this->genToken());
		}

		/**
		 * Check if the supplied credentials match a valid user
		 *
		 * @param string $user
		 * @param string $pass
		 * @return void
		 */
		public function checkAuth ($user, $pass) {
			$_SESSION['authUser'] = array();
			$_SESSION['authToken'] = $this->token;
			$sql = 'SELECT id, name, email, level, state FROM %prefix%users WHERE email = :email AND passwd = :passwd ORDER BY level ASC LIMIT 0, 1';
			$res = Sjonsite::$db->prepare($sql);
			if ($res->execute(array(
				':email' => Sjonsite::$io->post($user),
				':passwd' => sha1(Sjonsite::$io->post($pass))
			))) {
				$row = $res->fetch(PDO::FETCH_ASSOC);
				if (is_array($row) && isset($row['id'])) {
					foreach ($row as $k => $v) {
						$this->user->$k = $v;
						$_SESSION['authUser'][$k] = $v;
					}
				}
			}
			$res = null;
		}

		/**
		 * Returns true if the current user is not authenticated
		 *
		 * @return bool
		 */
		public function isGuest () {
			if ($this->checkToken()) {
				if ($this->user->id > 0) {
					return false;
				}
			}
			return true;
		}

		/**
		 * Returns true if the current user is an authenticated and active user
		 * If both isGuest() and isValid() return false, the user is not active!
		 *
		 * @return bool
		 */
		public function isValid () {
			if ($this->checkToken()) {
				if ($this->user->id > 0) {
					return ($this->user->state == Sjonsite_Model::ACTIVE);
				}
			}
			return false;
		}

		/**
		 * Returns true if the current user is valid, and has the appropriate level
		 * Roles are cumulative (eg: editor is also user), actions are precise.
		 *
		 * @param int $role
		 * @param int $action
		 * @return bool
		 */
		public function isAuthorized ($role, $action) {
			if ($this->isValid()) {
				if ((($this->user->level & $role) == $role) && (($this->user->level & $action) == $action)) {
					return true;
				}
			}
			return false;
		}

		/**
		 * Check if the stored token matches a freshly generated one
		 *
		 * @return bool
		 */
		private function checkToken () {
			return ($this->token == $this->genToken());
		}

		/**
		 * Generate a unique token based on include dir, user agent, and remote address
		 *
		 * @return string
		 */
		private function genToken () {
			$token = 'SJONSITE:' . filter_input(INPUT_SERVER, 'HTTP_USER_AGENT', FILTER_SANITIZE_STRING) . ':' . SJONSITE_INCLUDE . ':' . filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_SANITIZE_STRING) . ':SJONSITE';
			return sha1($token);
		}

		/**
		 * Return the user's id
		 *
		 * @return int
		 */
		public function userId () {
			return $this->user->id;
		}

		/**
		 * Return the user's name
		 *
		 * @return string
		 */
		public function userName () {
			return $this->user->name;
		}

		/**
		 * Return the user's email
		 *
		 * @return string
		 */
		public function userEmail () {
			return $this->user->email;
		}

		/**
		 * Return the user's level
		 *
		 * @return int
		 */
		public function userLevel () {
			return $this->user->level;
		}

		/**
		 * Return the user's state
		 *
		 * @return constant
		 * @see Sjonsite_Model
		 */
		public function userState () {
			return $this->user->state;
		}

	}

	/**
	 * Class Sjonsite_AuthException
	 *
	 * @package Sjonsite
	 */
	class Sjonsite_AuthException extends Sjonsite_Exception {}

