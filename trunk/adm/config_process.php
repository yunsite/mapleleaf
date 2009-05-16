<?php
//session start
session_start();
define('IN_MP',true);
include "../common.php";
$mp_root_path='../';
if(!isset($_SESSION['admin']))
{
	header("location:login.php");
	exit;
}
	//process the data
	$board_name=$_POST['board_name']?htmlspecialchars(trim($_POST['board_name']),ENT_QUOTES):'风舞六月';
	$admin_email=$_POST['admin_email']?htmlspecialchars(trim($_POST['admin_email']),ENT_QUOTES):'admin@rainyjune.cn';
	$copyright_info=$_POST['copyright_info']?htmlspecialchars(trim($_POST['copyright_info']),ENT_QUOTES):'Copyright &copy; 2009 rainyjune';
	$filter_words=$_POST['filter_words']?fix_filter_string($_POST['filter_words']):'';
	$valid_code_open=(int)$_POST['valid_code_open'];
	$page_on=(int)$_POST['page_on'];
	$num_perpage=(int)$_POST['num_perpage'];
	$data=sprintf("<?php \n\t \$board_name='%s';\n\t \$admin_email='%s';\n\t \$copyright_info='%s';\n\t \$filter_words='%s';\n\t \$valid_code_open=%d;\n\t \$page_on=%d;\n\t \$num_perpage=%d;\n?>",$board_name,$admin_email,$copyright_info,$filter_words,$valid_code_open,$page_on,$num_perpage);
	
	//write it into site.conf.php
	writeover('site.conf.php',$data);
	header("location:admin.php");
?>
