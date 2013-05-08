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
		$query = "SELECT `R`.`id`, `R`.`region`, `RP`.`region` AS `parent` FROM `{$cfg['db']['prefix']}regions` AS `R`
					LEFT JOIN `{$cfg['db']['prefix']}regions` AS `RP`
						ON `R`.`id_parent_region` = `RP`.`id`";
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
		$query = "SELECT `PD`.`content`, `R`.`region`, `RP`.`region` AS `parent` FROM `{$cfg['db']['prefix']}page_data` AS `PD` 
					INNER JOIN `{$cfg['db']['prefix']}pages` AS `P`
						ON `PD`.`id_page` = `P`.`id` 
					INNER JOIN `{$cfg['db']['prefix']}regions` AS `R`
						ON `PD`.`id_region` = `R`.`id`
					LEFT JOIN `{$cfg['db']['prefix']}regions` AS `RP`
						ON `R`.`id_parent_region` = `RP`.`id`
					WHERE `P`.`name` = '{$page}'";
		$db->connect();


		if($result = $db->query($query))
		{
			while($row = $result->fetch_array())
	        {
	   //      	$p = new PhpStringParser();
	   //      	ob_start(); 
				// 	echo $p->parse($row['content']);
				// 	$output = ob_get_contents();
				// ob_end_clean(); 
	        	if(!empty($row['parent']))
			        $res[$row['parent']][$row['region']] = $row['content'];//preg_replace_callback('/(\<\?=|\<\?php=|\<\?php)(.*?)\?\>/', eval($row['content']), $row['content']);
	    		else
	    			$res[$row['region']] = $row['content'];
	        }
	    }
        $db->close();
		return $res;
	}


}