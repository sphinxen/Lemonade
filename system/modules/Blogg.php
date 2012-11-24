<?php

if(!defined('BASE')) die('No direct access!');

class Blogg
{
	// public $comments;
	public $posts;

	// private $tab_comments;
	private $tab_posts;
	private $url;

	public function __construct($tab_posts = "blogg_posts", $controller = "CBlogg")//, $tab_comments = "gb_comments")
	{	
		global $cfg;

		// $this->comments = new Post($tab_comments, $tab_posts);
		$this->posts = new Post($tab_posts, "parent");
		// $this->tab_comments = $tab_comments;
		$this->tab_posts = $tab_posts;

		foreach ($cfg['controllers'] as $path => $value) 
		{
			if($value['class'] == $controller){
				$this->url = $path;
				break;
			}
		}
	}

// 	public function search($string)
// 	{
// 		global $db;

//  		$query = <<<EOD
//  		SELECT 
//  			  `C`.*
//  			, `P`.`post` 
//  			, `P`.`id` AS `id_{$this->tab_posts}`
//  		FROM `{$this->tab_comments}` AS `C`
//  			LEFT OUTER JOIN `{$this->tab_posts}` AS `P`
//  			ON `C`.`id_{$this->tab_posts}` = `P`.`id` 
//  		WHERE `C`.`post` LIKE '%{$string}%' OR `P`.`post` LIKE '%{$string}%'
// EOD;


		
// 		$db->connect();

// 		$res = $db->query($query);
		
// 		if($res->num_rows > 0)
// 			return $res;
// 		return null;	
// 	}

	/**
	 * Returns a blogg
	 * 
	 * @return string A complete blogg ready to print
	 */
	public function getBlogg($limit = 30, $offset = 0)
	{
		if($res = $this->getPosts($limit, $offset))
		{
			$id = isset($this->id) ? " id='{$this->id}'" : "";
			$class = isset($this->class) ? " class='{$this->class}'" : "";


			

			$blogg = "<div{$id}{$class}>";
			foreach ($res as $post)
			{
				$grav_url = "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $post['email'] ) ) ) . "?d=mm&s=80";
				$edit = $post['id_user'] == $_SESSION['id'] ? '<div class="right"><a href="'.$this->url.'/delete/'.$post["id"].'">delete</a> | <a href="'.$this->url.'/edit/'.$post["id"].'">edit</a></div>' : '';
				$edited = $post['edited'] != null ? "<div>Last edited: {$post['edited']}</div>" : "";
				
				$blogg .= <<<EOD
				<div class="post">
				<div class="headline"><img src="{$grav_url}" />{$post['author']}  {$post['date']}{$edit}</div>
				<div class="post-data">{$post['post']}</div>
				{$edited}
				
				<p><a href="{$this->url}/add/{$post['id']}/0">comment</a></p>
				</div>
				<hr />
EOD;

			}

			// if(isset($_SESSION['id']))
			// {
			// 	$form = new Form();

			// 	$form->set_validate_rules("data", "Data", "clean");

			// 	if($form->validate())
			// 	{
			// 		// if(isset($_POST['post_id']))
			// 		// 	$this->posts->edit($_POST['post_id'], $_POST['data']);
			// 		// else
			// 		// 	$this->posts->add($_POST['data'], $_POST['id']);
			// 		echo $_POST['data'];
			// 	}

			// 	$data = "<fieldset class='clearfix inline-block'>";
			// 	$data .= $form->start("post");

			// 	$data .= $form->textarea(array('class' => 'wymeditor','name' => 'data', 'width' => '550px'));
			// 	$data .= $form->input('hidden', array('value' => $_SESSION['id'], 'name' => 'id'));
			// 	$data .= $form->input('submit', array('class' => 'wymupdate', 'value' => 'Save', 'style' => 'width:100%'));
			// 	$data .= "</form></fieldset>";

			// 	$blogg .= $data;
			// }
			$blogg .= "</div>";
		}


		return $blogg;
	}

	/**
	 * Returns all the posts data
	 *
	 * Return all the posts data as a nested array
	 *
	 * @return mixed The query result of all the posts
	 */
	public function getPosts($limit = 30, $offset = 0){ return $this->resultFetch( $this->posts->getAll($limit, $offset, "`P`.`date` DESC") ); }
	public function edit($post_id, $post_data){ return $this->posts->edit($post_id, $post_data) > 0 ? true  : false; }
	public function add($post_data, $user_id, $parent_id = null){ return $this->posts->add($post_data, $user_id, $parent_id); }
	public function remove($post_id) { return $this->posts->delete($post_id) > 0 ? true : false; }
	public function delete($post_id) { return $this->posts->setVisible($post_id) > 0 ? true : false; }
	public function getPost($post_id, $comments = FALSE)
	{
		if($comments)
			return $this->posts->get($post_id)->fetch_assoc();
		else
		{
			$result = $this->getPosts();
			foreach ($result as $post)
			{
				if($post['id'] == $post_id)
					return $post;
			}
		}
		return false;
	}

	private function resultFetch($result)
	{
		$res_arr = array();
		if($result->num_rows > 0)
		{

			while ($res = $result->fetch_assoc()) 
			{
				if(empty($res['id_parent']))
					$res_arr[] = $res;
				else
					$this->resultSet($res, $res_arr);
			}
		}
		return $res_arr;
	}

	private function resultSet($result, &$res_arr)
	{
		foreach ($res_arr as &$array) 
		{	
			if($result['id_parent'] == $array['id']) {
				$array['children'][] = $result;
				return true;
			}
			if(is_array($array['children']))
				if($this->resultSet($result, $array['children']))
					return true;
		}
		return false;
	}
}