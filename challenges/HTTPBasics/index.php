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
     * Get hints of the lesson
     *
     * @return array Returns an array containing all the hints
     */
    public function getHints()
    {
        return $this->hints;
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
     * Starting point of the lesson
     */
    public function start()
    {
        array_push(
            $this->hints,
            "Type in your name and press 'go'",
            "Turn on Show Parameters or other features",
            "Try to intercept the request with WebScarab",
            "Press the Show Lesson Plan button to view a lesson summary",
            "Press the Show Solution button to view a lesson solution"
        );

        if (!empty($_POST)) {
            $this->setCompleted(true);
            $this->addSuccessMessage();
            $this->htmlContent .= file_get_contents(__DIR__."/content.html");
            $this->htmlContent .= "<h3>Welcome $_POST[name]</h3>";
        } else {
            $this->htmlContent .= file_get_contents(__DIR__."/content.html");
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
