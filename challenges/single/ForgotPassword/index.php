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
 * Lesson Name: Forgot Password
 */
class ForgotPassword extends BaseLesson
{

    const USERNAME = "admin";
    const FAV_COLOR = "green";
    const PASSWORD = "!sj@LHU88&2G";

    /**
     * Get title of the lesson
     *
     * @return string Returns the title
     */
    public function getTitle()
    {
        return "Forgot Password";
    }

    /**
     * Get category of the lesson
     *
     * @return string Returns the lesson category
     */
    public function getCategory()
    {
        return "Authentication Flaws";   //See category.php for list of all the categories
    }

    /**
     * Starting point of the lesson
     */
    public function start()
    {
        $this->hints = array(
            'There is no lock out policy in place, brute force your way!',
            'Try using user names you might encounter throughout WebGoatPHP',
            'There are only so many possible colors, can you guess one?'
        );

        $this->htmlContent .= file_get_contents(__DIR__."/content.html");

        // If a form is submitted
        // evaluate the submission
        if (isset($_POST['submit'])) {

            if ($_POST['username'] == self::USERNAME && $_POST['color'] == self::FAV_COLOR) {
                // If the submission is correct
                $this->setCompleted(true);

                //Remove the form
                $this->htmlContent = substr($this->htmlContent, 0, strpos($this->htmlContent, "<!--BeginForm-->"));

                //Show the password
                $message = "<br><h4>Dear $_POST[username], your password is ".self::PASSWORD." </h4>";
                $this->htmlContent .= $message;
            } else {
                //Invalid combination
                $this->addErrorMessage("Invalid Request.");
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
