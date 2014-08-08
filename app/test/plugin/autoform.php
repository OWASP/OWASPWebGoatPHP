<?php
class AutoformPluginTest extends JTest
{
    function __construct()
    {

    }
    function testStart()
    {
        $this->assertTrue(true);
    }
    function test1()
    {
        $p=new AutoformPlugin();
        $p->AddElement(array(
            "type"=>"text",
            "style"=>"color:red;border:3px double;",
            "label"=>"Firstname",
            "name"=>"Firstname",
            "default"=>"hasan",
            "unit"=>"",
            "dependency"=>"",
            "validation"=>"/[a-zA-Z۰-۹ا-ی آ-ی0-9]{0,20}/",
            "options"=>array(),
        ));
    }

}