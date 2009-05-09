<?php
session_start();
define('IN_MP',true);
require_once('common.php');
require('./includes/template_lite/class.template.php');
include('smileys/smileys.php');

/*****************************************************
				检索数据
******************************************************/
$file="./data/gb.txt";
$file_reply='./data/reply.txt';

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

//分页数目
$reply_num=count($reply_data);
//若分页开启
$nums=count($data);
//初始化总页数
$pages=1;

//检索相关留言和回复,并转化表情符号
//foreach($data as $message)
for($i=0;$i<$nums;$i++)
{
	// 转换表情符号，只对留言进行转换，没有对回复进行转化
	$data[$i][2] = parse_smileys($data[$i][2], "./smileys/images/", $smileys);

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
}
if($page_on==1)
{
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
/*echo '<pre>';
var_dump($data);
echo '</pre>';*/
//exit;
$admin=isset($_SESSION['admin'])?true:false;
$tpl = new Template_Lite;
$tpl->compile_dir = "./compiled/";
$tpl->template_dir = "./templates/";
$tpl->assign('admin',$admin);
$tpl->assign('pages',$pages);
$tpl->assign('data',$data);
$tpl->assign('nums',$nums);
$tpl->assign('page_on',$page_on);
$tpl->assign("title",$board_name);
$tpl->assign("admin_email",$admin_email);
$tpl->assign("copyright_info",$copyright_info);
$tpl->assign('valid_code_open',$valid_code_open);
$tpl->display("index.html");
?>