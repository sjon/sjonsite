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
			<div id="main">
				<h1>Sjonsite Admin</h1>
<?php
$this->template('include/messages');
?>
				<div class="form">
<?php
if (empty($this->pageformData->p_id)) {
?>
					<form action="/admin/pages/add" method="post">
						<fieldset>
							<legend>Add a page</legend>
							<input type="hidden" name="p_id" value="null" />
							<input type="hidden" name="p_pid" value="<?php echo $this->out($this->pageformData->p_pid); ?>" />
							<input type="hidden" name="p_sorting" value="<?php echo $this->out($this->pageformData->p_sorting); ?>" />
							<div class="label">
								<label>What do you want to add?</label>
								<div class="field">
									<label><input type="radio" name="type" value="pages" checked="checked" /> Content page </label>
									<label><input type="radio" name="type" value="gallery" /> Gallery page </label>
								</div>
							</div>
							<div class="label">
								<label for="page-title">Title:</label>
								<div class="field">
									<input type="text" id="page-title" name="p_title" value="" size="40" />
								</div>
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
								<label for="page-uri">URI:</label>
								<div class="field">
									<input type="text" id="page-uri" name="p_uri" value="<?php echo $this->out($this->pageformData->p_uri); ?>" size="40" />
								</div>
							</div>
							<div class="label">
								<label for="page-state">State:</label>
								<div class="field">
									<select id="page-state" name="p_state" size="1">
										<option value="A<?php echo ($this->pageformData->p_state == Sjonsite_Model::ACTIVE ? '" selected="selected' : null); ?>"> active </option>
										<option value="S<?php echo ($this->pageformData->p_state == Sjonsite_Model::SUSPENDED ? '" selected="selected' : null); ?>"> suspended </option>
										<option value="R<?php echo ($this->pageformData->p_state == Sjonsite_Model::REMOVED ? '" selected="selected' : null); ?>"> removed </option>
									</select>
								</div>
							</div>
						</fieldset>
<?php
    if ($this->pageformData->p_gallery > 0) {
?>
						<fieldset>
							<legend>Gallery Page</legend>
							<input type="hidden" name="p_gallery" value="<?php echo $this->out($this->pageformData->p_gallery); ?>" />
							<div class="label<?php if (array_key_exists('p_title', $this->formErrors)) echo ' error'; ?>">
								<label for="page-title2">Title:</label>
								<div class="field">
									<input type="text" id="page-title2" name="p_title" value="<?php echo $this->out($this->pageformData->p_title); ?>" size="40" />
								</div>
							</div>
							<div class="label">
								<label for="page-summary2">Summary:</label>
								<div class="field">
									<textarea id="page-summary2" name="p_summary" rows="10" cols="80" class="wymeditor small" title=""><?php echo $this->out($this->pageformData->p_summary); ?></textarea>
								</div>
							</div>
							<input type="hidden" name="p_content" value="null" />
						</fieldset>
<?php
    }
    else {
?>
						<fieldset>
							<legend>Content Page</legend>
							<input type="hidden" name="p_gallery" value="null" />
							<div class="label<?php if (array_key_exists('p_title', $this->formErrors)) echo ' error'; ?>">
								<label for="page-title">Title:</label>
								<div class="field">
									<input type="text" id="page-title" name="p_title" value="<?php echo $this->out($this->pageformData->p_title); ?>" size="40" />
								</div>
							</div>
							<div class="label">
								<label for="page-summary">Summary:</label>
								<div class="field">
									<textarea id="page-summary" name="p_summary" rows="10" cols="80" class="wymeditor small" title=""><?php echo $this->out($this->pageformData->p_summary); ?></textarea>
								</div>
							</div>
							<div class="label">
								<label for="page-content">Content:</label>
								<div class="field">
									<textarea id="page-content" name="p_content" rows="30" cols="80" class="wymeditor" title=""><?php echo $this->out($this->pageformData->p_content); ?></textarea>
								</div>
							</div>
						</fieldset>
<?php
    }
?>
						<div class="buttons">
							<button type="submit" class="edit">
								Update content
							</button>
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