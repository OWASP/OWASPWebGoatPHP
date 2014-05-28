<?php

class SingleModeController extends JCatchControl
{
    public function Handle($request)
    {
        if (jf::CurrentUser()) {

            //This gives absolute path
            $request = jf::$BaseRequest;    //FIXME: Fix JCatchControl so that this is not required

            $relativePath = $this->getRelativePath($request);
            $fileName = LESSON_PATH.$relativePath;

            if (strpos($relativePath, "/static/") !== false) {
                if (file_exists($fileName)) {
                    echo file_get_contents($fileName);
                    return true;
                }
            } else {

                $nameOfLesson = basename($relativePath);

                jf::DeleteGeneralSetting("categoryLessons");

                if (!jf::LoadGeneralSetting("categoryLessons")) {
                    \webgoat\Scanner::run();
                }

                $this->allCategoryLesson = jf::LoadGeneralSetting("categoryLessons");

                try {
                    $lessonObj = \webgoat\Scanner::getLessonObject($nameOfLesson);
                    $lessonObj->start();
                    $this->lessonTitle = $lessonObj->getTitle();
                    $this->hints = $lessonObj->getHints();
                    $this->htmlContent = $lessonObj->getContent();
                    $this->nameOfLesson = $nameOfLesson;

                } catch (Exception $e) {
                    $this->error = "Lesson Not found. Please select a lesson.";
                }

                return $this->Present();
            }
        } else {
            $this->Redirect(jf::url().'/user/login');
        }
    }

    private function getRelativePath($request)
    {
        $presentDir = basename(dirname(__FILE__));
        return substr($request, (strpos($request, $presentDir) + strlen($presentDir) + 1));
    }
}
