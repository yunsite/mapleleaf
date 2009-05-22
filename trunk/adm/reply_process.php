<?php
session_start();
define('IN_MP',true);
require_once('../common.php');
require_once('../maple.class.php');
if(!isset($_SESSION['admin']))
{
	header("location:index.php");
	exit;
}
$mid=0;
$mid=(int)$_POST['mid'];
$reply_content = htmlspecialchars(trim($_POST['reply_content']));
$reply_content = nl2br($reply_content);
$reply_content = str_replace(array("\n", "\r\n", "\r"), '', $reply_content);
$time=time();
$input=$mid.'"'.$reply_content.'"'.$time."\n";

$maple=new Maple($board_name,$admin_email,$copyright_info,$filter_words,$valid_code_open,$page_on,$num_perpage,$theme,'yes');
$maple->add_reply($mid,$input);
header("Location:admin.php?subtab=message");
?>

