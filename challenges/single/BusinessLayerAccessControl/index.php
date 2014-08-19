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
 * Lesson Name: Bypass Business Layer Access Control
 */
class BusinessLayerAccessControl extends BaseLesson
{
    /**
     * Name of the database table
     */
    const TABLE_NAME = "lesson_BusinessLayerAccessControl_users";

    /**
     * Name of the session key
     */
    const SESSION_NAME = "lesson_BusinessLayerAccessControl_loggedIn";

    /**
     * Get title of the lesson
     *
     * @return string Returns the title
     */
    public function getTitle()
    {
        return "Business Layer Access Control";
    }

    /**
     * Get category of the lesson
     *
     * @return string Returns the lesson category
     */
    public function getCategory()
    {
        return "Access Control Flaws";   //See category.php for list of all the categories
    }

    /**
     * Starting point of the lesson
     */
    public function start()
    {
        $this->hints = array(
            'How does the application know that the user selected the delete function?',
            'Try to tamper the data'
        );

        $this->htmlContent .= file_get_contents(__DIR__."/content.html");

        // If a form is submitted, evaluate the submission
        if (isset($_POST['user']) && isset($_POST['password'])) {
            //Form of screen 1
            if ($this->validateLogin($_POST['user'], $_POST['password'])) {
            //Login Successful
                $this->saveSessionData(self::SESSION_NAME, $_POST['user']);
            } else {    //Login Failed
                $this->addErrorMessage("Login Failed");
            }

        } elseif (isset($_POST['action'])) {    //Form of screen 2
            if ($_POST['action'] == "Logout") {
                $this->logout();

            } elseif ($_POST['action'] == "View") {
                $this->showScreen3();
                return true;

            } elseif ($_POST['action'] == "Delete") {
                $loggedUser = $this->getSessionData(self::SESSION_NAME);

                if ($_POST['user'] == $loggedUser) {
                    //User trying to delete himself. Error!!
                    $this->addErrorMessage("Cannot delete itself");
                } else {

                    $loggedInUserRole = $this->getRole($loggedUser);
                    if ($loggedInUserRole == "employee") {
                        $this->setCompleted(true);
                    }

                    $this->deleteUser($_POST['user']);
                }
            }
        }

        if ($userId = $this->getSessionData(self::SESSION_NAME)) {
            $this->showScreen2($userId);
        } else {
            $this->showScreen1();
        }
        return true;
    }

    private function showScreen1()
    {
        $this->htmlContent .= '<div class="row"><div class="col-sm-offset-2 col-sm-4"><h4>Login</h4></div></div><br>
                <form class="form-horizontal" method="POST">
                    <div class="form-group">
                        <label for="user" class="col-sm-2 control-label">User:</label>
                        <div class="col-sm-4">
                            <select name="user" class="form-control" id="user">';

        $users = $this->getUsers();

        foreach ($users as $user) {
            $this->htmlContent .= "<option value='$user[id]'>$user[first_name] $user[last_name] ($user[role])</option>";
        }

        $this->htmlContent .= '</select></div></div>
            <div class="form-group">
                <label for="password" class="col-sm-2 control-label">Password:</label>
                <div class="col-sm-4">
                    <input type="password" class="form-control" name="password" id="password">
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-4">
                    <input type="submit" value="Login" name="submit" class="btn btn-default">
                </div>
            </div></form>';
    }

    private function showScreen2($id = null)
    {
        if ($id == null) {
            throw new ArgumentMissingException("User Id missing");
        }

        $role = $this->getRole($id);
        $allUsersDetail = $this->getUsers();
        $currentUserDetails = $this->getUsers($id);

        $this->htmlContent .= '<div class="row">
            <div class="col-md-4 col-md-offset-1">
                <form class="form-horizontal" method="POST">
                    <div class="form-group">
                        <h4 style="display:inline">Welcome '.$currentUserDetails["first_name"].'</h4>
                        <small>Choose an action</small>
                    </div>
                    <div class="form-group">
                        <label for="user">Select a User</label>
                        <select name="user" class="form-control" id="user">';

        if ($role == "employee") {
            $this->htmlContent .= "<option value='$id'>$currentUserDetails[first_name]
            $currentUserDetails[last_name]</option>";

            $this->htmlContent .= '</select></div>
                <div class="form-group">
                    <input type="submit" value="View" name="action" class="btn btn-default">
                    <input type="submit" value="Logout" name="action" class="btn btn-default">
                </div></form></div></div>';

        } else {

            foreach ($allUsersDetail as $user) {
                $this->htmlContent .= "<option value='$user[id]'>$user[first_name] $user[last_name]</option>";
            }

            if ($role == "manager") {
                $this->htmlContent .= '</select></div>
                <div class="form-group">
                    <input type="submit" value="View" name="action" class="btn btn-default">
                    <input type="submit" value="Logout" name="action" class="btn btn-default">
                </div></form></div></div>';

            } elseif ($role == "admin") {
                $this->htmlContent .= '</select></div>
                <div class="form-group">
                    <input type="submit" value="View" name="action" class="btn btn-default">
                    <input type="submit" value="Delete" name="action" class="btn btn-default">
                    <input type="submit" value="Logout" name="action" class="btn btn-default">
                </div></form></div></div>';
            }
        }
    }

