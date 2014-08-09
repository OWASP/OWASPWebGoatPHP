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
 * Lesson Name: Encoding Basics
 */
class EncodingBasics extends BaseLesson
{
    const DEFAULT_STRING = "DEFAULT";

    /**
     * Get title of the lesson
     *
     * @return string Returns the title
     */
    public function getTitle()
    {
        return "Encoding Basics";
    }

    /**
     * Get category of the lesson
     *
     * @return string Returns the lesson category
     */
    public function getCategory()
    {
        return "Insecure Storage";   //See category.php for list of all the categories
    }

    /**
     * Starting point of the lesson
     */
    public function start()
    {
        $this->hints = array(
            'Enter a string and press "Submit"',
            'Enter "abc" and notice the rot13 encoding is "nop" (increase each letter by 13 characters)'
        );

        $this->htmlContent .= file_get_contents(__DIR__."/content.html");

        if (isset($_POST['string'])) {
            $this->addTable($_POST['string']);
            $this->setCompleted(true);

        } else {
            $this->addTable(self::DEFAULT_STRING);
        }
    }

    /**
     * Function to add Base64 in the table
     *
     * @param string $text String to be converted
     */
    private function addBase64($text)
    {
        $textInBase64Enc = base64_encode($text);
        $textInBase64Dec = base64_decode($text);

        $this->htmlContent .= "<tr>
            <td>Base64 encoding is a simple reversable encoding used to encode bytes into ASCII characters.
                Useful for making bytes into a printable string, but provides no security.</td>
            <td>$textInBase64Enc</td>
            <td>$textInBase64Dec</td>
        </tr>";
    }

    /**
     * Function to add Entity Encoding in the table
     *
     * @param string $text String to be converted
     */
    private function addEntityEnc($text)
    {
        $textEntityEnc = htmlentities($text);
        $textEntityDec = html_entity_decode($text);

        $this->htmlContent .= "<tr>
            <td>Entity encoding uses special sequences like &amp; for special characters.
             This prevents these characters from being interpreted by most interpreters.</td>
            <td>$textEntityEnc</td>
            <td>$textEntityDec</td>
        </tr>";
    }

    /**
     * Function to add MD5 in the table
     *
     * @param string $text String to be converted
     */
    private function addMD5($text)
    {
        $textMD5 = md5($text);

        $this->htmlContent .= "<tr>
            <td>MD5 hash is a checksum that can be used to validate a string or byte array,
              but cannot be reversed to find the original string or bytes.
              For obscure cryptographic reasons, it is better to use SHA-512 if you have a choice.</td>
            <td>$textMD5</td>
            <td>Cannot reverse a hash</td>
        </tr>";
    }

    /**
     * Function to add SHA256 in the table
     *
     * @param string $text String to be converted
     */
    private function addSHA256($text)
    {
        $textSHA256 = hash('sha256', $text);

        $this->htmlContent .= "<tr>
            <td>SHA-256 hash is a checksum that can be used to validate a string or byte array,
             but cannot be reversed to find the original string or bytes.</td>
            <td>$textSHA256</td>
            <td>Cannot reverse a hash</td>
        </tr>";
    }

    /**
     * Function to add URL encoding in the table
     *
     * @param string $text String to be converted
     */
    private function addURLEncode($text)
    {
        $textURLEnc = urlencode($text);
        $textURLDec = urldecode($text);

        $this->htmlContent .= "<tr>
            <td>URL encoding converts characters into a format that can be transmitted over the Internet.
                It replaces unsafe ASCII characters with a '%' followed by two hexadecimal digits.</td>
            <td>$textURLEnc</td>
            <td>$textURLDec</td>
        </tr>";
    }

    /**
     * Function to add ROT13 in the table
     *
     * @param string $text String to be converted
     */
    private function addROT13($text)
    {
        $textROT13 = str_rot13($text);

        $this->htmlContent .= "<tr>
            <td>Rot13 encoding is a way to make text unreadable, but is easily reversed and provides no security.</td>
            <td>$textROT13</td>
            <td>N/A</td>
        </tr>";
    }

    /**
     * Function to add the main content
     *
     * @param string $string String that is to be converted using various
     * schemes.
     */
    private function addTable($string)
    {
        $this->htmlContent .= '<div class="row">
            <div class="col-sm-offset-1 col-sm-9">
                <table class="table table-condensed table-striped" style="table-layout: fixed; word-wrap: break-word;">
                    <thead>
                        <tr>
                            <th class="col-sm-6">Description</th>
                            <th class="col-sm-4">Encoded</th>
                            <th class="col-sm-2">Decoded</th>
                        </tr>
                    </thead>
                    <tbody>';
        $this->addBase64($string);
        $this->addEntityEnc($string);
        $this->addMD5($string);
        $this->addSHA256($string);
        $this->addURLEncode($string);
        $this->addROT13($string);

        $this->htmlContent .= "</tbody></table></div></div>";   //Add the closing tags
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
