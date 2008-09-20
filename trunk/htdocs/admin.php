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
			$this->requiredLevel = $requiredLevel;
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
		 * Pagesform data object
		 *
		 * @var Sjonsite_PagesModel
		 */
		protected $pagesformData;

		/**
		 * Handle adding pages
		 *
		 * @return void
		 */
		protected function doPagesAdd () {
			try {
				if ($this->checkAuth(self::authPages)) {
					$this->pageformData = new Sjonsite_PagesModel();
					$this->pageformData->p_pid = $this->param('p_pid', null);
					$this->pageformData->p_title = $this->param('p_title', null);
					$this->formAction = 'add';
					$this->formErrors = array();
					if ($this->ispost()) {
						$type = $this->param('type', 'content');
						$sql = 'SELECT MAX(p_sorting) AS sort FROM ' . SJONSITE_PDO_PREFIX . 'pages WHERE p_pid ' . (is_null($this->pageformData->p_pid) ? 'IS NULL' : '= ' . $this->db->quote($this->pageformData->p_pid));
						$res = $this->db->query($sql);
						$this->pageformData->p_sorting = ($res->fetchColumn() + 1);
						$res = null;
						if ($type == 'gallery') {
							$sql = 'INSERT INTO ' . SJONSITE_PDO_PREFIX . 'gallery (g_id, g_page, g_title, g_summary) VALUES (NULL, NULL, :title, NULL)';
							$res = $this->db->prepare($sql);
							$res->execute(array(
								':title' => 'Gallery for ' . $this->pageformData->p_title
							));
							$this->pageformData->p_gallery = $this->db->lastInsertId();
							$res = null;
						}
						$uri = null;
						if (!is_null($this->pageformData->p_pid)) {
							$sql = 'SELECT p_uri AS uri FROM ' . SJONSITE_PDO_PREFIX . 'pages WHERE p_id = ' . $this->db->quote($this->pageformData->p_pid);
							$res = $this->db->query($sql);
							$uri = $res->fetchColumn();
							$res = null;
						}
						$uri .= '/' . $this->normalize($this->pageformData->p_title);
						$sql = 'INSERT INTO ' . SJONSITE_PDO_PREFIX . 'pages (p_id, p_pid, p_uri, p_title, p_gallery, p_sorting, p_state) VALUES (NULL, :pid, :uri, :title, :gallery, :sorting, :state)';
						$res = $this->db->prepare($sql);
						$res->execute(array(
							':pid' => $this->pageformData->p_pid,
							':uri' => $uri,
							':title' => $this->pageformData->p_title,
							':gallery' => $this->pageformData->p_gallery,
							':sorting' => $this->pageformData->p_sorting,
							':state' => Sjonsite_Model::SUSPENDED
						));
						$this->pageformData->p_id = $this->db->lastInsertId();
						$res = null;
						if ($this->pageformData->p_gallery > 0) {
							$sql = 'UPDATE ' . SJONSITE_PDO_PREFIX . 'gallery SET g_page = ' . $this->db->quote($this->pageformData->p_id) . ' WHERE g_id = ' . $this->db->quote($this->pageformData->p_gallery);
							$res = $this->db->exec($sql);
						}
						$this->setMessage('Page added');
						$this->redirect('/admin/pages/edit/' . $this->pageformData->p_id);
					}
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
					$this->pageformData = new Sjonsite_PagesModel();
					$sql = 'SELECT * FROM ' . SJONSITE_PDO_PREFIX . 'pages WHERE p_id = ' . $this->db->quote($this->pathPart(4));
					$res = $this->db->query($sql, PDO::FETCH_CLASS, 'Sjonsite_PagesModel');
					if ($res && $this->pageformData = $res->fetch(PDO::FETCH_CLASS)) {
						$this->formAction = 'edit/' . $this->pageformData->p_id;
						$this->formErrors = array();
						if ($this->ispost()) {
						}
						$this->template('admin-pages-form');
					}
					else {
						throw new Exception('Unknown page selected');
					}
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
					$sql = 'SELECT p_id, p_pid, p_uri, p_title, p_gallery, p_state FROM ' . SJONSITE_PDO_PREFIX . 'pages ORDER BY p_uri';
					$res = $this->db->query($sql, PDO::FETCH_CLASS, 'Sjonsite_PagesModel');
					$this->pagesList = array();
					while ($res && $row = $res->fetch(PDO::FETCH_CLASS)) {
						$this->pagesList[$row->p_id] = $row;
					}
					$res = null;
					foreach ($this->pagesList as $pid => $pdata) {
						$this->pagesList[$pid]->indent = ($pdata->p_pid ? ($this->pagesList[$pdata->p_pid]->p_pid ? ($this->pagesList[$this->pagesList[$pdata->p_pid]->p_pid]->p_pid ? 3 : 2) : 1) : 0);
					}
					$this->template('admin-pages');
				}
			}
			catch (Exception $e) {
				$this->ex = $e;
				$this->template('system-error');
			}
		}

		/**
		 * Galleryform data object
		 *
		 * @var Sjonsite_GalleryModel
		 */
		protected $galleryformData;

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
					$sql = '
SELECT
	g_id,
	p.p_title,
	g_title,
	COUNT(i.i_id) AS i_count
FROM
	' . SJONSITE_PDO_PREFIX . 'gallery g
	LEFT JOIN
	' . SJONSITE_PDO_PREFIX . 'pages p ON p.p_id = g.g_page
	LEFT JOIN
	' . SJONSITE_PDO_PREFIX . 'images i ON i.i_parent_id = g.g_id
