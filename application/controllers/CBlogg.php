<?php
/**
*	@author Josef Karlsson <sphinxen83@gmail.com>
*	@package Lemonade
*/

if(!defined('BASE')) die('No direct access!');


class CBlogg extends CController 
{
	private $blogg;
	private $template;
	public function __construct()
	{
		parent::__construct();
		$this->blogg = $this->load_model('Blogg');
		$this->template = 'templates/post';
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

		// Fetch all posts
		$posts = $this->blogg->getPosts();

		// Sends the form data to the template an returns a HTML formated string
		foreach ($posts as $post) 
			$data['region']['content']['main'] .= $this->postFormat($post, "post", "post");

		// Check if user is logged in and display a form
		if(isset($_SESSION['id']))
			$data['region']['content']['main'] .=  $this->form($post_id);

		$this->load_view('default/default_view', $data);
	}

	public function delete($post_id)
	{
		// Load the data from config file
		global $cfg;
		$data = $cfg['data'];

		/**
		 * @todo The "Post" class don't return affected_rows
		 */
		// Display status message
		if($this->blogg->delete($post_id))
			$data['region']['content']['main'] = "Post deleted";
		else
			$data['region']['content']['main'] = "Post delete fail";
		$this->load_view('default/default_view', $data);
	}

	public function edit($post_id, $comment = -1)
	{
		// Load the data from config file
		global $cfg;
		$data = $cfg['data'];

		$data['region']['content']['main'] = $this->form($post_id, $comment, true);

		$this->load_view('default/default_view', $data);
	}

	public function add($post_id = -1, $comment = -1)
	{
		// Load the data from config file
		global $cfg;
		$data = $cfg['data'];

		$data['region']['content']['main'] = $this->form($post_id, $comment);

		$this->load_view('default/default_view', $data);
	}

	public function view($post_id)
	{
		// Load the data from config file
		global $cfg;
		$data = $cfg['data'];

		$post = $this->blogg->getPost($post_id);

		// Get the post data
		$data['region']['content']['main'] .= $this->postFormat($post, "post", "post");

		foreach ($post['children'] as $comment)
			$data['region']['content']['main'] .= $this->postFormat($comment, NULL, "comment");

		$data['region']['content']['main'] .= $this->form();
		$this->load_view('default/default_view', $data);
	}

	/**
	 * Creates a form for create and update posts
	 *
	 * @param  integer $post_id ID of the post to be edited, -1 if new post
	 * @param  integer $comment ID of the comment to be edite, -1 if new comment
	 * @param  boolean $edit    Flag for edit, false if new post
	 * @return string           Return a html form
	 */
	private function form($post_id = -1, $comment = -1, $edit = false)
	{
		$form = new Form();

		$form->set_validate_rules("data", "Data", "clean|required");

		if($form->validate())
		{
			$status = "<p class='error'>Update post failed</p>";

			if($_POST['post_id'] > 0 && $_POST['comment'] == 0)
			{
				if($this->blogg->add($_POST['data'], $_SESSION['id'], $_POST['post_id']))
					$status = "<p class='succeed'>Comment succesfully created</p>";
			}elseif($_POST['post_id'] > 0 && $_POST['comment'] > 0)
			{
				if($this->blogg->edit($_POST['comment'], $_POST['data']))
					$status = "<p class='succeed'>Succesfully updated comment</p>";
			}elseif($_POST['post_id'] > 0)
			{
				if($this->blogg->edit($_POST['post_id'], $_POST['data']))
					$status = "<p class='succeed'>Succesfully updated post</p>";
			}else
			{
				 if($this->blogg->add($_POST['data'], $_SESSION['id']))
				 	$status = "<p class='succeed'>Post succesfully created</p>";
			}
		}
		$post = $edit ? $this->blogg->getPost($post_id, true) : "";

		$data = $status."<fieldset class='clearfix inline-block'>";
		$data .= $form->start("post");

		$data .= $form->textarea(array('class' => 'wymeditor', 'value' => $post['post'], 'name' => 'data', 'width' => '550px'));
		$data .= $form->input('hidden', array('value' => $post_id, 'name' => 'post_id'));
		$data .= $form->input('hidden', array('value' => $comment, 'name' => 'comment'));
		$data .= $form->input('submit', array('class' => 'wymupdate','value' => 'Save', 'style' => 'width:100%'));
		$data .= "</form></fieldset>";

		return $data;
	}


	/**
	 * Returns the view of a post template
	 *
	 *
	 * @param  mix[]  $data  An array of post data
	 * @param  string $id    The post id
	 * @param  string $class A class name för style
	 * @return string        A html post template
	 */
	private function postFormat($data, $id = "post", $class = "post")
	{
		global $cfg;

		// Check the search path of this controller
		foreach ($cfg['controllers'] as $path => $value) {
			if($value['class'] == get_class($this)) {
				$data['url'] = '/'.$path;
				break;
			}
		}

		// include gravatar
		$data['grav_url'] = "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $data['email'] ) ) ) . "?d=mm&s=80";

		$data['post_id'] = $data['id'];

		$data['id'] = $data['post_id'].'_'.$id;
		$data['class'] = $class;

		return $this->load_view($this->template, $data, TRUE);
	}
}

