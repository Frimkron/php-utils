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
 * Represents a time field
 */
class time_field extends form_field
{	
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
	public function __construct($name, $value="", $display_name="", $required=false, $validation_help="",
		$min_time_hour="", $min_time_minute="", $max_time_hour=23, $max_time_minute=59)
	{
		//standard stuff
		$this->name = $name;
		$this->value = $value;
		$this->display_name = $display_name;
		$this->required = $required;
		$this->validation_help = $validation_help;
				
		//validation params
		$this->min_time_hour = $min_time_hour;
		$this->min_time_minute = $min_time_minute;
		$this->max_time_hour = $max_time_hour;
		$this->max_time_minute = $max_time_minute;
		
		//value
		$this->hour_value = $value["hour"];
		$this->minute_value = $value["minute"];
		
	}
	
    
	/**
	 * Determines if the given field value is valid or not
	 * @param array $time_array The time to be validated as an array containing 'hour' and 'minute' keys with appropriate values
	 * @return boolean Returns true if the field is valid, false otherwise
	 */
	public function validate($time_array)
	{

		$hour_value = trim($time_array['hour']);
		$minute_value = trim($time_array['minute']);
    	
		//check for blank
		if($hour_value=="" || $minute_value=="")
		{
			
			if($hour_value!="" || $minute_value!="")
				return false;
			else
				return !$this->required;
		
		}
    	
		//check that values are numeric
		if(!is_numeric($hour_value) || !is_numeric($minute_value))
			return false;
    	
		//check that values are integers
		if(floor($hour_value)!=$hour_value || floor($minute_value)!=$minute_value)
			return false;
    	
		//check each value's range
		if($hour_value<0 || $hour_value>23 || $minute_value<0 || $minute_value>59)
			return false;
		    	
		//min time check
		if($hour_value < $this->min_time_hour)
	    		return false;
	    	else
	    	{

	    		if($hour_value == $this->min_time_hour)
	    			if($minute_value < $this->min_time_minute)
	    				return false;
    		
	    	}
    	
		//max time check
		if($hour_value > $this->max_time_hour)
	    		return false;
	    	else
	    	{
	    		
	    		if($hour_value == $this->max_time_hour)
				if($minute_value > $this->max_time_minute)
					return false;
	    		
	    	}
    	
	    	return true;
	    	
	}

	public function populate($form_name="")
	{
		$this->value = $_REQUEST[($form_name!=""?"$form_name:":"").$this->name];
		$this->hour_value = $this->value["hour"];
		$this->minute_value = $this->value["minute"];
	}
}
?>