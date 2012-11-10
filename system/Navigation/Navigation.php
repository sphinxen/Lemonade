<?php  if(!defined('BASE')) die('No direct access!');
/**
*
*	@author Josef Karlsson <sphinxen83@gmail.com>
*	@package Lemonade
*/

class Navigation
{
	public function __construct()
	{
		
	}

	/**
	*
	*	@param mixed[] $nav
	*	@param boolean $inline
	*	@return string
	*/
	public function GenerateNavigation($nav, $inline = TRUE)
	{
		if(empty($nav))
			return "";
		
		global $segment;

		$id = !empty($nav['id']) ? " id='".$nav['id']."'" : NULL;
		$class = !empty($nav['class']) ? $nav['class'] : NULL;
		$display = $inline ? ' class="inline-block"' : NULL;

		$open = !empty($class) || !empty($display) ? ' class="' : '';
		$close = !empty($open) ? '"' : '';

		$menu = "<div{$id}{$open}{$class}{$close}><ul>";

		foreach ($nav['items'] as $key => $value) 
		{
			$id = !empty($value['id']) ? " id='".$value['id']."'" : NULL;
			$class = !empty($value['class']) ? " class='".$value['class'] : NULL;

			if($value['path'] == BASE.strtolower($segment[0]))
			{
				$class = " class='" . $value['class'] . "  active'";
			}
			$menu .= "<li{$display}><a{$id}{$class} href='".$value['path']."'>".$key."</a></li>";
		}

		$menu .= "</ul></div>";

		return $menu;
	}
}