    private function showScreen3()
    {

        $userDetails = $this->getUsers($_POST['user']);

        $this->htmlContent .= "<div class='row'>
            <div class='col-md-6 col-md-offset-1'>
                <table class='table table-striped'>
                    <tr>
                        <th>First Name</th>
                        <td>$userDetails[first_name]</td>
                    </tr>
                    <tr>
                        <th>Last Name</th>
                        <td>$userDetails[last_name]</td>
                    </tr>
                    <tr>
                        <th>Street</th>
                        <td>$userDetails[street]</td>
                    </tr>
                    <tr>
                        <th>City</th>
                        <td>$userDetails[city]</td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td>$userDetails[phone]</td>
                    </tr>
                    <tr>
                        <th>Salary</th>
                        <td>$userDetails[salary]</td>
                    </tr>
                    <tr>
                        <th>Credit Card</th>
                        <td>$userDetails[cc_no]</td>
                    </tr>
                    <tr>
                        <th>Card Limit</th>
                        <td>$userDetails[cc_limit]</td>
                    </tr>
                </table>
                <a href='?' class='btn btn-default'>Go Back</a></div></div>";
    }

    private function getRole($id)
    {
        $result = \jf::SQL("SELECT * FROM ".self::TABLE_NAME. " WHERE id = ?", $id);
        return $result[0]['role'];
    }

    private function getUsers($userId = null)
    {
        if ($userId == null) {
            return \jf::SQL("SELECT * FROM ".self::TABLE_NAME);

        } else {
            $result = \jf::SQL("SELECT * FROM ".self::TABLE_NAME. " WHERE id =? ", $userId);
            return $result[0];
        }
    }

    private function validateLogin($user, $pass)
    {
        $userResult = \jf::SQL("SELECT * FROM ".self::TABLE_NAME. " WHERE id = ? and password = ?", $user, $pass);
        return (count($userResult) > 0 ? 1: 0);
    }

    private function logout()
    {
        $this->deleteSessionData(self::SESSION_NAME);
    }

    private function deleteUser($id = null)
    {
        if ($id == null) {
            throw new ArgumentMissingException("User id missing");
        }

        \jf::SQL("DELETE FROM ".self::TABLE_NAME. " WHERE id = ? ", $id);
    }

    /**
     * Overridden method to enable secure coding
     *
     * @return array
     */
    public function isSecureCodingAllowed()
    {
        return array('status' => true, 'start' => 83, 'end' => 106);
    }

    /**
     * Reset the lesson
     */
    public function reset()
    {
        \jf::SQL("DROP TABLE IF EXISTS ". self::TABLE_NAME);
        \jf::SQL(
            "CREATE TABLE ".self::TABLE_NAME. "(id int not null auto_increment primary key,
            role varchar(30) not null, first_name varchar(50), last_name varchar(50), password varchar(50),
            street varchar(100), city varchar(50), phone varchar(20), salary int, cc_no varchar(20),
            cc_limit varchar(10))"
        );
        \jf::SQL(
            "INSERT INTO ".self::TABLE_NAME.
            "(role, first_name, last_name, password, street, city, phone, salary, cc_no, cc_limit) values
            ('employee', 'Larry', 'Stooge', 'larry', '9175 Guilford Rd',
            'New York, NY', '443-689-0192', '55000', '2578546969853547', '5000'),
            ('employee', 'Curly', 'Stooge', 'curly', '1112 Crusoe Lane',
            'New York, NY', '410-667-6654', '50000', 'NA', '0'),
            ('employee', 'Eric', 'Walker', 'eric', '1160 Prescott Rd',
            'New York, NY', '410-667-6654', '13000', 'NA', '0'),
            ('employee', 'Tom', 'Cat', 'tom', '2211 HyperThread Rd',
            'New York, NY', '443-599-0762', '80000', '5481360857968521', '30000'),
            ('manager', 'Moe', 'Stooge', 'moe', '3013 AMD Ave',
            'New York, NY', '443-938-5301', '140000', 'NA', '0'),
            ('manager', 'David', 'Giambi', 'david', '5132 DIMM Avenue',
            'New York, NY', '610-521-8413', '100000', '6981754825018101', '10000'),
            ('admin', 'John', 'Wayne', 'john', '129 Third St',
            'New York, NY', '610-213-1134', '200000', '4437334565679921', '30000')"
        );
        $this->setCompleted(false);
        $this->deleteSessionData(self::SESSION_NAME);

        return true;
    }
}
