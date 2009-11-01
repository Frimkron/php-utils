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
 * Represents a time field
 */
class time_field extends form_field
{	
	const TYPE_SELECTS = 1;
	const TYPE_TEXTBOXES = 2;
	
	/**
	 * The hour component of the earliset valid time
	 * @var integer
	 */
	private $min_time_hour;
	
	/**
	 * The minute component of the earliest valid time
	 * @var integer 
	 */
	private $min_time_minute;
	
	/**
	 * The hour component of the latest valid time
	 * @var integer
	 */
	private $max_time_hour;
	
	/**
	 * The minute component of the latest valid time
	 * @var integer
	 */
	private $max_time_minute;

	private $hour_value;
	
	private $minute_value;
	

	// ************************************************************************************************

	

	/**
	 * Constructs the field description
	 * @param boolean $required Must this field be filled in to be valid or not
	 * @param integer $min_time_hour The hour component of the earliest valid time
	 * @param integer $min_time_minute The minute component of the earliest valid time
	 * @param integer $max_time_hour The hour component of the latest valid time
	 * @param integer $max_time_minute The minute component of the latest valid minute
	 */
	public function __construct($name, $hour_value="", $minute_value="", $display_name="", $required=false, $validation_help="",
		$type=form_field::TYPE_DEFAULT, $min_time_hour="", $min_time_minute="", $max_time_hour=23, $max_time_minute=59)
	{
		//standard stuff
		parent::__construct($name, "", $display_name, $required, $validation_help, $type);
				
		//value
		$this->hour_value = $hour_value;
		$this->minute_value = $minute_value;
		
		//validation params
		$this->min_time_hour = $min_time_hour;
		$this->min_time_minute = $min_time_minute;
		$this->max_time_hour = $max_time_hour;
		$this->max_time_minute = $max_time_minute;
	}
	
    
	/**
	 * Determines if the given field value is valid or not
	 * @param array $time_array The time to be validated as an array containing 'hour' and 'minute' keys with appropriate values
	 * @return boolean Returns true if the field is valid, false otherwise
	 */
	public function is_valid()
	{ 	
		//check for blank
		if($this->hour_value=="" || $this->minute_value=="")
		{			
			if($this->hour_value!="" || $this->minute_value!=""){
				return false;
			}else{
				return !$this->required;
			}		
		}
    	
		//check that values are numeric
		if(!is_numeric($this->hour_value) || !is_numeric($this->minute_value)){
			return false;
		}
    	
		//check that values are integers
		if(floor($this->hour_value)!=$this->hour_value || floor($this->minute_value)!=$this->minute_value){
			return false;
		}
    	
		//check each value's range
		if($this->hour_value<0 || $this->hour_value>23 || $this->minute_value<0 || $this->minute_value>59){
			return false;
		}
		    	
		//min time check
		if($this->min_time_hour!="" && $this->hour_value < $this->min_time_hour){
	    		return false;
		}else{
    		if($this->min_time_hour=="" || $this->hour_value == $this->min_time_hour){
    			if($this->min_time_minute!="" && $this->minute_value < $this->min_time_minute){
					return false;
    			}    		
    		}
	    }
    	
		//max time check
		if($this->max_time_hour!="" && $this->hour_value > $this->max_time_hour){
	    	return false;
		}else{	    	
	    	if($this->max_time_hour=="" || $this->hour_value == $this->max_time_hour){
				if($this->max_time_minute!="" && $this->minute_value > $this->max_time_minute){
					return false;
				}
	    	}	
	    }
    	
	    return true;
	    	
	}

	public function populate()
	{
		$value = $_REQUEST[$this->get_full_name()];
		if(is_array($value))
		{
			$this->hour_value = $value["hour"];
			$this->minute_value = $value["minute"];
		}
	}
	
	private function get_hour_field_name()
	{
		return $this->get_full_name()."[hour]";
	}
	
	private function get_minute_field_name()
	{
		return $this->get_full_name()."[minute]";
	}
	
	public function get_hour_select_attributes()
	{
		return "name=\"".attr_filter($this->get_hour_field_name())."\" ";
	}
	
	public function print_hour_select_attributes()
	{
		print $this->get_hour_select_attributes();
	}
	
	public function get_minute_select_attributes()
	{
		return "name=\"".attr_filter($this->get_minute_field_name())."\" ";
	}
	
	public function print_minute_select_attributes()
	{
		print $this->get_minute_select_attributes();
	}
	
	public function get_hour_option_attributes($num)
	{
		return "value=\"".attr_filter($num)."\" "
			.($num==$this->hour_value?"selected=\"selected\"":"");
	}
	
	public function print_hour_option_attributes($num)
	{
		print $this->get_hour_option_attributes($num);
	}
	
	public function get_minute_option_attributes($num)
	{
		return "value=\"".attr_filter($num)."\" "
			.($num==$this->minute_value?"selected=\"selected\"":"");
	}
	
	public function print_minute_option_attributes($num)
	{
		print $this->get_minute_option_attributes($num);
	}
	
	public function get_field_html()
	{
		switch($this->type)
		{
			case time_field::TYPE_TEXTBOXES:
				return 
					"<input class=\"time_hour_textbox\" type=\"text\" ".$this->get_hour_textbox_attributes()." />"
					.":"
					."<input class=\"time_minute_textbox\" type=\"text\" ".$this->get_minute_textbox_attributes()." />";
			
			case time_field::TYPE_SELECTS:
			case time_field::TYPE_DEFAULT:
			default:
				$hour_opts = "";
				$hour_opts .= "<option ".$this->get_hour_option_attributes("")."></option>";
				for($i=0;$i<=23;$i++){
					$label = ($i<10?"0":"").$i;
					$hour_opts .= "<option ".$this->get_hour_option_attributes($i).">".html_filter($label)."</option>";
				}
				$minute_opts = "";
				$minute_opts .= "<option ".$this->get_minute_option_attributes("")."></option>";
				for($i=0;$i<=59;$i++){
					$label = ($i<10?"0":"").$i;
					$minute_opts .= "<option ".$this->get_minute_option_attributes($i).">".html_filter($label)."</option>";
				}
				return 
					"<select class=\"time_hour_select\" ".$this->get_hour_select_attributes().">".$hour_opts."</select>"
					.":"
					."<select class=\"time_minute_select\" ".$this->get_minute_select_attributes().">".$minute_opts."</select>";
		}
	}
	
	public function get_hidden_name_values()
	{
		return array(
			$this->get_hour_field_name() => $this->hour_value,
			$this->get_minute_field_name() => $this->minute_value
		);
	}
}
?>