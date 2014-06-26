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

class ModeWorkshopUserCreateController extends JControl
{
    const SUCCESS_MESSAGE = "User successfully created";
    const USER_EXISTS_MESSAGE = "User already exists";
    const UNAUTHORIZED_MESSAGE = "You are not authorized for this action";
    const PARAMETER_MISSING_MESSAGE = "Required POST parameters are missing";
    const PERMISSION_NAME = "add_workshop_users";
    const ROLE_NAME = "workshop_user";  // Role that the new user will be assigned to

    public function Start()
    {
        if (jf::CurrentUser() && jf::Check(self::PERMISSION_NAME)) {
            if (isset($_POST['username']) && isset($_POST['password'])) {
                $username = $_POST['username'];
                $password = $_POST['password'];

                if (empty($username) || empty($password)) {
                    echo json_encode(array('status' => false, 'message' => self::PARAMETER_MISSING_MESSAGE));
                } else {

                    if (jf::$User->UserExists($username)) {
                        // If user already exists
                        echo json_encode(array('status' => false, 'message' => self::USER_EXISTS_MESSAGE));
                    } else {
                        // Everything OK. Create a new user and assign the role
                        $userId = jf::$User->CreateUser($username, $password);  // Create user
                        $roleId = jf::$RBAC->Roles->TitleId(self::ROLE_NAME);
                        jf::$RBAC->Users->Assign($roleId, $userId); // Assign role to the newly created user
                        echo json_encode(array('status' => true, 'message' => self::SUCCESS_MESSAGE, 'id' => $userId));
                    }
                }
            } else {
                // Required parameters are missing
                echo json_encode(array('status' => false, 'message' => self::PARAMETER_MISSING_MESSAGE));
            }
        } else {
            // User is not authorized
            echo json_encode(array('status' => false, 'message' => self::UNAUTHORIZED_MESSAGE));
        }
        return true;
    }
}
