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
 * Lesson Name: Session Fixation
 */
class SessionFixation extends BaseLesson
{
    const SESSION_LEVEL = "lesson_SessionFixation_level";   // To store current level in session
    const SESSION_STAGE1 = "lesson_SessionFixation_email";  // To store email contents in session
    const SESSION_STAGE2 = "lesson_SessionFixation_SID";    // To store value of SID in session
    const USERNAME = "Jane";
    const PASSWORD = "tarzan";

    /**
     * Get title of the lesson
     *
     * @return string Returns the title
     */
    public function getTitle()
    {
        return "Session Fixation";
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
            'Stage 1: Where is the link in the mail?',
            'Stage 1: Add a SID to the link',
            'Stage 1: A SID could look something like this: SID=Whatever',
            'Stage 2: Click on the link!',
            'Stage 3: Log in as Jane with user name jane and password tarzan',
            'Stage 4: Click on the link provided',
            'Stage 4: What is your actual SID?',
            'Stage 4: Change the SID to the chosen one in the mail'
        );

        // If a form is submitted
        if (isset($_POST['stage1'])) {  // Stage 1 submission
            $validLink = "?SID=";
            if (strpos($_POST['content'], $validLink)) {
                // If SID is present in the URL
                $this->saveSessionData(self::SESSION_LEVEL, 2);  // Stage 2
                $this->saveSessionData(self::SESSION_STAGE1, $_POST['content']); //Save the email contents
            } else {
                $this->addErrorMessage("Please try again!");
            }
        } elseif ($this->getSessionData(self::SESSION_LEVEL) == 2 && isset($_GET['SID'])) {
            // Correct submission of Stage 2
            $this->saveSessionData(self::SESSION_LEVEL, 3);
            $this->saveSessionData(self::SESSION_STAGE2, $_GET['SID']);
        } elseif ($this->getSessionData(self::SESSION_LEVEL) == 3 && isset($_POST['stage3'])) {
            // Submission of Stage 3
            if ($_POST['username'] == self::USERNAME && $_POST['password'] == self::PASSWORD) {
                // Valid Login
                $this->saveSessionData(self::SESSION_LEVEL, 4);
            } else {
                $this->addErrorMessage("Invalid Login credentials");
            }
        } elseif ($this->getSessionData(self::SESSION_LEVEL) == 4 && isset($_GET['SID'])) {
                $this->saveSessionData(self::SESSION_LEVEL, 5);
        } elseif ($this->getSessionData(self::SESSION_LEVEL) == 5 && isset($_GET['SID'])) {
            if ($_GET['SID'] == $this->getSessionData(self::SESSION_STAGE2)) {
                $this->setCompleted(true);
                $this->saveSessionData(self::SESSION_LEVEL, 6);
            }
        }

        if (!($level = $this->getSessionData(self::SESSION_LEVEL))) {
            $this->showStage1();
        } elseif ($level == 2) {
            $this->showStage2($this->getSessionData(self::SESSION_STAGE1));
        } elseif ($level == 3) {
            $this->showStage3();
        } elseif ($level == 4) {
            $this->showStage4();
        } elseif ($level == 5) {
            $this->showStage5();
        } elseif ($level == 6) {
            $this->showStage6();
        }
    }

    /**
     * Function to replace the url in the email
     * with the current URL
     */
    private function replaceURLInContent()
    {
        $anchor = "<a href='?'";
        $anchorWithURL = "<a href='//$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]'";

        $this->htmlContent = str_replace($anchor, $anchorWithURL, $this->htmlContent);
    }

    /**
     * Function to show stage 1
     */
    private function showStage1()
    {
        $this->htmlContent .= file_get_contents(__DIR__."/stage1.html");
        $this->replaceURLInContent();
    }

    /**
     * Function to show stage 2
     *
     * @param string $emailContent Contents of the email
     */
    private function showStage2($emailContent)
    {
        $this->htmlContent .= file_get_contents(__DIR__."/stage2.html");
        $this->htmlContent .= $emailContent;
    }

    /**
     * Function to show stage 3
     */
    private function showStage3()
    {
        $this->htmlContent .= file_get_contents(__DIR__."/stage3.html");
    }

    /**
     * Function to show stage 4
     */
    private function showStage4()
    {
        $this->htmlContent .= file_get_contents(__DIR__."/stage4.html");
    }

    /**
     * Function to show stage 5
     */
    private function showStage5()
    {
        $this->htmlContent .= file_get_contents(__DIR__."/stage5.html");
    }

    /**
     * Function to show stage 6
     */
    private function showStage6()
    {
        $this->htmlContent .= file_get_contents(__DIR__."/final.html");
    }

    /**
     * Reset the lesson
     */
    public function reset()
    {
        $this->deleteSessionData(self::SESSION_LEVEL);
        $this->deleteSessionData(self::SESSION_STAGE1);
        $this->deleteSessionData(self::SESSION_STAGE2);

        $this->setCompleted(false);
        return true;
    }
}
