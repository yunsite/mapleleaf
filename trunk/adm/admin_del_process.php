<?php
session_start();
define('IN_MP',true);
include "../common.php";
$mp_root_path='../';

if(!isset($_SESSION['admin']))
{
	header("location:login.php");
	exit;
}

@$del_ids=$_POST['select_mid']?$_POST['select_mid']:array();
// Check whether admin had selected some options
if($del_ids==array())
{
	header("location:admin.php?subtab=message");
	exit;
}

// Operation
$file=$mp_root_path."data/gb.txt";
$reply_file=$mp_root_path."data/reply.txt";

$del_num=count($del_ids);
for($i=0;$i<$del_num;$i++)
{
	$deleted_id=(int)$del_ids[$i];
	mp_del($file,'message',$deleted_id);
	if ($_POST[$deleted_id]==1)
	{
		mp_del($reply_file,'reply',$deleted_id);
	}
}

header("Location:admin.php?subtab=message&randomvalue=".rand());
?>
