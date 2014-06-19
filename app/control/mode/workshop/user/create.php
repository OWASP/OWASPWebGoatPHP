<?php

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
