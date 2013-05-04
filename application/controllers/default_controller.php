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

		// Load models
		$content = $this->load_model('Content');

		// Load all the page data from database
		$data['region'] = $content->get_all_content('home');

		/*
		*	Pagedata goes here
		*/

		$this->load_view('default/default_view', $data);
	}
}
