<?php

class ContestChallengeController extends JCatchControl
{
    public function Handle($request)
    {
        // This gives complete request path
        $request = jf::$BaseRequest;

        if (jf::CurrentUser()) {
            // Check if the user has permissions to view the challenges
            if (jf::Check('view_contest_chal')) {
                $relativePath = $this->getRelativePath($request);
                $absolutePath = CONTEST_CHALLENGE_PATH.$relativePath;

                $challengeName = $relativePath; // FIXME: ONLY FOR TESTING, NOT ALWAYS TRUE
                $challengeDetails = \webgoat\ContestChallenges::getByName($challengeName);
                $this->ChallengeName = $challengeDetails[0]['ChallengeName'];

                $fileContents = file_get_contents($absolutePath."/index.html");
                $this->Content = $fileContents;

                if (isset($_POST['submit'])) {
                    $this->addSubmission($challengeName);
                }

                return $this->Present();
            } else {
                // Unauthorized
                $this->Redirect(SiteRoot);
            }

        } else {
            // User not logged in
            $this->Redirect(jf::url()."/user/login?return=/$request");
        }
    }

    private function getRelativePath($request)
    {
        $presentDir = basename(dirname(__FILE__));
        return substr($request, (strpos($request, $presentDir) + strlen($presentDir) + 1));
    }

    private function addSubmission($challenge)
    {
        $challengeDetails = \webgoat\ContestChallenges::getByName($challenge);

        $flag = $_POST['flag'];
        $ip = \jf\HttpRequest::IP();
        $challengeID = $challengeDetails[0]['ID'];
        $userID = jf::CurrentUser();

        $data = array(
            'UserID' => $userID,
            'ChallengeID' => $challengeID,
            'Flag' => $flag,
            'IP' => $ip,
            'timestamp' => time()
        );

        \webgoat\ContestSubmissions::add($data);
        \webgoat\ContestChallenges::incrementTotalAttempts($challenge);

        if (\webgoat\ContestSubmissions::evaluate($challengeID, $flag)) {
            $this->Submission = 1;
            // Increment complete count
            \webgoat\ContestChallenges::incrementCompletedCount($challenge);
        } else {
            $this->Submission = 0;
        }
    }
}
