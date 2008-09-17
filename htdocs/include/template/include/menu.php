<?php

	/**
	 * Sjonsite Template - Menu
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @package Sjonsite_Template
	 * @copyright Sjon's dotCom 2008
	 * @license Mozilla Public License 1.1
	 * @version $Id$
	 */

	/**
	 * Little helper function that checks if the current item is part of the request
	 *
	 * @param array $item
	 * @param string $request
	 * @return bool
	 */
	function isCurrent ($item, $request) {
		if ($item['uri'] == $request) return true;
		if (isset($item['children']) && count($item['children'])) {
			foreach ($item['children'] as $child) {
				if ($child['uri'] == $request) return true;
				if (isset($child['children']) && count($child['children'])) {
					foreach ($child['children'] as $subChild) {
						if ($subChild['uri'] == $request) return true;
					}
				}
			}
		}
		return false;
	}

	if (count($this->menuItems)) {
?>
			<div id="menu">
				<ul>
<?php
		foreach ($this->menuItems as $item) {
			if ($item['state'] == Sjonsite_Model::ACTIVE) {
				if (isCurrent($item, $this->request)) {
?>
					<li>
						<a href="<?php echo $item['uri']; ?>" class="current"><?php echo $this->out($item['title']); ?></a>
<?php
					if (count($item['children'])) {
?>
						<ul>
<?php
						foreach ($item['children'] as $subItem) {
							if ($subItem['state'] == Sjonsite_Model::ACTIVE) {
								if (isCurrent($subItem, $this->request)) {
?>
							<li>
								<a href="<?php echo $subItem['uri']; ?>" class="current"><?php echo $this->out($subItem['title']); ?></a>
<?php
									if (count($subItem['children'])) {
?>
								<ul>
<?php
										foreach ($subItem['children'] as $subSubItem) {
											if ($subSubItem['state'] == Sjonsite_Model::ACTIVE) {
												if (isCurrent($subSubItem, $this->request)) {
?>
									<li>
										<a href="<?php echo $subSubItem['uri']; ?>" class="current"><?php echo $this->out($subSubItem['title']); ?></a>
<?php
													if (count($subSubItem['children'])) {
?>
										<ul>
<?php
														foreach ($subSubItem['children'] as $subSubSubItem) {
?>
											<li><a href="<?php echo $subSubSubItem['uri']; ?>"><?php echo $this->out($subSubSubItem['title']); ?></a></li>
<?php
														}
?>
										</ul>
<?php
													}
?>
									</li>
<?php
												}
												else {
?>
									<li><a href="<?php echo $subSubItem['uri']; ?>"><?php echo $this->out($subSubItem['title']); ?></a></li>
<?php
												}
											}
										}
?>
								</ul>
<?php
									}
?>
							</li>
<?php
								}
								else {
?>
							<li><a href="<?php echo $subItem['uri']; ?>"><?php echo $this->out($subItem['title']); ?></a></li>
<?php
								}
							}
						}
?>
						</ul>
<?php
					}
?>
					</li>
<?php
				}
				else {
?>
					<li><a href="<?php echo $item['uri']; ?>"><?php echo $this->out($item['title']); ?></a></li>
<?php
				}
			}
		}
?>
				</ul>
			</div>
<?php
	}
	if ($this->isAdmin()) {
?>
			<div id="admin-menu">
				<ul>
					<li><a href="/admin">Admin Home</a></li>
					<li><a href="/admin/pages">Pages</a></li>
					<li><a href="/admin/gallery">Gallery</a></li>
					<li><a href="/admin/users">Users</a></li>
					<li><a href="/admin/settings">Settings</a></li>
					<li><a href="/admin/logout">Logout</a></li>
				</ul>
			</div>
<?php
	}
?>