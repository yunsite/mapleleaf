<?php
/**
*
* @package mapleleaf
* @version 2009-01-15 
* @copyright (c) 2009 mapleleaf Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

// start session
session_start();
define('IN_MP',true);
require_once('common.php');
require_once('./includes/fckeditor/fckeditor.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="./includes/index.js"></script>
<link rel="stylesheet" href="./style/common.css" type="text/css">
<title>欢迎光临<?php echo $board_name;?></title>
</head>

<body><!-- background sound start--><bgsound src="http://www.rainyjune.cn/music/thisnight.wma" /><!--background sound end-->
<div id="container">
<h1>欢迎留言</h1>
<table id="table1">
<tr class="header">
	<td class="ls">昵称</td><td class="m">留言</td><td>时间</td>
</tr>
<?php
//set the filenames of which to be retrieved
$file="./data/gb.txt";
$file_reply='./data/reply.txt';

//initialize some variables
$data=array();
$reply_data=array();

//get all messages ,as Array
$data=readover($file);
array_pop($data);
$data=array_reverse($data);

//get all replies,as Array
$reply_data=readover($file_reply);

//if replies is not null,check one message has one reply or none
$check_reply=true;
if(!$reply_data)
{
	$check_reply=false;
}


// about pagination
$reply_num=count($reply_data);
// if pagination turned on,get content to be displayed for current page as Array
if($page_on==1)
{
	$nums=count($data);
	$pages=ceil($nums/$num_perpage);
	$page_current=0;
	if(isset($_GET['pid']))
	{
		$page_current=(int)$_GET['pid'];
	}
	if($page_current>=$pages)
	{
		$page_current=$pages-1;
	}
	if($page_current<0)
	{
		$page_current=0;
	}
	$start=$page_current*$num_perpage;
	$data=array_slice($data,$start,$num_perpage);
}
// retrieve relevant messages and replies
foreach($data as $message)
{  
//initialize variable
$reply_info=array();
// if we need retrieve reply for the message
if($check_reply==true)
{
	for($i=0;$i<$reply_num;$i++)
	{
		$reply_array_index=-1;
		$reply_current_search=$reply_data[$i];
		$mid=$message[0];
		$reply_index=$reply_current_search[0];
		if($reply_index==$mid)
		{
			$reply_array_index=$i;
			break;
		}
	}
	if($reply_array_index!=-1)
	{
		$reply_info=$reply_data[$reply_array_index];
	}
}
// generate proper content for users
echo "<tr class='message'><td class='left'>";
if($message[1]=='Admin')
{
	echo '<font color="red">Admin</font>';
}
else
{
	echo filter_words($message[1]);
}
echo "</td><td class='left'>".filter_words($message[2]);
// display relevant reply for current message
if($reply_info!=array())
{
	if(strpos($message[2],'&lt;p&gt;')===FALSE)
	{
		echo '<br/>';
	}
	echo '<font color="red">Admin于'.date('m-d H:i',(int)$reply_info[2]).'回复：</font>'.$reply_info[1];
}
echo "</td>
	  <td class='left'>".date('Y-m-d H:i',(int)$message[3])."</td></tr>";
}
// if pagination turned on,generate page navigation
if($page_on==1)
{
	echo "<tr><td colspan='3'>共 $pages 页 $nums 条留言";
	for($i=0;$i<$pages;$i++)
	{
		echo "<a href='index.php?pid=$i'>".($i+1)."</a>&nbsp;";
	}
	echo "</td></tr>";
}
?>

</table><br/>
<div align="center">请您留言：</div>
<form action="process.php" method="post" onsubmit="return checkall()">
<table id="table1">
<tr>
	<td class="l">昵称</td>
	<td class="s">
<?php 
if (isset($_SESSION['admin']))
{
	echo '<input name="user" id="user" type="hidden" maxlength="10"  onfocus="clear_user()" value="Admin" /><font color="red">Admin</font>';
}					
else
{
	echo '<input name="user" id="user" type="text" maxlength="10"  onfocus="clear_user()" value="Guest'.rand().'" />';
}
?>
	</td>
	<td class="left">&nbsp;<div id="user_msg"></div></td>
</tr>
<tr>
	<td class="l" valign="top">留言</td>
	<td class="left"><!--<textarea name="content" cols="20" rows="5" onfocus="clear_content()"></textarea>-->
<?php
	// use fckeditor for users to post their messages
	$myFCKeditor = new FCKeditor('content') ;
	$myFCKeditor->BasePath		=	"./includes/fckeditor/" ;
	$myFCKeditor->ToolbarSet	=	"Basic";
	$myFCKeditor->Config['EnterMode'] = 	'br';
//	$myFCKeditor->Config['SkinPath'] = 	'./includes/fckeditor/editor/skins/default/';
	$myFCKeditor->Value			=	'Hello' ;
	$myFCKeditor->Create() ;
?>
	</td>
	<td class="left">&nbsp;<br /><div id="content_msg"></div></td>
</tr>
<?php
// if valid code turned on ,generate it.
if($valid_code_open==1)
{
?>
<tr>
	<td class="l">验证码</td>
	<td class="left"><input type="text" name="valid_code" size="4" maxlength="4" />&nbsp;<img src="./includes/showimgcode.php" border="0" align="absbottom" /></td>
	<td class="left">&nbsp;</td>
</tr>
<?php
}
?>
<tr>
	<td>&nbsp;</td>
	<td colspan="2" class="left"><input name="submit" type="submit" value="提交留言" /></td>
</tr>

</table>
</form>
	<div id="botton"><?php echo html_entity_decode($copyright_info);?>&nbsp;　<a href="mailto:<?php echo $admin_email;?>">站长信箱</a>　<a href="./adm/index.php">管理</a><br />Powered by <a href="http://maple.dreamneverfall.cn" target="_blank" title="Find More">MapleLeaf 1.5</a></div>
    </div>
</body>
</html>