WHERE
	(i.i_parent = ' . $this->db->quote(Sjonsite_Model::GALLERY) . ' OR i.i_parent IS NULL)
ORDER BY
	g_title';
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
						$this->doUsersValidate(true);
						if (count($this->formErrors) == 0) {
							$sql = 'INSERT INTO ' . SJONSITE_PDO_PREFIX . 'users (u_id, u_name, u_email, u_passwd, u_level, u_state) VALUES (NULL, :name, :email, :passwd, :level, :state)';
							$res = $this->db->prepare($sql);
							if ($res->execute(array(
								':name' => $this->userformData->u_name,
								':email' => $this->userformData->u_email,
								':passwd' => $this->userformData->u_passwd,
								':level' => $this->userformData->u_level,
								':state' => $this->userformData->u_state,
							))) {
								$this->setMessage('User &lsquo;' . $this->out($this->userformData->u_name) . '&rsquo; added');
								$this->redirect('/admin/users');
							}
							else {
								$this->setMessage('Error adding user &lsquo;' . $this->out($this->userformData->u_name) . '&rsquo;', self::error);
							}
						}
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
							$this->doUsersValidate();
							if (count($this->formErrors) == 0) {
								$sql = 'UPDATE ' . SJONSITE_PDO_PREFIX . 'users SET u_name = :name, u_email = :email, u_passwd = :passwd, u_level = :level, u_state = :state WHERE u_id = :id';
								$res = $this->db->prepare($sql);
								if ($res->execute(array(
									':id' => $this->userformData->u_id,
									':name' => $this->userformData->u_name,
									':email' => $this->userformData->u_email,
									':passwd' => $this->userformData->u_passwd,
									':level' => $this->userformData->u_level,
									':state' => $this->userformData->u_state,
								))) {
									$this->setMessage('User &lsquo;' . $this->out($this->userformData->u_name) . '&rsquo; updated');
									$this->redirect('/admin/users');
								}
								else {
									$this->setMessage('Error updating user &lsquo;' . $this->out($this->userformData->u_name) . '&rsquo;', self::error);
								}
							}
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
		 * Validate users input
		 *
		 * @param bool $requirePassword
		 * @return void
		 */
		private function doUsersValidate ($requirePassword = false) {
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
			if ($this->isemail($this->userformData->u_email)) {
				$sql = 'SELECT COUNT(*) AS total FROM ' . SJONSITE_PDO_PREFIX . 'users WHERE u_email = ' . $this->db->quote($this->userformData->u_email);
				if ($this->userformData->u_id > 0) {
					$sql .= ' AND u_id <> ' . $this->db->quote($this->userformData->u_id);
				}
				$res = $this->db->query($sql);
				if ($res && $res->fetchColumn() > 0) {
					$this->formErrors['u_email'] = true;
					$this->setMessage('The email is already in use!', self::error);
				}
			}
			$passwd = $this->param('u_passwd');
			$passwd_check = $this->param('u_passwd_check');
			if ($requirePassword && empty($passwd)) {
				$this->formErrors['u_passwd'] = true;
				$this->setMessage('You need to fill in a password!', self::error);
			}
			if ($passwd && ($passwd != $passwd_check)) {
				$this->formErrors['u_passwd'] = true;
				$this->setMessage('You need to fill out the same password twice!', self::error);
			}
			if ($passwd && ($passwd == $passwd_check)) {
				$this->userformData->u_passwd = sha1($passwd);
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
		}

		/**
		 * Handle removing users
		 *
		 * @return void
		 */
		protected function doUsersRemove () {
			try {
				if ($this->checkAuth(self::authUsers)) {
					$this->userformData = new Sjonsite_UsersModel();
					$sql = 'SELECT * FROM ' . SJONSITE_PDO_PREFIX . 'users WHERE u_id = ' . $this->db->quote($this->pathPart(4));
					$res = $this->db->query($sql, PDO::FETCH_CLASS, 'Sjonsite_UsersModel');
					if ($res && $this->userformData = $res->fetch(PDO::FETCH_CLASS)) {
						if ($this->ispost()) {
							if ($this->param('sure', false) == true) {
								$sql = 'DELETE FROM ' . SJONSITE_PDO_PREFIX . 'users WHERE u_id = ' . $this->db->quote($this->userformData->u_id);
								$res = $this->db->query($sql);
								if ($res && $res->rowCount() == 1) {
									$this->setMessage('User removed', self::info);
								}
								else {
									$this->setMessage('Error removing user', self::error);
								}
							}
							else {
								$this->setMessage('Skipped removing user', self::info);
							}
							$this->redirect('/admin/users');
						}
						$this->formType = 'remove';
						$this->formAction = '/admin/users/remove/' . $this->userformData->u_id;
						$this->formData = array(
							'title' => 'Remove user',
							'question' => 'Are you sure you want to remove user &lsquo;' . $this->out($this->userformData->u_name) . '&rsquo;?'
						);
						$this->template('admin-message');
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
		 * Handle listing users
		 *
		 * @return void
		 */
		protected function doUsersList () {
			try {
				if ($this->checkAuth(self::authUsers)) {
					$sql = 'SELECT u_id, u_name, u_email, u_level, u_state FROM ' . SJONSITE_PDO_PREFIX . 'users ORDER BY u_id';
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