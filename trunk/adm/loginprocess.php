<?php
define('IN_MP',true);
include "../common.php";
session_start();
if(isset($_SESSION['admin']) && $_SESSION['admin']==$admin)
{
	header("location:admin2.php");
	exit;
}
if(isset($_POST['user']) && isset($_POST['password']))
{
	if($_POST['user']==$admin && $_POST['password']==$password)
	{
		$_SESSION['admin']=$_POST['user'];
		header("Location:admin2.php");
		exit;
	}
	else 
	{
		showerror('<font color="red">账号或密码不正确</font>,现在返回登录<a href="login.php">页面</a>...',true,'index.php');
	}
	
}
else
{
	showerror('<font color="red">请不要尝试非法登录！</font>现在返回<a href="../">首页</a>...',true,'../index.php');
}
?>