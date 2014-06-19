<?php

class ModeWorkshopUserDeleteController extends JControl
{
    const SUCCESS_MESSAGE = "User successfully deleted";
    const USER_NOT_EXISTS_MESSAGE = "User does not exists";
    const UNAUTHORIZED_MESSAGE = "You are not authorized for this action";
    const PARAMETER_MISSING_MESSAGE = "Required POST parameter is missing";
    const PERMISSION_NAME = "delete_workshop_users";

    public function Start()
    {
        // Check if the user is logged in and
        // have the required permissions
        if (jf::CurrentUser() && jf::Check(self::PERMISSION_NAME)) {
            // Check if POST parameter present
            if (isset($_POST['username'])) {
                $username = $_POST['username'];

                if (jf::$User->UserExists($username)) {
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
