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
 * Lesson Name: Forced Browsing
 */
class ForcedBrowsing extends BaseLesson
{
    /**
     * Get title of the lesson
     *
     * @return string Returns the title
     */
    public function getTitle()
    {
        return "Forced Browsing";
    }

    /**
     * Get category of the lesson
     *
     * @return string Returns the lesson category
     */
    public function getCategory()
    {
        return "Insecure Configuration";   //See category.php for list of all the categories
    }

    /**
     * Starting point of the lesson
     */
    public function start()
    {
        $this->hints = array(
            'Try to guess the URL for the config page',
            'The config page is guessable and hackable',
            'Play with the URL'
        );

        $this->htmlContent .= file_get_contents(__DIR__."/content.html");

        if (preg_match("#/conf$#", $_REQUEST['__r'])) {
            $this->setCompleted(true);
            $this->showConfig();
        }
    }

    /**
     * Function to remove the HTML content
     */
    private function removeHTMLContent()
    {
        $this->htmlContent = substr($this->htmlContent, 0, strpos($this->htmlContent, "<!--BeginContent"));
    }

    /**
     * Function to show the config page
     */
    private function showConfig()
    {
        $this->removeHTMLContent();
        $this->htmlContent .= "<h4>Welcome to the configuration page. You can reset the admin password</h4>";
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
