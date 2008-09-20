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

	if ((isset($this->menuItems) && count($this->menuItems)) || ($this->settings->searchEnabled && !$this->isAdmin())) {
?>
			<div id="menu">
<?php
		if ($this->settings->searchEnabled) {
?>
				<div class="form">
					<form action="/search" method="get">
						<div id="search-box">
							<input class="text" size="10" type="text" name="q" id="field-q" value="<?php echo $this->out($this->searchQuery); ?>" />
							<button type="submit">Search</button>
						</div>
					</form>
				</div>
				<br />
<?php
		}
		if (isset($this->menuItems) && count($this->menuItems)) {
?>
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
<?php
		}
?>
			</div>
<?php
	}
	if ($this->isAdmin()) {
?>
			<div id="admin-menu">
				<ul>
					<li><a href="/admin"><img src="/img/admin/home.png" alt="Home" /> Home</a></li>
					<li><a href="/admin/pages"><img src="/img/admin/pages.png" alt="Pages" /> Pages</a></li>
					<li><a href="/admin/gallery"><img src="/img/admin/gallery.png" alt="Gallery" /> Gallery</a></li>
					<li><a href="/admin/users"><img src="/img/admin/users.png" alt="Users" /> Users</a></li>
					<li><a href="/admin/settings"><img src="/img/admin/settings.png" alt="Settings" /> Settings</a></li>
					<li><a href="/admin/logout"><img src="/img/admin/logout.png" alt="Logout" /> Logout</a></li>
				</ul>
			</div>
<?php
	}
?>