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
			$data['content']['main'] = "<p>In order for Lemonade to work proporly a database connection needs to be asstablished.
			<br />Open <strong>" . ROOT.'core/config.php'. "</strong> and enter the data to your database.";
		}

		elseif(!$db->table_exists('users'))
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


			
			if($form->validate())
			{
				$this->create_database();
			}
			


			$user_form = $form->start(null, "block");
			$user_form .= "<div>";
			$user_form .= "<label>Username</label>";
			$user_form .= $form->input('text', array('name' => 'username'));
			$user_form .= "<label>E-mail</label>";
			$user_form .= $form->input('text', array('name' => 'email'));
			$user_form .= "<label>Password</label>";
			$user_form .= $form->input('password', array('name' => 'password', 'autocomplete' => 'off'));
			$user_form .= "<label>Confirm Password</label>";
			$user_form .= $form->input('password', array('name' => 'confirm_pass', 'autocomplete' => 'off'));
			$user_form .= "<br />";
			$user_form .= $form->input('submit', array('value' => 'Create Account'));
			$user_form .= "</div>";
			$user_form .= "</form>";
			$user_form .= $form->validate_error();

			$data['content']['main'] = $user_form;
		}
		$this->load_view('default/default_view', $data);
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

		$this->load_view('default/default_view', $data);
	}

	private function create_database()
	{
		global $cfg;
		global $db;

		$salt = sha1($_POST['password']);

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
	 				CONSTRAINT FOREIGN KEY (`id_user`) REFERENCES `{$cfg['db']['prefix']}users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
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
				`action` VARCHAR(64) NOT NULL,
				`name` VARCHAR(64) NOT NULL DEFAULT 'default_name',
				`pubished` TINYINT NOT NULL DEFAULT '0'
			);

			CREATE TABLE IF NOT EXISTS `{$cfg['db']['prefix']}regions`
			(
				`id` INT PRIMARY KEY AUTO_INCREMENT,
				`id_parent_region` INT NULL,
					CONSTRAINT FOREIGN KEY (`id_parent_region`) REFERENCES `{$cfg['db']['prefix']}regions`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
				`region` VARCHAR(64) NOT NULL
			);

			CREATE TABLE IF NOT EXISTS `{$cfg['db']['prefix']}page_data`
			(
				`id` INT PRIMARY KEY AUTO_INCREMENT,
				`id_page` INT NOT NULL,
					CONSTRAINT FOREIGN KEY (`id_page`) REFERENCES `{$cfg['db']['prefix']}pages`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
				`id_region` INT NOT NULL,
					CONSTRAINT FOREIGN KEY (`id_region`) REFERENCES `{$cfg['db']['prefix']}regions`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
				`content` LONGTEXT NULL
			);

			INSERT INTO `{$cfg['db']['prefix']}pages` (`controller`, `action`, `name`, `pubished`)
				VALUES
					('default_controller', 'index', 'home', 1);

			INSERT INTO `{$cfg['db']['prefix']}users` (`username`, `email`, `password`) 
				VALUES 
					('{$_POST['username']}', '{$_POST['email']}', '{$salt}{$_POST['password']}');

			INSERT INTO `{$cfg['db']['prefix']}regions` (`id`, `id_parent_region`, `region`)
				VALUES
					 (1, NULL, 'header')
					,(2, 1, 'logo')
					,(3 , NULL, 'content')
					,(4, 3, 'left')
					,(5, 3, 'main')
					,(6, 3, 'right')
					,(7, NULL, 'footer');
EOD;


		$db->connect();
		$db->multi_query($query);
		$db->close();

		redirect($cfg['default_page']);
	}
}