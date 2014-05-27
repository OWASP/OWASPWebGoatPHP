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
 * Lesson Name : HTTP Basics
 */
class HTTPBasics extends BaseLesson
{
    public function init()
    {
        //Do nothing
        return true;
    }

    /**
     * Get title of the lesson
     *
     * @return string Returns the title
     */
    public function getTitle()
    {
        return "HTTP Basics";
    }

    /**
     * Evaluates the user's submission
     */
    public function evaluateSubmission()
    {
        $this->setCompleted(true);
        $this->addSuccessMessage();

        $this->createContent();
        $this->addLineBreak();
        $this->addParagraph("Hello $_POST[name] !!");
    }

    /**
     * Get category Id of the lesson
     * Category Ids are defined in the Category class
     *
     * @return int Returns the lesson category
     */
    public function getCategoryId()
    {
        $category = new Category();
        return $category->getCategoryId("General");
    }

    /**
     * Get hints of the lesson
     *
     * @return array Returns an array containing all the hints
     */
    public function getHints()
    {
        $hints = array();
        array_push($hints, "Type in your name and press 'go'");
        array_push($hints, "Turn on Show Parameters or other features");
        array_push($hints, "Try to intercept the request with WebScarab");
        array_push($hints, "Press the Show Lesson Plan button to view a lesson summary");
        array_push($hints, "Press the Show Solution button to view a lesson solution");

        return $hints;
    }

    /**
     * Create the content of the lesson
     */
    public function createContent()
    {
        $paragraph1 = "Enter your name in the input field below and press 'go' to submit. The server will accept the
        request, reverse the input, and display it back to the user, illustrating the basics of handling an HTTP
        request";

        $paragraph2 = "The user should become familiar with the features of WebGoat by manipulating the above buttons to
        view hints, show the HTTP request parameters, the HTTP request cookies, and the PHP source code. You may also
        try using WebScarab for the first time";

        $this->addParagraph($paragraph1);
        $this->addParagraph($paragraph2);

        $form = '<form class="form-inline" method="POST">
                    <div class="form-group">
                        <label for="name">Enter Your Name :</label>
                        <input type="text" name="name" class="form-control" id="name" placeholder="Name">
                    </div>
                    <button type="submit" class="btn btn-default">Submit</button>
                 </form>';
        $this->addLineBreak();
        $this->addRaw($form);
    }

    public function destruct()
    {
        $this->setCompleted(false);
        return true;
    }
}
