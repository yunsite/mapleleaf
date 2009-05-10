<?php
// start session
session_start();
define('IN_MP',true);
include "../common.php";
$mp_root_path='../';

// if current visitor is admin,the operation is allowed
if(!isset($_SESSION['admin']))
{
	header("location:login.php");
	exit;
}
/*$del_ids=array();
$del_r_mid=array();
$del_ids=$_POST['select_mid'];
$del_r_mid=$_POST['del_r_mid'];*/

@$del_ids=$_POST['select_mid']?$_POST['select_mid']:array();
@$del_r_mid=$_POST['del_r_mid']?$_POST['del_r_mid']:array();
// Check whether admin had selected some options
if($del_ids==array()|| $del_r_mid==array())
{
	header("location:admin2.php?subtab=message");
	exit;
}

// Operation
$file=$mp_root_path."data/gb.txt";
$reply_file=$mp_root_path."data/reply.txt";

/*foreach($del_ids as $del_id)
{
	$deleted_id=(int)$del_id;
	mp_del($file,'message',$deleted_id);
	mp_del($reply_file,'reply',$deleted_id);
}*/
$del_num=count($del_ids);
for($i=0;$i<$del_num;$i++)
{
	$deleted_id=(int)$del_ids[$i];
	mp_del($file,'message',$deleted_id);
	if ($del_r_mid[$i]==1)
	{
		mp_del($reply_file,'reply',$deleted_id);
	}
}

header("Location:admin2.php?subtab=message&randomvalue=".rand());
?>