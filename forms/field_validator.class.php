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
 * Convenience functions for validating form fields
 */

/**
 * Determines if a date field value is valid according to the specified criteria
 * @param array $field_value The field value to be validated. Must have 'day', 'month' and 'year' keys with appropriate values
 * @param boolean $required Must this field be filled in to be valid or not
 * @param integer $min_date_day The day component of the earlist valid date
 * @param integer $min_date_month The month component of the earliest valid date
 * @param integer $min_date_year The year component of the earliest valid date
 * @param integer $max_date_day The day component of the latest valid date
 * @param integer $max_date_month The month component of the latest valid date
 * @param integer $max_date_year The year component of the latest valid year
 * @return boolean Returns true if the field value is valid, false otherwise
 */
function validate_date($field_value, $required, 
		$min_date_day, $min_date_month, $min_date_year,
		$max_date_day, $max_date_month, $max_date_year)
{
	
	require_once dirname(__FILE__)."/date_field.class.php";
	
	$date_field = new date_field("", $field_value, "", $required, "", 
					$min_date_day, $min_date_month, $min_date_year,
					$max_date_day, $max_date_month, $max_date_year);
	
	return $date_field->is_valid();
		
}
	
	
/**
 * Determines if an email field value is valid according to the specified criteria
 * @param string $field_value The field value to be validated
 * @param boolean $required Must this field be filled in to be valid or not
 * @param integer $max_length The maximum number of characters the email address can be
 * @return boolean Returns true if the field value is valid, false otherwise
 */
function validate_email($field_value, $required, $max_length)
{
	
	require_once dirname(__FILE__)."/email_field.class.php";
	
	$email_field = new email_field("", $field_value, "", $required, "", $max_length);
	
	return $email_field->is_valid();
	
}
	
	
/**
 * Determines if an enumerated type field value is valid according to the specified criteria
 * @param string $field_value The field value to be validated
 * @param boolean $required Must this field be filled in to be valid or not
 * @param array $enum_array The set of allowed values for the field
 * @return boolean Returns true if the field value is valid, false otherwise
 */
function validate_enum($field_value, $required, $enum_array)
{
	
	require_once dirname(__FILE__)."/enum_field.class.php";
	
	$enum_field = new enum_field("", $field_value, "", $required, "", $enum_array);
	
	return $enum_field->is_valid();
	
}
	 
	 
/**
 * Determines if a float field value is valid or not according to the criteria specified
 * @param float $field_value The field value to be validated
 * @param boolean required Must this field be filled in to be valid or not
 * @param float $range_min The minimum valid value the field value can be
 * @param float $range_max The maximum valid field value
 * @return boolean Returns true if the field value is valid, false otherwise
 */
function validate_float($field_value, $required, $range_min, $range_max)
{

	require_once dirname(__FILE__)."/float_field.class.php";
	
	$float_field = new float_field("", $field_value, "", $required, "", 
		$range_min, $range_max);
	
	return $float_field->is_valid();
	
}
	
	 
 /**
 * Determines if an integer field value is valid according to the specified criteria
 * @param integer $field_value The field value to be validated
 * @param boolean $required Must this field be filled in to be valid or not
 * @param integer $range_min The minimum valid field value
 * @param integer $range_max The maximum valid field value
 * @return boolean Returns true if the field is valid, false otherwise
 */
function validate_integer($field_value, $required, $range_min, $range_max)
{

	require_once dirname(__FILE__)."/integer_field.class.php";
	
	$integer_field = new integer_field("", $field_value, "", $required, "", 
		$range_min, $range_max);
	
	return $integer_field->is_valid();
	
}
	
	 
/**
 * Determines if a string field value is valid or not according to the specified criteria
 * @param string $field_value The field value to be validated
 * @param boolean $required Must this field be filled in to be valid or not
 * @param integer $max_length The maximum number of characters the field value can be
 * @param string $regex A regular expression describing the set of possible field values
 * @param integer $min_length The minimum number of characters the field value can be
 * @return boolean Returns true if the field value is valid, false otherwise
 */
function validate_string($field_value, $required, $max_length, $regex="", $min_length=0)
{

	require_once dirname(__FILE__)."/string_field.class.php";
	
	$string_field = new string_field("", $field_value, "", $required, "", 
		$max_length, $regex, $min_length);
	
	return $string_field->is_valid();
		
}
	
	 
/**
 * Determines if a time field value is valid or not according to the specified criteria
 * @param array $field_value The field value to be validated. Must contain 'hour' and 'minute' keys with appropriate values. 
 * @param boolean $required Must this field be filled in to be valid or not
 * @param integer $min_time_hour The hour component of the earliest valid time
 * @param integer $min_time_minute The minute component of the earliest valid time
 * @param integer $max_time_hour The hour component of the latest valid time
 * @param integer $max_time_minute The minute component of the latest valid minute
 * @return boolean Returns true if the field value is valid, false otherwise
 */
function validate_time($field_value, $required, $min_time_hour=0, $min_time_minute=0,
				 	$max_time_hour=23, $max_time_minute=59)
{
	
	include_once dirname(__FILE__)."/time_field.class.php";
	
	$time_field = new time_field("", $field_value, "", $required, "", 
		$min_time_hour, $min_time_minute, $max_time_hour, $max_time_minute);
	
	return $time_field->is_valid();
	
}
	

?>