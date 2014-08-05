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

/**
 * Controller to create permissions, roles and their linking.
 * This controller must be run only once.
 *
 * @author Shivam Dixit <shivamd001@gmail.com>
 */
class InitController extends JControl
{
    public function Start()
    {
        $RBAC = jf::$RBAC;
        //Create a guest user
        $userId = jf::$User->CreateUser('guest', 'guest');
        $userId2 = jf::$User->CreateUser('workshop.admin', 'Admin!234');
        $userId3 = jf::$User->CreateUser('contest.admin', 'Admin!234');

        //Create RBAC permissions
        $permissionId1 = $RBAC->Permissions->Add('root', 'Root permissions');

        $permissionId2 = $RBAC->Permissions->Add('single', 'Single User Mode', $permissionId1);
        $permissionId3 = $RBAC->Permissions->Add('single_chal', 'Single User Mode Challenges', $permissionId2);
        $permissionId4 = $RBAC->Permissions->Add('view_single_chal', 'View Single Mode Challenges', $permissionId3);

        $permissionId5 = $RBAC->Permissions->Add('workshop', 'Workshop Mode', $permissionId1);
        $permissionId6 = $RBAC->Permissions->Add('workshop_chal', 'Workshop mode challenges', $permissionId5);
        $permissionId7 = $RBAC->Permissions->Add('view_workshop_chal', 'View Workshop mode challenges', $permissionId6);
        $permissionId8 = $RBAC->Permissions->Add('edit_workshop_chal', 'Edit Workshop mode challenges', $permissionId6);

        $permissionId8 = $RBAC->Permissions->Add('workshop_users', 'Workshop mode users', $permissionId5);
        $permissionId9 = $RBAC->Permissions->Add('add_workshop_users', 'Add Workshop mode users', $permissionId8);
        $permissionId10 = $RBAC->Permissions->Add('edit_workshop_users', 'Edit Workshop mode users', $permissionId8);
        $permissionId11 = $RBAC->Permissions->Add('delete_workshop_users', 'Del Workshop mode users', $permissionId8);

        $permissionId12 = $RBAC->Permissions->Add('contest', 'Contest Mode', $permissionId1);
        $permissionId13 = $RBAC->Permissions->Add('contest_chal', 'Contest mode challenges', $permissionId12);
        $permissionId14 = $RBAC->Permissions->Add('view_contest_chal', 'View Contest mode challenges', $permissionId13);
        $permissionId15 = $RBAC->Permissions->Add('edit_contest_chal', 'Edit Contest mode challenges', $permissionId13);

        $permissionId16 = $RBAC->Permissions->Add('contest_users', 'Contest mode users', $permissionId12);
        $permissionId17 = $RBAC->Permissions->Add('add_contest_users', 'Add Contest mode users', $permissionId16);
        $permissionId18 = $RBAC->Permissions->Add('edit_contest_users', 'Edit Contest mode users', $permissionId16);
        $permissionId19 = $RBAC->Permissions->Add('delete_contest_users', 'Del Contest mode users', $permissionId16);

        //Create RBAC roles
        $roleId1 = $RBAC->Roles->Add('root', 'Root of the application');

        $roleId2 = $RBAC->Roles->Add('user', 'Users of the application', $roleId1);
        $roleId3 = $RBAC->Roles->Add('single_user', 'User of the single user mode', $roleId2);
        $roleId4 = $RBAC->Roles->Add('workshop_user', 'User of the workshop mode', $roleId2);
        $roleId5 = $RBAC->Roles->Add('contest_user', 'User of the contest mode', $roleId2);

        $roleId6 = $RBAC->Roles->Add('admin', 'Admins of the application');
        $roleId7 = $RBAC->Roles->Add('workshop_admin', 'Admin of the workshop mode', $roleId6);
        $roleId8 = $RBAC->Roles->Add('contest_admin', 'Admin of the contest mode', $roleId6);

        //Create Permission-Roles association
        $RBAC->Permissions->Assign($roleId1, $permissionId1);   //Root has all the permissions

        $RBAC->Permissions->Assign($roleId3, $permissionId4);
        $RBAC->Permissions->Assign($roleId4, $permissionId7);
        $RBAC->Permissions->Assign($roleId5, $permissionId14);

        $RBAC->Permissions->Assign($roleId7, $permissionId5);
        $RBAC->Permissions->Assign($roleId8, $permissionId12);

        //Create Roles-Users association
        $RBAC->Users->Assign($roleId3, $userId);
        $RBAC->Users->Assign($roleId7, $userId2);
        $RBAC->Users->Assign($roleId8, $userId3);

        return $this->Present();
    }
}
