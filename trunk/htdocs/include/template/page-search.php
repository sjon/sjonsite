<?php

	/**
	 * Sjonsite Template - Page Search
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @package Sjonsite
	 * @copyright Sjon's dotCom 2008
	 * @license Mozilla Public License 1.1
	 * @version $Id$
	 */

	$this->template('include/header');

	if ($this->searchQuery) {
		if (count($this->searchResults)) {
			// results
?>
			<h1>Zoekresultaten</h1>
			<p>Lorum ipsum dolor sit amet</p>
			<div id="search-results">
<?php
			foreach ($this->searchResults as $result) {
?>
				<div id="search-result-<?php echo md5($result['p_uri']); ?>">
					<h2><a href="<?php echo $this->out($result['p_uri']); ?>"><?php echo $this->out($result['p_title']); ?></a></h2>
					<p><?php echo $this->out($result['p_summary']); ?></p>
				</div>
<?php
			}
?>
			</div>
			<div id="search-results-pager">
<?php
			if ($this->searchPage > 1) { // $this->searchCount
?>
				<a href="/search?q=<?php echo rawurlencode($this->searchQuery); if ($this->searchPage > 2) echo '&amp;p=' . intval($this->searchPage - 1); ?>">vorige</a>
<?php
			}
			else {
?>
				vorige
<?php
			}
			for ($i = 1; $i <= $this->searchPages; $i++) {
?>
				<a href="/search?q=<?php echo rawurlencode($this->searchQuery); if ($i > 1) echo '&amp;p=' . $i; ?>"><?php echo $i; ?></a>
<?php
			}
			if ($this->searchPage < $this->searchPages) {
?>
				<a href="/search?q=<?php echo rawurlencode($this->searchQuery), '&amp;p=', intval($this->searchPage + 1); ?>">volgende</a>
<?php
			}
			else {
?>
				volgende
<?php
			}
?>
			</div>
<?php
		}
		else {
			// no results
			echo 'No results';
?>
<?php
		}
	}
	else {
		// form only
		echo 'Form';
?>
<?php
	}

	$this->template('include/footer');
?>