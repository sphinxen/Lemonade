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

		// Load the content model
		$content = $this->load_model('Content');

		// Load all the page data from database
		$data['region'] = $content->get_all_content('home');

		$form = new Form();

		$form->set_validate_rules("data", "Data", "clean");

		$form->set_validate_rules("user", "Username or E-mail", "trim|required|clean");
		$form->set_validate_rules("password", "Password", "trim|required|md5");
		$form->set_validate_rules("confirm_pass", "Confirm Password", "trim|required");

		if($form->validate())
		{
			$this->login();
		}

		$login_menu = $form->start();
		$login_menu .= "<legend>Username or E-mail</legend>";
		$login_menu .= $form->input('text', array('name' => 'user'));
		$login_menu .= "<legend>Password</legend>";
		$login_menu .= $form->input('password', array('name' => 'password'));
		$login_menu .= "<br />";
		$login_menu .= $form->input('submit', array('value' => 'Login'));
		$login_menu .= "</form>";
		$login_menu .= $form->validate_error();

		$data['region']['content']['main'] = $login_menu;

		$this->load_view('default/default_view', $data);
	}
	public function logout()
	{
		$this->user->logout();

		redirect($cfg['default_page']);
	}
	
	public function login()
	{
		$form = new Form();

		$form->set_validate_rules("user", "User", "trim|clean|required");
		$form->set_validate_rules("password", "Password", "required|md5");

		if($form->validate())
		{
			$user_model = $this->load_model('Login');

			if($user_model->login($_POST['user'], $_POST['password']))
				redirect($cfg['default_page']);
			else
				redirect('user');
		}
		else
			redirect('user');
	}


}
