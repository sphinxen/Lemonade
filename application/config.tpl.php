<?php
/**
*	@author Josef Karlsson <sphinxen83@gmail.com>
*	@package Lemonade
*
*/

if(!defined('BASE')) die('No direct access!');

$cfg = array(

/**
* Variables for database connection (MySQL)
*/
'db' => array(
		 'server' 	=> 'localhost'
		,'username' => ''
		,'password' => ''
		,'database' => 'lemonade'
		,'charset' 	=> 'utf8'
		,'prefix' 	=> ''
	)

/**
*	Routing settings
*
* 	Defines a controller to be redirected to. The key represents the URL request.
* 	The value can either be a controller class or a relative URL.
*
* 	@example 'home' => 'away' or 'home' => 'CSetup'
*/
,'routes' => array(
	'error' => 'CError'
	,'setup' => 'CSetup'
	,'content' => 'CContent'
	,'login' => 'CLogin'
	,'user' => 'CUser'
)

/**
*	Set the page to load as default
*	Default is 'index'
*/
,'default_page' => 'index'

/**
*	Set the session name
*
*/
,'session_name' => 'lemonade'


/**
*	Som default data that are used when generating view
*
*/
,'data' => array(

	'menu' => array(

		//	Defines the admin-menu only shown when a user with admin rights are logged in
		'admin' => array(
					'id' => 'admin-nav'
					,'class' => 'menu'
					,'items' => array(
						'Manage Content' => array('path' => BASE.'content/', 'id' => NULL, 'class' => NULL)
						)
				)

		,'user' => array(
					'id' => 'user-nav'
					,'class' => 'menu'
					,'items' => array(
						'Logout' =>  array('path' => BASE.'user/logout', 'id' => NULL, 'class' => NULL)
						)
				)
		)

	)

);
