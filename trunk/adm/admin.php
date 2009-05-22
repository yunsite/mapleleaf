<?php
session_start();
define('IN_MP',true);
require('../common.php');
require_once('../includes/template_lite/class.template.php');
require('../maple.class.php');
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

//exit;
//theme
$themes=array();
$d=dir('../themes');
while(false!==($entry=$d->read()))
{
	if(substr($entry,0,1)!='.')
	{
		$themes[$entry]=$entry;
	}
}
$d->close();

$maple=new Maple($board_name,$admin_email,$copyright_info,$filter_words,$valid_code_open,$page_on,$num_perpage,$theme,'yes');
$data=$maple->get_data(0,'admin');

$nums=$maple->count_messages();
$reply_num=$maple->count_reply();
//exit;
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

$tpl->assign('theme',$theme);

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
$tpl->assign('themes',$themes);
$tpl->assign('selected_theme',$theme);
$tpl->assign('data',$data);
$tpl->display("admin.tpl");
?>