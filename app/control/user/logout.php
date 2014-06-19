<?php

class UserLogoutController extends JControl
{
    public function Start()
    {
        // If user is logged in
        if (jf::CurrentUser()) {
            jf::Logout();
        }

        if (isset($_GET["return"])) {
            $Return = $_GET["return"];
        } else {
            $Return = "";
        }

        $this->Redirect(SiteRoot.$Return);
    }
}
