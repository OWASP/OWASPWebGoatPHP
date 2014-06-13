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
 * Lesson Name: Weak Authentication Cookie
 */
class WeakAuthenticationCookie extends BaseLesson
{
    const AUTH_COOKIE = "AuthCookie";
    const USERNAME1 = "webgoat";
    const USERNAME2 = "owasp";
    const USERNAME3 = "alice";
    const SALT = "12345";

    /**
     * Get title of the lesson
     *
     * @return string Returns the title
     */
    public function getTitle()
    {
        return "Weak Authentication Cookie";
    }

    /**
     * Get category of the lesson
     *
     * @return string Returns the lesson category
     */
    public function getCategory()
    {
        return "Session Management Flaws";   //See category.php for list of all the categories
    }

    /**
     * Starting point of the lesson
     */
    public function start()
    {
        $this->hints = array(
            'The server authenticates the user using a cookie, if you send the right cookie.',
            'Is the AuthCookie value guessable knowing the username and password?'
        );

        $this->htmlContent .= file_get_contents(__DIR__."/content.html");

        // If a form is submitted
        if (isset($_POST['submit'])) {
            if ($_POST['username'] == self::USERNAME1 && $_POST['password'] == self::USERNAME1) {
                setcookie(self::AUTH_COOKIE, $this->encode(self::USERNAME1.self::SALT));
                $this->showWelcomeMessage($_POST['username']);
            } elseif ($_POST['username'] == self::USERNAME2 && $_POST['password'] == self::USERNAME2) {
                setcookie(self::AUTH_COOKIE, $this->encode(self::USERNAME2.self::SALT));
                $this->showWelcomeMessage($_POST['username']);
            } else {
                $this->addErrorMessage("Invalid login credentials.");
            }
        }

        // If logout is clicked
        if (isset($_POST['logout'])) {
            setcookie(self::AUTH_COOKIE, "", time() - 3600);
            unset($_COOKIE[self::AUTH_COOKIE]);
        }

        // If cookie already exists, verify it
        if (isset($_COOKIE[self::AUTH_COOKIE])) {

            if ($_COOKIE[self::AUTH_COOKIE] == $this->encode(self::USERNAME1.self::SALT)) {
                $this->showWelcomeMessage(self::USERNAME1);
            } elseif ($_COOKIE[self::AUTH_COOKIE] == $this->encode(self::USERNAME2.self::SALT)) {
                $this->showWelcomeMessage(self::USERNAME2);
            } elseif ($_COOKIE[self::AUTH_COOKIE] == $this->encode(self::USERNAME3.self::SALT)) {
                // If the cookie is correctly guessed
                $this->showWelcomeMessage(self::USERNAME3);
                $this->setCompleted(true);
            }
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
    private function showWelcomeMessage($name)
    {
        $this->removeHTMLForm();
        $this->htmlContent .= "<h4>Welcome $name! You have been logged in.</h4><br>
        <form method='POST'><input type='submit' name='logout' value='Logout' class='btn btn-default'></form>";
    }

    /**
     * Function to encode a cookie by incrementing
     * each character by one
     *
     * @param string $string String to be encoded
     *
     * @return string Encoded string
     */
    private function encode($string)
    {
        for ($i = 0; $i < strlen($string); $i++) {
            $string[$i] = chr(ord($string[$i]) + 1);
        }

        return strrev($string);
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
