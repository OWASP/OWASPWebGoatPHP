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
 * Lesson Name: Numeric SQL Injection
 */
class NumericSQLInjection extends BaseLesson
{
    const TABLE_NAME ="lesson_NumericSQLInjection_weather";

    /**
     * Get title of the lesson
     *
     * @return string Returns the title
     */
    public function getTitle()
    {
        return "Numeric SQL Injection";
    }

    /**
     * Get category of the lesson
     *
     * @return string Returns the lesson category
     */
    public function getCategory()
    {
        return "Injection Flaws";   //See category.php for list of all the categories
    }

    /**
     * Starting point of the lesson
     */
    public function start()
    {
        $this->hints = array(
            'The application is taking the input and concatenating it at the end of a pre-formed SQL command',
            'Compound SQL statements can be made by joining multiple tests with
            keywords like AND and OR. Try appending a SQL statement that always resolves to true',
            'Try to intercept the post request with OWASP ZAP and replace the station with -> 101 OR 1 = 1'
        );
        $this->htmlContent .= file_get_contents(__DIR__."/content.html");
        $allStationInfo = $this->getWeatherInfo();

        $count = 0;
        foreach ($allStationInfo as $stationInfo) {
            $this->htmlContent .= "<option value='$stationInfo[station]'>$stationInfo[name]</option>";
            if ($count++ >= 3) {     //Show only partial records
                break;
            }
        }

        // Add the Go button
        $this->htmlContent .= '</select></div>
            <div class="form-group">
                <input type="submit" name="submit" value="Go" class="btn btn-default">
            </div></form></div></div><br>
            <div class="row">
                <div class="col-md-7 col-md-offset-1">';

        // If a form is submitted
        // evaluate the submission
        if (isset($_POST['submit'])) {

            // Show that query that is being executed
            $this->htmlContent .= "<div class='alert alert-warning'>
                SELECT * FROM weather_info WHERE station = $_POST[station]</div></div></div>";

            $this->htmlContent .= '
            <div class="row">
                <div class="col-md-7 col-md-offset-1">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Station</th>
                                <th>Name</th>
                                <th>State</th>
                                <th>Min Temp</th>
                                <th>Max Temp</th>
                            </tr>
                        </thead>
                        <tbody>';

            $queryResults = $this->getWeatherInfo($_POST['station']);

            foreach ($queryResults as $result) {
                $this->htmlContent .= "
                <tr>
                    <td>$result[station]</td>
                    <td>$result[name]</td>
                    <td>$result[state]</td>
                    <td>$result[min_temp]</td>
                    <td>$result[max_temp]</td>
                </tr>";
            }

            $this->htmlContent .= "</tbody></table></div></div>";   // Remaining closing tags

            if (count($queryResults) > 1) {
                // If the submission is correct
                $this->setCompleted(true);
            }

        } else {

            // Show the query that will be executed
            $this->htmlContent .= '<div class="alert alert-warning">SELECT * FROM weather_info WHERE station = ?</div>
            </div></div>';
        }
    }

    /**
     * Function to fetch station data
     *
     * @param int $id Station Id
     *
     * @return array    Result of database query
     */
    private function getWeatherInfo($id = null)
    {

        if ($id == null) {
            return \jf::SQL("SELECT * FROM ".self::TABLE_NAME);
        } else {
            return \jf::SQL("SELECT * FROM ".self::TABLE_NAME. " WHERE station = ".$id); //Notice the concatenation
        }
    }

    /**
     * Reset the lesson
     */
    public function reset()
    {
        \jf::SQL("DROP TABLE IF EXISTS ". self::TABLE_NAME);
        \jf::SQL(
            "CREATE TABLE ".self::TABLE_NAME. "(station int auto_increment primary key,
            name varchar(30) not null, state varchar(5) not null, min_temp int not null, max_temp int not null)"
        );
        \jf::SQL(
            "INSERT INTO ".self::TABLE_NAME."(name, state, min_temp, max_temp) values ('Columbia', 'MD', -10, 20),
            ('Seattle', 'WA', -15, 18), ('New York', 'NY', -10, 20), ('Houston', 'TX', 20, 30),
            ('Camp David', 'MD', -5, 20), ('Ice Station', 'NA', -30, 0)"
        );

        $this->setCompleted(false);
        return true;
    }
}
