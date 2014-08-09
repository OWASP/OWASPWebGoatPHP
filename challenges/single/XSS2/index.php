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
 * Lesson Name: XSS 2
 */
class XSS2 extends BaseLesson
{

    const TABLE_NAME = "lesson_XSS2_messages";

    /**
     * Get title of the lesson
     *
     * @return string Returns the title
     */
    public function getTitle()
    {
        return "XSS 2 (Stored)";
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
            'Stored XSS',
            'Enter a javascript in message',
        );

        if (isset($_POST['submit'])) {
            $this->addMessage($_POST['message']);
        }

        if (isset($_POST['success'])) {     // If the submission is correct
            $this->setCompleted(true);
            $this->deleteMessages();    // Delete the messages so that it doesn't keep looping
        }

        $this->htmlContent .= file_get_contents(__DIR__."/content.html");

        $allMessages = $this->getMessages();
        if ($allMessages) {     //If there are some messages
            foreach ($allMessages as $message) {
                $this->htmlContent .= "<li>$message[message]</li>";
            }
        }

        $this->htmlContent .= "</ol></div></div>";  //Closing the remaining tags

        if (!$allMessages) {
            $this->htmlContent .= "<p>No message exists</p>";
        }
    }

    /**
     * Function to delete the messages
     */
    public function deleteMessages()
    {
        \jf::SQL("DELETE FROM ".self::TABLE_NAME);
    }

    /**
     * Reset the lesson
     */
    public function reset()
    {
        \jf::SQL("DROP TABLE IF EXISTS ".self::TABLE_NAME);
        \jf::SQL("CREATE TABLE ".self::TABLE_NAME. "(id int auto_increment primary key, message text)");

        $this->setCompleted(false);
        return true;
    }

    private function addMessage($message)
    {
        \jf::SQL("INSERT INTO ".self::TABLE_NAME."(message) VALUES (?)", $message);
    }

    private function getMessages()
    {
        return \jf::SQL("SELECT * FROM ".self::TABLE_NAME);
    }
}
