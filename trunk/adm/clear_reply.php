<?php
// start session
session_start();
define('IN_MP',true);
require('../common.php');
$mp_root_path='../';

// if current visitor is admin,show this page,or direct to login.php
if(!isset($_SESSION['admin']))
{
	header("location:login.php");
	exit;
}
$r_file_name=$mp_root_path.'data/reply.txt';

// Clear all replies
writeover($r_file_name,'');
header("location:admin.php?subtab=message");
?>
