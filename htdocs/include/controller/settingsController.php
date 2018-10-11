<?php

/**
 * Sjonsite - SettingsController
 *
 * @author Sjon <sjonscom@gmail.com>
 * @package Sjonsite
 * @copyright Sjon's dotCom 2008
 * @license Mozilla Public License 1.1
 * @version $Id$
 */

/**
 * Class Sjonsite_SettingsController
 *
 * @package Sjonsite
 */
class Sjonsite_SettingsController extends Sjonsite_Controller {

    public function processRequest () {
        $this->cacheDisabled(true);
        if (Sjonsite::$auth->isAuthorized(Sjonsite_Auth::ROLE_ADMINISTRATOR, Sjonsite_Auth::ACT_LIST) && Sjonsite::$auth->isAuthorized(Sjonsite_Auth::ROLE_ADMINISTRATOR, Sjonsite_Auth::ACT_EDIT)) {
            Sjonsite::$request->setContent('OK');
            return;
            $this->form = new Sjonsite_Form(array('action' => $this->revision->uri, 'method' => 'post', 'legend' => 'System Settings'));
            $this->form->addField('username', 'text', 'Username:')->setValue('username');
            $this->form->addField('password', 'password', 'Password:');
            $this->form->addButton('Save Settings');
            $this->form->setValue('cmd', 'save');
            if (Sjonsite::$io->isPost()) {
                if (!$this->form->checkToken()) {
                    throw new Sjonsite_ControllerException('Invalid form data');
                }
                // process
                Sjonsite::$auth->checkAuth('username', 'password');
                if (Sjonsite::$auth->isValid()) {
                    Sjonsite::$request->setRedirect('/system');
                    return;
                }
                Sjonsite::setMessage('Invalid Credentials', Sjonsite::warning);
            }
            $this->form->genToken();
            $this->displayTemplate(Sjonsite::template('system-settings'));
        }
        Sjonsite::setMessage('You are not authorized', Sjonsite::error);
        Sjonsite::$request->setRedirect('/login');
    }

}

