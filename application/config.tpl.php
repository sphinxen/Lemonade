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