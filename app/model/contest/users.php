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

use \jf;

class ContestUsers extends jf\UserManager
{
    const ROLE_NAME = "contest_user";

    /**
     * Function to get all contest users
     *
     * @return mixed Array of all the contest users
     */
    public static function getAll()
    {
        $roleId = jf::$RBAC->Roles->TitleID(self::ROLE_NAME);
        return jf::SQL(
            "SELECT Username, ID from jf_users inner join jf_rbac_userroles on ID = UserID where RoleID = ?",
            $roleId
        );
    }
}
