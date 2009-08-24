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
 * Represents an enumerated type field - where the value can be one of a limited set of strings such as 'yes' and 'no'
 */
class enum_field extends form_field
{	
	/**
	 * The set of allowed values the field can be
	 * @var array
	 */
	private $enum_array;

	private $type;

	// ************************************************************************************************

	
	public function __construct($name, $value="", $display_name="", $required=false, 
		$validation_help="", $enum_array="", $type="")
	{
		//standard stuff
		$this->name = $name;
		$this->value = $value;
		$this->display_name = $display_name;
		$this->required = $required;
		$this->validation_help = $validation_help;
				
		//validation params
		$this->enum_array = $enum_array;	
		
		$this->type = $type;	
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
}
?>