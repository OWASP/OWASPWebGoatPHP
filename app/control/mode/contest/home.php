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
                    $this->ContestName = $activeContest[0]['ContestName'];

                    $startTime = $activeContest[0]['StartTimestamp'];
                    $currentTime = time();

                    if ($currentTime < $startTime) {
                        $this->TimeRemaining = $startTime - $currentTime;
                    } else {
                        $challenges = \webgoat\ContestChallenges::getByContestID();
                        if (count($challenges) == 0) {
                            $this->Error = "Currently there are no challenges in this contest";
                        } else {
                            $this->Challenges = $challenges;
                        }
                    }
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
