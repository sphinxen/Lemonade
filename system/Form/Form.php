<?php 
/**
*   From class to easy create and vertify forms and inputs            
*   
*   @package lemonade
*   @author Josef Karlsson <sphinxen83@gmail.com>
*/

if(!defined('BASE')) die('No direct access!');

class Form 
{
	private $rules;
    private $error;

	public function start($id = null, $class = null, $action = "#", $method = 'post')
	{
        $id = isset($id) ? ' id="'.$id.'"' : "";
        $class = isset($class) ? ' class="'.$class .'"': "";
        //$action = isset($action) ? ' action="'.$action.'"' : '';
		return '<form' . $id . $class .' method="'.$method.'" action="'.$action.'">';
	}

    /**
    *   Create a input field
    *
    *   @param string $type Defines the type of the field
    *   @param string[] $attributes Defines the attributes of the field
    */
	public function input($type = 'text', $attributes = array('name' => 'default'))
	{

		//	Check for <input> elements
		if(preg_match("/^(text|radio|checkbox|password|hidden|submit|reset|button|image|file)$/",$type))
		{
            $openTag='<input type="'.$type.'" ';
            $closeChar=' ';
            $closeTag='/>';
        }
         
        return $openTag.$this->elemAttributes($attributes).$closeChar.$closeTag;
	}

    /**
    *   Create a textarea
    *   
    *   @param string[] $attributes Defines the attributes of the textarea
    */
	public function textarea($attributes = array('name' => 'default'))
	{
        if(isset($attributes['value'])){
            $value = $attributes['value'];
            unset($attributes['value']);
        }
        $openTag='<textarea ';
        $closeChar='>';
        $closeTag='</textarea>';

        return $openTag.$this->elemAttributes($attributes).$closeChar.$value.$closeTag;
    }

    /**
    *   Create a selection menu
    *
    *   @param string[] $attributes Defines the attributes of the select menu
    *   @param string[] $option Defines the objects of the select menu
    */
    public function select($options = array('option' => 'text'), $attributes = array('name' => 'default'))
    {
    	$openTag='<select ';
        $closeChar='>';
        $closeTag='</select>';

        foreach($options as $value=>$text)
        {
                $selOptions.='<option value="'.$value.'">'.$text.'</option>';
        }
        return $openTag.$this->elemAttributes($attributes).$closeChar.$selOptions.$closeTag;
    }

    /**
    *   Extract all the attributes of an array and return it as a string
    *
    *   @param string[] $attributes
    *   @return string
    */
    private function elemAttributes($attributes)
    {
    	foreach($attributes as $attribute => $value)
    	{
            $elemAttributes .= $attribute.'="'.$value.'" ';
       	}
       	return $elemAttributes;
    }

    /**
    *   Defines the validation rules of an input field
    *
    *   @param string $field The field of witch the rule are set for
    *   @param string $rules The rules that are to set
    */
    public function set_validate_rules($field, $name = '', $rules = '')
    {
    	if (count($_POST) == 0)
			return $this;

    	if(empty($field) || empty($rules))
    		continue;

        $this->rules[$field]['name'] = $name;
    	$this->rules[$field]['rules'] = explode('|', $rules);
    }

    /**
    *   Runs the validation check
    *
    *   @return bool True if valid input
    */
    public function validate()
    {
        if (count($_POST) == 0)
            return false;

        $_POST = &$_POST;

        foreach ($_POST as $field => $value) 
        {   
            if(isset($this->rules[$field]))
            {
                foreach ($this->rules[$field]['rules'] as $rule) 
                {       
                    switch ($rule) 
                    {
                    case 'trim':
                        $_POST[$field] = trim($_POST[$field]);
                        break;
                    
                    case 'clean':
                        global $db;
                        $db->connect();
                        $_POST[$field] = $db->real_escape_string($_POST[$field]);
                        $db->close();
                        break;

                    case 'email':

                        break;

                    case 'required':
                        if(empty($value))
                            $this->error[] = "{$this->rules[$field]['name']} is required. <br />";
                        break;

                    case 'md5':
                        $_POST[$field] = md5($_POST[$field]);
                        break;

                    case 'sha1':
                        $_POST[$field] = sha1($_POST[$field]);
                        break;

                    default:
                        if(substr($rule, 0, 7) == 'matches')
                        {
                            if($_POST[preg_replace("/(.)+<|>/", '', $rule)] != $value)
                            {   
                                $this->error[] = "{$this->rules[$field]['name']}s does not match. <br />";
                            }
                        }
                    }
                }
            }
        }   
        if(count($this->error))
            return false;
        return true;
    }

    /**
    *   Returns any validate errors
    *
    *   @return string String of errors
    */
    public function validate_error()
    {
        foreach ($this->error as $error) 
        {
           $errors .= '<p class="error">'.$error."</p>";
        }
        return $errors;
    }
	
}
