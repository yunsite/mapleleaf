<?php
define('IN_MP',true);
require_once('common.php');
$maple=new Maple();
$new_message=$maple->add_message_check();
$maple->add_message($new_message);
header("Location:index.php");
?>