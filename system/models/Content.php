<?php

if(!defined('BASE')) die('No direct access!');

class Content 
{
	/**
	*	Returns all available regions
	*
	*	@return string[]
	*/
	public function get_regions()
	{
		global $db;

		$query = "SELECT * FROM `{$cfg['db']['prefix']}regions`";
		$db->connect();
		$result = $db->query($query);
		$db->close();
 
		while($row = $result->fetch_array())
        {
            $res[] = $row;
        }
        
		return $res;
	}

	public function get_pages()
	{
		global $db;
		$query = "SELECT * FROM `{$cfg['db']['prefix']}pages`";

		$db->connect();
		if($result = $db->query($query))
			while($row = $result->fetch_array())
	        {
	            $res[] = $row;
	        }
        $db->close();
		return $res;
	}

	public function get_content($id_page, $id_region)
	{
		global $db;
		$query = "SELECT `content` FROM `{$cfg['db']['prefix']}page_data`
					WHERE `id_region` = '{$id_region}' AND `id_page` = '{$id_page}'";
		$db->connect();
		$result = $db->query($query);
		$db->close();

		$row = $result->fetch_array();
		return $row['content'];
	}

	public function get_all_content($page) 
	{

		global $db;
		$query = "SELECT `PD`.`content`, `R`.`region` FROM `{$cfg['db']['prefix']}page_data` AS `PD` 
					INNER JOIN `{$cfg['db']['prefix']}pages` AS `P`
						ON `PD`.`id_page` = `P`.`id` 
					INNER JOIN `{$cfg['db']['prefix']}regions` AS `R`
						ON `PD`.`id_region` = `R`.`id`
					WHERE `P`.`name` = '{$page}'";
		$db->connect();


		if($result = $db->query($query))
		{
			while($row = $result->fetch_array())
	        {
    			$res[$row['region']] = $row['content'];
	        }
	    }
        $db->close();
		return $res;
	}


	public function save()
	{
		global $db;

		$query = "SELECT `id` FROM `{$cfg['db']['prefix']}page_data` 
					WHERE `id_region` = '{$_POST['region']}'
					AND `id_page` = '{$_POST['page']}'";

		$db->connect();
		$result = $db->query($query);
		$db->close();

		
		if($result->num_rows == 1)
		{
			$row = $result->fetch_array();
			$query = "UPDATE `{$cfg['db']['prefix']}page_data` 
					SET `content` = '{$_POST['data']}' WHERE `id` = {$row['id']}";
		}
		else
		{
			$query = "INSERT INTO `{$cfg['db']['prefix']}page_data` (`id_page`, `id_region`, `content`)
					VALUES ({$_POST['page']}, {$_POST['region']}, '{$_POST['data']}')";
		}

		$db->connect();
		$db->query($query);
		$db->close();
	}

	public function insertPage()
	{
		global $db;

		$id = NULL;

		// if($_POST['page_id'] == 0)
		$query = "INSERT INTO `{$cfg['db']['prefix']}pages` (`name`)
				VALUES ('{$_POST['page']}')";
		// else
		// 	$query = "UPDATE `{$cfg['db']['prefix']}pages` 
		// 			SET `name` = '{$_POST['page']}' WHERE `id` = {$_POST['page_id']}";

		$db->connect();
		$result = $db->query($query);

		$id = $db->insert_id;
		$db->close();

		return $id;
	}

	public function updatePage()
	{
		global $db;

		$query = "UPDATE `{$cfg['db']['prefix']}pages` 
	 			SET `name` = '{$_POST['page']}' WHERE `id` = {$_POST['page_id']}";

		$db->connect();
		$result = $db->query($query);
		$db->close();
	}

	public function insertRegion()
	{
		global $db;

		$id = NULL;

		$query = "INSERT INTO `{$cfg['db']['prefix']}regions` (`region`,`editable`)
				VALUES ('{$_POST['region']}', 1)";

		$db->connect();
		$result = $db->query($query);

		$id = $db->insert_id;
		$db->close();

		return $id;
	}

	public function updateRegion()
	{
		global $db;

		$query = "UPDATE `{$cfg['db']['prefix']}regions`
	 			SET `name` = '{$_POST['region']}' WHERE `id` = {$_POST['region_id']}";

		$db->connect();
		$result = $db->query($query);
		$db->close();
	}
}