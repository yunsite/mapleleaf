<?php
session_start();
define('IN_MP',true);
include "../common.php";

if(!isset($_SESSION['admin']))
{
	header("location:login.php");
	exit;
}
$maple=new Maple('yes');
switch ($_REQUEST['process_type'])
{
	case 'config_set':
		$board_name=$_POST['board_name']?htmlspecialchars(trim($_POST['board_name']),ENT_QUOTES):'风舞六月';
		$admin_email=$_POST['admin_email']?htmlspecialchars(trim($_POST['admin_email']),ENT_QUOTES):'admin@rainyjune.cn';
		$copyright_info=$_POST['copyright_info']?htmlspecialchars(trim($_POST['copyright_info']),ENT_QUOTES):'Copyright &copy; 2009 rainyjune';
		$filter_words=$_POST['filter_words']?$maple->fix_filter_string($_POST['filter_words']):'';
		$valid_code_open=(int)$_POST['valid_code_open'];
		$page_on=(int)$_POST['page_on'];
		$num_perpage=(int)$_POST['num_perpage'];
		$theme=$_POST['theme'];
		$password=isset($_POST['password'])?$_POST['password']:'admin';
		$timezone=$_POST['timezone'];
		$data=sprintf("<?php \n\t \$board_name='%s';\n\t \$admin_email='%s';\n\t \$copyright_info='%s';\n\t \$filter_words='%s';\n\t \$valid_code_open=%d;\n\t \$page_on=%d;\n\t \$num_perpage=%d;\n\t \$theme='%s';\n\t \$admin='%s';\n\t \$password='%s';\n\t \$timezone='%s'; \n?>",$board_name,$admin_email,$copyright_info,$filter_words,$valid_code_open,$page_on,$num_perpage,$theme,'admin',$password,$timezone);
		
		//write it into site.conf.php
		$maple->writeover('site.conf.php',$data,'wb');
		header("location:admin.php");
		break;
	case 'del':
		$mid=$_GET['mid'];
		if(isset($mid))
		{
			$maple->mp_del($maple->_m_file,'message',$mid);
		}
		//若回复中有关于此留言的记录，执行删除回复操作
		$reply_del=(int)$_GET['reply'];
		if($reply_del==1)
		{
			$maple->mp_del($maple->_r_file,'reply',$mid);
		}
		header("Location:admin.php?subtab=message&randomvalue=".rand());
		break;
	case 'reply_process':
		$mid=0;
		$mid=(int)$_POST['mid'];
		$reply_content = htmlspecialchars(trim($_POST['reply_content']));
		$reply_content = nl2br($reply_content);
		$reply_content = str_replace(array("\n", "\r\n", "\r"), '', $reply_content);
		$time=time();
		$input=$mid.'"'.$reply_content.'"'.$time."\n";
		$maple->add_reply($mid,$input);
		header("Location:admin.php?subtab=message");
		break;
	case 'admin_del_process':
		@$del_ids=$_POST['select_mid']?$_POST['select_mid']:array();
		// Check whether admin had selected some options
		if($del_ids==array())
		{
			header("location:admin.php?subtab=message");
			exit;
		}
		$del_num=count($del_ids);
		for($i=0;$i<$del_num;$i++)
		{
			$deleted_id=(int)$del_ids[$i];
			$maple->mp_del($maple->_m_file,'message',$deleted_id);
			if ($_POST[$deleted_id]==1)
			{
				$maple->mp_del($maple->_r_file,'reply',$deleted_id);
			}
		}
		header("Location:admin.php?subtab=message&randomvalue=".rand());
		break;
	case 'clear_message':
		$maple->clear_messages();
		header("location:admin.php?subtab=message");
		break;
	case 'clear_reply':
		$maple->clear_reply();
		header("location:admin.php?subtab=message");
		break;
	default:
		header("location:admin.php");
}
?>