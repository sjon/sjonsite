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
	// u_id, u_name, u_email, u_level, u_state
?>
			<div id="main">
				<h1>Sjonsite Admin</h1>
				<p>Lorum ipsum</p>
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
		foreach ($this->usersList as $user) {
?>
							<tr>
								<td><?php echo $this->out($user->u_name); ?></td>
								<td><?php echo $this->out($user->u_email); ?></td>
								<td>
									<img src="/img/admin/user-level-1.png" alt="Can manage pages" />
									<img src="/img/admin/user-level-2.png" alt="Can manage gallery" />
									<img src="/img/admin/user-level-4.png" alt="Can manage users" />
									<img src="/img/admin/user-level-8.png" alt="Can manage settings" />
								</td>
								<td>
									<img src="/img/admin/user-state-a.png" alt="This user is active" />
									<img src="/img/admin/user-state-s.png" alt="This user is suspended" />
									<img src="/img/admin/user-state-r.png" alt="This user is removed" />
									<img src="/img/admin/user-state-u.png" alt="Unknown state for this user" />
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