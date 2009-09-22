<?php

	/**
	 * Sjonsite Template - Header
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @package Sjonsite_Template
	 * @copyright Sjon's dotCom 2008
	 * @license Mozilla Public License 1.1
	 * @version $Id$
	 */

	if (Sjonsite::hasMessage()) {
?>
				<div id="messages">
					<ul>
<?php
		while ($msg = Sjonsite::getMessage()) {
?>
						<li class="<?php echo $msg[1]; ?>"><?php echo Sjonsite::$io->out($msg[0], true); ?></li>
<?php
		}
?>
					</ul>
				</div>
<?php
	}
?>