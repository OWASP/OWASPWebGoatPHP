<?php
class jFormController extends JControl
{
	function Start()
	{
		$form1 = $this->CreateForm ();
		$form2 = new jForm ();

		if (isset ( $_POST ))
		{
			$form1->SetData ();
			print_ ( $form1->IsValid () );
		}
		$this->Widget = $form1;
		return $this->Present ();
	}
	function CreateForm()
	{
		$form1 = new jForm ();
		$firstname = new jFormInput ( $form1, "Name" );
		{
			$firstname->SetDescription ( "Your first name." );
		}
		$lastname = new jFormInput ( $form1, "Lastname" );
		$username = new jFormInputUsername ( $form1, "Username" );
		$gender = new jFormRadio ( $form1, "Gender", array (1 => "Male", 2 => "Female" ) );
		$education = new jFormDropdown ( $form1, "Education", array ("High-school", "Bachelors", "Masters", "PhD" ) );
		$city = new jFormInput ( $form1, "City" );
		{
			$city->SetValidation ( "/^.{1,20}$/" );
		}
		$address = new jFormInput ( $form1, "Address" );
		{
			$address->SetStyle ( "width:400px" );
			$address->SetValidation ( "/^.{10,100}$/" );
			$address->SetDescription ( "Provide valid address, and a postal one." );
		}
		$postalCode = new jFormInputNumber ( $form1, "Postal Code", 10, 10 );
		$birthdate = new jFormInputDate ( $form1, "Birthdate" );
		$mobile = new jFormInputNumber ( $form1, "Mobile", 8, 20 );
		$email = new jFormInputEmail ( $form1, "E-Mail" );
		$textarea = new jFormTextarea ( $form1, "Comments" );
		{
			$textarea->SetStyle ( "width:250px;min-height:100px;" );
		}

		$captcha = new jFormCaptcha ( $form1);
		$form1_submit = new jFormSubmit ( $form1, "Create" );
		return $form1;
	}
}
?>