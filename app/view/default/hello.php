<?php

if (isset($this->Name)){?>

	Hello <?php echo $this->Name ?>, the new jFramework visitor!
<?php } else { ?>
 	<form>Enter your name: <input type='text' name='name' /> <input type='submit' /></form>
<?php }

if($id = jf::LoadSessionSetting('shivam'))
{
	echo "Session found. Your id is : ".$id;
}
else
{
	echo "No session exists";
}

 ?>