<?php

/**
 * Sjonsite Template - Admin Pages
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
					<table summary="List of pages">
						<thead>
							<tr>
								<th>Title</th>
								<th>Type</th>
								<th>State</th>
								<th>Sort</th>
								<th title="Number of attached images">#</th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<td colspan="5">&nbsp;</td>
								<td><a href="/admin/pages/add" title="Add a new page"><img src="/img/admin/page-add.png" alt="add" /></a></td>
							</tr>
						</tfoot>
						<tbody>
<?php
if (count($this->pagesList) > 0) {
    $even = false;
    foreach ($this->pagesList as $page) {
?>
							<tr class="<?php echo $even ? 'even' : 'odd'; $even = !$even; ?>">
								<td><?php echo str_repeat('&nbsp;&nbsp;&nbsp;', $page->indent), $this->out($page->p_title); ?></td>
								<td class="center">
<?php
        if ($page->p_gallery > 0) {
?>
									<img src="/img/admin/page-gallery.png" title="This is a gallery page" alt="Gallery" />
<?php
        }
        else {
?>
									<img src="/img/admin/page-content.png" title="This is a content page" alt="Content" />
<?php
        }
?>
								</td>
								<td class="center">
<?php
        switch ($page->p_state) {
            case Sjonsite_Model::ACTIVE:
?>
									<img src="/img/admin/system-state-a.png" title="This page is active" alt="This page is active" />
<?php
                break;
            case Sjonsite_Model::SUSPENDED:
?>
									<img src="/img/admin/system-state-s.png" title="This page is suspended" alt="This page is suspended" />
<?php
                break;
            case Sjonsite_Model::REMOVED:
?>
									<img src="/img/admin/system-state-r.png" title="This page is removed" alt="This page is removed" />
<?php
                break;
            default:
?>
									<img src="/img/admin/system-state-u.png" title="Unknown state for this page" alt="Unknown state for this page" />
<?php
                break;
        }
?>
								</td>
								<td class="center">
									<a href="/admin/pages/sort/<?php echo $this->out($page->p_id); ?>/up" title="Move page &lsquo;<?php echo $this->out($page->p_title); ?>&rsquo; up"><img src="/img/admin/page-sort-up.png" alt="up" /></a>
									<a href="/admin/pages/sort/<?php echo $this->out($page->p_id); ?>/down" title="Move page &lsquo;<?php echo $this->out($page->p_title); ?>&rsquo; down"><img src="/img/admin/page-sort-down.png" alt="down" /></a>
								</td>
								<td class="center"><?php echo $this->out($page->i_count); ?></td>
								<td>
									<a href="/admin/pages/edit/<?php echo $this->out($page->p_id); ?>" title="Edit page &lsquo;<?php echo $this->out($page->p_title); ?>&rsquo;"><img src="/img/admin/page-edit.png" alt="edit" /></a>
									<a href="/admin/pages/remove/<?php echo $this->out($page->p_id); ?>" title="Remove page &lsquo;<?php echo $this->out($page->p_title); ?>&rsquo;"><img src="/img/admin/page-remove.png" alt="remove" /></a>
									&nbsp;
									<a href="/admin/pages/add?p_pid=<?php echo $this->out($page->p_id); ?>" title="Add a child page to &lsquo;<?php echo $this->out($page->p_title); ?>&rsquo;"><img src="/img/admin/page-add-child.png" alt="add child" /></a>
<?php
        if ($page->p_gallery > 0) {
?>
									<!--img src="/img/admin/not-available.png" alt="" /-->
									<a href="/admin/gallery/add/<?php echo $this->out($page->p_gallery); ?>" title="Add images to this gallery"><img src="/img/admin/gallery-add.png" alt="add" /></a>
<?php
        }
        else {
?>
									<!--a href="/admin/images/add/<?php echo $this->out($page->p_id); ?>" title="Add images to this page"><img src="/img/admin/gallery-add.png" alt="add" /></a-->
									<img src="/img/admin/not-available.png" alt="" />
<?php
        }
?>
									&nbsp;
									<a href="<?php echo $this->out($page->p_uri); ?>" class="preview" title="View this page"><img src="/img/admin/page-preview.png" alt="view" /></a>
								</td>
							</tr>
<?php
    }
}
else {
?>
							<tr>
								<td colspan="6">No pages found</td>
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