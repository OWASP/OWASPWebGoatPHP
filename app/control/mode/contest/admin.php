<?php

class ModeContestAdmin extends JControl
{
    public function Start()
    {
        if (jf::CurrentUser()) {
            if (jf::Check("contest")) {
                return $this->Present();
            } else {
                // User is not authorized
                $this->Redirect(SiteRoot);  // Redirect to home page
            }
        } else {
            // User is not authenticated
            $this->Redirect(jf::url()."/user/login?return=/".jf::$BaseRequest);
        }
    }
}
