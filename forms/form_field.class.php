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

/**
 * Class which all form field objects inherit from
 */
abstract class form_field
{
	const TYPE_DEFAULT = 0;
	
	//---------------- members ---------------------------------------	

	/**
	 * Array of key-value pairs to use in validation function
	 * @var array 
	 */
	protected $validation_params = array();
	/**
	 * The name of this field
	 * @var string
	 */
	protected $name;
	/**
	 * The value of this field
	 * @var mixed
	 */
	protected $value;
	/**
	 * The displayed text beside this field
	 * @var string
	 */
	protected $display_name;
	/**
	 * Whether or not this field must be filled in to be valid
	 * @var boolean
	 */
	protected $required;
	/**
	 * The text to display when the user enters an invalid value for this field
	 * @var string
	 */
	protected $validation_help;
	
	protected $display_message = "";
	
	protected $type;
	
	protected $form = "";
	
	//--------------- methods -----------------------------------------
	
	/**
	 * Constructs the field representation
	 * @param string $name The name of this field. This is the name used in the POST request
	 * @param mixed $value The current value of this field. Type depends on the field type.
	 * @param string $display_name The label text to show beside this form field
	 * @param boolean $required Whether or not this field must be filled in to be valid
	 * @param array $validation_params Array of key-value pairs containing validation params 
	 * 		for this field type. See each subclass definition for description of params to use 
	 * 		in each case.
	 * @param string $validation_help The message to display to the user when they have entered
	 * 		an invalid value for this field. e.g. "Please enter a valid date"
	 */
	public function __construct($name, $value="", $display_name="", $required=false, 
		$validation_help="", $type=form_field::TYPE_DEFAULT)
	{
		$this->name = $name;
		$this->value = $value;
		$this->display_name = $display_name;
		$this->required = $required;
		$this->validation_help = $validation_help;
		$this->type = $type;
	}
	
	public function get_name()
	{
		return $this->name;
	}
	
	public function is_required()
	{
		return $this->required;
	}
	
	public function get_display_name()
	{
		return $this->display_name;
	}
	
	public function get_validation_help()
	{
		return $this->validation_help;
	}
	
	public function set_display_message($message)
	{
		$this->display_message = $message;
	}
	
	public function get_display_message()
	{
		return $this->display_message;
	}
	
	public function has_display_message()
	{
		return $this->display_message != "";
	}
	
	public function populate()
	{
		$this->value = trim($_REQUEST[$this->get_full_name()]);
	}
	
	public function get_value()
	{
		return $this->value;
	}
	
	/**
	 * Determines if the value of a the field is valid or not
	 * @return boolean Returns true if the field value is valid or false otherwise
	 */
	public abstract function is_valid();
	
	public abstract function get_field_html();
	
	public function print_field_html()
	{
		print $this->get_field_html();
	}
	
	public function get_form()
	{
		return $this->form;
	}
	
	public function set_form($form)
	{
		$this->form = $form;
	}
	
	public function get_type()
	{
		return $this->type;
	}
	
	public function get_full_name()
	{
		return ($this->form!=""?$this->form->get_name().":":"") . $this->name;
	}
	
	public function get_hidden_name_values()
	{
		return array($this->get_full_name() => $this->get_value());
	}
}

?>