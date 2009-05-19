<?php
define('IN_MP',true);
require('../common.php');
require_once('../includes/template_lite/class.template.php');
$tpl = new Template_Lite;
$tpl->compile_dir = "../compiled/";
$tpl->template_dir = "../templates/";

$tpl->assign('theme',$theme);
$tpl->display("login.html");
?>