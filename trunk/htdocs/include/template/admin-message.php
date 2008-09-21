<?php

	/**
	 * Sjonsite Template - Admin Message
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @package Sjonsite
	 * @copyright Sjon's dotCom 2008
	 * @license Mozilla Public License 1.1
	 * @version $Id$
	 */

	$this->template('include/header');
	// formType, formAction, formData
?>
			<div id="main">
				<h1>Sjonsite Admin</h1>
<?php
	$this->template('include/messages');
?>
				<div class="form">
					<form action="<?php echo $this->formAction; ?>" method="post">
						<fieldset>
							<legend><?php echo $this->formData['title']; ?></legend>
							<div class="label">
								<label><?php echo $this->formData['question']; ?></label>
								<div class="field">
									<label><input type="radio" name="sure" value="true" /> Yes</label>
									<label><input type="radio" name="sure" value="false" checked="checked" /> No</label>
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