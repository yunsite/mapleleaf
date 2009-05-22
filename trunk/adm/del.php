<?php
// start session
session_start();
define('IN_MP',true);
include "../common.php";
$mp_root_path='../';
// If this user is admin,the operation is allowed
if(isset($_SESSION['admin']))
{
	$mid=$_GET['mid'];
	require('../maple.class.php');
	$maple=new Maple($board_name,$admin_email,$copyright_info,$filter_words,$valid_code_open,$page_on,$num_perpage,$theme,'yes');
	if(isset($mid))
	{
		$maple->mp_del($maple->_m_file,'message',$mid);
	}
	//若回复中有关于此留言的记录，执行删除回复操作
	$reply_del=(int)$_GET['reply'];
	if($reply_del==1)
	{
		$maple->mp_del($maple->_r_file,'reply',$mid);
	}
	header("Location:admin.php?subtab=message&randomvalue=".rand());
}
else // Or, direct to the index.php
{
	header("location:index.php");
}
?>
