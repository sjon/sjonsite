<?php

	/**
	 * Sjonsite Template - Page Contact
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
				<h1>Contactformulier</h1>
				<p>Lorum ipsum dolor sit amet</p>
<?php
	if ($this->contactSubmitted && $this->contactSuccess) {
?>
				<p>The form has been sent. Thank you, and, if needed, we'll get in touch shortly.</p>
<?php
	}
	else {
		if ($this->contactSubmitted && !$this->contactSuccess && count($this->contactErrors)) {
?>
				<p class="error">You didn't fill in all the fields properly. Please check your data, and try again.</p>
<?php
		}
		elseif ($this->contactSubmitted && !$this->contactSuccess) {
?>
				<p class="error">There was a problem sending the form. Please try again later.</p>
<?php
		}
?>
				<form action="/contact" method="post">
					<fieldset>
						<legend>Contact</legend>
						<div class="field<?php if (array_key_exists('name', $this->contactErrors)) echo ' error'; ?>">
							<label for="name">Name:</label>
							<input class="text" size="20" type="text" name="name" id="name" value="<?php echo $this->out($this->contactName); ?>" />
						</div>
						<div class="field<?php if (array_key_exists('email', $this->contactErrors)) echo ' error'; ?>">
							<label for="email">Email:</label>
							<input class="text" size="20" type="text" name="email" id="email" value="<?php echo $this->out($this->contactEmail); ?>" />
						</div>
						<div class="field<?php if (array_key_exists('message', $this->contactErrors)) echo ' error'; ?>">
							<label for="message">Message:</label>
							<textarea cols="20" rows="8" name="message" id="message"><?php echo $this->out($this->contactMessage); ?></textarea>
						</div>
					</fieldset>
					<div class="buttons">
						<input type="hidden" name="hash" value="<?php echo $this->out($this->contactHash); ?>" />
						<button type="submit">Send</button>
					</div>
				</form>
			</div>
<?php
	}
	$this->template('include/footer');
?>