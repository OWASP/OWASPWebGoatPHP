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

class ModeWorkshopUserDeleteController extends JControl
{
    const SUCCESS_MESSAGE = "User successfully deleted";
    const USER_NOT_EXISTS_MESSAGE = "User does not exists";
    const UNAUTHORIZED_MESSAGE = "You are not authorized for this action";
    const PARAMETER_MISSING_MESSAGE = "Required POST parameter is missing";
    const PERMISSION_NAME = "delete_workshop_users";
    const ROLE_NAME = "workshop_user";

    public function Start()
    {
        // Check if the user is logged in and
        // have the required permissions
        if (jf::CurrentUser() && jf::Check(self::PERMISSION_NAME)) {
            // Check if POST parameter present
            if (isset($_POST['username'])) {
                $username = $_POST['username'];
                if (jf::$User->UserExists($username)) {

                    // First remove the user role association
                    $userId = jf::$User->UserID($username);
                    $roleId = jf::$RBAC->Roles->TitleId(self::ROLE_NAME);
                    jf::$RBAC->Users->Unassign($roleId, $userId);
                    // Delete the user
                    jf::$User->DeleteUser($username);
                    echo json_encode(array('status' => true, 'message' => self::SUCCESS_MESSAGE));

                } else {
                    // User does not exists. Error!
                    echo json_encode(array('status' => false, 'message' => self::USER_NOT_EXISTS_MESSAGE));
                }

            } else {
                echo json_encode(array('status' => false, 'message' => self::PARAMETER_MISSING_MESSAGE));
            }

        } else {
            echo json_encode(array('status' => false, 'message' => self::UNAUTHORIZED_MESSAGE));
        }

        return true;
    }
}
