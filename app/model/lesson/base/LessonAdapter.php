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
abstract class LessonAdapter
{
    /**
     * @var string Contains the content of lesson in HTML format.
     */
    protected $htmlContent;

    /**
     * Constructor for LessonAdapter
     */
    public function __construct()
    {
        //Do nothing
    }

    /**********************************************************
     * Abstract methods which are required to be implemented  *
     * when creating a new challenge.                         *
     **********************************************************/

    abstract public function init();    //It will be called at the start. Use it for initial setup

    abstract public function getTitle();    //Returns the title of lesson

    abstract public function getCategoryId();   //Returns the id of the category of lesson

    abstract public function createContent();   //Generate HTML using API

    abstract public function evaluateSubmission();  //Evaluate user submission and generate content to display

    abstract public function getHints();    //Returns the hints for a challenge

    abstract public function destruct();    //For deleting the db etc


    /**
     * Check if lesson is completed or not
     *
     * @return bool Returns true if lesson is completed else false
     */
    public static function isCompleted()
    {
        return \jf::LoadSessionSetting(__CLASS__);
    }

    /**
     * Set or unset lesson completed
     *
     * @param bool $bool
     */
    protected function setCompleted($bool = false)
    {
        \jf::SaveSessionSetting(__CLASS__, $bool);
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
     * Resets the lesson by first destructing
     * and then initializing
     */
    public function resetLesson()
    {
        $this->destruct();

        $this->init();
    }

    /***************************************
     * API to create content of the Lesson *
     ***************************************/

    /**
     * Adds the opening <div> to $htmlContent
     *
     * @param string $class Class for the <div>
     */
    protected function addDivOpen($class = null)
    {
        if ($class != null) {
            $this->htmlContent .= "<div class='$class'>";
        } else {
            $this->htmlContent .= "<div>";
        }
    }

    /**
     * Adds the closing <div> to $htmlContent
     */
    protected function addDivClose()
    {
        $this->htmlContent .= "</div>";
    }

    /**
     * Adds the <p> tag to $htmlContent
     *
     * @param string $text Content of the p tag
     * @param string $class Class for the p tag
     *
     * @throws ArgumentMissingException If the arguments are missing
     */
    protected function addParagraph($text = null, $class = null)
    {
        if ($text == null) {
            throw new ArgumentMissingException("Text of the paragraph tag is missing");
        }

        if ($class != null) {
            $this->htmlContent .= "<p class='$class'>$text</p>";
        } else {
            $this->htmlContent .= "<p>$text</p>";
        }

    }

    /**
     * Adds the <h> tag to the $htmlContent
     *
     * @param string $text Text of the <h> tag
     * @param int $number Specifies h1, h2, h3 etc. Ex: $number = 4 for <h4> tag
     * @param string $class Class of the header tag
     *
     * @throws InvalidHeaderException If the $number specified is invalid header tag number
     * @throws ArgumentMissingException If the arguments are missing
     */
    protected function addHeader($text = null, $number = null, $class = null)
    {
        if ($text == null) {
            throw new ArgumentMissingException("Text of the header tag is missing");
        }

        if ($number == null || $number < 1 || $number > 7) {
            throw new InvalidHeaderException("Invalid value of number {'$number'} for header tag");
        }

        if ($class != null) {
            $this->htmlContent .= "<h$number class='$class'>$text</h$number>";
        } else {
            $this->htmlContent .= "<h$number>$text</h$number>";
        }
    }

    /**
     * Adds a line break to $htmlContent
     */
    protected function addLineBreak()
    {
        $this->htmlContent .= "<br>";
    }

    /**
     * Adds raw HTML to $htmlContent
     *
     * @param string $html A valid string containing HTML
     *
     * @throws ArgumentMissingException If the argument is missing
     */
    protected function addRaw($html = null)
    {
        if ($html == null) {
            throw new ArgumentMissingException("HTML content missing for adding raw html.");
        }

        $this->htmlContent .= $html;
    }

    /**
     * Adds the success message.
     * Use it when the lesson gets completed
     */
    protected function addSuccessMessage()
    {
        $this->htmlContent .= "<div class='alert alert-success'>
                                    Congratulations. You have successfully completed this lesson.
                               </div>";
    }
}
