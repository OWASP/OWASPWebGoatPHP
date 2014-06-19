<?php

class UserLoginController extends JControl
{
    public function Start()
    {
        // If user is already logged in
        if (jf::CurrentUser()) {
            if (isset($_GET["return"])) {
                $return = $_GET["return"];
            } else {
                $return = "";
            }
            $this->Redirect(SiteRoot.$return);  // Site root does not contain trailing '/'
        }

        // TODO: Implement a secure 'Remember Me'

        if (isset($_POST["Username"]) && isset($_POST['Password'])) {
            $this->Result = jf::Login($_POST['Username'], $_POST['Password']);
        }

        //Login Successful
        if (isset($this->Result) && $this->Result) {
            if (isset($_GET["return"])) {
                $return = $_GET["return"];
            } else {
                $return = "";
            }

            $this->Redirect(SiteRoot.$return);
        }

        return $this->Present();
    }
}
