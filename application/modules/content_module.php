<?php
/**
*	@author Josef Karlsson <sphinxen83@gmail.com>
*	@package Lemonade
*/
class Content_module 
{
	private $db;

	public function __construct()
	{
		global $db;
		$this->db = $db;
	}

	/**
	*	@param string
	*	@return mixed
	*/
	public function GetData($page)
	{
		$query = "SELECT * FROM `data` WHERE `id`"
	}
}