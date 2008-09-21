<?php

	/**
	 * Sjonsite Template - Admin Login
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
	if ($_SESSION['adminFlag']) {
?>
				<p>You don't have enough privileges to view this page.</p>
<?php
	}
	else {
?>
				<div class="form">
					<form action="/admin" method="post">
						<fieldset>
							<legend>Authenticate yourself</legend>
							<input type="hidden" name="token" value="null" />
							<div class="label">
								<label for="auth-login">Username:</label>
								<div class="field">
									<input type="text" id="auth-login" name="authLogin" value="" size="20" />
								</div>
							</div>
							<div class="label">
								<label for="auth-passwd">Password:</label>
								<div class="field">
									<input type="password" id="auth-passwd" name="authPasswd" value="" size="20" />
								</div>
							</div>
						</fieldset>
						<div class="buttons">
							<button type="submit">
								Authenticate
							</button>
						</div>
					</form>
				</div>
<?php
	}
?>
			</div>
<?php
	$this->template('include/footer');
?>