<?php 
/**
*	@author Josef Karlsson <sphinxen83@gmail.com>
*	@package Lemonade
*/

if(!defined('BASE')) die('No direct access!');

class Login
{
	public function login($user = null, $password = null)
	{
		global $db;

		if($user == null || $password == null)
			return false;

		if(isset($_SESSION['user']))
			return false;

		$salt = sha1($password);
		$db->connect();

		$result = $db->query("SELECT * FROM `users` WHERE `username` = '{$user}' OR `email` = '{$user}' AND `password` = '{$salt}{$password}'");
		
		if($result->num_rows > 0)
		{
			$row = $result->fetch_array();

			$_SESSION['id'] = $row['id'];
			$_SESSION['user'] = $row['username'];
			$db->close();
			return true;
		}
		
		return false;
	}

	public function logout()
	{
		// Unset all of the session variables.
		$_SESSION = array();

		// Finally, destroy the session.
		session_destroy();
	}
}