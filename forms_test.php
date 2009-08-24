<?php

require_once dirname(__FILE__)."/forms/input_form.class.php";
require_once dirname(__FILE__)."/forms/string_field.class.php";

$form = new input_form("myform","post","?");
$form->add_field(new string_field("name","","Name",true,"Enter your name",30));

if(isset($_POST["submit"]))
{
	$form->validate();
	if($form->is_valid())
	{
		
	}
	else
	{
		print_form();
	}
}
else
{
	print_form();
}

function print_form()
{
	?>
<html>
	<body>
		<form>		
			<table>
		
			</table>
		</form>
	</body>
</html>
	<?php
}

?>