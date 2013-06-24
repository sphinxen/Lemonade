<?php
/**
*	Lemonade is a light-weight MVC
*
*	@author Josef Karlsson <sphinxen83@gmail.com>
*	@package Lemonade
*
*/

if(!defined('BASE')) die('No direct access!');

class Lemonade_blender implements ISingleton
{
	protected static $instance = null;

	private function __construct()
	{
		global $cfg;
		session_name($cfg['session_name']);
		session_start();

		set_exception_handler(array($this, 'DefaultExceptionHandler'));

		global $db;
		$db = Database::GetInstance();
	}

	/**
	* 	Make sure only one instance is running simultaniously
	*
	*	@return Lemonade_blender
	*/
	public static function GetInstance()
	{
        if(self::$instance == null)
            self::$instance = new self();
        return self::$instance;
    }

	/**
    *	Create a common exception handler 
    *
    *	@param Exception $aException
    */
	public static function DefaultExceptionHandler($aException)
	{
	    die("<h3>Exceptionhandler</h3><p>File " . $aException->getFile() . " at line" . $aException->getLine() ."<p>Uncaught exception: " . $aException->getMessage() . "<pre>" . print_r($aException->getTrace(), true) . "</pre>");
	}

	/**
	*	Take care of the requested url and load the controller if exists
	*/
	public function FrontController()
	{
		global $cfg;
		global $segment;
		global $db;

		$db->connect();

		// 	Check if a connection to the database could be established and that application/controller folder is writable,
		//  otherwise load the setup page
		if( $db->connect_error || !$db->table_exists('users') )
			$segment[0] = !empty($segment[0]) ? $segment[0] : 'setup';
		else
		{
			$segment = explode('/', strtolower(substr($_SERVER['REQUEST_URI'], strlen(BASE))));

			$segment[0] = !empty($segment[0]) ? $segment[0] : $cfg['default_page'];
		}
		$segment[1] = !empty($segment[1]) ? $segment[1] : 'index';

		$controller = $segment[0];
		$action = $segment[1];
		$args = $segment;
		unset($args[0]);
		unset($args[1]);


		//	Check if the requested controller exists and that it's a subclass of CController
		$routExists = isset($cfg['routes'][$controller]);
		$classExists = FALSE;
		$classEnable = FALSE;

		$class = $routExists ? $cfg['routes'][$controller] : $controller;
		$classExists = class_exists($class);

		if($classExists)
		{
			$rc = new ReflectionClass($class);
			if($rc->isSubclassOf('CController'))
			{
				if($rc->hasMethod($action))
				{
					$controllerObj = $rc->newInstance();
					$method = $rc->getMethod($action);
					$method->invokeArgs($controllerObj, $args);
				}
				// else
					// header('location: http://' . $_SERVER['SERVER_NAME'] . BASE. 'error/e404');
			}
		}
		// else header('location: http://'. $_SERVER['SERVER_NAME'] . BASE. 'error/e404');
	}
}