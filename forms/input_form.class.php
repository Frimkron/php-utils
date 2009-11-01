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

require_once dirname(__FILE__)."/functions.php";
require_once dirname(__FILE__)."/form_field.class.php";

/**
 * Class representing a collection of data fields 
 * Can be used to create a form on the page or validate data
 * posted from a form
 */
class input_form
{

	/**
	 * The list of field components included in this form
	 * @var array
	 */
	private $fields = array();

	/**
	 * The name of the form
	 * @var string
	 */
	private $name;
	
	private $method;
	private $action;
	private $submit_label;
	
	private $is_valid = true;
	private $hiddens_valid = true;
		


	/**
	 * Constructs the form object
	 * @param string $name The name of the form
	 * @param class $submit The submit button field object - a form_field object
	 */
    public function __construct($name="", $method="", $action="", $submit_label="Submit") 
    {
    	$this->name = $name;
    	$this->method = $method;
    	$this->action = $action;
    	$this->submit_label = $submit_label;
    }
    
    /**
     * Adds a field to the form object
     * @param class $form_field The form_field object to add to the form
     */
    public function add_field($form_field, $hidden=false)
    {
    	//add to array, allowing lookup by name
    	$this->fields[$form_field->get_name()] = 
    		array("field"=>$form_field, "hidden"=>$hidden);
    			
    	// set reference to form
    	$form_field->set_form($this);
    }
    
    /**
     * Gets a field of the form by field name
     * @param string $field_name The name of the field object to retreive
     * @return class The form_field object stored under this name
     */
    public function get_field($field_name)
    {
    	return $this->fields[$field_name]["field"];
    }
    
    public function is_hidden($field_name)
    {
    	return $this->fields[$field_name]["hidden"];
    }
     
    public function populate_fields()
    {
    	foreach($this->fields as $field_data)
    	{
    		$field_data["field"]->populate($this->name);	
    	}
    }  	
    
    public function get_field_message($field_name)
    {
    	return $this->get_field($field_name)->get_display_message();
    }
    
    public function do_field_message($field_name)
    {
    	global $output;
    	
    	echo $output->xml_filter($this->get_field_message($field_name));
    }
    
    public function validate()
    {
    	$this->is_valid = true;
    	$this->hiddens_valid = true;
    	
    	foreach($this->fields as $field_data)
    	{
    		if(!$field_data["field"]->is_valid())
    		{
    			$this->is_valid = false;
    			if($field_data["hidden"])
    			{
    				$this->hiddens_valid = false;
    			}
    			$field_data["field"]->set_display_message($field_data["field"]->get_validation_help());	
    		}
    		else
    		{
    			$field_data["field"]->set_display_message("");
    		}
    	}
    	return $this->is_valid;
    }
    
    public function is_valid()
    {
    	return $this->is_valid;
    }
    
    public function get_hiddens_valid()
    {
    	return $this->hiddens_valid;
    }
    
    public function get_html_attributes()
    {
    	return 
    		"method=\"".attr_filter($this->method)."\" "
    		."action=\"".attr_filter($this->action)."\" ";
    }
    
    public function print_html_attributes()
    {
    	print $this->get_html_attributes();
    }
    
    public function get_hiddens_html()
    {
    	$html = "";
    	foreach($this->fields as $fieldname => $fieldinfo)
    	{
    		if($fieldinfo["hidden"])
    		{
    			foreach($fieldinfo["field"]->get_hidden_name_values() as $name => $value)
    			{
    				$html .= 
	    				"<input type=\"hidden\" "
	    				."name=\"" . attr_filter($name). "\" "
	    				."value=\"" . attr_filter($value) . "\" "
	    				."/>\n";
    			}
    		}
    	}
    	return $html;
    }
    
    public function print_hiddens_html()
    {
    	print $this->get_hiddens_html();
    }
    
    public function get_form_html()
    {
    	$html = 
    		"<form " . $this->get_html_attributes() . ">\n";
    	$html .= 
    		$this->get_hiddens_html();
    	$html .= 
    		"	<dl>\n";
    	foreach($this->fields as $fieldname => $fieldinfo)
    	{
    		if(!$fieldinfo["hidden"])
    		{
    			$html .= 
    				"<dt>\n"
    				. html_filter($fieldinfo["field"]->get_display_name()) . "\n"
    				."</dt>\n"
    				."<dd>\n"
    				. $fieldinfo["field"]->get_field_html() ."\n";
    			if($fieldinfo["field"]->is_required())
    			{
    				$html .= "* ";    					
    			}
    			if($fieldinfo["field"]->has_display_message())
    			{
    				$html .= 
    					"<span class=\"validation_message\">" . html_filter($fieldinfo["field"]->get_display_message()) . "</span>";
    			}
    			$html .= 
    				"</dd>\n";
    		}
    	}
    	$html .= 
    		"		<dt>&nbsp;</dt>\n"
    		."		<dd>\n"
    		.$this->get_submit_html()
    		."		</dd>\n"
    		."	</dl>\n"
    		."</form>\n";
    	return $html;
    }
    
    public function print_form_html()
    {
    	print $this->get_form_html();
    }
    
    public function get_name()
    {
    	return $this->name;
    }
    
    public function get_full_submit_name()
    {
    	return $this->name.":"."submit";
    }
    
    public function get_submit_html()
    {
    	return "<input class=\"form_submit\" type=\"submit\" name=\"".attr_filter($this->get_full_submit_name())."\" " 
    		."value=\"".attr_filter($this->submit_label)."\" />";
    }
    
    public function print_submit_html()
    {
    	print $this->get_submit_html();
    }
}
?>