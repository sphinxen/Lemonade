<?php
/**
* Bootstrapping, setting up and load the core
* @author Josef Karlsson <sphinxen83@gmail.com>
* @package Lemonade
*/

if(!defined('BASE')) die('No direct access!');

$base_url = BASE;

/**
*	Load the config file(s)
*	@example require_once 'application/config.php';
*/
if(file_exists(ROOT.'application/config.php'))
   require_once ROOT.'application/config.php';
else
//   require_once ROOT.'application/config.tpl.php';
   die("Can't locate the 'config.php' file in the application folder. <br>Please generate \"application/config.php\" or copy \"application/config.tpl.php\" in your install directory and configure it to match your setup.");

/**
 * 	Enable auto-load of class declarations.
 *
 *	@param string $sClassName
 */
function __autoload($aClassName)
{
        $file1 = ROOT . "system/controllers/{$aClassName}.php";
        $file2 = ROOT . "application/controllers/{$aClassName}.php";
        $file3 = ROOT . "system/{$aClassName}/{$aClassName}.php";
        $file4 = ROOT . "system/models/{$aClassName}.php";
        if(is_file($file1))
                require_once($file1);
        elseif(is_file($file2))
                require_once($file2);
        elseif(is_file($file3))
        	require_once($file3);
        elseif(is_file($file4))
                require_once($file4);
}

/**
*	Redirect page to given url
*
*	@param string $url Absoule or relative URL path
*/
function redirect($url)
{
    if(preg_match_all('/(http:\/\/|ftp:\/\/|https:\/\/)([^ \s\t\r\n\v\f]*)\.([A-Za-z]*)/', $url))
    	header('location: '.$url);
    header('location: '. BASE . $url);
}

interface ISingleton
{
        public static function GetInstance();
}

interface IController
{
        public function Index();
}
