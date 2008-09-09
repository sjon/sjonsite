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
		 * Handle Admin Home
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