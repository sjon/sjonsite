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
		 * Process request
		 *
		 * @return void
		 */
		public function processRequest () {
			// check auth
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
				case 'logout':
					$this->doLogout();
					break;
				default:
					$this->doAdmin();
					break;
			}
		}

		const authPages = 1;

		const authGallery = 2;

		const authUsers = 4;

		protected function checkAuth ($requiredLevel = 7) {
			if (empty($_SESSION['adminData'])) {
				$_SESSION['adminFlag'] = false;
				$_SESSION['adminData'] = new Sjonsite_UsersModel();
			}
			if ($this->param('authLogin')) {
				try {
					$sql = 'SELECT * FROM ' . SJONSITE_PDO_PREFIX . 'users WHERE u_email = ' . $this->db->quote($this->param('authLogin'));
					$res = $this->db->query($sql, PDO::FETCH_CLASS, 'Sjonsite_UsersModel');
					$row = $res->fetch(PDO::FETCH_CLASS);
					$res = null;
					if ($row->u_id > 0 && $row->u_passwd == sha1($this->param('authPasswd'))) {
						$_SESSION['adminData'] = $row;
					}
				}
				catch (Exception $e) {
					$this->ex = $e;
					$this->template('system-error');
				}
			}
			if ($_SESSION['adminData']->u_id > 0) {
				return ($_SESSION['adminData'] & $requiredLevel);
			}
			return false;
		}

		/**
		 * Handle adding pages
		 *
		 * @return void
		 */
		protected function doPagesAdd () {
			try {
				// prepare data
				$this->template('admin-pages-form');
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
				// prepare data
				$this->template('admin-pages-form');
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
				// prepare data
				$this->template('admin-message');
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
				// prepare data
				$this->template('admin-pages');
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
				// prepare data
				$this->template('admin-gallery-form');
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
				// prepare data
				$this->template('admin-gallery-form');
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
				// prepare data
				$this->template('admin-message');
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
				// prepare data
				$this->template('admin-gallery');
			}
			catch (Exception $e) {
				$this->ex = $e;
				$this->template('system-error');
			}
		}

		/**
		 * Handle adding users
		 *
		 * @return void
		 */
		protected function doUsersAdd () {
			try {
				// prepare data
				$this->template('admin-users-form');
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
				// prepare data
				$this->template('admin-users-form');
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
				// prepare data
				$this->template('admin-message');
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
				// prepare data
				$this->template('admin-users');
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
				// prepare data
				$this->template('admin-home');
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