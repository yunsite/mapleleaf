<?php
// start session
session_start();
define('IN_MP',true);
require('../common.php');
require_once('../maple.class.php');

// if current visitor is admin,show this page,or direct to login.php
if(!isset($_SESSION['admin']))
{
	header("location:login.php");
	exit;
}
$maple=new Maple($board_name,$admin_email,$copyright_info,$filter_words,$valid_code_open,$page_on,$num_perpage,$theme,'yes');
$maple->clear_messages();
header("location:admin.php?subtab=message");
?>
