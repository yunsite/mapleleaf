<?php
// start session
session_start();
define('IN_MP',true);
require('../common.php');
require_once('../includes/template_lite/class.template.php');
include('../smileys/smileys.php');

// if current visitor is admin,show this page,or direct to login.php
if(!isset($_SESSION['admin']))
{
	header("location:login.php");
	exit;
}

// Which tab should be displayed?
$current_tab='overview';
$tabs_array=array('overview','siteset','message');
if(isset($_GET['subtab']))
{
	if(in_array($_GET['subtab'],$tabs_array))
	{
		$current_tab=$_GET['subtab'];
	}
}

/*****************************************************
				检索数据
******************************************************/
$file="../data/gb.txt";
$file_reply='../data/reply.txt';

//初始化变量
$data=array();
$reply_data=array();

//得到所有的留言
$data=readover($file);
array_pop($data);
$data=array_reverse($data);

//得到所有回复
$reply_data=readover($file_reply);
$check_reply=$reply_data?true:false;


$reply_num=count($reply_data);

$nums=count($data);
//初始化总页数
$pages=1;

//检索相关留言和回复
//foreach($data as $message)
for($i=0;$i<$nums;$i++)
{
	// 转换表情符号，只对留言进行转换，没有对回复进行转化
	$data[$i][2]=str_replace(array('&gt;:(','&gt;:-('),array('>:(','>:-('),$data[$i][2]);
	$data[$i][2] = parse_smileys($data[$i][2], "../smileys/images/", $smileys);
	// if we need retrieve reply for the message
	if($check_reply==true)
	{
		for($j=0;$j<$reply_num;$j++)
		{
			$reply_current_search=$reply_data[$j];
			$mid=$data[$i][0];
			$reply_index=$reply_current_search[0];
			if($reply_index==$mid)
			{
				$data[$i]['reply']=$reply_data[$j];
				break;
			}
		}
	}
	else
	{
		break;
	}
}
if (defined(GD_VERSION))
{
$gd_version=GD_VERSION;
}
else
{
$gd_version='<font color="red">未知</font>';
}
$isSafeMode=$isSafeMode ? 'On' : 'Off';
$register_globals=ini_get("register_globals") ? 'On' : 'Off';
$magic_quotes_gpc=ini_get("magic_quotes_gpc") ? 'On' : 'Off';
$allow_url_fopen=ini_get("allow_url_fopen") ? 'On' : 'Off';

$tpl = new Template_Lite;
$tpl->compile_dir = "../compiled/";
$tpl->template_dir = "../templates/";

$tpl->assign('current_tab',$current_tab);
$tpl->assign('m_num',$nums);
$tpl->assign('r_num',$reply_num);
$tpl->assign('mapleleaf_version',$mapleleaf_version);
$tpl->assign('php_version',PHP_VERSION);
$tpl->assign('gd_version',$gd_version);
$tpl->assign('isSafeMode',$isSafeMode);
$tpl->assign('register_globals',$register_globals);
$tpl->assign('magic_quotes_gpc',$magic_quotes_gpc);
$tpl->assign('allow_url_fopen',$allow_url_fopen);

$tpl->assign('board_name',$board_name);
$tpl->assign('admin_email',$admin_email);
$tpl->assign('copyright_info',$copyright_info);
$tpl->assign('filter_words',$filter_words);
$tpl->assign('valid_code_open',$valid_code_open);
$tpl->assign('page_on',$page_on);
$tpl->assign('num_perpage',$num_perpage);

$tpl->assign('data',$data);
$tpl->display("admin.tpl");
?>