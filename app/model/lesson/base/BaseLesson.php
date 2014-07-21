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
     * Get the HTML content of the lesson
     *
     * @return string Content of lesson in HTML
     */
    public function getContent()
    {
        return $this->htmlContent;
    }

    /**
     * Get hints of the lesson
     *
     * @return array Returns an array containing all the hints
     */
    public function getHints()
    {
        return $this->hints;
    }

    /**
     * Function to enable/disable securing coding mode
     * for a lesson. To enable secure coding mode override
     * this method to:
     *
     * return array('status' => true, 'start' => Line_No, 'end' => Line_No);
     *
     * Lines between 'start' and 'end' will be displayed
     * to the user to modify.
     *
     * By default secure coding mode will be disabled.
     *
     * @return array Contains the status of secure coding mode
     */
    public function isSecureCodingAllowed()
    {
        // FIXME: Start and end line number will change if code is changed.
        return array('status' => false);
    }

    /**
     * Set or unset lesson completed
     *
     * @param bool $bool
     */
    protected function setCompleted($bool = false)
    {
        \jf::SaveUserSetting("completed_".get_called_class(), $bool);
        $this->addSuccessMessage();
    }

    /**
     * Store some data in the session
     *
     * @param string|int $key   The key associated with the value
     * @param mixed $value  The actual value to be stored
     *
     * @throws ArgumentMissingException If $key or $value is missing
     */
    protected function saveSessionData($key = null, $value = null)
    {
        if ($key == null || $value == null) {
            throw new ArgumentMissingException('Missing key/value for saving session data');
        }

        \jf::SaveSessionSetting($key, $value);
    }

    /**
     * Get the stored session data
     *
     * @param string|int $key The key associated with the value
     *
     * @return mixed    The stored data
     * @throws ArgumentMissingException If $key is missing
     */
    protected function getSessionData($key = null)
    {
        if ($key == null) {
            throw new ArgumentMissingException('Missing key to get session data');
        }

        return \jf::LoadSessionSetting($key);
    }

    /**
     * Function to remove the stored session data
     *
     * @param string|int $key The key associated with the value
     *
     * @throws ArgumentMissingException If $key is missing
     */
    protected function deleteSessionData($key = null)
    {
        if ($key == null) {
            throw new ArgumentMissingException('Missing key to get session data');
        }

        \jf::DeleteSessionSetting($key);
    }

    /**
     * Function to add a uniform success message.
     */
    protected function addSuccessMessage()
    {
        $this->htmlContent = "<div class='alert alert-success'>
                                    Congratulations. You have successfully completed this lesson.
                               </div>". $this->htmlContent;
    }

    /**
     * To display a uniform error message at
     * the top of the lesson.
     *
     * @param string $error Error Message to be displayed
     */
    protected function addErrorMessage($error = null)
    {
        $this->htmlContent = "<div class='alert alert-danger'>
                                    Error !! $error
                               </div>". $this->htmlContent;
    }

    /**********************************************************
     * Abstract methods which are required to be implemented  *
     * when creating a new challenge.                         *
     **********************************************************/
    abstract public function start();

    abstract public function getTitle();    //Returns the hints for a challenge

    abstract public function getCategory();   //Returns the id of the category of lesson

    abstract public function reset();
}
