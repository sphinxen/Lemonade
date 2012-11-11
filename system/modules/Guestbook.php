<?php

class Guestbook
{
	// public $comments;
	public $posts;

	// private $tab_comments;
	private $tab_posts;

	public function __construct($tab_posts = "gb_posts")//, $tab_comments = "gb_comments")
	{	
		// $this->comments = new Post($tab_comments, $tab_posts);
		$this->posts = new Post($tab_posts);
		// $this->tab_comments = $tab_comments;
		$this->tab_posts = $tab_posts;
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
	 * Returns a guestbook
	 * 
	 * @return string A complete guestbook ready to print
	 */
	public function getGuestbook($limit = 30, $offset = 0)
	{
		if($res = $this->getGuestbookRaw($limit, $offset))
		{
			$id = isset($this->id) ? " id='{$this->id}'" : "";
			$class = isset($this->class) ? " class='{$this->class}'" : "";

			$guestbook = "<div{$id}{$class}>";
			while($post = $res->fetch_array())
			{
				$guestbook .= <<<EOD
				<div class="post">
				<div>{$post['author']}  {$post['date']}</div>
				<div>{$post['post']} {$post['edited']}</div>
				</div>
				<hr />
EOD;

			}
			$guestbook .= "</div>";
		}
		

		return $guestbook;
	}

	/**
	 * Returns the raw data of the guestbook
	 * 
	 * @return mixed The query result of all the posts
	 */
	public function getGuestbookRaw($limit = 30, $offset = 0)
	{
		return $this->posts->getAll($limit, $offset);
	}
}