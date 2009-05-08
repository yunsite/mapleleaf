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

// Which tab should be displayed?
$current_tab='';
$tabs_array=array('overview','siteset','message');
if(isset($_GET['subtab']))
{
	if(in_array($_GET['subtab'],$tabs_array))
	{
		$current_tab=$_GET['subtab'];
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="<?php echo $mp_root_path.'style/admin.css';?>" />
<script type="text/javascript" src="<?php echo $mp_root_path.'includes/admin.js';?>"></script>
<title>管理员控制面板首页</title>
</head>

<body>
<div id="admin_header">
	<a href="../">首页</a>&nbsp;<a href="logout.php" title="注销"><img src="<?php echo $mp_root_path.'style/images/icon_logout.gif';?>"  alt="注销" border="0" /></a>
</div>
<div id="con">
<ul id="tags">
	<li <?php if($current_tab=='') echo 'class="selectTag"';?>><a id="overview" onclick="selectTag('tagContent0',this)" 
  href="javascript:void(0)">综合</a> </li>
  <LI><A onClick="selectTag('tagContent1',this)" 
  href="javascript:void(0)">站点设置</A> </LI>
 <li <?php if($current_tab=='message') echo 'class="selectTag"';?>><A id="message_m" onClick="selectTag('tagContent2',this)" 
  href="javascript:void(0)">留言管理</A> </li></UL>
<div id="tagContent">
<div id="tagContent0" 
<?php 
if($current_tab=='')
{
	echo  'class="tagContent selectTag"';
}
else
{
	echo 'class="tagContent"';
}?>>
<table>
	<tr>
		<td><h1>欢迎来到MapleLeaf</h1></td>
	</tr>
	<tr>
		<td align="left">感谢您选择MapleLeaf作为留言板解决方案. 这个界面将显示您的留言板的总体信息.上方的链接允许您管理留言板.</td>
	</tr>
</table>
<table  width="256px" align="left" cellpadding="0" cellspacing="0" style="margin-top:5px;">
	<tr>
		<td align="left"><b>统计信息</b></td>
	</tr>
	<tr>
		<td align="left">留言数量：</td><td align="right"><?php $m_file=$mp_root_path."data/gb.txt";$m_data=array();$m_data=readover($m_file);array_pop($m_data);  echo count($m_data);?></td>
	</tr>
	<tr>
		<td align="left">回复数量：</td><td align="right"><?php $r_file=$mp_root_path."data/reply.txt";$r_data=array();$r_data=readover($r_file); echo count($r_data);?></td>
	</tr>
	<tr>
		<td align="left">当前版本：</td><td align="right"><?php echo $mapleleaf_version;?></td>
	</tr>
	<tr>
		<td align="left"><b>系统信息</b></td>
	</tr>
	<tr>
		<td align="left">PHP版本：</td><td align="right"><?php echo PHP_VERSION;?></td>
	</tr>
	<tr>
		<td align="left">GD版本： </td><td align="right"><?php /*echo GD_VERSION;*/ if (defined(GD_VERSION)){echo GD_VERSION;}else{echo '<font color="red">未知</font>';} ;?></td>
	</tr>
	<tr>
		<td align="left">安全模式：</td><td align="right"><?php echo ($isSafeMode ? 'On' : 'Off')?></td>
	</tr>
	<tr>
		<td align="left">Register_Globals：</td><td align="right"><?php echo ini_get("register_globals") ? 'On' : 'Off'?></td>
	</tr>
	<tr>
		<td align="left">Magic_Quotes_Gpc：</td><td align="right"><?php echo ini_get("magic_quotes_gpc") ? 'On' : 'Off'?></td>
	</tr>
	<tr>
		<td align="left">allow_url_fopen：</td><td align="right"><?php echo ini_get("allow_url_fopen") ? 'On' : 'Off'?></td>
	</tr>
</table>
</DIV>
<div class="tagContent" id="tagContent1">
<form action="config_process.php" method="post">
<input type="hidden" name="process_type" value="config_set" />
<fieldset>
<legend>整体设置</legend>
<table cellpadding="0" cellspacing="0" width="600px">
	<tr>
		<td width="150px">留言板名称:</td><td align="left"><input name="board_name" type="text" size="20" value="<?php echo $board_name;?>" /></td>
	</tr>
	<tr>
		<td>站长信箱:</td><td align="left"><input name="admin_email" type="text" size="20" value="<?php echo $admin_email;?>" /></td>
	</tr>
	<tr>
		<td>版权信息:</td><td align="left"><textarea name="copyright_info" cols="20" rows="3"><?php echo $copyright_info;?></textarea></td>
	</tr>
</table>
</fieldset>
<fieldset>
<legend>留言设置</legend>
<table cellpadding="0" cellspacing="0" width="600px">
	<tr>
		<td width="150px">过滤词汇：</td><td align="left"><textarea name="filter_words" cols="20" rows="3"><?php echo $filter_words;?></textarea></td>
	</tr>
	<tr>
		<td>启用验证码：</td><td align="left"><input name="valid_code_open" type="radio" value="1" 
<?php if($valid_code_open==1){echo "checked='checked'";}?>>启用<input name="valid_code_open" type="radio" value="0" <?php if($valid_code_open==0){echo "checked='checked'";}?>>关闭</td>
	</tr>
	<tr>
		<td>启用分页功能：</td><td align="left"><input name="page_on" type="radio" value="1" <?php if($page_on==1){echo "checked='checked'";}?> />启用<input name="page_on" type="radio" value="0" <?php if($page_on==0){echo "checked='checked'";}?> />关闭</td>
	</tr>
	<tr>
		<td>每页显示留言数：</td><td align="left"><input name="num_perpage" type="text" value="<?php echo $num_perpage;?>" />(当分页启用后，此设置起效)</td>
	</tr>
</table>
</fieldset>
<input type="submit" value="提交" /><input type="reset" value="重设" />
</form>

</div>
<DIV  id="tagContent2"
<?php 
if($current_tab=='message')
{
	echo  'class="tagContent selectTag"';
}
else
{
	echo 'class="tagContent"';
}?>
 >
<div id="container2">
<!--<h1>留言管理</h1>-->
<form action="admin_del_process.php" method="post">
<table id="table2">
	<tr class="header">
		<td>选择</td><td class="ls">昵称</td><td class="m">留言</td><td>删除</td><td>回复</td>
	</tr>
<?php
$file=$mp_root_path."data/gb.txt";
$file_reply=$mp_root_path.'data/reply.txt';
$data=array();
$reply_data=array();
$data=readover($file);
$reply_data=readover($file_reply);
array_pop($data);
$check_reply=true;
if(!$reply_data)
{
	$check_reply=false;
}
$data=array_reverse($data);
$reply_num=count($reply_data);
/*
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
*/
foreach($data as $message)
{  
$reply_info=array();
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
echo "<tr class='admin_message'><td><input type='checkbox' name='select_mid[]' value='".$message[0]."' /></td><td class='left'>";
if(htmlspecialchars_decode($message[1])=='Admin')
{
	echo '<font color="red">Admin</font>';
}
else
{
	echo filter_words(htmlspecialchars_decode($message[1]));
}
echo "</td><td class='left'>".htmlspecialchars_decode($message[2])."<br />时间：".date('Y-m-d H:i',(int)$message[3]);
$reply_del_mark=0;
if($reply_info!=array())
{
	$reply_del_mark=1;
	if(strpos($message[2],'&lt;p&gt;')===FALSE)
	{
		echo '<br/>';
	}
	echo '您于'.date('m-d H:i',(int)$reply_info[2]).'回复：'.htmlspecialchars_decode($reply_info[1]);
	
}
echo "</td>";
	  
echo "<td><a href='del.php?mid=$message[0]&reply=$reply_del_mark'>删除</a></td><td><a href='reply.php?mid=$message[0]'>回复</a></td></tr>";
}//end foreach
/*
if($page_on==1)
{
echo "<tr><td colspan='5'>共 $pages 页 $nums 条留言";
for($i=0;$i<$pages;$i++)
{
	echo "<a href='index.php?pid=$i'>".($i+1)."</a>&nbsp;";
}
echo "</td></tr>";

}
*/
echo "<tr><td colspan='5' align='left'><input type='submit' value='删除所选' />&nbsp;<input type='button' value='清空所有留言'  onclick=\"javascript:if(window.confirm('你确实要删除所有留言吗？同时会删除所有回复'))window.open('clear_message.php','_self')\" />&nbsp;<input type='button' value='清空所有回复' onclick=\"javascript:if(window.confirm('你确实要删除所有回复？'))window.open('clear_reply.php','_self')\" /></td></tr>";
?>
</table>
</form>
<br/>
    </div>
</DIV>
</DIV>
</DIV>
<div id="botton">Powered by <a href="http://www.rainyjune.cn">MapleLeaf</a>&nbsp;&copy; 2009 mapleleaf Group</div>
</body>
</html>