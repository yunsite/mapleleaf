<?php
session_start();
define('IN_MP',true);
require_once('../common.php');
if(!isset($_SESSION['admin']))
{
	header("location:index.php");
	exit;
}
$mid=0;
$mid=(int)$_POST['mid'];
//$reply_content=htmlspecialchars(str_replace("\n",' ',trim($_POST['reply_content'])),ENT_COMPAT,'UTF-8');
$reply_content = htmlspecialchars(trim($_POST['reply_content']));
$reply_content = nl2br($reply_content);
$reply_content = str_replace(array("\n", "\r\n", "\r"), '', $reply_content);
if($reply_content=='')
{
	showerror("您回复为空？！<a href='".$_SERVER['HTTP_REFERER']."'>返回</a>中...",true,$_SERVER['HTTP_REFERER']);
	exit;
}
if($mid < 0)
{
	showerror("非法操作！<a href='".$_SERVER['HTTP_REFERER']."'>返回</a>中...",true,$_SERVER['HTTP_REFERER']);
	exit;
}
$time=time();
$input=$mid.'"'.$reply_content.'"'.$time."\n";
$file_name='../data/reply.txt';
writeover($file_name,$input,'ab');
header("Location:admin.php?subtab=message");
?>

