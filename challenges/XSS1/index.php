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
 * Lesson Name: XSS
 */
class XSS1 extends BaseLesson
{
    /**
     * Get title of the lesson
     *
     * @return string Returns the title
     */
    public function getTitle()
    {
        return "XSS 1 (Reflected)";
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
            'Use script tags in input',
            'Can you get the script to disclose the JSESSIONID cookie?',
            'Can you get the script to access the credit card form field?'
        );

        $this->htmlContent .= file_get_contents(__DIR__."/content.html");

        // If a form is submitted
        // evaluate the submission
        if (isset($_POST['submit'])) {

            $pin = $_POST['pin'];

            if (!is_numeric($pin) || (strlen($pin) != 3)) {
                $message = "Sorry, the pin '$pin' is Invalid";
                $this->htmlContent .= "<div class='text-center alert alert-danger'>$message</div>";
            } else {
                $this->htmlContent .= "<div class='text-center alert alert-success'>Successfully purchased!!</div>";
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
