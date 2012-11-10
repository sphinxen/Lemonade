<?php
/**
*
*	@author Josef Karlsson <sphinxen83@gmail.com>
*	@package Lemonade
*/

if(!defined('BASE')) die('No direct access!');

class CSetup extends CController 
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		global $db;
		if($db->connect_error)
		{
			echo "<p>In order for Lemonade to work proporly a database connection needs to be asstablished.
			<br />Open <strong>" . ROOT.'core/config.php'. "</strong> and enter the data to your database.";
		}

		if(!is_writable(ROOT.'application/controllers/'))
		{
			echo "<p>Lemonade need writing permission to the following folders: ";
			if(!is_writable(ROOT.'application/controllers/'))
				echo "<br><strong>".ROOT."application/controllers/</strong>";
		}

		if(!$db->table_exists('users'))
		{
			$data['stylesheets'] = array(BASE.'assets/css/stylesheet.css');
			$data['logo'] = BASE.'assets/images/logo.svg';
			$data['textlogo'] = 'Lemonade';
			$data['title'] = 'Setup';

			$form = new Form();

			$form->set_validate_rules("username", "Username", "trim|clean");
			$form->set_validate_rules("email", "E-mail", "trim|required|clean");
			$form->set_validate_rules("password", "Password", "trim|required|matches<confirm_pass>|md5");
			$form->set_validate_rules("confirm_pass", "Confirm Password", "trim|required");


			
			if(!$form->validate())
			{
				$this->create_database();
			}
			


			$user_form = $form->start(null, "block");
			$user_form .= "<fieldset>";
			$user_form .= "<label>Username</label>";
			$user_form .= $form->input('text', array('name' => 'username'));
			$user_form .= "<label>E-mail</label>";
			$user_form .= $form->input('text', array('name' => 'email'));
			$user_form .= "<label>Password</label>";
			$user_form .= $form->input('text', array('name' => 'password'));
			$user_form .= "<label>Confirm Password</label>";
			$user_form .= $form->input('text', array('name' => 'confirm_pass'));
			$user_form .= "<br />";
			$user_form .= $form->input('submit', array('value' => 'Create Account'));
			$user_form .= "</fieldset>";
			$user_form .= "</form>";
			$user_form .= $form->validate_error();

			$data['content']['main'] = $user_form;

			$this->view('default/default_view', $data);
		}
	}

	/**
	*	Adds new page content
	*	
	*/
	public function add()
	{
		$data['header'] = 'header';
		$data['content'] = 'Add Content';
		$data['footer'] = 'footer';
		$data['title'] = 'Lemonade';
		$data['headline'] = 'Lemonade';
		$data['stylesheets'] = array(BASE.'assets/css/stylesheet.css');
		$data['javascripts'] = array(BASE.'assets/css/javascript.js');

		$data['logo'] = BASE.'assets/images/logo.svg';

		$this->view('default/default_view', $data);
	}

	private function create_database()
	{
		global $cfg;
		global $db;


		$query = <<<EOD
			CREATE TABLE IF NOT EXISTS `{$cfg['db']['prefix']}users`	
	 		(
		 		`id` INT PRIMARY KEY AUTO_INCREMENT,
				`username` VARCHAR(20) NULL,
		 		`email` VARCHAR(64) NOT NULL,
				`password` VARCHAR(64) NOT NULL
	 		);
	 		
	 		CREATE TABLE IF NOT EXISTS `{$cfg['db']['prefix']}userdata`
	 		(
	 			`id_user` INT NOT NULL,
	 				CONSTRAINT FOREIGN KEY (`id_user`) REFERENCES `{$cfg['db']['prefix']}users`(`id`) ON UPDATE CASCADE,
	 			`first_name` VARCHAR(64) NULL,
		 		`last_name` VARCHAR(64) NULL,
		 		`birthdate` VARCHAR(12) NULL, 
		 		`address` VARCHAR(64) NULL,
		 		`zipcode` INT (10) NULL,
		 		`city` VARCHAR(32) NULL,
		 		`country` VARCHAR(64) NULL,
		 		`gender` CHAR(1) NULL,
		 		`avatar` BLOB NULL
	 		);

			CREATE TABLE IF NOT EXISTS `{$cfg['db']['prefix']}pages`
			(
				`id` INT PRIMARY KEY AUTO_INCREMENT,
				`controller` VARCHAR(64) NOT NULL,
				`action` VARCHAR(64) NOT NULL
			);

			CREATE TABLE IF NOT EXISTS `{$cfg['db']['prefix']}regions`
			(
				`id` INT PRIMARY KEY AUTO_INCREMENT,
				`region` VARCHAR(64) NOT NULL
			);

			CREATE TABLE IF NOT EXISTS `{$cfg['db']['prefix']}page_data`
			(
				`id` INT PRIMARY KEY AUTO_INCREMENT,
				`id_page` INT NOT NULL,
					CONSTRAINT FOREIGN KEY (`id_page`) REFERENCES `{$cfg['db']['prefix']}pages`(`id`) ON UPDATE CASCADE,
				`id_region` INT NOT NULL,
					CONSTRAINT FOREIGN KEY (`id_region`) REFERENCES `{$cfg['db']['prefix']}regions`(`id`) ON UPDATE CASCADE,
				`content` LONGTEXT NULL
			);

			INSERT INTO `{$cfg['db']['prefix']}users` (`username`, `email`, `password`) 
				VALUES 
					('{$_POST['username']}', '{$_POST['email']}', '{$_POST['password']}');

			INSERT INTO `{$cfg['db']['prefix']}regions` (`id`, `id_parent_region`, `region`)
				VALUES
					 (1, NULL, 'header')
					,(2, 1, 'logo')
					,(3 , NULL, 'content')
					,(4, 2, 'left')
					,(5, 2, 'main')
					,(6, 2, 'right')
					,(7, NULL, 'footer');
EOD;

		$db->connect();
		$db->multi_query($query);
		$db->close();

		redirect(BASE.$cfg['default_page']);
	}
}