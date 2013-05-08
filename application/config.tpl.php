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
*	Defines the controllers
*
*	'core' attribute decides if the controller is part of the Lemonade core. 
*	If set to FALSE a navigation menu is able to be generated.
*
*	'enabled' attribue decides if the controller is enable.
*	If set to FALSE the controller won't be able to load.
*/
,'controllers' => array(
	 'index' => array('class' => 'default_controller', 'core' => FALSE, 'enabled' => TRUE)

	,'error' => array('class' => 'CError', 'core' => TRUE, 'enabled' => TRUE)
	,'setup' => array('class' => 'CSetup', 'core' => TRUE, 'enabled' => TRUE)
	,'content' => array('class' => 'CContent', 'core' => TRUE, 'enabled' => TRUE)
	,'login' => array('class' => 'CLogin', 'core' => TRUE, 'enabled' => TRUE)
	,'user' => array('class' => 'CUser', 'core' => TRUE, 'enabled' => TRUE)
	)

/**
*	Set the page to load as default
*	Default is 'index'
*/
,'default_page' => 'index'

,'session_name' => 'lemonade'


,'data' => array(

	// Sets a text that will be shown at the top of the page next to the logo
	'textlogo' => 'Lemonade'

	// Sets a logo that will be shown at the top of the page next to the textlogo
	,'logo' => BASE.'assets/images/logo.svg'


	,'menu' => array(

		// Defines the main-menu
		'main' => array(
					'id' => 'main-nav'
					,'class' => NULL
					,'items' => array(
						 'Home' => array('path' => BASE.'index', 'id' => NULL, 'class' => NULL)
						)
				)

		//	Defines the admin-menu only shown when a user with admin rights are logged in
		,'admin' => array(
					'id' => 'admin-nav'
					,'class' => NULL
					,'items' => array(
						'Manage Content' => array('path' => BASE.'content/', 'id' => NULL, 'class' => NULL)
						)
				)

		,'user' => array(
					'id' => 'user-nav'
					,'class' => NULL
					,'items' => array(
						'Logout' =>  array('path' => BASE.'user/logout', 'id' => NULL, 'class' => NULL)
						)
				)
		)

	// Defines slylesheets
	,'stylesheets' => array(
				BASE.'assets/css/stylesheet.css'
				,BASE.'assets/js/markitup/skins/markitup/style.css'
				,BASE.'assets/js/markitup/sets/default/style.css'
				)


	// Defines javascript files
	,'javascripts' => array(
				 BASE.'assets/js/jquery-1.7.1.js'
				//,BASE.'assets/js/jquery.colorbox.js'
				,BASE.'assets/js/markitup/jquery.markitup.js'
				,BASE.'assets/js/markitup/sets/default/set.js'
				,BASE.'assets/js/lemonade.js'
				)

	// Defines the title of the page shown by the browser
	,'title' => 'Lemonade'
	)

);