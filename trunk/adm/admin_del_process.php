<?php
session_start();
define('IN_MP',true);
include "../common.php";
require_once('../maple.class.php');
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

$maple=new Maple($board_name,$admin_email,$copyright_info,$filter_words,$valid_code_open,$page_on,$num_perpage,$theme,'yes');

$del_num=count($del_ids);

for($i=0;$i<$del_num;$i++)
{
	$deleted_id=(int)$del_ids[$i];
	$maple->mp_del($maple->_m_file,'message',$deleted_id);
	if ($_POST[$deleted_id]==1)
	{
		$maple->mp_del($maple->_r_file,'reply',$deleted_id);
	}
}

header("Location:admin.php?subtab=message&randomvalue=".rand());
?>
