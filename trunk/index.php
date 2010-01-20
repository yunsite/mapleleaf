<?php
session_start();
define('IN_MP',true);
require_once('./preload.php');
$maple=new MP_CONTROLLER();
$maple->run();
?>
