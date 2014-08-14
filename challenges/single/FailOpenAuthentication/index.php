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
 * Lesson Name: Fail Open Authentication Scheme
 */
class FailOpenAuthentication extends BaseLesson
{
    const USERNAME = "webgoat";
    const PASSWORD = "webgoat";
    const SESSION_NAME = "login";

    /**
     * Get title of the lesson
     *
     * @return string Returns the title
     */
    public function getTitle()
    {
        return "Fail Open Authentication";
    }

    /**
     * Get category of the lesson
     *
     * @return string Returns the lesson category
     */
    public function getCategory()
    {
        return "Improper Error Handling";   //See category.php for list of all the categories
    }

    /**
     * Starting point of the lesson
     */
    public function start()
    {
        $this->hints = array(
            'You can force errors during the authentication process.',
            'You can change length, existence, or values of authentication parameters.',
            'Try removing a parameter ENTIRELY'
        );

        $this->htmlContent .= file_get_contents(__DIR__."/content.html");

        // If a form is submitted
        // evaluate the submission
        if (isset($_POST['submit'])) {

            if (!isset($_POST['username'])) {
                $this->addErrorMessage("Invalid username or password");
            } else {

                $this->saveSessionData(self::SESSION_NAME, $_POST['username']);

                try {
                    if ($_POST['username'] == self::USERNAME && $_POST['password'] == self::PASSWORD) {
                        // Login successful using valid credentials
                        // Do nothing
                    } else {
                        // Login not successful. Delete the session, show the message
                        $this->deleteSessionData(self::SESSION_NAME);
                        $this->addErrorMessage("Invalid username or password");
                    }
                } catch (\Exception $e) {
                    // Because of the exception session
                    // is not deleted and Login in successful
                    $this->setCompleted(true);
                }
            }
        }

        if (isset($_POST['logout'])) {
            $this->deleteSessionData(self::SESSION_NAME);
        }

        if ($user = $this->getSessionData(self::SESSION_NAME)) {
            // If user is logged in show welcome message
            $this->showWelcomeMessage($user);
        }
    }

    /**
     * Function to remove the HTML form from
     * the variable $htmlContent
     */
    private function removeHTMLForm()
    {
        $this->htmlContent = substr($this->htmlContent, 0, strpos($this->htmlContent, "<!--LoginForm"));
    }

    /**
     * Function to show welcome message if the
     * user is logged in
     */
    private function showWelcomeMessage($username)
    {
        $this->removeHTMLForm();
        $this->htmlContent .= "<h4>Welcome $username.</h4><br>
        <form method='POST'><input type='submit' name='logout' value='Logout' class='btn btn-default'></form>";
    }

    /**
     * Overridden method to enable secure coding
     *
     * @return array
     */
    public function isSecureCodingAllowed()
    {
        return array('status' => true, 'start' => 71, 'end' => 92);
    }

    /**
     * Reset the lesson
     */
    public function reset()
    {
        $this->deleteSessionData(self::SESSION_NAME);
        $this->setCompleted(false);
        return true;
    }
}
