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
 * class ContestChallengeScanner
 * Scans the contest challenges directory
 *
 * @package webgoat
 */
class ContestChallengeScanner extends \JModel
{
    /**
     * Fetch all the contest challenges name
     *
     * @return array Sorted array containing unique name of
     * all the challenges
     */
    public static function run()
    {
        $result = array();

        foreach (new \DirectoryIterator(CONTEST_CHALLENGE_PATH) as $file) {
            if ($file->isDot()) {
                continue;
            } elseif ($file->isDir()) {
                array_push($result, $file->getFilename());
            }
        }
        sort($result);
        return $result;
    }
}
