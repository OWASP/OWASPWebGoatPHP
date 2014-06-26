<?php

class ModeWorkshopAdmin extends JControl
{
    public function Start()
    {
        if (jf::CurrentUser() && jf::Check('workshop')) {
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
            return $this->Present();
        } else {
            $this->Redirect(jf::url()."/user/login?return=/".jf::$BaseRequest);
        }
    }
}
