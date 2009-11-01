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
require_once dirname(__FILE__)."/functions.php";

class boolean_field extends form_field
{
	
	const ON_VALUE = "on";
	const OFF_VALUE = "off";
	
	const TYPE_CHECKBOX = 1;
	const TYPE_RADIOS = 2;
	const TYPE_SELECT = 3;
	
	private $enum_array;
	
	private $false_label;
	private $true_label;
		
	
	public function __construct($name, $value="", $display_name="", $required=false, 
		$validation_help="", $type=form_field::TYPE_DEFAULT, $false_label="", $true_label="")
	{		
		//standard stuff
		parent::__construct($name, $value, $display_name, $required, $validation_help, $type);
		
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
		return 
			"name=\"".attr_filter($this->get_full_name())."\" "
			."value=\"".attr_filter(boolean_field::ON_VALUE)."\" "
			.(trim($this->value)==boolean_field::ON_VALUE?"checked=\"checked\"":"");
	}
	
	public function print_checkbox_attributes()
	{
		print $this->get_checkbox_attributes();
	}
	
	public function get_radio_attributes($truefalse)
	{
		return "name=\"".attr_filter($this->get_full_name())."\" "
				."value=\"".attr_filter($truefalse?boolean_field::ON_VALUE:boolean_field::OFF_VALUE)."\" "
				.((trim($this->value)==boolean_field::ON_VALUE)==$truefalse?"checked=\"checked\"":"");
	}
	
	public function print_radio_attributes($truefalse)
	{
		print $this->get_radio_attributes($truefalse);
	}
	
	private function get_radio_id($truefalse)
	{
		return $this->get_full_name().":".($truefalse?boolean_field::ON_VALUE:boolean_field::OFF_VALUE);
	}
	
	public function get_select_attributes()
	{
		return "name=\"".attr_filter($this->get_full_name())."\"";
	}
	
	public function print_select_attributes()
	{
		print $this->get_select_attributes();
	}
	
	public function get_option_attributes($truefalse)
	{
		return "value=\"".attr_filter($truefalse?boolean_field::ON_VALUE:boolean_field::OFF_VALUE)."\" "
			.((trim($this->value)==boolean_field::ON_VALUE)==$truefalse?"selected=\"selected\"":"");
	}
	
	public function print_option_attributes($truefalse)
	{
		print $this->get_option_attributes($truefalse);
	}
	
	public function get_field_html()
	{
		switch($this->type)
		{
			case boolean_field::TYPE_RADIOS:
				return 
					"<span class=\"boolean_radio_option\">"
						."<label class=\"boolean_label\" for=\"".attr_filter($this->get_radio_id(true))."\">".html_filter($this->true_label)."</label>"
						."<input class=\"boolean_radio\" type=\"radio\" id=\"".attr_filter($this->get_radio_id(true))."\"".$this->get_radio_attributes(true)." />"
					."</span>"
					."<span class=\"boolean_radio_option\">"
						."<label class=\"boolean_label\" for=\"".attr_filter($this->get_radio_id(false))."\">".html_filter($this->false_label)."</label>"
						."<input class=\"boolean_radio\" type=\"radio\" id=\"".attr_filter($this->get_radio_id(false))."\" ".$this->get_radio_attributes(false)." />"
					."</span>";
			
			case boolean_field::TYPE_SELECT:
				return
					"<select class=\"boolean_select\" ".$this->get_select_attributes().">"
						."<option ".$this->get_option_attributes(true).">".html_filter($this->true_label)."</option>"
						."<option ".$this->get_option_attributes(false).">".html_filter($this->false_label)."</option>"
					."</select>";
					
			case boolean_field::TYPE_CHECKBOX:	
			case boolean_field::TYPE_DEFAULT:			
			default:
				return 
					"<input class=\"boolean_checkbox\" type=\"checkbox\" " . $this->get_checkbox_attributes() . " />";
		}
		
	}

}
?>
