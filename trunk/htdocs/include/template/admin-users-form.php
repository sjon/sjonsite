<?php

	/**
	 * Sjonsite Template - Admin Users Form
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
				<p>Lorum ipsum</p>
				<div class="form">
					<form action="/admin/users/<?php echo $this->formAction; ?>" method="post">
						<fieldset>
							<legend>User data</legend>
							<input type="hidden" name="u_id" value="<?php echo $this->out($this->userformData->u_id); ?>" />
							<div class="label<?php if (array_key_exists('u_name', $this->formErrors)) echo ' error'; ?>">
								<label for="user-name">Name:</label>
								<div class="field">
									<input type="text" id="user-name" name="u_name" value="<?php echo $this->out($this->userformData->u_name); ?>" size="40" />
								</div>
							</div>
							<div class="label<?php if (array_key_exists('u_email', $this->formErrors)) echo ' error'; ?>">
								<label for="user-email">E-mail:</label>
								<div class="field">
									<input type="text" id="user-email" name="u_email" value="<?php echo $this->out($this->userformData->u_email); ?>" size="40" />
								</div>
							</div>
							<div class="label<?php if (array_key_exists('u_passwd', $this->formErrors)) echo ' error'; ?> optional">
								<label for="user-passwd">Password: <em class="optional">(optional)</em></label>
								<div class="field">
									<input type="password" id="user-passwd" name="u_passwd" size="40" />
								</div>
							</div>
							<div class="label optional">
								<label for="user-passwd-check">Password check: <em class="optional">(optional)</em></label>
								<div class="field">
									<input type="password" id="user-passwd-check" name="u_passwd_check" size="40" />
								</div>
							</div>
							<div class="label<?php if (array_key_exists('u_level', $this->formErrors)) echo ' error'; ?>">
								<label>Authorisation level:</label>
								<div class="field">
									<label><input type="checkbox" name="u_level[]" value="1<?php if (($this->userformData->u_level & 1) == 1) echo '" checked="checked'; ?>" /> pages</label>
									<label><input type="checkbox" name="u_level[]" value="2<?php if (($this->userformData->u_level & 2) == 2) echo '" checked="checked'; ?>" /> gallery</label>
									<label><input type="checkbox" name="u_level[]" value="4<?php if (($this->userformData->u_level & 4) == 4) echo '" checked="checked'; ?>" /> users</label>
									<label><input type="checkbox" name="u_level[]" value="8<?php if (($this->userformData->u_level & 8) == 8) echo '" checked="checked'; ?>" /> settings</label>
								</div>
							</div>
							<div class="label">
								<label for="user-state">State:</label>
								<div class="field">
									<select id="user-state" name="u_state" size="1">
										<option value="A<?php echo ($this->userformData->u_state == Sjonsite_Model::ACTIVE ? '" selected="selected' : null); ?>"> active </option>
										<option value="S<?php echo ($this->userformData->u_state == Sjonsite_Model::SUSPENDED ? '" selected="selected' : null); ?>"> suspended </option>
										<option value="R<?php echo ($this->userformData->u_state == Sjonsite_Model::REMOVED ? '" selected="selected' : null); ?>"> removed </option>
									</select>
								</div>
							</div>
						</fieldset>
						<div class="buttons">
<?php
	if ($this->formAction == 'add') {
?>
							<button type="submit" class="add">
								Insert user
							</button>
<?php
	}
	else {
?>
							<button type="submit" class="edit">
								Update user
							</button>
<?php
	}
?>
						</div>
					</form>
				</div>
			</div>
<?php
	$this->template('include/footer');
?>