<?php

/*
 * Created on Jun 13, 2011 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 require_once 'IForum.php';
 class CForum implements iForum
 {
 	private $mysqli,
 	
 			$this->tab_subjects,
 		 	$this->tab_threads,
 			$this->tab_posts,
 			
 			$this->tab_users,
 	
 			$id_subject,
 			$pageTitle,
 			$current,
 			
 			$id_thread,
 			$id_post;
 	 	
 public function __construct(	 $server = DB_SERVER
 								,$username = DB_USERNAME
 								,$password = DB_PASSWORD
 								,$database = DB_DATABASE
 								,$tab_users = TAB_USERS
 								,$tab_subjects = TAB_SUBJECTS
 								,$tab_threads = TAB_THREAD
 								,$tab_posts = TAB_POSTS
 								,$charset = DB_CHARSET
 							)
 	{
		$this->mysqli = new mysqli($server, $username, $password, $database);
		$this->mysqli->set_charset($charset);
		
		$this->view_info = VIEW_INFO;
		$this->tab_subjects = $this->tab_subjects;
		$this->tab_threads = $this->tab_threads;
		$this->tab_posts = $this->tab_posts;
		$this->tab_users = $this->tab_users;
		
		$this->pageTitle = "";
		
		$this->current_subject = "";
		$this->current_thread = "";
		
		$this->threadID = NULL;
		$this->subjectID = NULL;
		$this->postID = NULL;
 	}
 public function addSubject($subject)
 	{
 		$query = "INSERT INTO `{$this->tab_subjects}` (subject) VALUES ('$subject')";
 		$this->query($query);
 	}
 	
 public function lockSubject($id_subject)
 	{
 		$query = <<<EOD
 			UPDATE `{$this->tab_posts}` AS P
 				LEFT JOIN `{$this->->tab_threads}` AS T
				ON `T`.`id` = `P`.`id_thread`
 					LEFT JOIN `{$this->tab_subject}` AS S
 					ON `S`.`id` = `T`.`id_subject`
 			SET `S`.`locked` = '0', `T`.`locked` = '0', `P`.`locked` = '0' WHERE '{$id_subject}' = `S`.`id`
EOD;
 		$this->query($query);	
 	}
 public function hideSubject($id_subject)
 	{
 		$query = <<<EOD
 		 	UPDATE `{$this->tab_posts}` AS P
 		 		LEFT JOIN `{$this->->tab_threads}` AS T
 				ON `T`.`id` = `P`.`id_thread`
 		 			LEFT JOIN `{$this->tab_subject}` AS S
 					ON `S`.`id` = `T`.`id_subject`
 			SET `S`.`hidden` = '0', `T`.`hidden` = '0', `P`.`hidden` = '0' WHERE '{$id_subject}' = `S`.`id`
EOD;
 		$res = $this->query($query);
 	}
 public function deleteSubject($id_subject)
 	{
		$query = "DELETE * FROM `{$this->tab_subjects}` WHERE `id` = '{$id_subject}`";
 		$this->query($query);
 		if($this->mysqli->error)
 			return "Could not remove subject." $this->mysqli->error;
 		return "Subject successfully removed.";
 	}
 public function getSubject($id_subject, $start = 0)
 	{
 		$query = <<<EOD
 		SELECT `T`.* `tmp`.* FROM (
 		SELECT
 		(SELECT COUNT(`P`.`id`) FROM `{$this->tab_posts}` AS `P`
 			INNER JOIN `{$this->tab_threads}` AS `T`
 			ON `P`.`id_thread` = `T`.`id`
 				INNER JOIN `{$this->tab_subjects}` AS `S`
 				ON `T`.`id_subject` = `S`.`id`
 		 ) 
 		,`U`.`username`
 		,`P`.`date`
 		FROM `{$this->tab_posts}` AS `P`
 			LEFT JOIN `{$this->tab_users}` AS `U`
 			ON `P`.`id_user` = `U`.`id`
 		ORDER BY `P`.`date` DESC) AS `tmp`
 		 FROM `{$this->tab_threads}` AS `T` WHERE `id_subject` = '{$id_subject}' AND `hidden` <> '0' ORDER BY `T`.`date` LIMIT {$start}, 25;
EOD;
		$res = $this->query($query);
 		$row = $res->fetch_field_direct(1);
 		$this->current = $id_subject;
 		$this->pageTitle = ' - '.$row->subject;
 		return $res;
 	}
 	
 public function getForumInfo()
 	{
 		$query = <<<EOD
 		SELECT * FROM (
 		SELECT `S`.`id` AS `id_subject`
 				,`S`.`subject`
 				,(SELECT COUNT(0) FROM `{$this->tab_threads}` AS `T` WHERE `S`.`id` = `T`.`id_subject` AND `T`.`hidden` <> '0') AS `threads`
 				,(SELECT COUNT(0) FROM (`{$this->tab_posts}` AS `P` JOIN `{$this->tab_threads}` AS `T` ON `T`.`id` = `P`.`id_thread`) WHERE `S`.`id` = `T`.`id_subject` AND `P`.`hidden` <> '0') AS `posts`
 				,`U`.`username`
 				,`U`.`first_name`
 				,`U`.`last_name`
 				,`T`.`thread`
 				,`P`.`date`
 		FROM
 		(
 			`{$this->tab_subjects}` AS `S`
 				LEFT JOIN `{$this->tab_threads}` AS `T` ON `T`.`id_subject` = `S`.`id`
 				LEFT JOIN `{$this->tab_posts}` AS `P` ON `P`.`id_thread` = `T`.`id`
 				LEFT JOIN `{$this->tab_users}` AS `U` ON `U`.`id` = `P`.`id_user`
 		) ORDER BY `date` DESC) AS `tmp`
 		GROUP BY `id_subject`;	
EOD;
// 		$query = "SELECT * FROM `VIEW_{$this->view_info}`";
		return $this->query($query);
 	}
 	public function addThread($thread, $id_user, $id_subject, $post, $locked = 1, $hidden = 1)
 	{
 		$query = <<<EOD
 			INSERT INTO `{$this->tab_threads}` (`thread`, `id_user`, `id_subject`, `date`, `locked`, `hidden`)
			VALUES ('{$thread}', '{$id_user}', '{$id_subject}', NOW(), '{$locked}', {$hidden});
EOD;
		$this->query($query);
		
		$this->addPost($post, $id_user, $this->mysql->insert_id, $locked, $hidden);
 	}
 	public function lockThread($id_thread)
 	{
 		$query = <<<EOD
 			UPDATE `{$this->tab_posts}` AS P
 				LEFT JOIN `{$this->->tab_threads}` AS T
				ON `T`.`id` = `P`.`id_thread`
 			SET `T`.`locked` = '0', `P`.`locked` = '0' WHERE '{$id_thread}' = `T`.`id`
EOD;
 		$this->query($query);
 	}
 	public function hideThread($id_thread)
 	{
 		$query = <<<EOD
 			UPDATE `{$this->tab_posts}` AS P
 				LEFT JOIN `{$this->->tab_threads}` AS T
				ON `T`.`id` = `P`.`id_thread`
 			SET `T`.`hidden` = '0', `P`.`hidden` = '0' WHERE '{$id_thread}' = `T`.`id`
EOD;
 		$this->query($query);
 	}
 	public function deleteThread($id_thread)
 	{
		$query = "DELET * FROM `{$this->tab_threads}` WHERE `id` = '{$id_thread}'";
		query($query);
		if($this->mysqli->error)
			return "Could not remove thread." $this->mysqli->error;
		return "Thread successfully removed.";
 	}
 	public function getThread($id_thread, $start = 0)
 	{ 		
 		$query = <<<EOD
 		SELECT `T`.`thread`, `P`.`post`, `P`.`date`, `U`.`username` FROM `{$this->tab_posts}`
 			LEFT JOIN `{$this->tab_posts}` AS P
 			ON `T`.`id` = `P`.`id_thread`
 			LEFT JOIN `{$this->tab_users}` AS U
 			ON `P`.`id_users` = `U`.`id`
 		WHERE `P`.`hidden` <> '0' 
 		ORDER BY `date` LIMIT {$start}, 25;
EOD;

 		$res = $this->query($query);
 		$query = "UPDATE `{$this->tab_threads}` SET `views` = 'views +1' WHERE `id` = {$id_thread} LIMIT 1";
 		$row = $res->fetch_field_direct(1);
 		$this->current = $id_thread;
 		$this->pageTitle = ' - '.$row->thread;
 		return $res;
 	}
 	public function addPost($post, $id_user, $id_thread, $locked = 1, $hidden = 1)
 	{
 		$query = <<<EOD
 			INSERT INTO {$this->tab_posts} (post, id_user, id_thread, date, locked, hidden)
			VALUES ('{$post}', '{$id_user}', '{$id_thread}', NOW(), '{$locked}', '{$hidden}')
			;
EOD;
// 		if($this->query($query))
//  		{
//  			$query = "UPDATE `{$this->tab_thread}` SET `replies` = 'replies + 1' WHERE `id` = `{$id_thread}` LIMIT 1";
//  			$this->query($query);
//  		}
 	}
 	public function lockPost($id_post)
 	{
 		$query = "UPDATE `{$this->tab_posts}` SET `locked` = '0' WHERE `id` = `{$id_post}` LIMIT 1";
 		$this->query($query);
 	}
 	public function hidePost($id_post)
 	{
 		$query = "UPDATE `{$this->tab_posts}` SET `hidden` = '0' WHERE `id` = `{$id_post}` LIMIT 1";
 		$this->query($query);
 	}
 	public function deletePost($id_post)
 	{
 		$query = <<<EOD
			DELETE * FROM {$this->table_post} WHERE `id` = '{$id_post}';
EOD;
		$this->query($query);
 	}
 	public function editPost($id_post, $post)
 	{
 		$query = "UPDATE {$this->table_post} SET  post = '{$post}' WHERE `id` = '{$id_post}' AND `locked` <> '0' LIMIT 1";
 		$this->query($query);
 	}
	public function getPost($id_post)
	{
		$query = <<<EOD
		SELECT `P`.`post`, `P`.`date` FROM `{$this->tab_posts}` AS `P`
			LEFT JOIN `{$this->tab_users}` AS `U`
			ON `P`.`id_user` = `U`.`id`	 
		WHERE `P`.`id` = '{$id_post}' AND `P`.`hidden` <> '0' 
EOD;
		return $this->query($query);
	}
	
 	public function close()
 	{
 		$this->mysqli->close();
 	}
 	public function getPageTitle()
 	{
 		return $this->pageTitle;
 	}
 	public function getLink()
 	{
 		$link = "<a href='?p=forum'>Main</a>";
 		if(isset($this->current->subject))
 	 		$link .= " => <a href='?p=forum&amp;s={$this->current->id_subject}'>{$this->current->subject}</a>";
 		if(isset($this->current->id_thread))
 	 		$link .= " => <a href='?p=forum&amp;t={$this->current->id_thread}'>{$this->current->title}</a>";
 		
 	 	return $link;
 	}

 	public function searchPost($string)
 	{
 		$query = <<<EOD
 		SELECT 
 			  `P`.`post`
 			, `P`.`id` AS `id_post`
 			, `T`.`thread` 
 			, `T`.`id` AS `id_thread`
 			, `S`.`subject`
 			, `S`.`id` AS `id_subject`
 		FROM `{$this->tab_posts}` AS `P`
 			INNER JOIN `{$this->tab_threads}` AS `T`
 			ON `P`.`id_thread` = `T`.`id` 
 			INNER JOIN `{$this->tab_subject}` AS `S`
 			ON `T`.`id_subject` = `S`.`id`
 		WHERE `P`.`post` LIKE '%{$string}%' OR `T`.`thread` LIKE '%{$string%}';
EOD;
 	}
 	
 	private function multi_query($query)
 	{
 		$this->mysqli->multi_query($query)
 		or die("Could not query ".$query);
 		while ($this->mysqli->next_result());
 	}
 	
 	private function query($query)
 	{
 		$res = $this->mysqli->query($query)
 		or die("<p>Could not query database,</p><code>{$query}</code>");
 		return $res;
 	}
 
 }
?>
