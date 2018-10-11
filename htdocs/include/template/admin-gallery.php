<?php

/**
 * Sjonsite Template - Admin Gallery
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
				<div class="list">
					<table summary="List of gallery pages">
						<thead>
							<tr>
								<th>Title</th>
								<th>Page title</th>
								<th title="Number of attached images">#</th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<td colspan="4">&nbsp;</td>
							</tr>
						</tfoot>
						<tbody>
<?php
if (count($this->galleryList) > 0) {
    $even = false;
    foreach ($this->galleryList as $gallery) {
?>
							<tr class="<?php echo $even ? 'even' : 'odd'; $even = !$even; ?>">
								<td><?php echo $this->out($gallery->g_title); ?></td>
								<td><?php echo $this->out($gallery->p_title); ?></td>
								<td class="center"><?php echo $this->out($gallery->i_count); ?></td>
								<td>
									<a href="/admin/gallery/edit/<?php echo $this->out($gallery->g_id); ?>" title="Edit gallery &lsquo;<?php echo $this->out($gallery->g_title); ?>&rsquo;"><img src="/img/admin/gallery-edit.png" alt="edit" /></a>
									<a href="/admin/gallery/remove/<?php echo $this->out($gallery->g_id); ?>" title="Remove gallery &lsquo;<?php echo $this->out($gallery->g_title); ?>&rsquo;"><img src="/img/admin/gallery-remove.png" alt="remove" /></a>
									&nbsp;
									<a href="/admin/gallery/add/<?php echo $this->out($gallery->g_id); ?>" title="Add images to gallery &lsquo;<?php echo $this->out($gallery->g_title); ?>&rsquo;"><img src="/img/admin/gallery-add.png" alt="add" /></a>
								</td>
							</tr>
<?php
    }
}
else {
?>
							<tr>
								<td colspan="4">No gallery found</td>
							</tr>
<?php
}
?>
						</tbody>
					</table>
				</div>
			</div>
<?php
$this->template('include/footer');
?>