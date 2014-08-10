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
 * Class ContestDetails
 * To store and retrieve contest details from the database
 *
 * @package webgoat
 */
class ContestDetails extends \JModel
{
    const TABLE_NAME = "contest_details";

    /**
     * Inserts the record into the database
     * Array must be of the form:
     * array('col_1' => val_1, 'col_2' => val_2, ...)
     *
     * A new contest can be added only when the previous contest
     * has finished.
     *
     * @param $array Array key-value pairs to insert
     *
     * @return int Returns the insert ID (auto increment)
     * @throws InvalidArgumentException If required parameter is missing,
     * @throws ActiveContestPresentException If an active contest is present
     */
    public static function add($array = null)
    {
        if ($array === null || (count($array) <= 0)) {
            throw new InvalidArgumentException("Required parameter missing");
        }

        if (self::isActivePresent()) {
            // Only one contest can be active at a time and since
            // active contest is already present hence throw an exception
            throw new ActiveContestPresentException("Active contest is already present");
        }

        $keys = array_keys($array);
        $values = array_values($array);
        $query = "INSERT INTO ".self::TABLE_NAME. " (".implode(',', $keys).") VALUES (".
            str_repeat('?, ', (count($array)-1))."?) ";

        return call_user_func_array("\\jf::SQL", array($query, $values));
    }

    /**
     * Fetch contest details from the database
     *
     * @param int $id ID to search for
     *
     * @return array Result of the query
     * @throws \Exception Required parameter missing
     */
    public static function getByID($id = null)
    {
        if ($id === null) {
            throw new \InvalidArgumentException("Required parameter missing");
        }

        return \jf::SQL("SELECT * FROM ".self::TABLE_NAME." WHERE ID = ?", $id);
    }

    /**
     * Checks if an active contest is present.
     * Each contest has two states:
     * active -> When the contest has not finished
     * inactive -> When the contest has finished
     *
     * For simplicity only one contest can be active at a time.
     * This scheme is followed so that details of past contest
     * are also available.
     *
     * @return bool True if active contest is present else false
     */
    public static function isActivePresent()
    {
        $currentTime = time();
        $query = "SELECT * FROM ".self::TABLE_NAME." WHERE EndTimestamp > $currentTime";

        $result = \jf::SQL($query);

        if (count($result) == 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the details of the active contest
     *
     * @return mixed Array containing details of active contest
     * or null if no contest is active
     */
    public static function getActive()
    {
        $currentTime = time();
        $query = "SELECT * FROM ".self::TABLE_NAME." WHERE EndTimestamp > $currentTime";

        return \jf::SQL($query);
    }

    /**
     * Get the ID of the active contest
     *
     * @return int ID of the active contest, null if no
     * contest is active
     */
    public static function getActiveID()
    {
        $currentTime = time();
        $query = "SELECT * FROM ".self::TABLE_NAME." WHERE EndTimestamp > $currentTime";

        $result = \jf::SQL($query);
        return $result[0]['ID'];
    }
}
