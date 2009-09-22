<?php

	/**
	 * Sjonsite - LoginController
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @package Sjonsite
	 * @copyright Sjon's dotCom 2008
	 * @license Mozilla Public License 1.1
	 * @version $Id$
	 */

	/**
	 * Class Sjonsite_LoginController
	 *
	 * @package Sjonsite
	 */
	class Sjonsite_LoginController extends Sjonsite_Controller {

		/**
		 * Process Login Request
		 *
		 * @return void
		 */
		public function processRequest () {
			$this->cacheDisabled(true);
			$this->form = new Sjonsite_Form(array('action' => $this->revision->uri, 'method' => 'post', 'legend' => 'Authenticate'));
			$this->form->addField('username', 'text', 'Username:')->setValue('username');
			$this->form->addField('password', 'password', 'Password:');
			$this->form->addButton('Authenticate', array('class' => 'login'));
			$this->form->setValue('cmd', 'authenticate');
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
			$this->displayTemplate(Sjonsite::template('system-login'));
		}

	}

