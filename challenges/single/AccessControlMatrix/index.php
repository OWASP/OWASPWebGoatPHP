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
 * Lesson Name: Access Control Matrix
 */
class AccessControlMatrix extends BaseLesson
{
    const TABLE_NAME = "lesson_AccessControlMatrix_users";

    /**
     * Get title of the lesson
     *
     * @return string Returns the title
     */
    public function getTitle()
    {
        return "Using Access Control Matrix";
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
            'Many sites attempt to restrict access to resources by role',
            'Developers frequently make mistakes implementing this scheme',
            'Attempt combinations of users, roles, and resources'
        );

        $this->htmlContent .= file_get_contents(__DIR__."/content.html");

        if (isset($_POST['user'])) {
            $userId = $this->getUserId($_POST['user']);
            $resource = $_POST['resource'];
            $userRole = \jf::$RBAC->Users->AllRoles($userId);
            $userRoleTitle = $userRole[0]['Title'];

            $string = "<h4>User $_POST[user] [".
                $userRoleTitle."] requested Resource $resource : ";

            if (\jf::Check($resource, $userId)) {

                $string .= "<span class='text-success'>Access Granted</span></h4>";
                if ($resource == "account_manager" && $userId != ($this->getUserId("Mark"))) {
                    $this->setCompleted(true);
                }

            } else {
                $string .= "<span class='text-danger'>Access Denied</span></h4>";
            }

            $this->htmlContent .= $string;
        }
    }

    /**
     * Overridden method to enable secure coding
     *
     * @return array
     */
    public function isSecureCodingAllowed()
    {
        return array('status' => true, 'start' => 118, 'end' => 139);
    }

    /**
     * Reset the lesson
     */
    public function reset()
    {
        \jf::SQL("DROP TABLE IF EXISTS ".self::TABLE_NAME);
        \jf::SQL("CREATE TABLE ".self::TABLE_NAME." (user_id int not null, name varchar(30) unique )");
        \jf::SQL("INSERT INTO ".self::TABLE_NAME." values(100, 'John'), (101, 'Shivam'), (102, 'Larry'), (103, 'Mark')");

        $roleId = \jf::$RBAC->Roles->TitleId('root_lesson_AccessControlMatrix');
        if ($roleId) {
            $permId = \jf::$RBAC->Permissions->TitleId('root_lesson_AccessControlMatrix');
            \jf::$RBAC->Roles->UnassignPermissions($roleId);
            \jf::$RBAC->Roles->Remove($roleId, true);
            \jf::$RBAC->Permissions->Remove($permId, true);
        }

        $permId0 = \jf::$RBAC->Permissions->Add('root_lesson_AccessControlMatrix', 'Root lesson AccessControlMatrix');
        $permId1 = \jf::$RBAC->Permissions->Add('public_share', 'Public Share lesson AccessControlMatrix', $permId0);
        $permId2 = \jf::$RBAC->Permissions->Add('time_card_entry', 'Time Card Entry lesson AccessControlMatrix', $permId0);
        $permId3 = \jf::$RBAC->Permissions->Add('performance_review', 'Performance Review lesson AccessControlMatrix'. $permId0);
        $permId4 = \jf::$RBAC->Permissions->Add('site_manager', 'Site Manager lesson AccessControlMatrix', $permId0);
        $permId5 = \jf::$RBAC->Permissions->Add('account_manager', 'Account Manager lesson AccessControlMatrix', $permId0);


        $roleId0 = \jf::$RBAC->Roles->Add('root_lesson_AccessControlMatrix', 'Root lesson AccessControlMatrix');
        $roleId1 = \jf::$RBAC->Roles->Add('public', 'Public lesson AccessControlMatrix', $roleId0);
        $roleId2 = \jf::$RBAC->Roles->Add('user', 'User lesson AccessControlMatrix', $roleId0);
        $roleId3 = \jf::$RBAC->Roles->Add('manager', 'Manager lesson AccessControlMatrix', $roleId0);
        $roleId4 = \jf::$RBAC->Roles->Add('admin', 'Administrator lesson AccessControlMatrix', $roleId0);

        \jf::$RBAC->Permissions->Assign($roleId0, $permId0);

        \jf::$RBAC->Permissions->Assign($roleId1, $permId1);
        \jf::$RBAC->Permissions->Assign($roleId2, $permId2);
        \jf::$RBAC->Permissions->Assign($roleId3, $permId3);
        \jf::$RBAC->Permissions->Assign($roleId3, $permId4);
        \jf::$RBAC->Permissions->Assign($roleId4, $permId5);
        \jf::$RBAC->Permissions->Assign($roleId2, $permId5);

        \jf::$RBAC->Users->Assign($roleId1, 100);
        \jf::$RBAC->Users->Assign($roleId2, 101);
        \jf::$RBAC->Users->Assign($roleId3, 102);
        \jf::$RBAC->Users->Assign($roleId4, 103);

        $this->setCompleted(false);
        return true;
    }

    private function getUserId($name = null)
    {
        $user = \jf::SQL("SELECT * FROM ".self::TABLE_NAME." where name = ?", $name);
        if (array_key_exists(0, $user)) {
            return $user[0]['user_id'];
        } else {
            return -1;
        }
    }
}
