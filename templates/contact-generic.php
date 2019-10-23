<?php

/**
 * Sjonsite Template - Contact page
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
				<h1>Contact us</h1>
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
				<div class="form">
					<form action="/contact" method="post">
						<fieldset>
							<legend>Contact</legend>
							<input type="hidden" name="hash" value="<?php echo $this->out($this->contactHash); ?>" />
							<div class="label<?php if (array_key_exists('name', $this->contactErrors)) echo ' error'; ?>">
								<label for="field-name">Name:</label>
								<div class="field">
									<input class="text" size="20" type="text" name="name" id="field-name" value="<?php echo $this->out($this->contactName); ?>" />
								</div>
							</div>
							<div class="label<?php if (array_key_exists('email', $this->contactErrors)) echo ' error'; ?>">
								<label for="field-email">Email:</label>
								<div class="field">
									<input class="text" size="20" type="text" name="email" id="field-email" value="<?php echo $this->out($this->contactEmail); ?>" />
								</div>
							</div>
							<div class="label<?php if (array_key_exists('message', $this->contactErrors)) echo ' error'; ?>">
								<label for="field-message">Message:</label>
								<div class="field">
									<textarea cols="20" rows="8" name="message" id="field-message"><?php echo $this->out($this->contactMessage); ?></textarea>
								</div>
							</div>
						</fieldset>
						<div class="buttons">
							<button type="submit">
								Send
							</button>
						</div>
					</form>
				</div>
			</div>
<?php
}
$this->template('include/footer');
?>