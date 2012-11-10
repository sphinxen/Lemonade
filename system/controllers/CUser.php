<?php
/**
*	@author Josef Karlsson <sphinxen83@gmail.com>
*	@package Lemonade
*/

class CUser extends CController 
{
	private $user;
	public function __construct()
	{
		parent::__construct();

		$this->user = new User();
	}

	/**
	*
	*
	*/
	public function index()
	{
		// Load the data from config file
		global $cfg;
		$data = $cfg['data'];

		// Load the content module
		$content = $this->module('Content');

		// Load all the page data from database
		$data['region'] = $content->get_all_content('home');

		$form = new Form();

		$form->set_validate_rules("data", "Data", "clean");

		$form->set_validate_rules("username", "Username", "trim|clean");
		$form->set_validate_rules("email", "E-mail", "trim|required|clean");
		$form->set_validate_rules("password", "Password", "trim|required|md5");
		$form->set_validate_rules("confirm_pass", "Confirm Password", "trim|required");

		if(!$form->validate())
		{
			$this->_login();
		}

		$user_form = $form->start();
		$user_form .= "<legend>Username or E-mail</legend>";
		$user_form .= $form->input('text', array('name' => 'user'));
		$user_form .= "<legend>Password</legend>";
		$user_form .= $form->input('password', array('name' => 'password'));
		$user_form .= "<br />";
		$user_form .= $form->input('submit', array('value' => 'Login'));
		$user_form .= "</form>";
		$user_form .= $form->validate_error();

		$data['region']['content']['main'] = $user_form;

		$this->view('default/default_view', $data);
	}
	public function logout()
	{
		$this->user->logout();

		redirect(BASE.'index');
	}
	private function _login()
	{
		if($this->user->login($_POST['user'], $_POST['password']));
		
		redirect(BASE.'index');
	}


}
