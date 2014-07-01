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
 * Lesson Name: Path Based Access Control
 */
class PathBasedAccessControl extends BaseLesson
{
    /**
     * Get title of the lesson
     *
     * @return string Returns the title
     */
    public function getTitle()
    {
        return "Path Based Access Control";
    }

    /**
     * Get category of the lesson
     *
     * @return string Returns the lesson category
     */
    public function getCategory()
    {
        return "Access Control Flaws";
    }


    /**
     * Starting point of the lesson
     */
    public function start()
    {
        $this->hints = array(
            'Try to get access to a file which is outside allowed directory',
            'Try to tamper POST data'
        );

        $allowedDir = __DIR__."/files/";
        $filesInDir = array_diff(scandir($allowedDir), array('..', '.'));

        $this->htmlContent .= file_get_contents(__DIR__."/content.html");
        $this->htmlContent .= "<h4>Current Directory : </h4>
                               <p>$allowedDir</p><br>";

        $this->htmlContent .= '<form class="form" method="POST">
                                   <div class="form-group">
                                       <label for="file">View a file :</label>
                                       <div class="row">
                                           <div class="col-sm-4">
                                               <select name="file" id="file" class="form-control">';

        foreach ($filesInDir as $file) {
            $this->htmlContent .= "<option value='$file'>$file</option>";
        }

        $this->htmlContent .= "</select></div></div></div>
        <div class='form-group'><input type='submit' value='Submit' class='btn btn-default'></div></form>
        <br><h4>Contents of File:</h4>";

        if (isset($_POST['file'])) {

            $fileName = $_POST['file'];
            $filePath = $allowedDir.$fileName;

            if (file_exists($filePath)) {
                // Add pre tags so that file is displayed in actual format
                $this->htmlContent .= "$fileName<pre>";
                $this->htmlContent .= file_get_contents($filePath);
                $this->htmlContent .= "</pre>";

                if (strpos($fileName, "/") != false) {
                    $this->setCompleted(true);
                }
            } else {
                $this->htmlContent .= "<div class='alert alert-warning'>File does not exists</div>";
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
