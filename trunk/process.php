<?php
session_start();
define('IN_MP',true);
require_once('common.php');
// short variable
/*$user=$_POST['user'];
$content=$_POST['content'];*/

$maple=new Maple();

$maple->add_message_check();
$time=time();

$admin_name_array=array('admin','root','administrator','管理员');
$admin_tmp_name=strtolower($user);
if(!isset($_SESSION['admin']) && in_array($admin_tmp_name,$admin_name_array))
{
	$user='anonymous';
}

$input=$user.'"'.$content.'"'.$time."\n";

$maple->add_message($input);
header("Location:index.php");
?>