<?php

namespace webgoat;

/**
 * Tests for the Category class
 *
 * @author Shivam Dixit <shivamd001@gmail.com>
 */
class CategoryTest extends \JTest
{
    private $category;

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->category = new Category();
    }

    /**
     * Test to check the id of a category
     */
    public function testGetCategoryId()
    {
        $allCategories = $this->category->getCategories();

        $this->assertEquals($this->category->getCategoryId("General"), array_search("General", $allCategories));
    }

    /**
     * Test to check the id of default category
     */
    public function testGetDefaultCategoryId()
    {
        $this->assertEquals($this->category->getDefaultCategoryId(), Category::DEFAULT_CATEGORY_ID);
    }

    /**
     * Test to check if category is successfully added
     */
    public function testAddCategory()
    {
        $id = $this->category->addCategory("Test Category");

        $this->assertEquals($this->category->getCategoryId("Test Category"), $id);
    }
}
