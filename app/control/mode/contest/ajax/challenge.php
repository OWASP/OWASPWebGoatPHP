<?php

class ModeContestAjaxChallenge extends JControl
{
    public function Start()
    {
        if (jf::CurrentUser()) {
            if (jf::Check("contest")) {
                if (isset($_POST['challenge'])
                    && isset($_POST['name'])
                    && isset($_POST['points'])
                    && isset($_POST['flag'])
                ) {
                    $hashedFlag = md5($_POST['flag']);
                    $activeContest = \webgoat\ContestDetails::getActive();
                    $activeContestID = $activeContest[0]['ID'];

                    $data = array(
                        'ContestID' => $activeContestID,
                        'ChallengeName' => $_POST['challenge'],
                        'NameToDisplay' => $_POST['name'],
                        'Points' => $_POST['points'],
                        'CorrectFlag' => $hashedFlag
                    );
                    \webgoat\ContestChallenges::add($data);
                    echo json_encode(array('status' => true, 'message' => 'Challenge successfully added'));

                    return true;
                }
            }
        }
    }
}
