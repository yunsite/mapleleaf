<?php
session_start();
define('IN_MP',true);
require_once('common.php');
require('./maple.class.php');
// short variable
$user=$_POST['user'];
$content=$_POST['content'];

$maple=new Maple($board_name,$admin_email,$copyright_info,$filter_words,$valid_code_open,$page_on,$num_perpage,$theme);

// perform some checks
if(empty($user) or empty($content))
{	
	$maple->showerror("你没有填写完成,现在正在<a href='./index.php'>返回</a>...",true,'index.php');
    exit;
}
if(strlen($content)>580)
{
     $maple->showerror("您的话语太多了，现在正在<a href='./index.php'>返回</a>...",true,'index.php');
     exit;
}
if($valid_code_open==1)
{
	if(!checkImgcode())
	{
		$maple->showerror("验证码错误，正在<a href='index.php'>返回</a>...",true,'index.php');
		exit;
	}
}
$user=htmlspecialchars(trim($user),ENT_COMPAT,'UTF-8');
$content = htmlspecialchars(trim($_POST['content']));
$content = nl2br($content);
$content = str_replace(array("\n", "\r\n", "\r"), '', $content);

$time=time();
if(!isset($_SESSION['admin']) && ($user=='Admin' || $user=='admin' || $user=='root' || $user=='administrator' || $user=='管理员'))
{
	$user='anonymous';
}

$input=$user.'"'.$content.'"'.$time."\n";

$maple->add_message($input);
header("Location:index.php");
?>
