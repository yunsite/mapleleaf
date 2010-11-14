<?php
session_start();
define('IN_MP',true);
require_once('./includes/preload.php');
$maple=new Maple_Controller();
$maple->run();