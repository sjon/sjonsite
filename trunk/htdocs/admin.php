<?php

	/**
	 * Sjonsite - Admin
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
	 * Load library
	 */
	require_once SJONSITE_INCLUDE . '/library.php';

	/**
	 * Class Sjonsite_Admin
	 *
	 * @package Sjonsite
	 */
	final class Sjonsite_Admin extends Sjonsite_Base {

		/**
		 * The action of the form element
		 *
		 * @var string
		 */
		protected $formAction;

		/**
		 * Any errors found with the form submission
		 *
		 * @var array
		 */
		protected $formErrors;

		/**
		 * Process request
		 *
		 * @return void
		 */
		public function processRequest () {
			$this->menuItems = array();
			switch ($this->pathPart(2)) {
				case 'pages':
					switch ($this->pathPart(3)) {
						case 'add':
							$this->doPagesAdd();
							break;
						case 'edit':
							$this->doPagesEdit();
							break;
						case 'remove':
							$this->doPagesRemove();
							break;
						default:
							$this->doPagesList();
							break;
					}
					break;
				case 'gallery':
					switch ($this->pathPart(3)) {
						case 'add':
							$this->doGalleryAdd();
							break;
						case 'edit':
							$this->doGalleryEdit();
							break;
						case 'remove':
							$this->doGalleryRemove();
							break;
						default:
							$this->doGalleryList();
							break;
					}
					break;
				case 'users':
					switch ($this->pathPart(3)) {
						case 'add':
							$this->doUsersAdd();
							break;
						case 'edit':
							$this->doUsersEdit();
							break;
						case 'remove':
							$this->doUsersRemove();
							break;
						default:
							$this->doUsersList();
							break;
					}
					break;
				case 'settings':
					$this->doSettings();
					break;
				case 'logout':
					$this->doLogout();
					break;
				default:
					$this->doAdmin();
					break;
			}
		}

		/**
		 * Has auth for pages
		 *
		 * @var int
		 * @see checkAuth()
		 */
		const authPages = 1;

		/**
		 * Has auth for pages
		 *
		 * @var int
		 * @see checkAuth()
		 */
		const authGallery = 2;

		/**
		 * Has auth for pages
		 *
		 * @var int
		 * @see checkAuth()
		 */
		const authUsers = 4;

		/**
		 * Has auth for settings
		 *
		 * @var int
		 * @see checkAuth()
		 */
		const authSettings = 8;

		/**
		 * Check authentication & authorization
		 * Returns true if the current user has & can
		 * Loads the admin-login template and returns false otherwise
		 *
		 * @param constant $requiredLevel
		 * @return bool
		 */
		protected function checkAuth ($requiredLevel = 7, $strict = true) {
			if (empty($_SESSION['adminData'])) {
				$_SESSION['adminFlag'] = false;
				$_SESSION['adminData'] = new Sjonsite_UsersModel();
			}
			if ($this->param('authLogin', false) !== false) {
				try {
					$sql = 'SELECT * FROM ' . SJONSITE_PDO_PREFIX . 'users WHERE u_email = ' . $this->db->quote($this->param('authLogin'));
					$res = $this->db->query($sql, PDO::FETCH_CLASS, 'Sjonsite_UsersModel');
					$row = $res->fetch(PDO::FETCH_CLASS);
					$res = null;
					if (($row instanceof Sjonsite_UsersModel) && ($row->u_id > 0) && ($row->u_passwd == sha1($this->param('authPasswd')))) {
						$_SESSION['adminFlag'] = true;
						$_SESSION['adminData'] = $row;
					}
					else {
						$this->setMessage('Invalid input', self::error);
						sleep(2);
					}
				}
				catch (Exception $e) {
					unset($_SESSION['adminData']);
					throw new Exception($e->getMessage(), $e->getCode());
				}
			}
			if ($_SESSION['adminData']->u_id > 0) {
				if (($_SESSION['adminData']->u_level & $requiredLevel) == $requiredLevel) {
					return true;
				}
				if (($strict === false) && (($_SESSION['adminData']->u_level & $requiredLevel) > 0)) {
					return true;
				}
			}
			$this->template('admin-login');
			return false;
		}

		/**
		 * Handle adding pages
		 *
		 * @return void
		 */
		protected function doPagesAdd () {
			try {
				if ($this->checkAuth(self::authPages)) {

					$this->template('admin-pages-form');
				}
			}
			catch (Exception $e) {
				$this->ex = $e;
				$this->template('system-error');
			}
		}

		/**
		 * Handle editing pages
		 *
		 * @return void
		 */
		protected function doPagesEdit () {
			try {
				if ($this->checkAuth(self::authPages)) {

					$this->template('admin-pages-form');
				}
			}
			catch (Exception $e) {
				$this->ex = $e;
				$this->template('system-error');
			}
		}

		/**
		 * Handle removing pages
		 *
		 * @return void
		 */
		protected function doPagesRemove () {
			try {
				if ($this->checkAuth(self::authPages)) {

					$this->template('admin-message');
				}
			}
			catch (Exception $e) {
				$this->ex = $e;
				$this->template('system-error');
			}
		}

		/**
		 * Handle listing pages
		 *
		 * @return void
		 */
		protected function doPagesList () {
			try {
				if ($this->checkAuth(self::authPages)) {
					$sql = 'SELECT p_id, p_uri, p_title, p_gallery, p_state FROM ' . SJONSITE_PDO_PREFIX . 'pages ORDER BY p_uri';
					$res = $this->db->query($sql, PDO::FETCH_CLASS, 'Sjonsite_PagesModel');
					$this->pagesList = array();
					while ($res && $row = $res->fetch(PDO::FETCH_CLASS)) {
						$this->pagesList[] = $row;
					}
					$res = null;
					$this->template('admin-pages');
				}
			}
			catch (Exception $e) {
				$this->ex = $e;
				$this->template('system-error');
			}
		}

		/**
		 * Handle adding galleries
		 *
		 * @return void
		 */
		protected function doGalleryAdd () {
			try {
				if ($this->checkAuth(self::authGallery)) {

					$this->template('admin-gallery-form');
				}
			}
			catch (Exception $e) {
				$this->ex = $e;
				$this->template('system-error');
			}
		}

		/**
		 * Handle editing galleries
		 *
		 * @return void
		 */
		protected function doGalleryEdit () {
			try {
				if ($this->checkAuth(self::authGallery)) {

					$this->template('admin-gallery-form');
				}
			}
			catch (Exception $e) {
				$this->ex = $e;
				$this->template('system-error');
			}
		}

		/**
		 * Handle removing galleries
		 *
		 * @return void
		 */
		protected function doGalleryRemove () {
			try {
				if ($this->checkAuth(self::authGallery)) {

					$this->template('admin-message');
				}
			}
			catch (Exception $e) {
				$this->ex = $e;
				$this->template('system-error');
			}
		}

		/**
		 * Handle listing galleries
		 *
		 * @return void
		 */
		protected function doGalleryList () {
			try {
				if ($this->checkAuth(self::authGallery)) {
					$sql = 'SELECT g_id, g_page, g_title FROM ' . SJONSITE_PDO_PREFIX . 'gallery ORDER BY g_title';
					$res = $this->db->query($sql, PDO::FETCH_CLASS, 'Sjonsite_GalleryModel');
					$this->galleryList = array();
					while ($res && $row = $res->fetch(PDO::FETCH_CLASS)) {
						$this->galleryList[] = $row;
					}
					$res = null;
					$this->template('admin-gallery');
				}
			}
			catch (Exception $e) {
				$this->ex = $e;
				$this->template('system-error');
			}
		}

		/**
		 * Userform data object
		 *
		 * @var Sjonsite_UsersModel
		 */
		protected $userformData;

		/**
		 * Handle adding users
		 *
		 * @return void
		 */
		protected function doUsersAdd () {
			try {
				if ($this->checkAuth(self::authUsers)) {
					$this->userformData = new Sjonsite_UsersModel();
					$this->formAction = 'add';
					$this->formErrors = array();
					if ($this->ispost()) {
					}
					$this->template('admin-users-form');
				}
			}
			catch (Exception $e) {
				$this->ex = $e;
				$this->template('system-error');
			}
		}

		/**
		 * Handle editing users
		 *
		 * @return void
		 */
		protected function doUsersEdit () {
			try {
				if ($this->checkAuth(self::authUsers)) {
					$this->userformData = new Sjonsite_UsersModel();
					$sql = 'SELECT * FROM ' . SJONSITE_PDO_PREFIX . 'users WHERE u_id = ' . $this->db->quote($this->pathPart(4));
					$res = $this->db->query($sql, PDO::FETCH_CLASS, 'Sjonsite_UsersModel');
					if ($res && $this->userformData = $res->fetch(PDO::FETCH_CLASS)) {
						$this->formAction = 'edit/' . $this->userformData->u_id;
						$this->formErrors = array();
						if ($this->ispost()) {
							$this->userformData->u_name = $this->param('u_name');
							if (empty($this->userformData->u_name)) {
								$this->formErrors['u_name'] = true;
								$this->setMessage('You need to fill in a name!', self::error);
							}
							$this->userformData->u_email = $this->param('u_email');
							if (!$this->isemail($this->userformData->u_email)) {
								$this->formErrors['u_email'] = true;
								$this->userformData->u_email = null;
								$this->setMessage('You need to fill in an email!', self::error);
							}
							// check email is unique
							if ($this->param('u_passwd')) {
								$passwd = $this->param('u_passwd');
								$passwd_check = $this->param('u_passwd_check');
								if (empty($passwd_check) || ($passwd != $passwd_check)) {
									$this->formErrors['u_email'] = true;
									$this->setMessage('If you want to change the password, fill it out the same twice!', self::error);
								}
								else {
									$this->userformData->u_passwd = sha1($passwd);
								}
							}
							$u_level = $this->param('u_level');
							$level = 0;
							if (is_array($u_level) && count($u_level)) {
								foreach ($u_level as $value) {
									$level += $value;
								}
							}
							if ($level == 0) {
								$this->formErrors['u_level'] = true;
								$this->setMessage('A user needs at least one level of authorisation!', self::error);
							}
							else {
								$this->userformData->u_level = $level;
							}
							$this->userformData->u_state = $this->param('u_state');
							// update
						}
						$this->template('admin-users-form');
					}
					else {
						throw new Exception('Unknown user selected');
					}
				}
			}
			catch (Exception $e) {
				$this->ex = $e;
				$this->template('system-error');
			}
		}

		/**
		 * Handle removing users
		 *
		 * @return void
		 */
		protected function doUsersRemove () {
			try {
				if ($this->checkAuth(self::authUsers)) {

					$this->template('admin-message');
				}
			}
			catch (Exception $e) {
				$this->ex = $e;
				$this->template('system-error');
			}
		}

		/**
		 * Handle listing users
		 *
		 * @return void
		 */
		protected function doUsersList () {
			try {
				if ($this->checkAuth(self::authUsers)) {
					$sql = 'SELECT u_id, u_name, u_email, u_level, u_state FROM ' . SJONSITE_PDO_PREFIX . 'users ORDER BY u_name';
					$res = $this->db->query($sql, PDO::FETCH_CLASS, 'Sjonsite_UsersModel');
					$this->usersList = array();
					while ($res && $row = $res->fetch(PDO::FETCH_CLASS)) {
						$this->usersList[] = $row;
					}
					$res = null;
					$this->template('admin-users');
				}
			}
			catch (Exception $e) {
				$this->ex = $e;
				$this->template('system-error');
			}
		}

		/**
		 * Handle settings page
		 *
		 * @return void
		 */
		protected function doSettings () {
			try {
				if ($this->checkAuth(self::authSettings)) {
					if ($this->ispost()) {
						foreach ($this->settings->getAll() as $name => $value) {
							if ($this->param($name) != $value) {
								$this->settings->update($this->db, $name, $this->param($name));
							}
						}
						$this->setMessage('Settings updated');
					}
					$this->template('admin-settings');
				}
			}
			catch (Exception $e) {
				$this->ex = $e;
				$this->template('system-error');
			}
		}

		/**
		 * Handle admin logout
		 *
		 * @return void
		 */
		protected function doLogout () {
			$_SESSION['adminFlag'] = false;
			$_SESSION['adminData'] = null;
			$this->redirect('/');
		}

		/**
		 * Handle admin home
		 *
		 * @return void
		 */
		protected function doAdmin () {
			try {
				if ($this->checkAuth((self::authPages | self::authGallery | self::authUsers), false)) {
					$sql = 'SELECT p_state, COUNT(*) AS total FROM ' . SJONSITE_PDO_PREFIX . 'pages GROUP BY p_state';
					$res = $this->db->query($sql);
					$this->pagesCount = $res->fetch(PDO::FETCH_ASSOC);
					$res = null;
					$sql = 'SELECT COUNT(*) AS total FROM ' . SJONSITE_PDO_PREFIX . 'gallery';
					$res = $this->db->query($sql);
					$this->galleryCount = $res->fetch(PDO::FETCH_ASSOC);
					$res = null;
					$sql = 'SELECT i_parent, COUNT(*) AS total FROM ' . SJONSITE_PDO_PREFIX . 'images GROUP BY i_parent';
					$res = $this->db->query($sql);
					$this->imagesCount = $res->fetch(PDO::FETCH_ASSOC);
					$res = null;
					$sql = 'SELECT u_state, COUNT(*) AS total FROM ' . SJONSITE_PDO_PREFIX . 'users GROUP BY u_state';
					$res = $this->db->query($sql);
					$this->adminPages = $res->fetch(PDO::FETCH_ASSOC);
					$res = null;
					$this->template('admin-home');
				}
			}
			catch (Exception $e) {
				$this->ex = $e;
				$this->template('system-error');
			}
		}

		/**
		 * Is the current user an admin user
		 *
		 * @return bool
		 */
		public function isAdmin () {
			return $_SESSION['adminFlag'];
		}

	}

	/**
	 * Run
	 */
	new Sjonsite_Admin();

?>