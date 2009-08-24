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
 * Represents a real number field eg 5.46
 */
class float_field extends form_field
{
		
	/**
	 * The minimum valid value for the field
	 * @var float
	 */
	private $range_min = 0;
	
	/**
	 * The maximum valid value for the field
	 * @var float
	 */ 
	private $range_max = 0;
	

	/**
	 * Constructs the field description
	 * @param boolean required Must this field be filled in to be valid or not
	 * @param float $range_min The minimum valid value the field value can be
	 * @param float $range_max The maximum valid field value
	 */
	public function __construct($name, $value="", $display_name="", $required=false, $validation_help="",
		$range_min="", $range_max="")
	{
		//standard stuff
		$this->name = $name;
		$this->value = $value;
		$this->display_name = $display_name;
		$this->required = $required;
		$this->validation_help = $validation_help;
				
		//validation params
		$this->range_min = $range_min;
		$this->range_max = $range_max;
		
		//create element
		/*
		$this->element = $GLOBALS['output']->get_document()->createElement("float-form-field");
		$this->element->setAttribute("value",$this->value);
		$this->element->setAttribute("range-min","$range_min");
		$this->element->setAttribute("range-max",$range_max);
		*/
	}
	
	
	/**
	 * Determines whether the field value is valid or not
	 * @return boolean Returns true if the field is valid, false otherwise
	 */
	function is_valid()
	{

		$field_value = trim($this->value);
		
		if($field_value == "")
			return !$this->required;
		
		if(!is_numeric($field_value))
			return false;
		
		if($field_value < $this->range_min || $field_value > $this->range_max)
			return false;
		
		return true;

	}

}
?>