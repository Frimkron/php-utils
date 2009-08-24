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
 * String field validation class
 */
class boolean_field extends form_field
{
	
	const ON_VALUE = "on";
	
	private $enum_array;

	private $type;
	
	private $false_label;
	private $true_label;
		
	
	public function __construct($name, $value="", $display_name="", $required=false, 
		$validation_help="", $type="", $false_label="", $true_label="")
	{		
		//standard stuff
		$this->name = $name;
		$this->value = $value;
		$this->display_name = $display_name;
		$this->required = $required;
		$this->validation_help = $validation_help;		
		
		$this->type = $type;
		$this->false_label = $false_label;
		$this->true_label = $true_label;
	}
	
	
	/**
	 * Determines if the field value is valid or not
	 * @return boolean Returns true if the field is valid, false otherwise
	 */
	public function is_valid()
	{
		
		$field_value = trim($this->value);
		
		if($field_value == "")
		{
			return !$this->required;
		}
		else
		{
			if($field_value != boolean_field::ON_VALUE)
			{
				return false;
			}
		}
		
		return true;
		
	}
	
	public function get_checkbox_attributes()
	{
		// TODO field name needs form name! Doh!
		return 
			"name=\"" . 
	}
	
	public function print_checkbox_attributes()
	{
		print $this->get_checkbox_attributes();
	}
	
	public function get_field_html()
	{
		//TODO
		return "";
	}

}
?>
