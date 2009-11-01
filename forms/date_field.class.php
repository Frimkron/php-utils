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
 * Represents a data field with day, month and year value
 */
class date_field extends form_field
{
	const TYPE_SELECTS = 1;
	const TYPE_TEXTBOXES = 2;
	
	const DEFAULT_MIN_YEAR_OPT = 1970;
	const DEFAULT_MAX_YEAR_OPT = 2038;
	
	
	/**
	 * Holds the number of days in each month
	 * @var array
	 */
	private $month_days = array(
		1 => 31,
		2 => 29,
		3 => 31,
		4 => 30,
		5 => 31,
		6 => 30,
		7 => 31,
		8 => 31,
		9 => 30,
		10=> 31,
		11=> 30,
		12=> 31
	);
	
	// Names of each month
	private $month_names = array(
		"Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"
	);
	
	/**
	 * The day component of the earliest valid date
	 * @var integer
	 */
	private $min_date_day;
	
	/**
	 * The month component of the earliest valid date
	 * @var integer
	 */
	private $min_date_month;
	
	/**
	 * The year component of the earliest valid date
	 * @var integer
	 */
	private $min_date_year;
	
	/**
	 * The day components of the latest valid date
	 * @var integer
	 */
	private $max_date_day;
	
	/**
	 * The month component of the latest valid date
	 * @var integer
	 */
	private $max_date_month;
	
	/**
	 * The year component of the latest valid year
	 * @var integer
	 */
	private $max_date_year;

	private $day_value;
	
	private $month_value;
	
	private $year_value;


	// ************************************************************************************************

