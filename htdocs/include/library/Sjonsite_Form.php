<?php

	/**
	 * Sjonsite - Form Class
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @package Sjonsite
	 * @copyright Sjon's dotCom 2008
	 * @license Mozilla Public License 1.1
	 * @version $Id$
	 */

	/**
	 * Class Sjonsite_Form
	 *
	 * @package Sjonsite
	 */
	final class Sjonsite_Form {

		/**
		 * Form configuration
		 *
		 * @var array
		 */
		private $_config = array(
			'indent' => 4,
			'action' => null,
			'method' => 'post',
			'enctype' => null,
			'legend' => null,
		);

		/**
		 * The form fields
		 *
		 * @var array
		 */
		private $_fields = array(
			'token' => array(
				'type' => 'hidden',
				'value' => null
			),
			'hash' => array(
				'type' => 'hidden',
				'value' => null
			),
			'cmd' => array(
				'type' => 'hidden',
				'value' => null
			)
		);

		/**
		 * Constructor
		 * @param array $config
		 */
		public function __construct ($config = array()) {
			foreach ($config as $key => $val) {
				$this->_config[$key] = $val;
			}
		}

		/**
		 * Add a field
		 *
		 * @param string $name
		 * @param string $type
		 * @param string $title
		 * @param array $opts
		 * @return Sjonsite_Form
		 */
		public function addField ($name, $type, $title, $opts = array()) {
			$this->_fields[$name] = array(
				'type' => $type,
				'value' => null,
				'title' => $title,
				'opts' => $opts
			);
			return $this;
		}

		/**
		 * Add a fieldset
		 *
		 * @param string $legend
		 * @return Sjonsite_Form
		 */
		public function addFieldset ($legend = null) {
			$this->_fields[] = array(
				'type' => 'fieldset',
				'value' => null,
				'title' => $legend
			);
			return $this;
		}

		/**
		 * Add a button element
		 *
		 * @param string $title
		 * @param array $opts
		 * @return Sjonsite_Form
		 */
		public function addButton ($title, $opts = array()) {
			$this->_fields[] = array(
				'type' => 'button',
				'value' => null,
				'title' => $title,
				'opts' => $opts
			);
			return $this;
		}

		/**
		 * Assign a value to a field
		 *
		 * @param string $field
		 * @param string $value
		 * @return Sjonsite_Form
		 */
		public function setValue ($field, $value = null) {
			$this->_fields[$field]['value'] = ($value ? $value : Sjonsite::$io->post($field));
			return $this;
		}

		/**
		 * Render the form to a string
		 *
		 * @return string
		 */
		public function __toString () {
			$rv = array();
			if (empty($this->_fields['hash']['value'])) {
				$this->_fields['hash']['value'] = hash('whirlpool', implode('-', array_keys($this->_fields)) . '-' . time());
			}
			$rv[] = $this->i(0) . '<form action="' . $this->_config['action'] . '" method="' . $this->_config['method'] . '"' . ($this->_config['enctype'] ? ' enctype="' . $this->_config['enctype'] . '"' : null) . '>';
			$rv[] = $this->i(1) . '<fieldset>';
			if ($this->_config['legend']) {
				$rv[] = $this->i(2) . '<legend>' . $this->_config['legend'] . '</legend>';
			}
			foreach ($this->_fields as $field => $config) {
				if ($config['type'] == 'hidden') {
					$rv[] = $this->i(2) . '<input type="hidden" name="' . $field . '" value="' . rawurlencode($config['value']) . '" />';
				}
			}
			foreach ($this->_fields as $field => $config) {
				if ($config['type'] == 'fieldset') {
					$rv[] = $this->i(1) . '</fieldset>';
					$rv[] = $this->i(1) . '<fieldset>';
					if ($config['title']) {
						$rv[] = $this->i(2) . '<legend>' . $config['title'] . '</legend>';
					}
				}
				elseif ($config['type'] == 'button') {
					$rv[] = $this->i(2) . '<div class="form-row">';
					$rv[] = $this->i(3) . '<div class="form-field">';
					$rv[] = $this->i(4) . '<button type="submit"' . $this->opts($config['opts']) . '>' . $config['title'] . '</button>';
					$rv[] = $this->i(3) . '</div>';
					$rv[] = $this->i(2) . '</div>';
				}
				elseif ($config['type'] == 'textarea') {
					$rv[] = $this->i(2) . '<div class="form-row">';
					$rv[] = $this->i(3) . '<label for="field-' . $field . '">' . $config['title'] . '</label>';
					$rv[] = $this->i(3) . '<div class="form-field">';
					$rv[] = $this->i(4) . '<textarea" name="' . $field . '" id="field-' . $field . '"' . $this->opts($config['opts']) . '>' . Sjonsite::$io->out($config['value']) . '</textarea>';
					$rv[] = $this->i(3) . '</div>';
					$rv[] = $this->i(2) . '</div>';
				}
				elseif ($config['type'] != 'hidden') {
					$rv[] = $this->i(2) . '<div class="form-row">';
					$rv[] = $this->i(3) . '<label for="field-' . $field . '">' . $config['title'] . '</label>';
					$rv[] = $this->i(3) . '<div class="form-field">';
					$rv[] = $this->i(4) . '<input type="' . $config['type'] . '" name="' . $field . '" id="field-' . $field . '" value="' . Sjonsite::$io->out($config['value']) . '"' . $this->opts($config['opts']) . ' />';
					$rv[] = $this->i(3) . '</div>';
					$rv[] = $this->i(2) . '</div>';
				}
			}
			$rv[] = $this->i(1) . '</fieldset>';
			$rv[] = $this->i(0) . '</form>';
			return implode("\n", $rv) . "\n";
		}

		/**
		 * Returns ident string
		 *
		 * @param int $n
		 * @return string
		 */
		protected function i ($n) {
			return str_repeat("\t", ($this->_config['indent'] + $n));
		}

		/**
		 * Return an attribute string from opts
		 *
		 * @param array $opts
		 * @return string
		 */
		protected function opts ($opts) {
			$rv = null;
			if ($opts) {
				foreach ($opts as $k => $v) {
					$rv .= ' ' . $k . '="' . $v . '"';
				}
			}
			return $rv;
		}

		public function genToken () {
			$token = hash('sha256', (Sjonsite::$io->server('REMOTE_ADDR') . sha1(SJONSITE_INCLUDE . SJONSITE_PDO_DSN) . mt_rand()));
			$this->_fields['token']['value'] = $token;
			if (!isset($_SESSION['sjonsite_form_token'])) {
				$_SESSION['sjonsite_form_token'] = array();
			}
			while (count($_SESSION['sjonsite_form_token']) > 2) array_shift($_SESSION['sjonsite_form_token']);
			array_push($_SESSION['sjonsite_form_token'], $token);
			return $token;
		}

		public function checkToken ($token = null) {
			if (empty($token)) {
				$token = Sjonsite::$io->post('token');
			}
			return (isset($_SESSION['sjonsite_form_token']) && in_array($token, $_SESSION['sjonsite_form_token'], true));
		}

	}

/*
				<form action="/login" method="post">
					<fieldset>
						<legend>Authenticate</legend>
						<input type="hidden" name="token" value="" />
						<input type="hidden" name="hash" value="" />
						<input type="hidden" name="cmd" value="" />
						<div class="form-row">
							<label for="field-username">Username:</label>
							<div class="form-field">
								<input type="text" name="username" id="field-username" title="" value="" />
							</div>
						</div>
						<div class="form-row">
							<label for="field-password">Password:</label>
							<div class="form-field">
								<input type="password" name="password" id="field-password" title="" value="" />
							</div>
						</div>
						<div class="form-row">
							<div class="form-field">
								<button type="submit" title="">
									Authenticate
								</button>
							</div>
						</div>
					</fieldset>
				</form>
*/
