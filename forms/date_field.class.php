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
 * Represents a data field with day, month and year value
 */
class date_field extends form_field
{
	
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

	public function __construct($name, $value="", $display_name="", $required=false, $validation_help="",
		$min_date_day="", $min_date_month="", $min_date_year="", $max_date_day="", $max_date_month="", $max_date_year="")
	{
		//standard stuff
		$this->name = $name;
		$this->value = $value;
		$this->display_name = $display_name;
		$this->required = $required;
		$this->validation_help = $validation_help;
		
		//date value
		$this->day_value = $this->value["day"];
		$this->month_value = $this->value["month"];
		$this->year_value = $this->value["year"];
		
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
			if($this->day_value!="" || $this->month_value!="" || $this->year_value!="")
				return false;
			else
				return !$this->required;
		}

		//check that values are numeric
		if(!is_numeric($ths->day_value) || !is_numeric($this->month_value) || !is_numeric($this->year_value))
			return false;

		//check that values are integers
		if(floor($this->day_value)!=$this->day_value || floor($this->month_value)!=$this->month_value 
				|| floor($this->year_value)!=$this->year_value)
			return false;
    	
		//check each value's range
		if($this->day_value<1 || $this->day_value>31 || $this->month_value<1 || $this->month_value>12)
			return false;
    	
		//check day doesn't exceed month max
		if($this->day_value > $this->month_days[$this->month_value])
			return false;    	

		//leap year check
		$is_leap_year = $this->year_value%4==0 && 
			($this->year_value%100!=0 || ($this->year_value%100==0 && $this->year_value%400==0));
		
		if(!$is_leap_year && $this->month_value==2 && $this->day_value==29)
			return false;
    	
		//min date check
		if($this->year_value < $this->min_date_year)
			return false;
		else
		{
			
	    		if($this->year_value == $this->min_date_year)
	    		{
	    			
	    			if($this->month_value < $this->min_date_month)
    					return false;
	    			else
    				{
    					
	    				if($this->month_value == $this->min_date_month)	
	    					if($this->day_value < $this->min_date_day)
	    						return false;
	    					
	    			}
	    			
    			}
    			
	    	}
    	
		//max date check
		if($this->year_value > $this->max_date_year)
			return false;
	    	else
		{
			
			if($this->year_value == $this->max_date_year)	
			{
	
				if($this->month_value > $this->max_date_month)
					return false;
				else
				{

					if($this->month_value == $this->max_date_month)
						if($this->day_value > $this->max_date_day)
							return false;
					
				}
				
			}
			
		}
    	
		return true;
		
	}
	
	public function populate($form_name="")
	{
		$this->value = $_REQUEST[($form_name!=""?"$form_name:":"").$this->name];
		$this->day_value = $this->value["day"];
		$this->month_value = $this->value["month"];
		$this->year_value = $this->value["year"];
	}	
}
?>