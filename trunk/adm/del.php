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
include "../common.php";
$mp_root_path='../';
// If this user is admin,the operation is allowed
if(isset($_SESSION['admin']))
{
	$mid=$_GET['mid'];
	// If $_GET['mid'] isset,delete relevant message
	if(isset($mid))
	{
		$file=$mp_root_path."data/gb.txt";
		mp_del($file,'message',$mid);
	}
	
	//若回复中有关于此留言的记录，执行删除回复操作
	$reply_del=(int)$_GET['reply'];
	// If $_GET['reply'] isset,delete relevant reply
	if($reply_del==1)
	{
		$reply_file=$mp_root_path."data/reply.txt";
		mp_del($reply_file,'reply',$mid);
	}
	header("Location:admin.php?subtab=message&randomvalue=".rand());
}
else // Or, direct to the index.php
{
	header("location:index.php");
}
?>