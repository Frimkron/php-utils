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

require_once dirname(__FILE__)."/string_field.class.php";
require_once dirname(__FILE__)."/functions.php";


/**
 * Represents an email field
 */
class email_field extends string_field
{	
	
	/**
	 * Constructs the field description
	 * @param string $name The name of the field
	 * @param string $value The value of the field
	 * @param string $display_name The text to display to the user beside this field
	 * @param boolean $required Whether or not this field must be filled in to be valid
	 * @param string $validation_help Message to display to the user when an invalid value is entered
	 */
	public function __construct($name, $value="", $display_name="", $required=false, $validation_help="",
		$type=form_field::TYPE_DEFAULT, $max_length="")
	{		
		// anything @ domainpart [ . domainpart [ . domainpart ] ... ]
		$email_regex = "/^.+@[a-zA-Z0-9\-]+(\.[a-zA-Z0-9\-]+)*$/";
		parent::__construct($name, $value, $display_name, $required, $validation_help,
			$type, $max_length, $email_regex);				
	}
	
}
?>