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

		// Load the content module
		$content = $this->module('Content');

		// Load all the page data from database for specified page
		$data['region'] = $content->get_all_content('home');	

		/*
		*	Pagedata goes here
		*/

		$this->view('default/default_view', $data);
	}

	public function guestbook()
	{
		// Load the data from config file
		global $cfg;
		$data = $cfg['data'];

		// Load modules
		$content = $this->module('Content');

		// Load all the page data from database
		$data['region'] = $content->get_all_content('guestbook');	




		
		$gb = $this->module('Guestbook');
		//$gb = new Guestbook();
		
		
		// $gb->posts->add("test", 1);
		// $gb->comments->add("comment", 1 , 1);
		// $gb->posts->delete(5);
		// $gb->posts->edit(4, "en ny text");
		// $result = $gb->posts->get(4);
		// $result = $gb->search("test");
		// if($result == null)
		// 	echo 'null';
		// 	$post = $result->fetch_array();
		// $result->close();
		// echo "<pre>".print_r($post, true). "</pre>";
		$data['region']['content']['main'] = $gb->getGuestbook();

		$this->view('default/default_view', $data);

	}
}
