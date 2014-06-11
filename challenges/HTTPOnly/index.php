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
 * Main logic of the lesson
 *
 * Lesson Name: HTTPOnly Test
 */
class HTTPOnly extends BaseLesson
{
    /**
     * Get title of the lesson
     *
     * @return string Returns the title
     */
    public function getTitle()
    {
        return "HTTPOnly Test";
    }

    /**
     * Get category of the lesson
     *
     * @return string Returns the lesson category
     */
    public function getCategory()
    {
        return "Cross-Site Scripting (XSS)";   //See category.php for list of all the categories
    }

    /**
     * Starting point of the lesson
     */
    public function start()
    {
        $this->hints = array(
            'Read the directions and try out the buttons.'
        );

        setcookie("unique2u", "SESSION-123456678", null, null, null, null, false);

        $this->htmlContent .= file_get_contents(__DIR__."/content.html");

        if (isset($_POST['httpOnly'])) {

            if ($_POST['httpOnly'] == 'true') {

                $inputText = 'id="httpOnly-true"';
                $replaceText = 'id="httpOnly-true" checked';
                $this->htmlContent = str_replace($inputText, $replaceText, $this->htmlContent);

                $inputText = 'id="httpOnly-false" checked>';
                $replaceText = 'id="httpOnly-false" >';
                $this->htmlContent = str_replace($inputText, $replaceText, $this->htmlContent);

                setcookie("unique2u", "SESSION-123456678", null, null, null, null, true);
            }
        }

        // If the submission is correct
        if (isset($_POST['success'])) {
             $this->setCompleted(true);
        }
    }

    /**
     * Reset the lesson
     */
    public function reset()
    {
        $this->setCompleted(false);
        return true;
    }
}
