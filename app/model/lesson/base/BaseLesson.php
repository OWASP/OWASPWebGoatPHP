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
 * Base class to create lessons.
 * Contains the API and the abstract methods.
 */
abstract class BaseLesson extends \JModel
{
    /**
     * @var string Contains the content of lesson in HTML format.
     */
    protected $htmlContent;

    /**
     * @var array Array containing hints of the lesson
     */
    protected $hints;

    /**
     * Constructor for LessonAdapter
     */
    public function __construct()
    {
        $this->htmlContent = "";
        $this->hints = array();
    }

    /**
     * Check if lesson is completed or not
     *
     * @return bool Returns true if lesson is completed else false
     */
    public static function isCompleted()
    {
        return \jf::LoadUserSetting("completed_".get_called_class());
    }

    /**
     * Set or unset lesson completed
     *
     * @param bool $bool
     */
    protected function setCompleted($bool = false)
    {
        \jf::SaveUserSetting("completed_".get_called_class(), $bool);
    }

    protected function saveSessionData($key = null, $value = null)
    {
        if ($key == null || $value == null) {
            throw new ArgumentMissingException('Missing key/value for saving session data');
        }

        \jf::SaveSessionSetting($key, $value);
    }

    protected function getSessionData($key = null)
    {
        if ($key == null) {
            throw new ArgumentMissingException('Missing key to get session data');
        }

        return \jf::LoadSessionSetting($key);
    }

    /**
     * Get the HTML content of the lesson
     *
     * @return string Content of lesson in HTML
     */
    public function getContent()
    {
        return $this->htmlContent;
    }

    /**
     * Function to have a uniform success message.
     * Use it when the lesson gets completed
     */
    protected function addSuccessMessage()
    {
        $this->htmlContent = "<div class='alert alert-success'>
                                    Congratulations. You have successfully completed this lesson.
                               </div>". $this->htmlContent;
    }

    /**********************************************************
     * Abstract methods which are required to be implemented  *
     * when creating a new challenge.                         *
     **********************************************************/
    abstract public function start();

    abstract public function getTitle();    //Returns the hints for a challenge

    abstract public function getCategory();   //Returns the id of the category of lesson

    abstract public function getHints();    //Returns the hints for a challenge

    abstract public function reset();
}
