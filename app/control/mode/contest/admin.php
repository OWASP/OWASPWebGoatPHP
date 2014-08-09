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
                    $this->addContest();
                }

                if (\webgoat\ContestDetails::isActivePresent()) {
                    // If an active contest is present
                    $contestDetails = \webgoat\ContestDetails::getActive();
                    $contestChallenges = \webgoat\ContestChallenges::getByContestID($contestDetails[0]['ID']);
                    $contestUsers = \webgoat\ContestUsers::getAll();

                    $this->ContestName = $contestDetails[0]['ContestName'];
                    $this->ContestStart = date("d/m/Y h:i:s A", $contestDetails[0]['StartTimestamp']);
                    $this->ContestEnd = date("d/m/Y h:i:s A", $contestDetails[0]['EndTimestamp']);

                    $this->UserCount = count($contestUsers);
                    $this->ChallengeCount = count($contestChallenges);

                    $this->Challenges = $contestChallenges;
                    $this->insertNewChallenges();
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

    private function addContest()
    {
        $startTimestamp = strtotime($_POST['start_date']);
        $endTimestamp = strtotime($_POST['end_date']);

        if ($startTimestamp >= $endTimestamp) {
            $this->Error = "Invalid Time";
        } else {
            $data = array(
                'ContestName' => $_POST['contest_name'],
                'ContestAdmin' => $_POST['contest_admin'],
                'StartTimestamp' => strtotime($_POST['start_date']),
                'EndTimestamp' => strtotime($_POST['end_date'])
            );

            \webgoat\ContestDetails::add($data);
        }
    }

    private function insertNewChallenges()
    {
        $allChallenges = \webgoat\ContestChallengeScanner::run();

        $result = array();
        foreach ($allChallenges as $challenge) {
            if (($details = \webgoat\ContestChallenges::getByName($challenge)) === null) {
                array_push($result, $challenge);
            }
        }

        $this->newChallenges = $result;
    }
}
