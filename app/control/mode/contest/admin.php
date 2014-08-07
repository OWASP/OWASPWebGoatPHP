<?php

class ModeContestAdmin extends JControl
{
    public function Start()
    {
        if (jf::CurrentUser()) {
            if (jf::Check("contest")) {
                // User is authorized

                if (isset($_POST['contest_submit'])) {
                    // Request to store the contest in the database
                    $data = array(
                        'ContestName' => $_POST['contest_name'],
                        'ContestAdmin' => $_POST['contest_admin'],
                        'StartTimestamp' => strtotime($_POST['start_date']),
                        'EndTimestamp' => strtotime($_POST['end_date'])
                    );

                    \webgoat\ContestDetails::add($data);
                }

                if (\webgoat\ContestDetails::isActivePresent()) {
                    // If an active contest is present

                } else {
                    // Show the option to start a contest
                    $this->noActiveContest = true;
                }

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
