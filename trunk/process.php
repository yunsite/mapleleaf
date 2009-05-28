<?php
define('IN_MP',true);
require_once('common.php');
$maple=new Maple();
//session_start();
$new_message=$maple->add_message_check();
//echo $new_message;exit;
//$input=$user.'"'.$content.'"'.$time."\n";
$maple->add_message($new_message);
header("Location:index.php");
?>