	public function __construct($name, $day_value="", $month_value="", $year_value="", 
		$display_name="", $required=false, $validation_help="", $type=form_field::TYPE_DEFAULT,
		$min_date_day="", $min_date_month="", $min_date_year="", $max_date_day="", 
		$max_date_month="", $max_date_year="")
	{
		//standard stuff
		parent::__construct($name, "", $display_name, $required, $validation_help, $type);
		
		//date value
		$this->day_value = $day_value;
		$this->month_value = $month_value;
		$this->year_value = $year_value;
		
		//validation params
		$this->min_date_day = $min_date_day;
		$this->min_date_month = $min_date_month;
		$this->min_date_year = $min_date_year;
		$this->max_date_day = $max_date_day;
		$this->max_date_month = $max_date_month;
		$this->max_date_year = $max_date_year;
			
	}
	
    
	/**
	 * Determines if the given field value is valid or not
	 * @return boolean Returns true if the field is valid, false otherwise
	 */
	public function is_valid()
	{		
		//check for blank
		if($this->day_value=="" || $this->month_value=="" || $this->year_value=="")
		{
			if($this->day_value!="" || $this->month_value!="" || $this->year_value!=""){
				return false;
			}else{
				return !$this->required;
			}
		}

		//check that values are numeric
		if(!is_numeric($this->day_value) || !is_numeric($this->month_value) || !is_numeric($this->year_value)){
			return false;
		}

		//check that values are integers
		if(floor($this->day_value)!=$this->day_value || floor($this->month_value)!=$this->month_value 
				|| floor($this->year_value)!=$this->year_value){
			return false;
		}
    	
		//check each value's range
		if($this->day_value<1 || $this->day_value>31 || $this->month_value<1 || $this->month_value>12){
			return false;
		}
    	
		//check day doesn't exceed month max
		if($this->day_value > $this->month_days[$this->month_value]){
			return false;    	
		}

		//leap year check
		$is_leap_year = $this->year_value%4==0 && 
			($this->year_value%100!=0 || ($this->year_value%100==0 && $this->year_value%400==0));
		
		if(!$is_leap_year && $this->month_value==2 && $this->day_value==29){
			return false;
		}
    	
		//min date check
		if($this->min_date_year!="" && $this->year_value < $this->min_date_year){
			return false;
		}else{			
    		if($this->min_date_year=="" || $this->year_value == $this->min_date_year){	    			
    			if($this->min_date_month!="" && $this->month_value < $this->min_date_month){
    				return false;
    			}else{    				
    				if($this->min_date_month=="" || $this->month_value == $this->min_date_month){	
    					if($this->min_date_day!="" && $this->day_value < $this->min_date_day){
    						return false;	    					
    					}
    				}
    			}	    			
    		}    			
	    }
    	
		//max date check
		if($this->max_date_year!="" && $this->year_value > $this->max_date_year){
			return false;
		}else{			
			if($this->max_date_year=="" || $this->year_value == $this->max_date_year){	
				if($this->max_date_month!="" && $this->month_value > $this->max_date_month){
					return false;
				}else{
					if($this->max_date_month=="" || $this->month_value == $this->max_date_month){
						if($this->max_date_day!="" && $this->day_value > $this->max_date_day){
							return false;					
						}
					}
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
			$this->day_value = $value["day"];
			$this->month_value = $value["month"];
			$this->year_value = $value["year"];
		}
	}	
	
	private function get_day_field_name()
	{
		return $this->get_full_name()."[day]";
	}
	
	private function get_month_field_name()
	{
		return $this->get_full_name()."[month]";
	}
	
	private function get_year_field_name()
	{
		return $this->get_full_name()."[year]";
	}
	
	public function get_day_select_attributes()
	{
		return "name=\"".attr_filter($this->get_day_field_name())."\" ";
	}
	
	public function print_day_select_attributes()
	{
		print $this->get_day_select_attributes();
	}
	
	public function get_month_select_attributes()
	{
		return "name=\"".attr_filter($this->get_month_field_name())."\"";
	}
	
	public function print_month_select_attributes()
	{
		print $this->get_month_select_attributes();
	}
	
	public function get_year_select_attributes()
	{
		return "name=\"".attr_filter($this->get_year_field_name())."\"";
	}
	
	public function print_year_select_attributes()
	{
		print $this->get_year_select_attributes(); 
	}
	
	public function get_day_option_attributes($number)
	{
		return "value=\"".attr_filter($number)."\" "
			.($this->day_value==$number?"selected=\"selected\"":"");
	}
	
	public function print_day_option_attributes($number)
	{
		print $this->get_day_option_attributes($number);
	}
	
	public function get_month_option_attributes($number)
	{
		return "value=\"".attr_filter($number)."\" "
			.($this->month_value==$number?"selected=\"selected\"":"");
	}
	
	public function print_month_option_attributes($number)
	{
		print $this->get_month_option_attributes($number);
	}
	
	public function get_year_option_attributes($number)
	{
		return "value=\"".attr_filter($number)."\" "
			.($this->year_value==$number?"selected=\"selected\"":"");
	}
	
	public function print_year_option_attributes($number)
	{
		print $this->get_year_option_attributes($number);
	}
	
	public function get_day_textbox_attributes()
	{
		return "name=\"".attr_filter($this->get_day_field_name())."\" "
			."value=\"".attr_filter($this->day_value!=""?$this->day_value:"DD")."\" ";
	}
	
	public function print_day_textbox_attributes()
	{
		print $this->get_day_textbox_attributes();
	}
	
	public function get_month_textbox_attributes()
	{
		return "name=\"".attr_filter($this->get_month_field_name())."\" "
			."value=\"".attr_filter($this->month_value!=""?$this->month_value:"MM")."\" ";
	}
	
	public function print_month_textbox_attributes()
	{
		print $this->get_month_textbox_attributes();
	}
	
	public function get_year_textbox_attributes()
	{
		return "name=\"".attr_filter($this->get_year_field_name())."\" "
			."value=\"".attr_filter($this->year_value!=""?$this->year_value:"YYYY")."\" ";
	}
	
	public function print_year_textbox_attributes()
	{
		print $this->get_year_textbox_attributes();
	}		 
	
	public function get_field_html()
	{
		switch($this->type)
		{
			case date_field::TYPE_TEXTBOXES:
				return 
					"<input class=\"date_day_textbox\" type=\"text\" ".$this->get_day_textbox_attributes()." />"
					."/"
					."<input class=\"date_month_textbox\" type=\"text\" ".$this->get_month_textbox_attributes()." />"
					."/"
					."<input class=\"date_year_textbox\" type=\"text\" ".$this->get_year_textbox_attributes()." />";
			
			case date_field::TYPE_SELECTS:
			case date_field::TYPE_DEFAULT:
			default:
				$day_opts = "";
				$day_opts .= "<option ".$this->get_day_option_attributes("")."></option>";
				for($i=1;$i<=31;$i++){
					$day_opts .= "<option ".$this->get_day_option_attributes($i).">".html_filter($i)."</option>";
				}
				$month_opts = "";
				$month_opts .= "<option ".$this->get_month_option_attributes("")."></option>";
				for($i=1;$i<=12;$i++){
					$month_opts .= "<option ".$this->get_month_option_attributes($i).">".html_filter($this->month_names[$i-1])."</option>";
				}
				$year_start = $this->min_date_year!=""?$this->min_date_year:date_field::DEFAULT_MIN_YEAR_OPT;
				$year_end = $this->max_date_year!=""?$this->max_date_year:date_field::DEFAULT_MAX_YEAR_OPT;
				$year_opts = "";
				$year_opts .= "<option ".$this->get_year_option_attributes("")."></option>";
				for($i=$year_start;$i<=$year_end;$i++){
					$year_opts .= "<option ".$this->get_year_option_attributes($i).">".html_filter($i)."</option>";
				}
				return 
					"<select class=\"date_day_select\" ".$this->get_day_select_attributes().">".$day_opts."</select>"
					."<select class=\"date_month_select\" ".$this->get_month_select_attributes().">".$month_opts."</select>"
					."<select class=\"date_year_select\" ".$this->get_year_select_attributes().">".$year_opts."</select>";
		}
	}
	
	public function get_hidden_name_values()
	{
		return array(
			$this->get_day_field_name() => $this->day_value,
			$this->get_month_field_name() => $this->month_value,
			$this->get_year_field_name() => $this->year_value
		);
	}
}
?>