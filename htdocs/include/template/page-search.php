<?php

	/**
	 * Sjonsite Template - Search page
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
?>
			<div id="main">
				<h1>Searchresults</h1>
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
					<a href="/search?q=<?php echo rawurlencode($this->searchQuery); if ($this->searchPage > 2) echo '&amp;p=' . intval($this->searchPage - 1); ?>">previous</a>
<?php
			}
			else {
?>
					previous
<?php
			}
			for ($i = 1; $i <= $this->searchPages; $i++) {
?>
					<a href="/search?q=<?php echo rawurlencode($this->searchQuery); if ($i > 1) echo '&amp;p=' . $i; ?>"><?php echo $i; ?></a>
<?php
			}
			if ($this->searchPage < $this->searchPages) {
?>
					<a href="/search?q=<?php echo rawurlencode($this->searchQuery), '&amp;p=', intval($this->searchPage + 1); ?>">next</a>
<?php
			}
			else {
?>
					next
<?php
			}
?>
				</div>
			</div>
<?php
		}
		else {
?>
			<div id="main">
				<h1>Searchresults</h1>
				<p class="warning">Your search didn't return any results.</p>
<?php
		}
	}
	else {
?>
			<div id="main">
				<h1>Search</h1>
				<p>Lorum ipsum dolor sit amet</p>
<?php
	}
?>
				<div class="form">
					<form action="/search" method="get">
						<fieldset>
							<legend>Search</legend>
							<div class="label">
								<label for="field-q">Searchquery:</label>
								<div class="field">
									<input class="text" size="10" type="text" name="q" id="field-q" value="<?php echo $this->out($this->searchQuery); ?>" />
								</div>
							</div>
						</fieldset>
						<div class="buttons">
							<button type="submit">
								Search
							</button>
						</div>
					</form>
				</div>
			</div>
<?php
	$this->template('include/footer');
?>