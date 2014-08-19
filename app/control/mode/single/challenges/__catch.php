<?php

class SingleModeController extends JCatchControl
{
    private $indentSize;

    public function Handle($request)
    {
        // This gives complete request path
        $request = jf::$BaseRequest;    //FIXME: Fix JCatchControl so that this is not required

        if (jf::CurrentUser()) {    // If user is logged in

            // Check if the user has permissions
            // to view the challenges
            if (jf::Check('view_single_chal')) {

                // Extract the relative request path
                // i.e the path after the controller URL
                // Ex: If request is http://localhost/webgoatphp/mode/single/challenges/HTTPBasics/static/test
                // $request will be mode/single/challenges/HTTPBasics/static/test
                // $relativePath will be HTTPBasics/static/test
                $relativePath = $this->getRelativePath($request);
                $absolutePath = LESSON_PATH.$relativePath;

                if (strpos($relativePath, "/static/") !== false) {
                    if (file_exists($absolutePath)) {
                        $FileMan = new \jf\DownloadManager();
                        return $FileMan->Feed($absolutePath);
                    }
                } else {
                    $nameOfLesson = stristr($relativePath, "/", true);
                    \webgoat\LessonScanner::loadClasses();

                    if (strpos($relativePath, "reset/") !== false) {
                        $lessonNameWithNS = "\\webgoat\\".$nameOfLesson;
                        $obj = new $lessonNameWithNS();
                        $obj->reset();

                        echo json_encode(array("status" => true));
                        return true;
                    } else {
                        if (((isset($_GET['refresh'])) || (!jf::LoadGeneralSetting("categoryLessons")))) {
                            \webgoat\LessonScanner::run();
                        }


                        $this->allCategoryLesson = jf::LoadGeneralSetting("categoryLessons");
                        try {

                            $lessonObj = \webgoat\LessonScanner::getLessonObject($nameOfLesson);
                            $lessonObj->start();
                            $this->lessonTitle = $lessonObj->getTitle();
                            $this->hints = $lessonObj->getHints();
                            $this->htmlContent = $lessonObj->getContent();
                            $this->nameOfLesson = $nameOfLesson;

                            $secureCoding = $lessonObj->isSecureCodingAllowed();
                            $sourceCodeToDisplay = "";
                            if ($secureCoding['status'] === true) {
                                $sourceCode = file($absolutePath."index.php");
                                $firstLine = $sourceCode[$secureCoding['start']];
                                $this->indentSize = strlen($firstLine) - strlen(ltrim($firstLine));

                                for ($i = $secureCoding['start']; $i < $secureCoding['end']; $i++) {
                                    $sourceCodeToDisplay .= ($this->removeWhitespaces($sourceCode[$i])."\n");
                                }
                                $this->sourceCode = $sourceCodeToDisplay;
                            }

                            // To show complete PHP Code
                            $sourceCode = file_get_contents($absolutePath."index.php");
                            $this->completeSourceCode = htmlentities($sourceCode);

                            if (isset($_POST['sourceCode'])) {
                                // Code to handle source code evaluation
                            }

                        } catch (Exception $e) {
                            //$this->error = "Lesson Not found. Please select a lesson.";
                            $this->error = $e->getMessage();
                        }

                        header("X-XSS-Protection: 0");  // Disable XSS protection
                        return $this->Present();
                    }
                }

            } else {
                // Not sufficient permissions, redirect
                // to home page of the application
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

    /**
     * Remove whitespaces to indent the code properly
     *
     * @param $line String which is to be trimmed
     *
     * @return string trimmed result
     */
    private function removeWhitespaces($line)
    {
        return rtrim(substr($line, $this->indentSize));
    }
}
