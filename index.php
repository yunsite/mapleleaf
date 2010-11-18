<?php
session_start();
define('IN_MP',true);
define('DEBUG_MODE', true);
require_once('./includes/preload.php');
$webapp=  FrontController::getInstance();
$webapp->run();