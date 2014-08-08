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
 * Class for categories of lessons
 *
 * @author Shivam Dixit <shivamd001@gmail.com>
 */
class Category extends \JModel
{
    protected $categories;

    const DEFAULT_CATEGORY_ID = 1;

    /**
     * Class Constructor
     *
     * Initializes the default categories.
     * Index of array represents the ID of the category
     */
    public function __construct()
    {
        $this->categories = array('Introduction', 'General', 'Access Control Flaws',
            "AJAX Security", "Authentication Flaws", "Code Quality",
            "Cross-Site Scripting (XSS)", "Improper Error Handling", "Injection Flaws",
            "Insecure Configuration", "Insecure Storage", "Malicious Execution",
            "Parameter Tampering", "Session Management Flaws", "Web Services", "Challenge"
        );
    }

    /**
     * Get an array of all the categories
     *
     * @return array Return an array containing categories
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Get Id of a category. If category is not
     * in the list, return default category Id
     *
     * @param string $category
     *
     * @return int Return the Id of the category
     */
    public function getCategoryId($category = null)
    {
        if ($category == null) {
            return Category::DEFAULT_CATEGORY_ID;
        }

        if (($id = array_search($category, $this->categories)) !== false) {
            return $id;
        } else {
            return Category::DEFAULT_CATEGORY_ID;
        }
    }

    /**
     * Get Id of the default category
     *
     * @return int Return Id of the default category
     */
    public function getDefaultCategoryId()
    {
        return Category::DEFAULT_CATEGORY_ID;
    }

    /**
     * Add a category to the default list
     *
     * @param string $category
     *
     * @return int Return the Id of the added category
     * @throws ArgumentMissingException
     */
    public function addCategory($category = null)
    {
        if ($category == null) {
            throw new ArgumentMissingException("Title of the category is missing");
        }

        array_push($this->categories, $category);

        return max(array_keys($this->categories));
    }
}
