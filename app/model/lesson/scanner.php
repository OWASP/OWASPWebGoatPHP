<?php

/**
 * Copyright (c) 2014 Shivam Dixit <shivamd001@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 2,
 * or (at your option) any later version, as published by the Free
 * Software Foundation
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details
 *
 * You should have received a copy of the GNU General Public
 * License along with this program; if not, write to the
 * Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

namespace webgoat;

/**
 * Scans the challenges directory
 * for all the challenges and loads
 * then into the application settings
 * for later use
 *
 * @package webgoat
 */
class LessonScanner extends \JModel
{
    /**
     * Function to scan all the sub directories in the
     * challenges directory and store them in application
     * settings
     */
    public static function run()
    {
        $categoryObj = new Category();
        $categories = $categoryObj->getCategories();
        $categoryLessons = array(); //Contains all categories and corresponding lessons

        foreach ($categories as $category) {
            $categoryLessons[$category] = array();
        }

        $subDirectories = glob(LESSON_PATH.'*', GLOB_ONLYDIR);

        foreach ($subDirectories as $lessonDir) {

            $className = "\\webgoat\\".basename($lessonDir);
            if (!class_exists($className)) {
                throw new ClassNotFoundException("No class {$className} exists. Please run loadClasses() first.");
            }

            $obj = new $className();
            $classNameWithoutNamespace = basename($lessonDir);

            //array key contains categories, value contains lessons belonging to that category
            array_push($categoryLessons[$obj->getCategory()], array($classNameWithoutNamespace, $obj));
        }

        \jf::SaveGeneralSetting('categoryLessons', $categoryLessons);
    }

    /**
     * Returns the object of the lesson from
     * the application settings.
     *
     * @param string $lessonName Name of the lesson to be searched for
     *
     * @return Object Lesson object
     * @throws ArgumentMissingException If $lessonName is missing
     * @throws LessonNotFoundException If the lesson is not found
     * @throws GeneralSettingsMissingException  If there is are no application
     *          settings present
     */
    public static function getLessonObject($lessonName = null)
    {
        if ($lessonName == null) {
            throw new ArgumentMissingException("Please select a lesson");
        }

        if (!\jf::LoadGeneralSetting('categoryLessons')) {
            throw new GeneralSettingsMissingException("No settings found for 'categoryLessons'");
        }

        foreach (\jf::LoadGeneralSetting('categoryLessons') as $lessons) {
            foreach ($lessons as $lesson) {
                if ($lesson[0] == $lessonName) {
                    return $lesson[1];
                }
            }
        }

        throw new LessonNotFoundException("Lesson '$lessonName' not found");
    }

    /**
     * Loads all the classes in the challenges directory
     * so that their object can be used directly.
     */
    public static function loadClasses()
    {
        $subDirectories = glob(LESSON_PATH.'*', GLOB_ONLYDIR);
        foreach ($subDirectories as $lessonDir) {
            require_once($lessonDir."/index.php");
        }
    }
}
