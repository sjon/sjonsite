<?php

/**
 * Sjonsite Template - Admin Users
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
					<table summary="List of users">
						<thead>
							<tr>
								<th>Name</th>
								<th>E-mail</th>
								<th>Access</th>
								<th>State</th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<td colspan="4">&nbsp;</td>
								<td><a href="/admin/users/add" title="Add a new user"><img src="/img/admin/user-add.png" alt="add" /></a></td>
							</tr>
						</tfoot>
						<tbody>
<?php
if (count($this->usersList) > 0) {
    $even = false;
    foreach ($this->usersList as $user) {
?>
							<tr class="<?php echo $even ? 'even' : 'odd'; $even = !$even; ?>">
								<td><?php echo $this->out($user->u_name); ?></td>
								<td><?php echo $this->out($user->u_email); ?></td>
								<td class="center">
<?php
        if (($user->u_level & 1) == 1) {
?>
									<img src="/img/admin/user-level-1.png" title="Can manage pages" alt="Can manage pages" />
<?php
        }
        else {
?>
									<img src="/img/admin/not-available.png" alt="" />
<?php
        }
        if (($user->u_level & 2) == 2) {
?>
									<img src="/img/admin/user-level-2.png" title="Can manage gallery" alt="Can manage gallery" />
<?php
        }
        else {
?>
									<img src="/img/admin/not-available.png" alt="" />
<?php
        }
        if (($user->u_level & 4) == 4) {
?>
									<img src="/img/admin/user-level-4.png" title="Can manage users" alt="Can manage users" />
<?php
        }
        else {
?>
									<img src="/img/admin/not-available.png" alt="" />
<?php
        }
        if (($user->u_level & 8) == 8) {
?>
									<img src="/img/admin/user-level-8.png" title="Can manage settings" alt="Can manage settings" />
<?php
        }
        else {
?>
									<img src="/img/admin/not-available.png" alt="" />
<?php
        }
?>
								</td>
								<td class="center">
<?php
        switch ($user->u_state) {
            case Sjonsite_Model::ACTIVE:
?>
									<img src="/img/admin/system-state-a.png" title="This user is active" alt="This user is active" />
<?php
                break;
            case Sjonsite_Model::SUSPENDED:
?>
									<img src="/img/admin/system-state-s.png" title="This user is suspended" alt="This user is suspended" />
<?php
                break;
            case Sjonsite_Model::REMOVED:
?>
									<img src="/img/admin/system-state-r.png" title="This user is removed" alt="This user is removed" />
<?php
                break;
            default:
?>
									<img src="/img/admin/system-state-u.png" title="Unknown state for this user" alt="Unknown state for this user" />
<?php
                break;
        }
?>
								</td>
								<td>
									<a href="/admin/users/edit/<?php echo $this->out($user->u_id); ?>" title="Edit user &lsquo;<?php echo $this->out($user->u_name); ?>&rsquo;"><img src="/img/admin/user-edit.png" alt="edit" /></a>
									<a href="/admin/users/remove/<?php echo $this->out($user->u_id); ?>" title="Remove user &lsquo;<?php echo $this->out($user->u_name); ?>&rsquo;"><img src="/img/admin/user-remove.png" alt="remove" /></a>
								</td>
							</tr>
<?php
    }
}
else {
?>
							<tr>
								<td colspan="5">No users found</td>
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