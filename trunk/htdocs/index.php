<?php

	/**
	 * Sjonsite - Main
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
	 * Class Sjonsite
	 *
	 * @package Sjonsite
	 */
	final class Sjonsite extends Sjonsite_Base {

		/**
		 * Process request
		 *
		 * @return void
		 */
		public function processRequest () {
			switch ($this->pathPart(1)) {
				case 'contact':
					$this->doContact();
					break;
				case 'search':
					$this->doSearch();
					break;
				default:
					$this->doPage();
					break;
			}
		}

		/**
		 * Handle contact request
		 *
		 * @return void
		 */
		protected function doContact () {
			try {
				$this->preparePage();
				$this->settings->remoteHash = sha1($_SERVER['REMOTE_ADDR'] . $this->settings->secretHash . $_SERVER['HTTP_USER_AGENT']);
				if ($this->ispost()) {

					$mail = $this->template('mail-contact', true);
				}
				// prepare data
				$this->template('page-contact');
			}
			catch (Exception $e) {
				$this->ex = $e;
				$this->template('system-error');
			}
		}

		/**
		 * Handle search request
		 *
		 * @return void
		 */
		protected function doSearch () {
			try {
				$this->preparePage();

				// prepare data
				$this->template('page-search');
			}
			catch (Exception $e) {
				$this->ex = $e;
				$this->template('system-error');
			}
		}

		/**
		 * Handle page request
		 *
		 * @return void
		 */
		protected function doPage () {
			try {
				$this->preparePage();

				// prepare data
				$this->template('page-content'); // page-gallery
			}
			catch (Exception $e) {
				$this->ex = $e;
				$this->template('system-error');
			}
		}

		/**
		 * Prepare page data
		 *
		 * @return void
		 */
		private function preparePage () {
			try {
				// Yes, three levels IS intentional
				$sql = 'SELECT p1.p_id AS p1_id, p1.p_pid AS p1_pid, p1.p_uri AS p1_uri, p1.p_title AS p1_title, p1.p_state AS p1_state, p2.p_id AS p2_id, p2.p_pid AS p2_pid, p2.p_uri AS p2_uri, p2.p_title AS p2_title, p2.p_state AS p2_state, p3.p_id AS p3_id, p3.p_pid AS p3_pid, p3.p_uri AS p3_uri, p3.p_title AS p3_title, p3.p_state AS p3_state FROM ' . SJONSITE_PDO_PREFIX . 'pages p1 LEFT JOIN ' . SJONSITE_PDO_PREFIX . 'pages p2 ON p2.p_pid = p1.p_id LEFT JOIN ' . SJONSITE_PDO_PREFIX . 'pages p3 ON p3.p_pid = p2.p_id WHERE p1.p_pid IS NULL ORDER BY p1.p_sorting, p2.p_sorting, p3.p_sorting';
				$res = $this->db->query($sql);
				$this->menuItems = array();
				while ($res && $row = $res->fetch(PDO::FETCH_ASSOC)) {
					if (empty($this->menuItems[$row['p1_id']])) {
						$this->menuItems[$row['p1_id']] = array(
							'parent' => $row['p1_pid'],
							'uri' => $row['p1_uri'],
							'title' => $row['p1_title'],
							'state' => $row['p1_state'],
							'children' => array()
						);
					}
					if (empty($this->menuItems[$row['p1_id']]['children'][$row['p2_id']])) {
						$this->menuItems[$row['p1_id']]['children'][$row['p2_id']] = array(
							'parent' => $row['p2_pid'],
							'uri' => $row['p2_uri'],
							'title' => $row['p2_title'],
							'state' => $row['p2_state'],
							'children' => array()
						);
					}
					if (empty($this->menuItems[$row['p1_id']]['children'][$row['p2_id']]['children'][$row['p3_id']])) {
						$this->menuItems[$row['p1_id']]['children'][$row['p2_id']]['children'][$row['p3_id']] = array(
							'parent' => $row['p3_pid'],
							'uri' => $row['p3_uri'],
							'title' => $row['p3_title'],
							'state' => $row['p3_state']
						);
					}
				}
				$res = null;
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
	new Sjonsite;

?>