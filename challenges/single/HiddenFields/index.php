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
 * Lesson Name: Exploiting Hidden Fields
 */
class HiddenFields extends BaseLesson
{
    /**
     * Get title of the lesson
     *
     * @return string Returns the title
     */
    public function getTitle()
    {
        return "Exploiting Hidden Fields";
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
            'This application is using hidden fields to transmit price information to the server',
            'Use a program to intercept and change the value in the hidden field'
        );

        $this->htmlContent .= file_get_contents(__DIR__."/content.html");

        if (isset($_POST['submit'])) {

            $amountCharged = $_POST['quantity'] * $_POST['price'];
            $this->htmlContent .= "<h4>The total amount charged from your Credit Card: $amountCharged</h4>";

            if ($_POST['price'] < 3000) {
                $this->setCompleted(true);
            }
        }
    }

    /**
     * Overridden method to enable secure coding
     *
     * @return array
     */
    public function isSecureCodingAllowed()
    {
        return array('status' => true, 'start' => 62, 'end' => 71);
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
