<?php
session_start();
define('IN_MP',true);
$old_user='';
if(isset($_SESSION['admin']))
{
	$old_user=$_SESSION['admin'];
	unset($_SESSION['admin']);
	session_destroy();
}
require('../common.php');
$tpl=new Template_Lite();
$tpl->compile_dir = "../compiled/";
$tpl->template_dir = "../templates/";
$tpl->assign('old_user',$old_user);
$tpl->assign('theme',$theme);
$tpl->display('logout.html');
?>