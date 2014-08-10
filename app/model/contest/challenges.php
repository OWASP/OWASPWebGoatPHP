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
 * Class ContestChallenges
 * To store and retrieve contest challenges from the database
 *
 * @package webgoat
 */
class ContestChallenges extends \JModel
{
    const TABLE_NAME = "contest_challenges";

    /**
     * Inserts the record into the database
     * Array must be of the form:
     * array('col_1' => val_1, 'col_2' => val_2, ...)
     *
     * @param $array Array key-value pairs to insert
     *
     * @return int Returns the insert ID (auto increment)
     * @throws InvalidArgumentException If required parameter is missing
     */
    public static function add($array = null)
    {
        if ($array === null || (count($array) <= 0)) {
            throw new InvalidArgumentException("Required parameter missing");
        }

        $keys = array_keys($array);
        $values = array_values($array);
        $query = "INSERT INTO ".self::TABLE_NAME. " (".implode(',', $keys).") VALUES (".
            str_repeat('?, ', (count($array)-1))."?) ";

        return call_user_func_array("\\jf::SQL", array($query, $values));
    }

    /**
     * Fetch challenge details from the database by challenge ID
     *
     * @param int $id ID to search for
     *
     * @return array Result of the query
     * @throws InvalidArgumentException If required parameter is missing
     */
    public static function getByID($id = null)
    {
        if ($id === null) {
            throw new InvalidArgumentException("Required parameter missing");
        }

        return \jf::SQL("SELECT * FROM ".self::TABLE_NAME." WHERE ID = ?", $id);
    }

    /**
     * Fetch details of a challenge by a given name
     *
     * @param string $challengeName Name of the challenge
     *
     * @return mixed Array containing details of challenge, null
     * if challenge is not present
     * @throws InvalidArgumentException
     */
    public static function getByName($challengeName = null)
    {
        if ($challengeName === null) {
            throw new InvalidArgumentException("Required parameter missing");
        }

        return \jf::SQL("SELECT * FROM ".self::TABLE_NAME." WHERE ChallengeName = ?", $challengeName);
    }

    /**
     * Fetch challenges of a given contest from the database
     *
     * @param int $contestID ID of the contest
     *
     * @return mixed Array of challenges if present, else null
     * @throws InvalidArgumentException If required params are missing
     */
    public static function getByContestID($contestID = null)
    {
        if ($contestID === null) {
            // If contest ID is not provided use the active contest ID
            if (($contestID = ContestDetails::getActiveID()) === null) {
                throw new InvalidArgumentException("Required parameter missing");
            }
        }

        return \jf::SQL("SELECT * FROM ".self::TABLE_NAME." WHERE ContestID = ?", $contestID);
    }

    /**
     * Delete all the challenges of a given contest ID
     */
    public static function deleteByContestID($contestID = null)
    {
        if ($contestID === null) {
            throw new InvalidArgumentException("Required parameter missing");
        }

        \jf::SQL("DELETE FROM ".self::TABLE_NAME. " WHERE ContestID = ?", $contestID);
    }

    /**
     * Increments the count of TotalAttempts by 1
     *
     * @throws InvalidArgumentException If required parameters are missing
     */
    public static function incrementTotalAttempts($challengeName = null)
    {
        if ($challengeName === null) {
            throw new InvalidArgumentException("Required parameter missing");
        }

        \jf::SQL(
            "UPDATE ".self::TABLE_NAME." SET TotalAttempts = TotalAttempts + 1 WHERE ChallengeName = ?",
            $challengeName
        );
    }

    /**
     * Increments the CompletedCount by 1
     *
     * @throws InvalidArgumentException If required parameters are missing
     */
    public static function incrementCompletedCount($challengeName = null)
    {
        if ($challengeName === null) {
            throw new InvalidArgumentException("Required parameter missing");
        }

        \jf::SQL(
            "UPDATE ".self::TABLE_NAME." SET CompletedCount = CompletedCount + 1 WHERE ChallengeName = ?",
            $challengeName
        );
    }
}
