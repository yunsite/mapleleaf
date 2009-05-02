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

// if current visitor is admin,the operation is allowed
if(!isset($_SESSION['admin']))
{
	header("location:login.php");
	exit;
}
$del_ids=array();
$del_ids=$_POST['select_mid'];

// Check whether admin had selected some options
if($del_ids==array())
{
	header("location:admin.php?subtab=message");
	exit;
}

// Operation
$file=$mp_root_path."data/gb.txt";
$reply_file=$mp_root_path."data/reply.txt";

foreach($del_ids as $del_id)
{
	$deleted_id=(int)$del_id;
	mp_del($file,'message',$deleted_id);
	mp_del($reply_file,'reply',$deleted_id);
}

header("Location:admin.php?subtab=message&randomvalue=".rand());
?>