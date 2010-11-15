<?php
session_start();
define('IN_MP',true);
require_once('./includes/preload.php');
$webapp=  FrontController::getInstance();
$webapp->run();