<?php

class ContestHomeController extends JControl
{
    public function Start()
    {
        $request = jf::$BaseRequest;

        if (jf::CurrentUser()) {
            // User is logged in, check if the user is authorized
            if (jf::Check("view_contest_chal")) {
                if (($activeContest = \webgoat\ContestDetails::getActive()) !== null) {
                    $startTime = $activeContest[0]['StartTimestamp'];
                    $currentTime = time();

                    $this->TimeRemaining = $startTime - $currentTime;
                    $this->ContestName = $activeContest[0]['ContestName'];
                } else {
                    $this->Error = "Currently there is no active contest. Check back later!!";
                }
                return $this->Present();
            } else {
                // User is not authorized
                $this->Redirect(SiteRoot);
            }
        } else {
            // User is not logged in
            $this->Redirect(jf::url()."/user/login?return=/$request");
        }
    }
}
