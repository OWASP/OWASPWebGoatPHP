<?php

/**
 * Tests for init controller
 *
 * @author Shivam Dixit <shivamd001@gmail.com>
 */
class InitTest extends JTest
{
    /**
     * Test to check if user successfully created
     */
    public function testUserExists()
    {
        $this->assertTrue(jf::$User->UserExists('guest'));
    }

    /*
     * Test to check permissions of a role
     */
    public function testRoleHasPermissions()
    {
        $roleId = jf::$RBAC->Roles->TitleID('workshop_admin');

        $permissionId1 = jf::$RBAC->Permissions->TitleID('add_workshop_users');
        $permissionId2 = jf::$RBAC->Permissions->TitleID('view_single_chal');
        $permissionId3 = jf::$RBAC->Permissions->TitleID('view_contest_chal');
        $permissionId4 = jf::$RBAC->Permissions->TitleID('delete_contest_users');

        $this->assertTrue(jf::$RBAC->Roles->HasPermission($roleId, $permissionId1));
        $this->assertFalse(jf::$RBAC->Roles->HasPermission($roleId, $permissionId2));
        $this->assertFalse(jf::$RBAC->Roles->HasPermission($roleId, $permissionId3));
        $this->assertFalse(jf::$RBAC->Roles->HasPermission($roleId, $permissionId4));
    }

    /**
     * Test to check roles of users
     */
    public function testUserRole()
    {
        /**
         * Store id of the user
         */
        $userId = jf::$User->UserID('guest');

        /**
         * Store id of the role
         */
        $roleId = jf::$RBAC->Roles->TitleID('single_user');
        $this->assertTrue(jf::$RBAC->Users->HasRole($roleId, $userId));
    }

    /**
     * Test to check permissions of users
     */
    public function testUserPermissions()
    {
        /**
         * Store id of the user
         */
        $userId = jf::$User->UserID('guest');

        $this->assertTrue(jf::Check('view_single_chal', $userId));
        $this->assertFalse(jf::Check('view_workshop_chal', $userId));
        $this->assertFalse(jf::Check('view_contest_chal', $userId));
        $this->assertFalse(jf::Check('edit_contest_chal', $userId));
        $this->assertFalse(jf::Check('add_workshop_users', $userId));
    }
}
