<?php

	/**
	 * Sjonsite - ResourceController
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @package Sjonsite
	 * @copyright Sjon's dotCom 2008
	 * @license Mozilla Public License 1.1
	 * @version $Id$
	 */

	/**
	 * Class Sjonsite_ResourceController
	 *
	 * @package Sjonsite
	 */
	class Sjonsite_ResourceController extends Sjonsite_Controller {

		/**
		 * Menu Tree
		 *
		 * @var array
		 */
		protected $menuTree;

		/**
		 * Process Request
		 *
		 * @return void
		 */
		public function processRequest () {
			/**
			 * Request can be anything, from a resource (uploaded image) to
			 * a full-fledged page.
			 *
			 * Resource:
			 * 'id' => 1,
			 * 'parent' => NULL,
			 * 'trail' => '1',
			 * 'type' => 'text/html',
			 * 'controller' => 'resource',
			 * 'sorting' => 1,
			 * 'visible' => 'Y',
			 * 'state' => 'A',
			 *
			 * Revision:
			 * 'id' => 1,
			 * 'resource' => 1,
			 * 'revision' => 1,
			 * 'uri' => '/',
			 * 'short' => 'Home',
			 * 'title' => 'Homepage',
			 * 'content' => '',
			 * 'state' => 'A',
			 */
			$resourceHash = sha1($this->resource->id . '-' . $this->revision->uri . '-' . $this->revision->id);
			$resourceFile = SJONSITE_INCLUDE . '/resource/' . substr($resourceHash, 0, 1) . '/' . $resourceHash;
			if (empty($this->revision->content) && file_exists($resourceFile)) {
				Sjonsite::$request->setType($this->resource->type);
				Sjonsite::$request->setContent(file_get_contents($resourceFile));
			}
			else {
				$templateParts = explode('/', $this->revision->uri);
				$templateParts[0] = 'resource';
				$template = null;
				while (empty($template) && count($templateParts)) {
					try {
						$template = Sjonsite::template(implode('-', $templateParts));
					} catch (Sjonsite_Exception $e) {
						$e = null;
						array_pop($templateParts);
					}
				}
				if (empty($template)) {
					$template = Sjonsite::template('resource-generic');
				}
				$this->buildMenuTree();
				$this->displayTemplate($template);
			}
		}

		/**
		 * Build our menuTree
		 *
		 * @return void
		 */
		protected function buildMenuTree () {
			$cachedTree = Sjonsite_Cache::get('system-resourcecontroller-menutree', SJONSITE_TTL);
			if ($cachedTree->isValid()) {
				$this->menuTree = $cachedTree->getData();
			}
			else {
				$this->menuTree = array();
				$sql = 'SELECT id, parent, trail, sorting FROM %prefix%resources WHERE visible = ' . Sjonsite::$db->quote(Sjonsite_Model::YES) . ' AND state = ' . Sjonsite::$db->quote(Sjonsite_Model::ACTIVE) . ' ORDER BY parent, sorting';
				$res = Sjonsite::$db->query($sql);
				$resources = $res->fetchAll(PDO::FETCH_ASSOC);
				$res = null;
				$resourceList = array();
				foreach ($resources as $resource) {
					$resourceList[$resource['id']] = $resource;
				}
				$sql = 'SELECT resource, revision, uri, short, title FROM %prefix%revisions WHERE resource IN(' . implode(', ', array_keys($resourceList)) . ') AND state = ' . Sjonsite::$db->quote(Sjonsite_Model::ACTIVE);
				$res = Sjonsite::$db->query($sql);
				$revisions = $res->fetchAll(PDO::FETCH_ASSOC);
				$res = null;
				foreach ($revisions as $revision) {
					$resourceList[$revision['resource']] = array_merge($resourceList[$revision['resource']], $revision);
				}
				$this->_buildMenuTree($this->menuTree, $resourceList);
				$cachedTree->setData($this->menuTree);
				Sjonsite_Cache::set($cachedTree);
			}
		}

		/**
		 * Really build the menuTree
		 *
		 * @param array $tree
		 * @param array $resourceList
		 * @param int $parent
		 * @return void
		 */
		private function _buildMenuTree (&$tree, $resourceList, $parent = null) {
			foreach ($resourceList as $id => $conf) {
				if ($conf['parent'] == $parent) {
					$tree[$conf['trail']] = $conf;
					$tree[$conf['trail']]['children'] = array();
					$this->_buildMenuTree($tree[$conf['trail']]['children'], $resourceList, $id);
				}
			}
		}

		/**
		 * Display a Template file
		 *
		 * @param string $template
		 * @return void
		 */
		protected function displayTemplate($template) {
			ob_start();
			include $template;
			Sjonsite::$request->setContent(ob_get_clean());
		}

	}

