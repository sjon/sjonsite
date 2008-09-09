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
				default:
					$this->doAdmin();
					break;
			}
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

	}

	/**
	 * Run
	 */
	new Sjonsite_Admin;

?>