<?php

class ModeWorkshopAdmin extends JControl
{
    public function Start()
    {
        if (jf::CurrentUser() && jf::Check('workshop')) {
            return $this->Present();
        } else {
            $this->Redirect(jf::url()."/user/login?return=/".jf::$BaseRequest);
        }
    }
}
