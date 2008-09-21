<?php

	/**
	 * Sjonsite Template - Header
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @package Sjonsite_Template
	 * @copyright Sjon's dotCom 2008
	 * @license Mozilla Public License 1.1
	 * @version $Id: header.php 31 2008-09-18 15:17:51Z sjonscom $
	 */

	if ($this->hasMessage()) {
?>
				<div id="messages">
					<ul>
<?php
		while ($msg = $this->getMessage()) {
?>
						<li class="<?php echo $msg[1]; ?>"><?php echo $this->out($msg[0], true); ?></li>
<?php
		}
?>
					</ul>
				</div>
<?php
	}
?>