<?php
session_start();
define('IN_MP',true);
require_once('./common.php');

$tpl = new Template_Lite();
$tpl->compile_dir = "./compiled/";
$tpl->template_dir = "./templates/";

$maple=new Maple();
$action=isset($_GET['action'])?$_GET['action']:'index';

$current_ip=$_SERVER['REMOTE_ADDR'];

if (is_baned($current_ip))
{
	$maple->showerror('你被管理员禁止登录！');
}
switch ($action)
{
	//show login window
	case 'login_window':
		if (isset($_SESSION['admin']))
		{
			header("location:index.php?action=admin");
			exit;
		}
		$tpl->assign('theme',$theme);
		$tpl->assign('mp_version',$mapleleaf_version);
		$tpl->display("login.html");
		break;
	//login process
	case 'login':
		if (isset($_SESSION['admin']))
		{
			header("location:index.php?action=admin");
			exit;
		}
		if(isset($_POST['user']) && isset($_POST['password']))
		{
			if($_POST['user']==$admin && $_POST['password']==$password)
			{
				$_SESSION['admin']=$_POST['user'];
				header("Location:index.php?action=admin");
				exit;
			}
			else 
			{
				$maple->showerror('<font color="red">账号或密码不正确</font>,现在返回登录<a href="index.php?action=login_window">页面</a>...',true,'index.php?action=login_window');
			}
			
		}
		else
		{
			$maple->showerror('<font color="red">请不要尝试非法登录！</font>现在返回<a href="index.php">首页</a>...',true,'index.php');
		}
		break;
	//logout
	case 'logout':
		$old_user='';
		if(isset($_SESSION['admin']))
		{
			$maple->delete_backup_files();
			$old_user=$_SESSION['admin'];
			unset($_SESSION['admin']);
			session_destroy();
		}
		$tpl->assign('old_user',$old_user);
		$tpl->assign('theme',$theme);
		$tpl->assign('mp_version',$mapleleaf_version);
		$tpl->display('logout.html');
		break;
	//add new message
	case 'add':
		$new_message=$maple->add_message_check();
		$maple->add_message($new_message);
		header("Location:index.php");
		break;
	//ok
	case 'reply_window':
		is_admin();
		if(!isset($_GET['mid']) || !isset($_GET['reply']))
		{
			header("location:index.php?action=admin&subtab=message");
			exit;
		}
		$mid=(int)$_GET['mid'];
		$if_replied=intval($_GET['reply']);
		if ($if_replied==1)
		{
			$reply_data=$maple->getdatabyid($maple->_r_file,$mid);
			$tpl->assign('mid',$mid);
			$tpl->assign('reply_content',$reply_data[1]);
			$tpl->display('reply_update.html');
		}
		else 
		{
			$tpl->assign('mid',$mid);
			$tpl->display('reply.html');
		}
		break;
	case 'reply_update':
		is_admin();
		$mid=0;
		$mid=(int)$_POST['mid'];
		$reply_content = htmlspecialchars(trim($_POST['reply_content']));
		$reply_content = nl2br($reply_content);
		$reply_content = str_replace(array("\n", "\r\n", "\r"), '', $reply_content);
		if (trim($reply_content)=='')
		{
			$maple->showerror('回复不可以为空',true,'index.php?action=admin&subtab=message',3);
		}
		$time=time();
		$input=$mid.'"'.$reply_content.'"'.$time."\n";
		$maple->maple_modify($maple->_r_file,$mid,$input);
		header("Location:index.php?action=admin&subtab=message");
		break;
	case 'update_message':
		is_admin();
		if(!isset($_GET['mid']))
		{
			header("location:index.php?action=admin&subtab=message");
			exit;
		}
		$mid=(int)$_GET['mid'];	
		$tpl->assign('mid',$mid);
		$message_info=$maple->getdatabyid($maple->_m_file,$mid);
		$tpl->assign('author',$message_info[1]);
		$tpl->assign('update_content',$message_info[2]);
		$tpl->assign('m_time',$message_info[3]);
		$tpl->display('update_message.html');
		break;
	//ok
	case 'reply':
		is_admin();
		$mid=0;
		$mid=(int)$_POST['mid'];
		$reply_content = htmlspecialchars(trim($_POST['reply_content']));
		$reply_content = nl2br($reply_content);
		$reply_content = str_replace(array("\n", "\r\n", "\r"), '', $reply_content);
		if (trim($reply_content)=='')
		{
			$maple->showerror('回复不可以为空',true,'index.php?action=admin&subtab=message',3);
		}
		$time=time();
		$input=$mid.'"'.$reply_content.'"'.$time."\n";
		$maple->add_reply($mid,$input);
		header("Location:index.php?action=admin&subtab=message");
		break;
	case 'update':
		is_admin();
		$mid=0;
		$mid=(int)$_POST['mid'];
		$author=$_POST['author'];
		$m_time=$_POST['m_time'];
		$update_content = htmlspecialchars(trim($_POST['update_content']));
		$update_content = nl2br($update_content);
		$update_content = str_replace(array("\n", "\r\n", "\r"), '', $update_content);
		$ip=$_SERVER['REMOTE_ADDR'];
		$input=$mid.'"'.$author.'"'.$update_content.'"'.$m_time.'"'.$ip."\n";
		$maple->maple_modify($maple->_m_file,$mid,$input);
		header("Location:index.php?action=admin&subtab=message");
		break;
	case 'delete':
		is_admin();
		$mid=intval($_GET['mid']);
		if(isset($mid))
		{
			$maple->maple_modify($maple->_m_file,$mid,'');
		}
		//若回复中有关于此留言的记录，执行删除回复操作
		$reply_del=(int)$_GET['reply'];
		if($reply_del==1)
		{
			$maple->maple_modify($maple->_r_file,$mid,'');
		}
		header("Location:index.php?action=admin&subtab=message&randomvalue=".rand());
		break;
		
	case 'delete_reply':
		is_admin();
		$mid=intval($_GET['mid']);
		if(isset($mid))
		{
			$maple->maple_modify($maple->_r_file,$mid,'');
		}
		header("Location:index.php?action=admin&subtab=message&randomvalue=".rand());
		break;

	case 'delete_m':
		is_admin();
		@$del_ids=$_POST['select_mid']?$_POST['select_mid']:array();
		// Check whether admin had selected some options
		if($del_ids==array())
		{
			header("location:index.php?action=admin&subtab=message");
			exit;
		}
		$del_num=count($del_ids);
		for($i=0;$i<$del_num;$i++)
		{
			$deleted_id=(int)$del_ids[$i];
			$maple->maple_modify($maple->_m_file,$deleted_id,'');
			if ($_POST[$deleted_id]==1)
			{
				$maple->maple_modify($maple->_r_file,$deleted_id,'');
			}
		}
		header("Location:index.php?action=admin&subtab=message&randomvalue=".rand());
		break;
	//ok
	case 'clear_all':
		is_admin();
		$maple->clear_messages();
		header("location:index.php?action=admin&subtab=message");
		break;
	//ok
	case 'clear_reply':
		is_admin();
		$maple->clear_reply();
		header("location:index.php?action=admin&subtab=message");
		break;
	//ok
	case 'config':
		is_admin();
		$board_name=$_POST['board_name']?htmlspecialchars(trim($_POST['board_name']),ENT_QUOTES):'风舞六月';
		$mb_open=$_POST['mb_open']?(int)$_POST['mb_open']:0;
		$close_reason=htmlspecialchars(trim($_POST['close_reason']),ENT_QUOTES);
		$admin_email=$_POST['admin_email']?htmlspecialchars(trim($_POST['admin_email']),ENT_QUOTES):'admin@rainyjune.cn';
		$copyright_info=$_POST['copyright_info']?htmlspecialchars(trim($_POST['copyright_info']),ENT_QUOTES):'Copyright &copy; 2009 rainyjune';
		$filter_words=$_POST['filter_words']?$maple->fix_filter_string($_POST['filter_words']):'';
		$valid_code_open=(int)$_POST['valid_code_open'];
		$page_on=(int)$_POST['page_on'];
		$num_perpage=(int)$_POST['num_perpage'];
		$theme=$_POST['theme'];
		$password=isset($_POST['password'])?$_POST['password']:'admin';
		$timezone=$_POST['timezone'];
		$data=sprintf("<?php \n\t 
		\$board_name='%s';\n\t 
		\$mb_open=%d;\n\t 
		\$close_reason='%s';\n\t 
		\$admin_email='%s';\n\t 
		\$copyright_info='%s';\n\t 
		\$filter_words='%s';\n\t 
		\$valid_code_open=%d;\n\t 
		\$page_on=%d;\n\t 
		\$num_perpage=%d;\n\t 
		\$theme='%s';\n\t 
		\$admin='%s';\n\t 
		\$password='%s';\n\t 
		\$timezone='%s'; 
		\n?>",
						$board_name,
						$mb_open,
						$close_reason,
						$admin_email,
						$copyright_info,
						$filter_words,
						$valid_code_open,
						$page_on,
						$num_perpage,
						$theme,
						'admin',
						$password,
						$timezone);
		
		//write it into site.conf.php
		$maple->writeover($maple->_site_conf_file,$data,'wb');
		header("location:index.php?action=admin&subtab=siteset");
		break;
	case 'admin':
		is_admin();
		// Which tab should be displayed?
		$current_tab='overview';
		$tabs_array=array('overview','siteset','message','ban_ip');
		if(isset($_GET['subtab']))
		{
			if(in_array($_GET['subtab'],$tabs_array))
			{
				$current_tab=$_GET['subtab'];
			}
		}
		

		//theme
		$themes=array();
		$d=dir($maple->_themes_directory);
		while(false!==($entry=$d->read()))
		{
			if(substr($entry,0,1)!='.')
			{
				$themes[$entry]=$entry;
			}
		}
		$d->close();
		$data=$maple->get_data(0,'admin');

		$ban_ip_info=$maple->get_baned_ips();
		
		$nums=$maple->count_nums($maple->_m_file);
		$reply_num=$maple->count_nums($maple->_r_file);
		$gd_info=gd_info();

		if (defined(GD_VERSION))
		{
		$gd_version=GD_VERSION;
		}
		elseif ($gd_info)
		{
			$gd_version=$gd_info['GD Version'];
		}
		else 
		{
		$gd_version='<font color="red">未知</font>';
		}
		$isSafeMode=$isSafeMode ? 'On' : 'Off';
		$register_globals=ini_get("register_globals") ? 'On' : 'Off';
		$magic_quotes_gpc=ini_get("magic_quotes_gpc") ? 'On' : 'Off';
		$allow_url_fopen=ini_get("allow_url_fopen") ? 'On' : 'Off';
		
		$timezone_array=array('Asia/Chongqing'=>'重庆',
								'Asia/Harbin'=>'哈尔滨',
								'Asia/Hong_Kong'=>'香港',
								'Asia/Macao'=>'澳门',
								'Asia/Shanghai'=>'上海',
								'Asia/Taipei'=>'台北',
								'Asia/Urumqi'=>'乌鲁木齐');
		$tpl->assign('mp_version',$mapleleaf_version);
		$tpl->assign('theme',$theme);
		$tpl->assign('timezones',$timezone_array);
		$tpl->assign('selected_timezone',$timezone);
		
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
		$tpl->assign('mb_open',$mb_open);
		$tpl->assign('close_reason',$close_reason);
		$tpl->assign('admin_email',$admin_email);
		$tpl->assign('copyright_info',$copyright_info);
		$tpl->assign('filter_words',$filter_words);
		$tpl->assign('valid_code_open',$valid_code_open);
		$tpl->assign('page_on',$page_on);
		$tpl->assign('num_perpage',$num_perpage);
		$tpl->assign('themes',$themes);
		$tpl->assign('selected_theme',$theme);
		$tpl->assign('data',$data);
		$tpl->assign('password',$password);
		
		$tpl->assign('ban_ip_array',$ban_ip_info);
		$tpl->display("admin.html");
		break;
	case 'backup':
		is_admin();
		$file = $maple->_m_file;
		$r_file=$maple->_r_file;
		if(!class_exists('ZipArchive'))
		{
			$maple->showerror("你的服务器不支持此功能！",true,'index.php?action=admin&subtab=message');
			exit;
		}
		$zip = new ZipArchive();
		$filename = "data/backup-".date('Ymd',time()).".zip";

		if ($zip->open($filename, ZIPARCHIVE::CREATE)!==TRUE) {
		   exit("cannot open <$filename>\n");
		}
		
		$zip->addFile($file);
		$zip->addFile($r_file);

		$zip->close();
		header("location:$filename");
		break;
	case 'ban':
		is_admin();
		$ip='';
		$ip=$_GET['ip'];
		if (!isset($ip) || $ip=="" || ip_valid($ip)==false)
		{
			header("location:index.php?action=admin&subtab=message");
			exit;
		}
		if(is_baned($ip))
		{
			header("location:index.php?action=admin&subtab=ban_ip");
			exit;
		}
		$insert_string=$ip."\n";
		$maple->writeover($maple->_ban_ip_file,$insert_string,'ab');
		header("location:index.php?action=admin&subtab=ban_ip");
		break;
	case 'ip_update':
		is_admin();
		@$ip_update_array=$_POST['select_mid'];
		if(!$ip_update_array)
		{
			header("location:index.php?action=admin&subtab=ban_ip");
			exit;
		}
		$ip_array=$maple->get_baned_ips();
		$new_ip_array=array_diff($ip_array,$ip_update_array);
		$new_ip_string=implode("\n",$new_ip_array);
		$maple->writeover($maple->_ban_ip_file,$new_ip_string);
		header("location:index.php?action=admin&subtab=ban_ip");
		break;
	default:
		if ($mb_open==1)
		{
			$maple->showerror("$close_reason");
			exit;
		}
		$current_page=isset($_GET['pid'])?(int)$_GET['pid']:0;
		$data=$maple->get_data($current_page);
		
		$nums=$maple->count_nums($maple->_m_file);
		$pages=ceil($nums/$maple->_num_perpage);
		$smileys=$maple->show_smileys_table();
		
		$ip_addr=$_SERVER['REMOTE_ADDR'];
		
		$admin=isset($_SESSION['admin'])?true:false;
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
		$tpl->assign('smileys',$smileys);
		$tpl->assign('mp_version',$mapleleaf_version);
		$tpl->assign('current_ip',$ip_addr);
		$tpl->display("index.html");
		
}
function is_admin()
{
	if (!isset($_SESSION['admin']))
	{
		header("location:index.php?action=login_window");
		exit;
	}
}
function is_baned($ip)
{
	global $maple;
	$all_baned_ips=array();
	$all_baned_ips=$maple->get_baned_ips();
	if (in_array($ip,$all_baned_ips))
	{
		return true;
	}
	else 
	{
		return false;
	}
}
?>