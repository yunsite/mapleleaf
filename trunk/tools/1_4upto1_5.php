<?php
/**
*
* @package mapleleaf
* @version 1_4upto1_5.php 2009-01-19
* @copyright (c) 2008 mapleleaf Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
define('IN_MP',true);
require_once('../common.php');
error_reporting(E_ALL);

// If we are on PHP >= 5.1.0 we can do this operation
if (version_compare(PHP_VERSION, '5.1.0', '<'))
{
	die('系统升级需要服务器上PHP版本>=5.1.0,而当前的PHP版本是'.PHP_VERSION);
}

//get all message from gb.txt
$file="../data/gb.txt";
$data=array();
$data=readover($file);
$num=count($data);

// If has no message in the txt file,exit
if($num==0)
{
	die('没有留言内容，无需转换');
}

$message=array();

// Iterate over the array
for($i=0;$i<$num;$i++)
{
	$per_m=$data[$i];
	// Convert the the format of time to int
	$per_m[2]=strtotime(trim($per_m[2]));
	// Add index,from 0
	array_unshift($per_m,$i);
	$message[]=$per_m;
}
$new='';
foreach($message as $p)
{
	$pn=join('"',$p);
	$new.=$pn."\n";
}
// Add the next message's index
$new.=$num."\n";

// Write into gb.txt
writeover($file,$new,'wb');

echo 'It seemed everthing goes well :)<br />';
echo 'Go to <a href="../index.php">Index Page</a>';
?>