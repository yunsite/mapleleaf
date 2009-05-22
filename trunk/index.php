<?php
session_start();
define('IN_MP',true);
require_once('common.php');
require('./includes/template_lite/class.template.php');
require('./maple.class.php');
$maple=new Maple($board_name,$admin_email,$copyright_info,$filter_words,$valid_code_open,$page_on,$num_perpage,$theme);

$current_page=isset($_GET['pid'])?(int)$_GET['pid']:0;
$data=$maple->get_data($current_page);
$nums=$maple->count_messages();
$pages=ceil($nums/$maple->_num_perpage);


$admin=isset($_SESSION['admin'])?true:false;
$tpl = new Template_Lite;
$tpl->compile_dir = "./compiled/";
$tpl->template_dir = "./templates/";
$tpl->assign('theme',$theme);
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
