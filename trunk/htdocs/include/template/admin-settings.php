<?php

	/**
	 * Sjonsite Template - Admin Settings
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @package Sjonsite
	 * @copyright Sjon's dotCom 2008
	 * @license Mozilla Public License 1.1
	 * @version $Id$
	 */

	$this->template('include/header');
?>
			<div id="main">
				<h1>Sjonsite Admin</h1>
<?php
	$this->template('include/messages');
?>
				<div class="form">
					<form action="/admin/settings" method="post">
						<fieldset>
							<legend>System settings</legend>
							<div class="label">
								<label for="settings-secretHash">Secret Hash:</label>
								<div class="field">
									<input type="password" id="settings-secretHash" name="secretHash" value="<?php echo $this->out($this->settings->secretHash); ?>" size="40" />
								</div>
							</div>
						</fieldset>
						<fieldset>
							<legend>Contactform settings</legend>
							<div class="label">
								<label for="settings-contactTo">E-mail address where the message will be sent:</label>
								<div class="field">
									<input type="text" id="settings-contactTo" name="contactTo" value="<?php echo $this->out($this->settings->contactTo); ?>" size="40" />
								</div>
							</div>
							<div class="label">
								<label for="settings-contactFrom">From address of the message:</label>
								<div class="field">
									<input type="text" id="settings-contactFrom" name="contactFrom" value="<?php echo $this->out($this->settings->contactFrom); ?>" size="40" />
								</div>
							</div>
							<div class="label">
								<label for="settings-contactSubject">Subject of the message:</label>
								<div class="field">
									<input type="text" id="settings-contactSubject" name="contactSubject" value="<?php echo $this->out($this->settings->contactSubject); ?>" size="40" />
								</div>
							</div>
						</fieldset>
						<fieldset>
							<legend>Search settings</legend>
							<div class="label">
								<label for="settings-searchEnabled">Search enabled:</label>
								<div class="field">
									<input type="hidden" name="searchEnabled" value="false" />
									<input type="checkbox" id="settings-searchEnabled" name="searchEnabled" value="true<?php if ($this->settings->searchEnabled) echo '" checked="checked'; ?>" />
								</div>
							</div>
							<div class="label">
								<label for="settings-searchPerPage">Numer of results per page:</label>
								<div class="field">
									<input type="text" id="settings-searchPerPage" name="searchPerPage" value="<?php echo $this->out($this->settings->searchPerPage); ?>" size="40" />
								</div>
							</div>
						</fieldset>
						<div class="buttons">
							<button type="submit">
								Submit
							</button>
						</div>
					</form>
				</div>
			</div>
<?php
	$this->template('include/footer');
?>