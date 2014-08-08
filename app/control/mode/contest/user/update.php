<?php

/**
 * Class ModeContestUserUpdate
 *
 * To be used with AJAX calls
 */
class ModeContestUserUpdate extends JControl
{
    public function Start()
    {
        if (jf::CurrentUser()) {
            $userName = jf::$XUser->Username();

            $oldPass = $_POST['old_password'];
            $newPass = $_POST['new_password'];
            $cnfNewPass = $_POST['cnew_password'];

            if ($newPass != $cnfNewPass) {
                echo json_encode(array('status' => false, 'error' => 'Password and Confirm Password do not match'));
            } elseif (!jf::Login($userName, $oldPass)) {
                echo json_encode(array('status' => false, 'error' => 'Old Password is incorrect'));
            } else {
                jf::$User->EditUser($userName, $userName, $newPass);
                echo json_encode(array('status' => true, 'message' => 'Password successfully updated'));
            }
        } else {
            echo json_encode(array('status' => false, 'error' => 'You are not authorized for this action'));
        }
        return true;
    }
}
