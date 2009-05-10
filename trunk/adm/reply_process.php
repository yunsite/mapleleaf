<?php
session_start();
define('IN_MP',true);
require_once('../common.php');
if(!isset($_SESSION['admin']))//若通过验证，则执行删除
{
	header("location:index.php");
	exit;
}
$mid=0;
$mid=(int)$_POST['mid'];
$reply_content=htmlspecialchars(str_replace("\n",' ',trim($_POST['reply_content'])),ENT_COMPAT,'UTF-8');
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
header("Location:admin2.php?subtab=message");
?>

