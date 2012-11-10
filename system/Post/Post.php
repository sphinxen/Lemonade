<?php
/**
 * A class to handle simple posts and comments
 * 
 * @author Josef Karlsson <sphinxen83@gmail.com>
 */

if(!defined('BASE')) die('No direct access!');


class Post {
	protected $tab_name;
	protected $tab_parent;
	protected $db;
	protected $tab_users;

	public function __construct($table_name = "posts", $table_parent = null, $table_users = "users")
	{
		$this->tab_name = $table_name;
		$this->tab_parent = $table_parent;
		$this->tab_users = $table_users;

		global $db;
		$this->db = $db; 

		// Check i the table exists in the database, otherwise it will be created
		$res = $this->query("SHOW TABLES LIKE '{$this->tab_name}'");

		if($res->num_rows == 0)
			$this->createDB();
		
	}

	/**
	 * Inserts a new post in the database
	 * 
	 * 
	 * @param String  $post      The post data. Could contain html tags
	 * @param integer  $id_user  The id of the user posting the content
	 * @param integer $id_parent ID of the parent table
	 * @param integer $locked    Decides whether the post should be open for changes(0) och locked(1)
	 * @param integer $hidden    Decides whether the post should be visible(0) or not(1)
	 */
	public function add($post, $id_user, $id_parent = null, $locked = 0, $hidden = 0)
 	{
 		if(isset($this->tab_parent) && $id_parent > 0)
 		{ 
 			$into = "id_{$this->tab_parent},";
 			$value = "'{$id_parent}',";
		}

 		$query = <<<EOD
 			INSERT INTO {$this->tab_name} (post, id_user, {$into} date, locked, hidden)
			VALUES ('{$post}', '{$id_user}', {$value} NOW(), '{$locked}', '{$hidden}')
			;
EOD;
		$this->query($query);
 	}

 	/**
 	 * Update the "lock" status
 	 * 
 	 * @param  integer $id_post Decides whether the post should be open for changes(0) och locked(1)
 	 * @return integer          The ID of the edited post
 	 */
 	public function setLock($id_post)
 	{
 		$query = "UPDATE `{$this->tab_name}` SET `locked` = '0' WHERE `id` = `{$id_post}` LIMIT 1";
 		$this->query($query);

 		return $this->db->insert_id;
 	}

 	/**
 	 * Update the "visible" status
 	 * 
 	 * @param  [type] $id_post Decides whether the post should be visible(0) or not(1)
 	 * @return integer          The ID of the edited post
 	 */
 	public function setVisible($id_post)
 	{
 		$query = "UPDATE `{$this->tab_name}` SET `hidden` = '0' WHERE `id` = `{$id_post}` LIMIT 1";
 		$this->query($query);

 		return $this->db->insert_id;
 	}

 	/**
 	 * Deletes a post
 	 * 
 	 * @param  integer $id_post ID of the post to delete
 	 * @return ínteger          Number of rows affected
 	 */
 	public function delete($id_post)
 	{
 		$query = <<<EOD
			DELETE FROM {$this->tab_name} WHERE `id` = '{$id_post}' LIMIT 1;
EOD;
		$this->query($query);
		return $this->db->affected_rows;
 	}

 	/**
 	 * Edit a post content
 	 * 
 	 * @param  integer $id_post ID of the post to edit
 	 * @param  string $post    The new content
 	 * @return integer          The ID of the edited post
 	 */
 	public function edit($id_post, $post)
 	{
 		$query = "UPDATE {$this->tab_name} SET  post = '{$post}', edited = NOW() WHERE `id` = '{$id_post}' AND `locked` = '0' LIMIT 1";
 		$res = $this->query($query);

 		return $this->db->insert_id;
 	}

 	/**
 	 * Get the post data
 	 * 
 	 * Including Username
 	 * @param  integer $id_post ID of the post to return
 	 * @return mixed          The query result
 	 */
	public function get($id_post)
	{
		$query = <<<EOD
		SELECT `P`.*, `U`.`username` AS `author` FROM `{$this->tab_name}` AS `P`
			LEFT JOIN `{$this->tab_users}` AS `U`
			ON `P`.`id_user` = `U`.`id`	 
		WHERE `P`.`id` = '{$id_post}' AND `P`.`hidden` = '0' 
EOD;
	
		return $this->query($query);
	}

	/**
	 * Get all posts
	 * 
	 * Including Username
	 * 
	 * @return mixed         The query result containing all the posts
	 */
	public function getAll($limit = 30, $offset = 0)
	{
		$query = <<<EOD
		SELECT `P`.*, `U`.`username` AS `author` FROM `{$this->tab_name}` AS `P`
			LEFT JOIN `{$this->tab_users}` AS `U`
			ON `P`.`id_user` = `U`.`id`	
		LIMIT {$offset}, {$limit}
EOD;
		return $this->query($query);
	}


	 public function search($string)
 	{
		$query = "SELECT * FROM {$this->tab_name} WHERE `post` LIKE '%{$string}%'";

		return $this->query($query);
 	}

 	/**
 	 * Creates a new table in the database
 	 * 
 	 * @return integer The number of rows that were affected
 	 */
 	private function createDB()
 	{
 		$parent = isset($this->tab_parent) ? 
 				"`id_{$this->tab_parent}` INT NOT NULL,
 					FOREIGN KEY (`id_{$this->tab_parent}`) REFERENCES `{$this->tab_parent}`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE,"
 					: "";

 		$query = <<<EOD
 		CREATE TABLE IF NOT EXISTS `{$this->tab_name}`
 			(
 				`id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
 				`post` LONGTEXT NOT NULL,
 				`id_user` INT NULL,
 					FOREIGN KEY (`id_user`) REFERENCES `{$this->users}`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
 				{$parent}
 				`date` DATETIME NOT NULL,
 				`edited` DATETIME NULL,
 				`locked` TINYINT NULL DEFAULT '1',
 				`hidden` TINYINT NULL DEFAULT '1'
 			);
EOD;
		$this->query($query);
		return $this->db->affected_rows;
 	} 

 	/**
 	 * A function to handle the querys
 	 * 
 	 * @param  string $query A query string
 	 * @return mixed        The result set
 	 */
 	private function query($query)
 	{
 		$this->db->connect();
		$res = $this->db->query($query);
		$this->db->close();
		return $res;
 	}
}