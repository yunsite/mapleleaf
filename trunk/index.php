<?php
session_start();
define('IN_MP',true);
define('APPROOT', dirname(__FILE__));
define('DEBUG_MODE', true);
require_once('./includes/preload.php');
$webapp= ZFramework::createApp();
$webapp->run();