<?php

function html_filter($input)
{
	$input = str_replace("&","&amp;",$input);
	$input = str_replace("<","&lt;",$input);
	$input = str_replace(">","&gt;",$input);
	return $input;
}

function attr_filter($input)
{
	$input = html_filter($input);
	$input = str_replace("\"","&quot;");
	return $input; 
}

?>