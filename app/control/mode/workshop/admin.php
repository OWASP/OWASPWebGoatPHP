<?php

class ModeWorkshopAdmin extends JControl
{
    public function Start()
    {
        if (jf::CurrentUser()) {
            if (jf::Check('workshop')) {
                $hiddenLessons = jf::LoadGeneralSetting("hiddenWorkshopLessons");

                // If request to hide the lesson
                if (isset($_POST['hide'])) {
                    if ($hiddenLessons === null) {
                        // If first request i.e settings not present
                        $hiddenLessons = array($_POST['hide']);
                    } else {
                        array_push($hiddenLessons, $_POST['hide']);
                    }

                    jf::SaveGeneralSetting("hiddenWorkshopLessons", $hiddenLessons);
                    echo json_encode(array('status' => true));
                    return true;
                }

                // If request to show the lesson
                if (isset($_POST['show'])) {

                    if ($hiddenLessons !== null) {
                        $position = array_search($_POST['show'], $hiddenLessons);
                        if ($position !== false) {
                            unset($hiddenLessons[$position]);
                        }
                    }

                    jf::SaveGeneralSetting("hiddenWorkshopLessons", $hiddenLessons);
                    echo json_encode(array('status' => true));
                    return true;
                }

                // Get the list of all the lessons/categories
                $this->allCategoryLesson = jf::LoadGeneralSetting("categoryLessons");
                $this->hiddenLessons = jf::LoadGeneralSetting("hiddenWorkshopLessons");

                // To generate 'overview' section of the dashboard
                // Store all the stats
                $obj = new \webgoat\WorkshopUsers();
                $workshopUsers = $obj->getAll();
                $this->totalUsers = count($workshopUsers);
                $this->totalCategories = count($this->allCategoryLesson);

                $lessonCount = 0;
                foreach ($this->allCategoryLesson as $category => $lessons) {
                    $lessonCount += count($lessons);
                }
                $this->totalLessons = $lessonCount;
                $this->totalVisibleLessons = $lessonCount - count($this->hiddenLessons);

                return $this->Present();

            } else {
                // User not authorized
                $this->Redirect(SiteRoot);  // Redirect to home page instead of Login Page
            }
        } else {
            // User not authenticated
            $this->Redirect(jf::url()."/user/login?return=/".jf::$BaseRequest);
        }
    }
}
