<?php
/**
*
* @package mapleleaf
* @version 2009-01-15 
* @copyright (c) 2008 mapleleaf Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

// start session
session_start();
define('IN_MP',true);
require_once('common.php');

// short variable
$user=$_POST['user'];
$content=$_POST['content'];

// perform some checks
if(empty($user) or empty($content))
{	
	showerror("你没有填写完成,现在正在<a href='./index.php'>返回</a>...",true,'index.php');
    exit;
}
if(strlen($content)>580)
{
     showerror("您的话语太多了，现在正在<a href='./index.php'>返回</a>...",true,'index.php');
     exit;
}
if($valid_code_open==1)
{
	if(!checkImgcode())
	{
		showerror("验证码错误，正在<a href='index.php'>返回</a>...",true,'index.php');
		exit;
	}
}
$user=htmlspecialchars(trim($user),ENT_COMPAT,'UTF-8');
// $content=str_replace("\n",' ',trim($_POST['content']));
// $content = nl2br(trim($_POST['content']));
// $content=htmlspecialchars(str_replace(array("\n", "\n\r", "\r", "\r\n"),'',$content));
$content = htmlspecialchars(trim($_POST['content']));
$content = nl2br($content);
$content = str_replace(array("\n", "\r\n", "\r"), '', $content);

$time=time();
if(!isset($_SESSION['admin']) && ($user=='Admin' || $user=='admin' || $user=='root' || $user=='administrator' || $user=='管理员'))
{
	$user='anonymous';
}

// write the message into gb.txt
$input=$user.'"'.$content.'"'.$time."\n";
$file_name='./data/gb.txt';
 
$file_data=array_reverse(file($file_name));
//var_dump($file_data);
//echo var_dump((int)$file_data[0]);
$index_num=(int)trim($file_data[0]);
$next_num=$index_num+1;
//echo $index_num.'--'.$next_num;
$input=file($file_name);
array_pop($input);
$input=implode('',$input);
$input.="$index_num".'"'.$user.'"'.$content.'"'.$time."\n$next_num\n";

writeover($file_name,$input);
header("Location:index.php");
?>