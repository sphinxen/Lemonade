<?php 
/**
*
*	@author Josef Karlsson <sphinxen83@gmail.com>
*	@package Lemonade
*/

if(!defined('BASE')) die('No direct access!');

class Database extends mysqli implements ISingleton
{
	protected static $instance = null;

	//private $prefix;

	public function connect()
	{
		global $cfg;
		parent::__construct($cfg['db']['server'], $cfg['db']['username'], $cfg['db']['password'], $cfg['db']['database']);
		//$this->prefix = $cfg['db']['prefix'];
		$this->set_charset($cfg['db']['charset']);
	}

	/**
	* 	Make sure only one instance is running simultaniously
	*/
	public static function GetInstance()
	{
        if(self::$instance == null)
            self::$instance = new self();
        return self::$instance;
    }

    /**
    *	Check if a tables exists
    *
    *	@param string $TableName
    * 	@return bool
    */
    public function table_exists($name)
    {
    	$result = $this->query("SHOW TABLES LIKE '".$name."'");
    	
    	return $result->num_rows;
    }
}
