<?php

class ModeSingleController extends JControl
{
    public function Start()
    {
        if (jf::CurrentUser()) {

            $basePath = dirname(__FILE__)."/../../model/lesson/challenges";

            $this->addLeftPanel($basePath);

            if (isset($_GET['lesson'])) {

                $lessonName = $_GET['lesson'];

                if (!is_dir($basePath.'/'. $lessonName)) {
                    $this->error = "Cannot find the given lesson.";
                } else {

                    $className = "\\webgoat\\". $lessonName;
                    $obj = new $className();
                    $obj->init();

                    if (!empty($_POST)) {
                        $obj->evaluateSubmission();
                    } else {
                        $obj->createContent();
                    }

                    $this->lessonTitle = $obj->getTitle();
                    $this->hints = $obj->getHints();
                    $this->htmlContent = $obj->getContent();

                }

            } else {
                $this->error = "No Lesson Selected.";
            }

            return $this->Present();

        } else {
            $this->Redirect(jf::url().'/user/login');
        }
    }

    private function addLeftPanel($lessonsPath = null)
    {
        if ($lessonsPath == null) {
            throw new \Exception("Argument 'Lesson Path' missing.");
        }

        $categoryObj = new \webgoat\Category();
        $categories = $categoryObj->getCategories();
        $categoryLessons = array();

        foreach ($categories as $category) {
            $categoryLessons[$category] = array();
        }

        $subDirectories = glob($lessonsPath.'/*', GLOB_ONLYDIR);

        foreach ($subDirectories as $lessonDir) {

            $className = "\\webgoat\\".basename($lessonDir);
            $obj = new $className();

            array_push($categoryLessons[$categories[$obj->getCategoryId()]], array(
                    basename($lessonDir), $obj->getTitle(), $obj->isCompleted()
            ));
        }

        $this->allCategoryLesson = $categoryLessons;
    }
}
