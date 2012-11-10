<?php
/**
*
*	@author Josef Karlsson <sphinxen83@gmail.com>
*	@package Lemonade
*/

if(!defined('BASE')) die('No direct access!');

class CError extends CController 
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		echo 'Unexpected error';
	}

	public function e404()
	{
		echo "error 404";
	}
}