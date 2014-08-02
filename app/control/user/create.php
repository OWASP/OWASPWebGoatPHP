<?php

class UserCreateController extends JControl
{
    public function __construct()
    {
        parent::__construct();
    }

    public function Start()
    {
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $Username = $_POST['username'];
            $Password = $_POST['password'];

            if (jf::$User->UserExists($Username)) {
                $this->Error = 1;
            } else {
                $result = jf::$User->CreateUser($Username, $Password);
                $this->Result = true;
                echo $result; //returns the userid of the user
            }
        }

        return $this->Present();
    }
}
