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
 * Lesson Name: HTML Field Restrictions
 */
class HTMLFieldRestrictions extends BaseLesson
{

    /**
     * Get title of the lesson
     *
     * @return string Returns the title
     */
    public function getTitle()
    {
        return "Bypass HTML Field Restrictions";
    }

    /**
     * Get category of the lesson
     *
     * @return string Returns the lesson category
     */
    public function getCategory()
    {
        return "Parameter Tampering";
    }


    /**
     * Starting point of the lesson
     */
    public function start()
    {
        $this->hints = array(
            'Use OWASP ZAP',
            'Modify the values of all the parameters in request'
        );

        $this->htmlContent .= file_get_contents(__DIR__."/content.html");

        if (isset($_POST['submit'])) {

            if ($_POST['submit'] != 'Submit') {
                if (isset($_POST['disabled-input'])) {
                    if (strlen($_POST['input']) > 5) {
                        if ($_POST['checkbox'] != "on" && $_POST['checkbox'] != "off") {
                            if ($_POST['radio'] != "foo" && $_POST['radio'] != "bar") {
                                if ($_POST['select'] != "foo" && $_POST['select'] != "bar") {
                                    $this->setCompleted(true);
                                }
                            }
                        }
                    }
                }
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
