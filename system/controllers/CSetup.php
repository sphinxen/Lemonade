<?php
/**
*	Part of Lemonade base controllers
*
*	@package    Lemonade
*	@subpackage Core
*	@author     Josef Karlsson <sphinxen83@gmail.com>
*	@link       (Sphinxen.se, http://www.sphinxen.se)
*/

if(!defined('BASE')) die('No direct access!');

/**
 * Setup controller for quick step by step setup
 *
 * @package    Lemonade
 * @subpackage Controller
 */
class CSetup extends CController
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		global $db;
		global $cfg;

		$data['title'] = "Setup";

		if($db->connect_error)
		{
			switch ($db->connect_errno) {
				case '1045':
					$message = "Access denied for user \"{$cfg['db']['username']}\" and password \"{$cfg['db']['password']}\". Please verify username and password.";
					break;
				case '2005':
					$message = "Unable to connect to \"{$cfg['db']['server']}\". Please verify the server address.";
					break;
				default:
					$message = $db->connect_error;
					break;
			}

			$data['content']['main'] = "<h3><strong>Database connection error</strong></h3><p>".$message."</p><p>In order for Lemonade to work proporly a database connection needs to be established.
					<br />Open <strong>application/config.php</strong> within the install directory and enter your database information.</p><p>Also verify that the database exists and that you have the right to access it.</p>";
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

		$this->load_view('default', $data);
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

		$this->load_view('default', $data);
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
				`region` VARCHAR(64) NOT NULL,
				`editable` TINYINT NOT NULL DEFAULT '1'
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
					('index', 'index', 'home', 1);

			INSERT INTO `{$cfg['db']['prefix']}users` (`username`, `email`, `password`) 
				VALUES 
					('{$_POST['username']}', '{$_POST['email']}', '{$salt}{$_POST['password']}');

			INSERT INTO `{$cfg['db']['prefix']}regions` (`id`, `region`, `editable`)
				VALUES
					 (1, 'header', 1)
					,(2, 'logo', 1)
					,(3 , 'content', 0)
					,(4, 'left', 1)
					,(5, 'main', 1)
					,(6, 'right', 1)
					,(7, 'footer', 1);
EOD;


		$db->connect();
		$db->multi_query($query);
		$db->close();

		redirect($cfg['default_page']);
	}
}
