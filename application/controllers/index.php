<?php
/**
*	@author Josef Karlsson <sphinxen83@gmail.com>
*	@package Lemonade
*/
class Index extends CController 
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

		$data['title'] = "Lemonade";

		// Load models
		$content = $this->load_model('Content');

		// Load all the page data from database
		$data['content'] = $content->get_all_content('home');

		/*
		*	Pagedata goes here
		*/

		$this->load_view('default', $data);
	}
}
