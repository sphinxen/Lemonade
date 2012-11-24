<?php
/**
*	@author Josef Karlsson <sphinxen83@gmail.com>
*	@package Lemonade
*/
class Default_controller extends CController 
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	*
	*
	*/
	public function index()
	{
		// Load the data from config file
		global $cfg;
		$data = $cfg['data'];

		// Load modules
		$content = $this->load_module('Content');
		$gb = $this->load_module('Blogg');

		// Load all the page data from database
	//	$data['region'] = $content->get_all_content('guestbook');	

		/*
		*	Pagedata goes here
		*/

		$data['region']['content']['main'] = $gb->getBlogg();

		$this->load_view('default/default_view', $data);
	}

	public function save()
	{

	}
}
