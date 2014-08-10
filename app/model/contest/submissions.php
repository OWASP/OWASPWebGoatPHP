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
 * Class ContestSubmissions
 * To store and retrieve data of contest submissions
 * from the database
 *
 * @package webgoat
 */
class ContestSubmissions extends \JModel
{
    const TABLE_NAME = "contest_submissions";

    /**
     * Inserts the record into the database
     * Array must be of the form:
     * array('col_1' => val_1, 'col_2' => val_2, ...)
     *
     * @param $array Array key-value pairs to insert
     *
     * @return int Returns the insert ID (auto increment)
     * @throws \Exception Required parameter missing
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
     * Fetch submission details from the database
     *
     * @param int $id ID to search for
     *
     * @return array Result of the query
     * @throws \Exception Required parameter missing
     */
    public static function getByID($id = null)
    {
        if ($id === null) {
            throw new InvalidArgumentException("Required parameter missing");
        }

        return \jf::SQL("SELECT * FROM ".self::TABLE_NAME." WHERE ID = ?", $id);
    }

    /**
     * Function to check if the submission is correct
     *
     * @param $challengeID int Unique ID of challenge
     * @param $flag String User entered flag
     *
     * @return bool true if submission is correct else false
     * @throws InvalidArgumentException
     */
    public static function evaluate($challengeID, $flag)
    {
        if ($flag === null || $challengeID === null) {
            throw new InvalidArgumentException("Required parameters missing");
        }

        $challenge = ContestChallenges::getByID($challengeID);
        if ($challenge[0]['CorrectFlag'] == md5($flag)) {
            return true;
        } else {
            return false;
        }
    }
}
