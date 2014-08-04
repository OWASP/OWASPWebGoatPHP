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

class ModeContestUserSignupController extends JControl
{
    const ROLE_NAME = "contest_user";  // Role that the new user will be assigned to

    function Start()
    {
        // If validation request
        if (isset($_GET['validate'])) {
            $token = $_GET['validate'];
            $userId = jf::LoadGeneralSetting("activation_{$token}");

            if (!jf::$XUser->UserIDExists($userId)) {
                $errorString = "Invalid validation token";
            } else {
                if (jf::$XUser->IsActive($userId)) {
                    $errorString = "Your account is already activated";
                } else {
                    jf::$XUser->Activate($userId);
                    jf::DeleteGeneralSetting("activation_{$token}");
                    $this->Success = "Your account is successfully activated";
                }
            }
        }

        // If sign up request
        if (isset($_POST["Username"])) {
            $username = $_POST['Username'];
            $password = $_POST['Password'];
            $email = $_POST['Email'];

            if (!preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/', $email)) {
                $errorString = "Invalid email address.";
            } elseif (jf::$XUser->UserID($username)) {
                $errorString = "User already exists.";
            } elseif ($_POST['Confirm'] != $password) {
                $errorString = "Passwords does not match.";
            }

            if (!isset($errorString)) {
                $userId = jf::$XUser->CreateUser($username, $password, $email);
                $roleId = jf::$RBAC->Roles->TitleId(self::ROLE_NAME);
                jf::$RBAC->Users->Assign($roleId, $userId); // Assign role to the newly created user

                // Send activation email
                if ($this->activationMail($email, $userId, $username)) {
                    $this->Success = "Signup successful. Check your email for activation link.";
                } else {
                    $errorString = "Could not send confirmation email.";
                }
            }
        }

        if (isset($errorString)) {
            $this->Error = $errorString;
        }

        return $this->Present();
    }

    private function activationMail($email, $userId, $username)
    {
        $activationToken = jf::$Security->RandomToken();
        jf::SaveGeneralSetting("activation_{$activationToken}", $userId);

        $myEmail = "admin@webgoatphp.com";
        $content = "Thank you for joining ".constant("jf_Application_Title"). " {$username},
                Please open the following link in order to activate your account:
                ".CONTEST_MODE_DIR."user/signup?validate={$activationToken}

                If you did not sign up on this site, just ignore this email.";
        return mail($email, "Account Confirmation", $content, "From: ".constant("jf_Application_Name")." <{$myEmail}>");
    }
}
