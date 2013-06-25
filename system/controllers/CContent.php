<?php  
/**
*
*	@author Josef Karlsson <sphinxen83@gmail.com>
*	@package Lemonade
*/

if(!defined('BASE')) die('No direct access!');
if(!isset($_SESSION['user'])) die('Not logged in!');

class CContent extends CController 
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Manage page contents
	 * 
	 * @return [type] [description]
	 */
	public function index()
	{	
		// Load the data from config file
		global $cfg;
		$data = $cfg['data'];

		// Load the content model
		$content = $this->load_model('Content');

		// Get all the available pages from database
		foreach ($content->get_pages() as $key) 
		{
			$pages[$key['id']] = $key['name'];
		}

		// Get all the available regions from the database
		foreach ($content->get_regions() as $key) {
			if($key['parent'])
				$regions[$key['id']] .= $key['parent'].'-'.$key['region'];
			else
				$regions[$key['id']] .= $key['region'];
		}

		$form = new Form();

		$form->set_validate_rules("data", "Data", "clean");

		if($form->validate())
		{
			$this->save();
		}

		$data['region']['content']['main'] = "<fieldset class='clearfix inline-block'><legend>Page</legend>";
		$data['region']['content']['main'] .= $form->start("content_form");
		$data['region']['content']['main'] .= "<lable>Select page <a class='right' href='".BASE."content/addPage'>Add page</a></lable>";
		$data['region']['content']['main'] .= $form->select($pages, array('style' => 'width:100%', 'name' => 'page'));
		$data['region']['content']['main'] .= "<lable>Select region <a class='right' href=".BASE."content/addRegion'>Add region</a></lable>";
		$data['region']['content']['main'] .= $form->select($regions, array('style' => 'width:100%', 'name' => 'region'));
		$data['region']['content']['main'] .= "<lable></lable>";
		$data['region']['content']['main'] .= $form->textarea(array('class' => 'ckeditor','name' => 'data'));
		$data['region']['content']['main'] .= "<lable></lable>";
		$data['region']['content']['main'] .= $form->input('submit', array('value' => 'Save', 'style' => 'width:100%'));
		$data['region']['content']['main'] .= "</form></fieldset>";

		$this->load_view('default', $data);
	}

	/**
	 * [get_content description]
	 * @return [type] [description]
	 */
	public function getContent()
	{//echo "string";
		// Load the content model
		$content = $this->load_model('Content');

		$result = $content->get_content($_POST['id_page'], $_POST['id_region']);
		echo $result;
	}

	public function addPage()
	{
		// Load the data from config file
		global $cfg;
		$data = $cfg['data'];


		// Load the content model
		$content = $this->load_model('Content');

		// Get all the available pages from database
		$pages[0] = '- New Page -';  
		foreach ($content->get_pages() as $key) 
		{
			$pages[$key['id']] = $key['name'];
		}

		// Get all the available regions from the database
		foreach ($content->get_regions() as $key) {
			if($key['parent'])
				$regions[$key['id']] .= $key['parent'].'-'.$key['region'];
			else
				$regions[$key['id']] .= $key['region'];
		}

		$form = new Form();

		$form->set_validate_rules("page", "Page name", "required");

		if($form->validate())
		{
			global $db;
			$db.connect();


			redirect(BASE."content");
		}

		$data['region']['content']['main'] = "<fieldset class='clearfix inline-block'><legend>New page</legend>";
		$data['region']['content']['main'] .= $form->start();
		$data['region']['content']['main'] .= "<lable>Parent page</lable>";
		$data['region']['content']['main'] .= $form->select($pages, array('style' => 'width:100%', 'name' => 'page'));
		$data['region']['content']['main'] .= "<lable>Page name</lable>";
		$data['region']['content']['main'] .= $form->input('text', array('style' => 'width:100%', 'name' => 'page'));
		$data['region']['content']['main'] .= $form->input('submit', array('value' => 'Save', 'style' => 'width:100%'));
		$data['region']['content']['main'] .= "</form></fieldset>";
		$data['region']['content']['main'] .= $form->validate_error();

		$this->load_view('default', $data);
	}

	public function addRegion()
	{

	}
	/**
	*
	*
	*/
// 	public function ManageContent()
// 	{
// 		global $cfg;
// 		$base = BASE;
// 		$data['region']['header']['headline'] = 'Lemonade';

// 		$data['menu']['main'] = array(
// 					'id' => 'main-nav'
// 					,'class' => NULL
// 					,'items' => array(
// 						 'Home' => array('path' => BASE.'index', 'id' => NULL, 'class' => NULL)
// 						)
// 				);

// 		$data['stylesheets'] = array(BASE.'assets/css/stylesheet.css');
// 		$data['javascripts'] = array('');

// 		$data['title'] = 'Lemonade';

// 		$data['logo'] = BASE.'assets/images/logo.svg';
// 		$data['menu']['sub'] = array(
// 					'id' => 'sub-nav'
// 					,'class' => NULL
// 					,'items' => array(
// 						 'New page' => array('path' => BASE.'content/index', 'id' => NULL, 'class' => NULL)
// 						 ,'New content' => array('path' => BASE.'content/newcontent', 'id' => NULL, 'class' => NULL)
// 						)
// 				);
// 		$submenu = $this->menu->GenerateNavigation($data['menu']['sub'], FALSE);
// 		$data['region']['content']['left'] = <<<EOD
// 		<div class='span-5'>
// 			{$submenu}
// 		</div>
// EOD;

// 		foreach ($cfg['controllers'] as $key => $value) 
// 		{
// 			if(!$value['core'])
// 				$pages .= "<option value=".$value['class'].">".$key."</option>";
// 		}

// 		$data['region']['content']['main'] = <<<EOD
// 		<div class='clearfix inline-block'>
// 		<form>
// 		<legend>Select page <a class="right" href="{$base}content/_addpage">Add page</a></legend>
// 		<select style="width:100%">
//   			{$pages}
// 		</select> 
// 		<legend>Select region <a class="right" href="{$base}content/_addregion">Add region</a></legend>
// 		<select style="width:100%">
//   			<option value="header">Header</option>
//   			<option value="content-left">Content-left</option>
//  			<option value="content-main">Content-main</option>
//   			<option value="content-right">Content-right</option>
//  			<option value="footer">Footer</option>
// 		</select> 
// 		<legend></legend>
// 		<textarea></textarea>
// 		<legend></legend>
// 		<input type="submit" />
// 		</form>
// 		</div>
// EOD;

// 		$this->load_view('default', $data);
// 	}


	public function save()
	{
		$form = new Form();

		$form->set_validate_rules("data", "Data", "clean");

		if($form->validate())
		{
			$content = $this->load_model('Content');

			$content->save();
		}
	}
}