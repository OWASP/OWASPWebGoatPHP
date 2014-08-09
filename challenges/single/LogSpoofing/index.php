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
 * Lesson Name: Log Spoofing
 */
class LogSpoofing extends BaseLesson
{
    /**
     * Get title of the lesson
     *
     * @return string Returns the title
     */
    public function getTitle()
    {
        return "Log Spoofing";
    }

    /**
     * Get category of the lesson
     *
     * @return string Returns the lesson category
     */
    public function getCategory()
    {
        return "Injection Flaws";   //See category.php for list of all the categories
    }

    /**
     * Starting point of the lesson
     */
    public function start()
    {
        $this->hints = array(
            'Use CR (%0d) and LF (%0a) for a new line',
            'Try: Smith%0d%0aLogin Succeeded for username: admin'
        );

        $this->htmlContent .= file_get_contents(__DIR__."/content.html");

        // If a form is submitted
        // evaluate the submission
        if (isset($_POST['submit'])) {

            $userName = urldecode($_POST['username']);

            if (strpos($userName, "\n") !== false) {   // If the submission is correct
                $this->setCompleted(true);
                $userName = str_replace("\n", "<br>", $userName);   //To display a new line in HTML
            }
            $this->htmlContent .= " $userName";
        }

        $this->htmlContent .= "</div></div></div>";     //End the div tags
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
