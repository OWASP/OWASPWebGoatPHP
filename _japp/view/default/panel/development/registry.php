<style>
* {
font-size:small;
}
</style>
<?php
$Op=reg();
echo nl2br(str_replace(" ","&nbsp;",htmlspecialchars(str_replace("=> stdClass Object","",print_r($Op,true)))));
?>
