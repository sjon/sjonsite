<?php

/**
 * Sjonsite - UploadController
 *
 * @author Sjon <sjonscom@gmail.com>
 * @package Sjonsite
 * @copyright Sjon's dotCom 2008
 * @license Mozilla Public License 1.1
 * @version $Id$
 */

/**
 * Class Sjonsite_UploadController
 *
 * @package Sjonsite
 */
class Sjonsite_UploadController extends Sjonsite_Controller {

    public function processRequest () {
        $this->cacheDisabled(true);
        if (Sjonsite::$auth->isAuthorized(Sjonsite_Auth::ROLE_ADMINISTRATOR, Sjonsite_Auth::ACT_LIST)) {
            Sjonsite::$request->setContent('OK');
            return;
        }
        Sjonsite::setMessage('You are not authorized', Sjonsite::error);
        Sjonsite::$request->setRedirect('/login');
    }

}

