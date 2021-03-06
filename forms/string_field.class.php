<?php
/* 
Copyright (c) 2009 Mark Frimston

Permission is hereby granted, free of charge, to any person
obtaining a copy of this software and associated documentation
files (the "Software"), to deal in the Software without
restriction, including without limitation the rights to use,
copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the
Software is furnished to do so, subject to the following
conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
OTHER DEALINGS IN THE SOFTWARE.
*/

require_once dirname(__FILE__)."/form_field.class.php";


/**
 * Represents a string field
 */
class string_field extends form_field
{
	const TYPE_TEXTBOX = 1;
	const TYPE_TEXTAREA = 2;
	
	/**
	 * The maximum number of characters the field value can be
	 * @var integer
	 */
	private $max_length = 0;
	
	/**
	 * A regular expression describing the set of valid field values
	 * @var string 
	 */
	private $regex = "";
	
	/**
	 * The minimum number of characters the field value can be
	 * @var integer
	 */
	private $min_length = 0;


	/**
	 * Constructs the field description
	 * @param boolean $required Must this field be filled in to be valid or not
	 * @param integer $max_length The maximum number of characters the field value can be
	 * @param string $regex A regular expression describing the set of possible field values
	 * @param integer $min_length The minimum number of characters the field value can be
	 */
	public function __construct($name, $value="", $display_name="", $required=false, $validation_help="",
		$type=form_field::TYPE_DEFAULT, $max_length="", $regex="", $min_length=0)
	{
		//standard stuff
		parent::__construct($name, $value, $display_name, $required, $validation_help, $type);
				
		//validation params
		$this->max_length = $max_length;
		$this->regex = $regex;
		$this->min_length = $min_length;	
	}
	
	
	/**
	 * Determines if the field value is valid or not
	 * @return boolean Returns true if the field is valid, false otherwise
	 */
	public function is_valid()
	{
		
		$field_value = trim($this->value);
		
		if($field_value == "")
			return !$this->required;

		if(($this->min_length != "" && strlen($field_value)<$this->min_length) 
				|| ($this->max_length != "" && strlen($field_value)>$this->max_length))
			return false;
		
		if($this->regex!="" && preg_match($this->regex,$field_value)<1)
			return false;
		
		return true;
		
	}
	
	public function get_textbox_attributes()
	{
		return "name=\"".attr_filter($this->get_full_name())."\" "
				."value=\"".attr_filter($this->value)."\"";
	}
	
	public function print_textbox_attributes()
	{
		print $this->get_textbox_attributes();
	}
	
	public function get_textarea_content()
	{
		return html_filter($this->value);
	}
	
	public function print_textarea_content()
	{
		print $this->get_textarea_content();
	}
	
	public function get_field_html()
	{
		switch($this->type)
		{
			case string_field::TYPE_TEXTAREA:
				return
					"<textarea ".$this->get_textarea_attributes().">".$this->get_textarea_content()."</textarea>";
			
			case string_field::TYPE_TEXTBOX:
			case string_field::TYPE_DEFAULT:
			default:
				return
					"<input class=\"string_textbox\" type=\"text\" ".$this->get_textbox_attributes()." />";
		}
	}

}
?>
