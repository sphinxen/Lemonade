<?php
/**
* @author Josef Karlsson <sphinxen83@gmail.com>
* @package Lemonade
 */


if(!defined('BASE')) die('No direct access!');

/**
* Create and contains data useful for the controller class
*
* @abstract
*/
abstract class CController implements IController
{
	protected $segment;
	private $data;
	protected $menu;
	
	protected function __construct()
	{
		global $segment;
		$this->segment = $segment;
		$this->menu = new Navigation();
	}

	/**
	* Loads the view
	*
	* Takes a array of data, extracts the keys to variables and load the view
	* Is able to return the view content as a variable
	* 
	* @param string $view Name of the view to be loaded, with relative path but without extention
	* @param mixed[] $data An associated array of data wich will be extracted to variables
	* @param bool $return If TRUE the data will be returned as a variable
	* @return string
	*/
	protected function view($view, $data = NULL, $return = FALSE)
	{	
		global $cfg;

		if(empty($data))
			$data = $this->data;
		else
		{
			$this->data = $data;
		}
	
		extract($data);
		extract($region);


		// Check if admin or user is loged in
		if($_SESSION['user']['role'] != 'admin')
		{
			$menu['admin'] = '';
			$menu['user'] = '';

			$form = new Form();

			
			$user_form = $form->start(null, 'block', 'user');
			$user_form .= "<fieldset><legend>Login</legend>";
			$user_form .= "<label>Username or E-mail</label>";
			$user_form .= $form->input('text', array('name' => 'user'));
			$user_form .= "<label>Password</label>";
			$user_form .= $form->input('password', array('name' => 'password'));
			$user_form .= "<br />";
			$user_form .= $form->input('submit', array('value' => 'Login'));
			$user_form .= "</fieldset></form>";

			$content['right'] = $user_form;
		}

		// Check if $menu['main'] is set. Otherwise tries to autogenerate a main-menu
		if(!isset($menu['main']))
		{
			$menu['main'] = array(
					'id' => 'main-nav'
					,'class' => NULL
					,'items' => array()
				);
			foreach ($cfg['controllers'] as $key => $value) {
				if(!$value['core'] && $value['enabled'])
					// [$key] = $cfg['controllers'][$key];
					$menu['main']['items'][ucwords($key)] = array('path' => BASE.$key, 'id' => NULL, 'class' => NULL);
			}
		}
		$logo = BASE.'assets/images/logo.svg';

		
		// Fetches the view and store the page in a variable
		ob_start(); 
			require_once(ROOT."application/views/{$view}.php");
			$page = ob_get_contents();
		ob_end_clean(); 
		
		
		
 		// Process the code and return or print the final page
		if($return)
			return $this->page_process($page);
		print($this->page_process($page));
	}

	/**
	*	Loads the module to handle the database requests
	*
	*	@param string $module Name of the module to be loaded, with relative path but without extention
	*/
	protected function module($module)
	{
		if(is_file(ROOT."application/modules/{$module}.php"))
		{
			require_once(ROOT."application/modules/{$module}.php");
			return new $module;
		}
		elseif(is_file(ROOT."system/modules/{$module}.php"))
		{
			require_once(ROOT."system/modules/{$module}.php");
			return new $module;
		}
	}

	/**
	 * Filter the page data and returns it
	 * 
	 * Process any PHP code, and give web addresses and email addresses href taggs
	 * @example <?php echo 'example';?> - will become: example
	 *          http://www.example.com - will become: <a href="http://www.example.com">http://www.example.com</a>
	 *          exampe@email.com - will become: <a href="mail:exampe@email.com">exampe@email.com</a>
	 * 
	 * 
	 * @param  string $string The data that will be processed
	 * @return string         Returns the processed data
	 */
	public function page_process($string)
	{		

		preg_match_all('/(((\<|&lt;)\?php)(.*?)(\?(\>|&gt;)))|((http:\/\/|ftp:\/\/|https:\/\/)([^ \s\t\r\n\v\f]*)\.([A-Za-z]*))|(([^ "(),:;<>@\s\t\r\n\v\f]*)@([^ "(),:;<>@[\]\s\t\r\n\v\f]*)(\.[A-Za-z]*))/', substr($string, strpos($string, '<body>'), strpos($string, '</body>')), $PHPcode);

		foreach (array_unique($PHPcode[0]) as $value) 
		{
			if(strstr($value, '?php'))
			{
				ob_start();
					eval(substr($value, strpos($value, '?')+4, strrpos($value, '?') - (strpos($value, '?php')+4)));
					$php = ob_get_contents();
				ob_end_clean();
				$string =  str_replace($value, $php, $string);
			}
			elseif(strstr($value, 'http://') || strstr($value, 'https://') || strstr($value, 'ftp://') && strstr($value, '<a href'))
				$string = str_replace($value, '<a href="'.$value.'">'.$value.'</a>', $string);
			elseif(strstr($value, '@') && strstr($value, '<a href'))
				$string = str_replace($value, '<a href="mailto:'.$value.'">'.$value.'</a>', $string);
		}

		// Remove blank-lines caused by php code
		$string = preg_replace('/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/', "", $string);
		
		return $string;
	}
	
	public function header($header, $return = FALSE)
	{             
		// Load the view and store it in a variable
		ob_start();
                        require_once(ROOT."application/views/{$header}.php");
                        $page = ob_get_contents();
                ob_end_clean();	
		
		// Process the code and return or print the final page
                if($return)
                        return $this->page_process($page);
                print($this->page_process($page));
	}
	
	public function footer($footer)
	{
		require_once(ROOT."application/views/{$footer}.php");
	}
}