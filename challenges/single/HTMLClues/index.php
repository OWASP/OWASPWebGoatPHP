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
 * Lesson Name: Sample Lesson
 */
class HTMLClues extends BaseLesson
{
    const USERNAME = "admin";
    const PASSWORD = "youGotIt";

    /**
     * Get title of the lesson
     *
     * @return string Returns the title
     */
    public function getTitle()
    {
        return "Discover clues in HTML";
    }

    /**
     * Get category of the lesson
     *
     * @return string Returns the lesson category
     */
    public function getCategory()
    {
        return "Code Quality";   //See category.php for list of all the categories
    }

    /**
     * Starting point of the lesson
     */
    public function start()
    {
        $this->hints = array(
            'You can view the HTML source by selecting "view source" in the browser menu',
            'There are lots of clues in the HTML',
            'Rotate 13'
        );

        $this->htmlContent .= file_get_contents(__DIR__."/content.html");

        // If a form is submitted
        // evaluate the submission
        if (isset($_POST['submit'])) {

            if ($_POST['username'] == self::USERNAME && $_POST['pass'] == self::PASSWORD) {
                $this->setCompleted(true);
            } else {
                $this->addErrorMessage("Invalid credentials");
            }
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
