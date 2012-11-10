<?php
/**
 * 
 */

if(!defined('BASE')) die('No direct access!');

class User
{
	public function login($user, $pass)
	{

		$query = "SELECT `U`.*, `R`.`role` FROM `users` AS `U` 
			INNER JOIN `user_roles` AS `UR`
			ON `U`.`id` = `UR`.`id_user`
				INNER JOIN `roles` AS `R`
				ON `UR`.`id_role` = `R`.`id`
			WHERE `U`.`username` = '{$user}' OR `U`.`email` = '{$user}'";

		$db = Database::GetInstance();

		$db->connect();
		$res = $db->query($query);

		$row = $res->fetch_array();
		$db->close();

		if($row['password'] != $pass)
			return FALSE;

		$_SESSION['user']['id'] = $row['id_user'];
		$_SESSION['user']['role'] = $row['role'];


		return TRUE;
	}

	public function logout()
	{
		unset($_SESSION['user']);
		session_destroy();
		return TRUE;
	}
}