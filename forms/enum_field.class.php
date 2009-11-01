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

/**
 * Represents an enumerated type field - where the value can be one of a limited set of strings such as 'yes' and 'no'
 */
class enum_field extends form_field
{	
	const TYPE_SELECT = 1;
	const TYPE_RADIOS = 2;
	
	/**
	 * The set of allowed values the field can be
	 * @var array
	 */
	private $enum_array;
	
	// array of labels corresponding to the enum values. These are shown as options 
	// to the user.
	private $label_array;

	// ************************************************************************************************

	
	public function __construct($name, $value="", $display_name="", $required=false, 
		$validation_help="", $type=form_field::TYPE_DEFAULT, $enum_array=array(), $label_array=array())
	{
		//standard stuff
		parent::__construct($name, $value, $display_name, $required, $validation_help, $type);
				
		//validation params
		$this->enum_array = $enum_array;	
			
		$this->label_array = $label_array;			
		if(count($this->label_array)<count($this->enum_array)){
			$this->label_array = $this->enum_array;
		}
	}
	
    
	/**
	 * Determines if the field value is valid or not
	 * @return boolean Returns true if the field value is valid, false otherwise 
	 */
	public function is_valid()
	{
		
		$field_value = trim($this->value);
		
		if($field_value == "")
			return !$this->required;
		
		if(!in_array($field_value, $this->enum_array))
			return false;
		
		return true;
    	
	}
	
	public function get_num_options()
	{
		return count($this->enum_array);
	}
	
	public function get_radio_attributes($num)
	{
		return
			"name=\"".attr_filter($this->get_full_name())."\" "
			."value=\"".attr_filter($this->enum_array[$num])."\" "
			.($this->value==$this->enum_array[$num]?"checked=\"checked\"":"");
	}
	
	public function print_radio_attributes($num)
	{
		print $this->get_radio_attributes($num);
	}
	
	private function get_radio_id($num)
	{
		return $this->get_full_name().":".$this->enum_array[$num];
	}
	
	public function get_select_attributes()
	{
		return "name=\"".attr_filter($this->get_full_name())."\" ";
	}
	
	public function print_select_attributes()
	{
		print $this->get_select_attributes();
	}
	
	public function get_option_attributes($num)
	{
		return "value=\"".attr_filter($this->enum_array[$num])."\" "
				.($this->value==$this->enum_array[$num]?"selected=\"selected\"":"");
	}
	
	public function print_option_attributes($num)
	{
		print $this->get_option_attributes($num);
	}
	
	public function get_field_html()
	{
		switch($this->type)
		{
			case enum_field::TYPE_RADIOS:
				$rads = "";
				for($i=0;$i<count($this->enum_array);$i++){
					$value = $this->enum_array[$i];
					$label = $this->label_array[$i];
					$rads .= 
						"<span class=\"enum_radio_option\">"
							."<label class=\"enum_label\" for=\"".attr_filter($this->get_radio_id($i))."\">".html_filter($label)."</label>"
							."<input class=\"enum_radio\" type=\"radio\" id=\"".attr_filter($this->get_radio_id($i))."\" ".$this->get_radio_attributes($i)." />"
						."</span>";
				}
				return $rads;
			
			case enum_field::TYPE_SELECT:
			case enum_field::TYPE_DEFAULT:
			default:
				$opts = "";
				$opts .= "<option ".$this->get_option_attributes("")."></option>";
				for($i=0;$i<count($this->enum_array);$i++){
					$value = $this->enum_array[$i];
					$label = $this->label_array[$i];
					$opts .=
						"<option ".$this->get_option_attributes($i).">".html_filter($label)."</option>";
				}
				return 
					"<select class=\"enum_select\" ".$this->get_select_attributes()." >".$opts."</select>";
		}
	}
}
?>