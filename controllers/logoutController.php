<?php

/**
 * Sjonsite - LogoutController
 *
 * @author Sjon <sjonscom@gmail.com>
 * @package Sjonsite
 * @copyright Sjon's dotCom 2008
 * @license Mozilla Public License 1.1
 * @version $Id$
 */

/**
 * Class Sjonsite_LogoutController
 *
 * @package Sjonsite
 */
class Sjonsite_LogoutController extends Sjonsite_Controller {

    /**
     * Process Login Request
     *
     * @return void
     */
    public function processRequest () {
        $this->cacheDisabled(true);
        session_destroy();
        Sjonsite::$request->setRedirect('/');
    }

}

