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
		 * Query
		 *
		 * @var string
		 * @see doSearch()
		 */
		protected $searchQuery;

		/**
		 * Results
		 *
		 * @var array
		 * @see doSearch()
		 */
		protected $searchResults;

		/**
		 * Resultcount
		 *
		 * @var int
		 * @see doSearch()
		 */
		protected $searchCount;

		/**
		 * Current page
		 *
		 * @var int
		 * @see doSearch()
		 */
		protected $searchPage;

		/**
		 * Total pages
		 *
		 * @var int
		 * @see doSearch()
		 */
		protected $searchPages;

		/**
		 * Handle search request
		 *
		 * @return void
		 */
		protected function doSearch () {
			try {
				$this->preparePage();
				$this->searchCount = 0;
				$this->searchQuery = $this->param('q', null);
				$this->searchResults = array();
				$this->searchPage = $this->param('p', 1);
				$this->searchPages = 0;
				if (empty($this->settings->searchPerPage)) {
					$this->settings->insert($this->db, 'searchPerPage', 10);
				}
				if ($this->searchQuery) {
					$sql = 'SELECT COUNT(*) AS total FROM ' . SJONSITE_PDO_PREFIX . 'pages WHERE (p_title LIKE ' . $this->db->quote('%' . $this->searchQuery . '%') . ' OR p_summary LIKE ' . $this->db->quote('%' . $this->searchQuery . '%') . ') AND p_state = ' . $this->db->quote(Sjonsite_Model::ACTIVE);
					$res = $this->db->query($sql);
					$this->searchCount = $res->fetchColumn();
					$res = null;
					$this->searchPages = (floor($this->searchCount / $this->settings->searchPerPage) + ($this->searchCount % $this->settings->searchPerPage ? 1 : 0));
					if ($this->searchPage < 1 || $this->searchPage > $this->searchPages) {
						$this->searchPage = 1;
					}
					if ($this->searchCount > 0) {
						$sql = 'SELECT p_uri, p_title, p_summary FROM ' . SJONSITE_PDO_PREFIX . 'pages WHERE (p_title LIKE ' . $this->db->quote('%' . $this->searchQuery . '%') . ' OR p_summary LIKE ' . $this->db->quote('%' . $this->searchQuery . '%') . ') AND p_state = ' . $this->db->quote(Sjonsite_Model::ACTIVE) . ' LIMIT ' . ($this->searchPage * $this->settings->searchPerPage - $this->settings->searchPerPage) . ', ' . $this->settings->searchPerPage;
						$res = $this->db->query($sql);
						while ($res && $row = $res->fetch(PDO::FETCH_ASSOC)) {
							$this->searchResults[] = $row;
						}
						$res = null;
					}
				}
				$this->template('page-search');
			}
			catch (Exception $e) {
				$this->ex = $e;
				$this->template('system-error');
			}
		}

		/**
		 * PagesModel object
		 *
		 * @var Sjonsite_PagesModel
		 * @see doPage()
		 */
		protected $pagePage;

		/**
		 * GalleryModel object
		 *
		 * @var Sjonsite_GalleryModel
		 * @see doPage()
		 */
		protected $pageGallery;

		/**
		 * ImagesModel object array
		 *
		 * @var array
		 * @see doPage()
		 */
		protected $pageImages;

		/**
		 * Handle page request
		 *
		 * @return void
		 */
		protected function doPage () {
			try {
				$this->preparePage();
				$sql = 'SELECT * FROM ' . SJONSITE_PDO_PREFIX . 'pages p LEFT JOIN ' . SJONSITE_PDO_PREFIX . 'gallery g ON p.p_gallery = g.g_id WHERE p_uri = ' . $this->db->quote($this->request);
				$res = $this->db->query($sql);
				if ($res && $row = $res->fetch(PDO::FETCH_ASSOC)) {
					$res = null;
					$this->pagePage = new Sjonsite_PagesModel($row);
					$this->pageImages = array();
					if ($this->pagePage->p_gallery) {
						$this->pageGallery = new Sjonsite_GalleryModel($row);
						$sql = 'SELECT i_id, i_uri, i_title, i_width, i_height FROM ' . SJONSITE_PDO_PREFIX . 'images WHERE i_parent = ' . $this->db->quote(Sjonsite_Model::GALLERY) . ' AND i_parent_id = ' . $this->db->quote($this->pageGallery->g_id);
						$res = $this->db->query($sql);
						while ($res && $row = $res->fetch(PDO::FETCH_ASSOC)) {
							$this->pageImages[$row['i_id']] = new Sjonsite_ImagesModel($row);
						}
						$res = null;
						$this->template('page-gallery');
					}
					else {
						$sql = 'SELECT i_id, i_uri, i_title, i_width, i_height FROM ' . SJONSITE_PDO_PREFIX . 'images WHERE i_parent = ' . $this->db->quote(Sjonsite_Model::PAGE) . ' AND i_parent_id = ' . $this->db->quote($this->pagePage->p_id);
						$res = $this->db->query($sql);
						while ($res && $row = $res->fetch(PDO::FETCH_ASSOC)) {
							$this->pageImages[$row['i_id']] = new Sjonsite_ImagesModel($row);
						}
						$res = null;
						$this->template('page-content'); // page-gallery
					}
				}
				else {
					$res = null;
					throw new Exception('Page not found' . $sql, 404);
				}
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
					if (empty($this->menuItems[$row['p1_id']]) && $row['p1_id']) {
						$this->menuItems[$row['p1_id']] = array(
							'parent' => $row['p1_pid'],
							'uri' => $row['p1_uri'],
							'title' => $row['p1_title'],
							'state' => $row['p1_state'],
							'children' => array()
						);
					}
					if (empty($this->menuItems[$row['p1_id']]['children'][$row['p2_id']]) && $row['p2_id']) {
						$this->menuItems[$row['p1_id']]['children'][$row['p2_id']] = array(
							'parent' => $row['p2_pid'],
							'uri' => $row['p2_uri'],
							'title' => $row['p2_title'],
							'state' => $row['p2_state'],
							'children' => array()
						);
					}
					if (empty($this->menuItems[$row['p1_id']]['children'][$row['p2_id']]['children'][$row['p3_id']]) && $row['p3_id']) {
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

		/**
		 * Is the current user an admin user
		 *
		 * @return bool
		 */
		public function isAdmin () {
			return false;
		}

	}

	/**
	 * Run
	 */
	new Sjonsite();

?>