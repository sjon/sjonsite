<?php

	/**
	 * Sjonsite Template - Admin Pages Form
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @package Sjonsite
	 * @copyright Sjon's dotCom 2008
	 * @license Mozilla Public License 1.1
	 * @version $Id$
	 */

	$this->template('include/header');
?>
<!--
	p_id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
	p_pid MEDIUMINT UNSIGNED NULL,
	p_uri VARCHAR (255) NOT NULL,
	p_title VARCHAR (255) NOT NULL,
	p_summary TEXT NULL,
	p_content MEDIUMTEXT NULL,
	p_gallery MEDIUMINT UNSIGNED NULL,
	p_sorting SMALLINT UNSIGNED NOT NULL,
	p_state ENUM ('A', 'S', 'R', 'U') NOT NULL DEFAULT 'U', -->
			<div id="main">
				<h1>Sjonsite Admin</h1>
				<p>Lorum ipsum dolor sit amet</p>
				<div class="form">
<?php
	if (empty($this->pageformData->p_id)) {
?>
					<form action="/admin/pages/<?php echo $this->formAction; ?>" method="post">
						<fieldset>
							<legend>What do you want to add?</legend>
							<input type="hidden" name="p_id" value="<?php echo $this->out($this->pageformData->p_id); ?>" />
							<input type="hidden" name="p_pid" value="<?php echo $this->out($this->pageformData->p_pid); ?>" />
							<div class="label">
								<label><input type="radio" name="type" value="pages" selected="selected" /> Content page </label>
							</div>
							<div class="label">
								<label><input type="radio" name="type" value="gallery" selected="selected" /> Gallery page </label>
							</div>
						</fieldset>
						<div class="buttons">
							<button type="submit" class="next">
								Next step
							</button>
						</div>
					</form>
<?php
	}
	else {
?>
					<form action="/admin/pages/<?php echo $this->formAction; ?>" method="post">
						<fieldset>
							<legend>Metadata</legend>
							<input type="hidden" name="p_id" value="<?php echo $this->out($this->pageformData->p_id); ?>" />
							<input type="hidden" name="p_pid" value="<?php echo $this->out($this->pageformData->p_pid); ?>" />
							<div class="label optional<?php if (array_key_exists('p_uri', $this->formErrors)) echo ' error'; ?>">
								<label for="page-uri">URI: <em class="optional">(optional)</em></label>
								<div class="field">
									<input type="text" id="page-uri" name="p_uri" value="<?php echo $this->out($this->pageformData->p_uri); ?>" size="40" />
								</div>
							</div>
							<div class="label">
								<label for="page-state">State:</label>
								<div class="field">
									<select id="page-state" name="u_state" size="1">
										<option value="A<?php echo ($this->pageformData->p_state == Sjonsite_Model::ACTIVE ? '" selected="selected' : null); ?>"> active </option>
										<option value="S<?php echo ($this->pageformData->p_state == Sjonsite_Model::SUSPENDED ? '" selected="selected' : null); ?>"> suspended </option>
										<option value="R<?php echo ($this->pageformData->p_state == Sjonsite_Model::REMOVED ? '" selected="selected' : null); ?>"> removed </option>
									</select>
								</div>
							</div>
						</fieldset>
						<fieldset>
							<legend>
								<label><input type="radio" name="p_gallery" value="null" /> Content Page</label>
							</legend>
							<div class="label<?php if (array_key_exists('p_title', $this->formErrors)) echo ' error'; ?>">
								<label for="page-title">Title:</label>
								<div class="field">
									<input type="text" id="page-title" name="p_title" value="<?php echo $this->out($this->pageformData->p_title); ?>" size="40" />
								</div>
							</div>
							<div class="label">
								<label for="page-summary">Summary:</label>
								<div class="field">
									<textarea id="page-summary" name="p_summary" rows="10" cols="80" class="wymeditor" title=""><?php echo $this->out($this->pageformData->p_summary); ?></textarea>
								</div>
							</div>
							<div class="label">
								<label for="page-content">Content:</label>
								<div class="field">
									<textarea id="page-content" name="p_content" rows="30" cols="80" class="wymeditor" title=""><?php echo $this->out($this->pageformData->p_content); ?></textarea>
								</div>
							</div>
						</fieldset>
						<fieldset>
							<legend>
								<label><input type="radio" name="p_gallery" value="<?php echo $this->out($this->pageformData->p_gallery); ?>" /> Gallery Page</label>
							</legend>
							<div class="label<?php if (array_key_exists('p_title', $this->formErrors)) echo ' error'; ?>">
								<label for="page-title2">Title:</label>
								<div class="field">
									<input type="text" id="page-title2" name="p_title" value="<?php echo $this->out($this->pageformData->p_title); ?>" size="40" />
								</div>
							</div>
							<div class="label">
								<label for="page-summary2">Summary:</label>
								<div class="field">
									<textarea id="page-summary2" name="p_summary" rows="10" cols="80" class="wymeditor" title=""><?php echo $this->out($this->pageformData->p_summary); ?></textarea>
								</div>
							</div>
						</fieldset>
						<div class="buttons">
<?php
		if ($this->formAction == 'add') {
?>
							<button type="submit" class="add">
								Insert content
							</button>
<?php
		}
		else {
?>
							<button type="submit" class="edit">
								Update content
							</button>
<?php
		}
?>
						</div>
					</form>
<?php
	}
?>
				</div>
			</div>
<?php
	$this->template('include/footer');
?>