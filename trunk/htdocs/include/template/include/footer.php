<?php

	/**
	 * Sjonsite Template - Footer
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @package Sjonsite_Template
	 * @copyright Sjon's dotCom 2008
	 * @license Mozilla Public License 1.1
	 * @version $Id$
	 */

	$this->template('include/menu');
?>
			<div class="clear">
			</div>
			<p id="system-stats">time: <?php printf('%0.1fms', (microtime(true) - SJONSITE_START) * 1000); ?> memory: <?php printf('%d KiB', memory_get_usage() / 1024); ?></p>
		</div>
	</body>
</html>