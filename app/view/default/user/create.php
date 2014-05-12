<html>

	<div>
		<?php

			if(isset($this->Error))
			{
				if($this->Error == 1)
					echo "User Id already exists";
			}
			else if(isset($this->Result))
			{
				echo "User successfully created";
			}
		?>
	</div>
	<form method="POST">
		<input type="text" name="username" placeholder="UserName"><br>
		<input type="password" name="password" placeholder="Password"><br>
		<input type="submit" name="submit">
	</form>

</html>