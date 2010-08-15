<?php
/**
 * Controller
 * @author      rainyjune<dreamneverfall@gmail.com>
 * @copyright   Copyright (c) 2008 - 2010 Maple Group. (http://maple.dreamneverfall.cn)
 * @license     GPL2
 * @version     2010-07-19
 */
include_once 'JuneTxtDb.class.php';
include_once 'Imgcode.php';
class Maple_Controller
{
    public  $_imgcode; //FLEA_Helper_ImgCode 实例
    public  $_model;// Maple_Data_Processor 实例，用于处理数据
    public  $_time_zone;//时区
    public  $_admin_name;//管理员username
    public  $_admin_password;//管理员密码
    public  $_board_name;//留言板名称
    public  $_mb_open;//是否关闭
    public  $_close_reason;//关闭原因
    public  $_admin_email;//     * 站长EMAIL
    public  $_copyright_info;//     * 站点版权信息
    public  $_filter_words;//     * 过滤词汇
    public  $_valid_code_open;//     * 验证码是否启用
    public  $_page_on;//     * 是否启用分页
    public  $_num_perpage;//     * 每页显示的信息数
    public  $_theme;//     * 当前的页面主题
    public  $_site_conf_file='config.php';//     * 站点配置文件位置
    public  $_themes_directory='themes/';//     * 主题文件的目录
    public	$_lang_directory='lang';//语言包位置
    public  $_smileys_dir='misc/';//     * 表情图片所在的文件夹位置
    public  $_errors=array();//     * 保存错误信息
    public  $_dbname='mapleleaf';//数据库名称
    public  $_message_table_name='gb';//留言信息数据表名称
    public  $_reply_table_name='reply';//回复数据表名称
    public  $_banedip_table_name='ban';//被禁止的IP列表的数据表的名称
    public  $_smileys = array(
    //	smiley			image name						width	height	title
	':-)'			=>	array('grin.gif',			'19',	'19',	'grin'),
	':lol:'			=>	array('lol.gif',			'19',	'19',	'LOL'),
	':cheese:'		=>	array('cheese.gif',			'19',	'19',	'cheese'),
	':)'			=>	array('smile.gif',			'19',	'19',	'smile'),
	';-)'			=>	array('wink.gif',			'19',	'19',	'wink'),
	';)'			=>	array('wink.gif',			'19',	'19',	'wink'),
	':smirk:'		=>	array('smirk.gif',			'19',	'19',	'smirk'),
	':roll:'		=>	array('rolleyes.gif',		'19',	'19',	'rolleyes'),
	':-S'			=>	array('confused.gif',		'19',	'19',	'confused'),
	':wow:'			=>	array('surprise.gif',		'19',	'19',	'surprised'),
	':bug:'			=>	array('bigsurprise.gif',	'19',	'19',	'big surprise'),
	':-P'			=>	array('tongue_laugh.gif',	'19',	'19',	'tongue laugh'),
	'%-P'			=>	array('tongue_rolleye.gif',	'19',	'19',	'tongue rolleye'),
	';-P'			=>	array('tongue_wink.gif',	'19',	'19',	'tongue wink'),
	':P'			=>	array('raspberry.gif',		'19',	'19',	'raspberry'),
	':blank:'		=>	array('blank.gif',			'19',	'19',	'blank stare'),
	':long:'		=>	array('longface.gif',		'19',	'19',	'long face'),
	':ohh:'			=>	array('ohh.gif',			'19',	'19',	'ohh'),
	':grrr:'		=>	array('grrr.gif',			'19',	'19',	'grrr'),
	':gulp:'		=>	array('gulp.gif',			'19',	'19',	'gulp'),
	'8-/'			=>	array('ohoh.gif',			'19',	'19',	'oh oh'),
	':down:'		=>	array('downer.gif',			'19',	'19',	'downer'),
	':red:'			=>	array('embarrassed.gif',	'19',	'19',	'red face'),
	':sick:'		=>	array('sick.gif',			'19',	'19',	'sick'),
	':shut:'		=>	array('shuteye.gif',		'19',	'19',	'shut eye'),
	':-/'			=>	array('hmm.gif',			'19',	'19',	'hmmm'),
	'&amp;gt;:('	=>	array('mad.gif',			'19',	'19',	'mad'),
	':mad:'			=>	array('mad.gif',			'19',	'19',	'mad'),
	'&amp;gt;:-('	=>	array('angry.gif',			'19',	'19',	'angry'),
	':angry:'		=>	array('angry.gif',			'19',	'19',	'angry'),
	':zip:'			=>	array('zip.gif',			'19',	'19',	'zipper'),
	':kiss:'		=>	array('kiss.gif',			'19',	'19',	'kiss'),
	':ahhh:'		=>	array('shock.gif',			'19',	'19',	'shock'),
	':coolsmile:'	=>	array('shade_smile.gif',	'19',	'19',	'cool smile'),
	':coolsmirk:'	=>	array('shade_smirk.gif',	'19',	'19',	'cool smirk'),
	':coolgrin:'	=>	array('shade_grin.gif',		'19',	'19',	'cool grin'),
	':coolhmm:'		=>	array('shade_hmm.gif',		'19',	'19',	'cool hmm'),
	':coolmad:'		=>	array('shade_mad.gif',		'19',	'19',	'cool mad'),
	':coolcheese:'	=>	array('shade_cheese.gif',	'19',	'19',	'cool cheese'),
	':vampire:'		=>	array('vampire.gif',		'19',	'19',	'vampire'),
	':snake:'		=>	array('snake.gif',			'19',	'19',	'snake'),
	':exclaim:'		=>	array('exclaim.gif',		'19',	'19',	'excaim'),
	':question:'	=>	array('question.gif',		'19',	'19',	'question') // no comma after last item
	);

    //构造函数
    function  __construct()
    {
        $this->_imgcode=new FLEA_Helper_ImgCode();
        $this->_model=new JuneTxtDb();
        $this->load_config();//载入配置
        if($this->_errors)//若有错误显示错误信息
            $this->show_message($this->_errors);
        $this->is_baned($_SERVER['REMOTE_ADDR']);//检查是否被禁止登录
    }

    //载入配置
    function load_config()
    {
        //check directories
        if (!is_dir($this->_themes_directory))
        	die("您主题目录无效");
        if(!is_dir($this->_smileys_dir))
            $this->_errors[]="您所指定的表情图案目录 {$this->_smileys_dir} 不存在";
        //check config file
        if(!file_exists($this->_site_conf_file))
            $this->_errors[]="你所指定的配置文件 {$this->_site_conf_file} 不存在";
        if(!is_writable($this->_site_conf_file))
            $this->_errors[]="你所指定的配置文件 {$this->_site_conf_file} 不可写";
        $this->get_all_info();
        if($this->_errors)
            $this->show_message($this->_errors);
    }

    function get_all_info()
    {
        $this->get_board_name();
        $this->get_mb_open();
        $this->get_close_reason();
        $this->get_admin_email();
        $this->get_copyright_info();
        $this->get_filter_words();
        $this->get_valid_code_open();
        $this->get_page_on();
        $this->get_num_perpage();
        $this->get_theme();
        $this->get_time_zone();
        $this->get_admin_name();
        $this->get_admin_password();
    }

    function is_baned($ip,$check=false)
    {
        $all_baned_ips=array();
        $all_baned_ips=$this->get_baned_ips();
        for($i=0,$c=count($all_baned_ips);$i<$c;$i++)
        {
        	$all_baned_ips[$i]=trim($all_baned_ips[$i]["ip"]);
        }
        if (in_array($ip,$all_baned_ips))
        {
            if($check)
                return TRUE;
            $this->show_message("你被管理员禁止登录！");
        }
    }
	/**
	 * 显示信息
	 */
    function show_message($msg,$redirect=false,$redirect_url='index.php',$time_delay=3)
    {
        include 'themes/'.$this->_theme.'/templates/'."show_message.php";
        exit;
    }
	/**
	 * 得到所有可用的主题
	 */
    public function get_all_themes()
    {
        $themes=array();
        $d=dir($this->_themes_directory);
        while(false!==($entry=$d->read()))
        {
            if(substr($entry,0,1)!='.')
                $themes[$entry]=$entry;
        }
        $d->close();
        return $themes;
    }
    function set_config()
    {
        is_admin();
        file_put_contents($this->_site_conf_file, '<?php');
        $this->set_board_name();
        $this->set_mb_open();
        $this->set_close_reason();
        $this->set_admin_email();
        $this->set_copyright_info();
        $this->set_filter_words();
        $this->set_valid_code_open();
        $this->set_page_on();
        $this->set_num_perpage();
        $this->set_theme();
        $this->set_admin_name();
        $this->set_admin_password();
        $this->set_time_zone();
        header("location:index.php?action=control_panel&subtab=siteset");
    }
    public function maple_quotes($var)
    {
        return htmlspecialchars(trim($var),ENT_QUOTES);
    }

    public function get_board_name()
    {
        include $this->_site_conf_file;
        if(isset ($board_name))
            $this->_board_name=$board_name;
        else
            $this->_errors[]="留言板名称没有设置";
    }

    private function set_board_name()
    {
        is_admin();
        $board_name=$_POST['board_name']?$this->maple_quotes($_POST['board_name']):'风舞六月';
        $str='';
        $str="\n\$board_name='$board_name';";
        $this->_model->_writeover($this->_site_conf_file,$str, 'ab');
    }

    public function get_mb_open()
    {
        include $this->_site_conf_file;
        if(isset ($mb_open))
            $this->_mb_open=$mb_open;
        else
            $this->_errors[]="留言板状态没有设置";
    }

    private function set_mb_open()
    {
        is_admin();
        $mb_open=$_POST['mb_open']?(int)$_POST['mb_open']:0;
        $str="\n\$mb_open=$mb_open;";
        $this->_model->_writeover($this->_site_conf_file, $str, 'ab');
    }

    public function get_close_reason()
    {
        include $this->_site_conf_file;
        if(isset ($close_reason))
            $this->_close_reason=$close_reason;
        else
            $this->_errors[]="关闭原因没有设置";
    }

    private function set_close_reason()
    {
        is_admin();
        $close_reason=$this->maple_quotes($_POST['close_reason']);
        $str="\n\$close_reason='$close_reason';";
        $this->_model->_writeover($this->_site_conf_file, $str, 'ab');
    }

    public function get_admin_email()
    {
        include $this->_site_conf_file;
        if(isset ($admin_email))
            $this->_admin_email=$admin_email;
        else
            $this->_errors[]="管理员Email没有填写";
    }

    private function set_admin_email()
    {
        is_admin();
        $admin_email=$_POST['admin_email']?$this->maple_quotes($_POST['admin_email']):'rainyjune@live.cn';
        $str="\n\$admin_email='$admin_email';";
        $this->_model->_writeover($this->_site_conf_file, $str, 'ab');
    }
    public function get_copyright_info()
    {
        include $this->_site_conf_file;
        if (isset ($copyright_info))
            $this->_copyright_info=$copyright_info;
        else
            $this->_errors[]="版权信息没有填写";
    }

    private function set_copyright_info()
    {
        is_admin();
        @$copyright_info=$_POST['copyright_info']?$this->maple_quotes($_POST['copyright_info']):'Copyright &copy; 2010 dreamneverfall.cn';
        $str="\n\$copyright_info='$copyright_info';";
        $this->_model->_writeover($this->_site_conf_file, $str, 'ab');
    }
    public function get_filter_words()
    {
        include $this->_site_conf_file;
        if(isset ($filter_words))
            $this->_filter_words=$filter_words;
        else
            $this->_errors[]="没有填写过滤词语";
    }

    private function set_filter_words()
    {
        is_admin();
        $filter_words=$_POST['filter_words']?$this->fix_filter_string($_POST['filter_words']):'';
        $str="\n\$filter_words='$filter_words';";
        $this->_model->_writeover($this->_site_conf_file, $str, 'ab');
    }

    public function get_valid_code_open()
    {
        include $this->_site_conf_file;
        if(isset ($valid_code_open))
            $this->_valid_code_open=$valid_code_open;
        else
            $this->_errors[]="验证码状态没有正确设置";
    }

    private function set_valid_code_open()
    {
        is_admin();
        $valid_code_open=(int)$_POST['valid_code_open'];
        $str="\n\$valid_code_open='$valid_code_open';";
        $this->_model->_writeover($this->_site_conf_file, $str, 'ab');
    }
    public function get_page_on()
    {
        include $this->_site_conf_file;
        if(isset ($page_on))
            $this->_page_on=$page_on;
        else
            $this->_errors[]="分页状态没有正确设置";
    }

    private function set_page_on()
    {
        is_admin();
        $page_on=(int)$_POST['page_on'];
        $str="\n\$page_on='$page_on';";
        $this->_model->_writeover($this->_site_conf_file, $str, 'ab');
    }
    public function get_num_perpage()
    {
        include $this->_site_conf_file;
        if(isset ($num_perpage))
            $this->_num_perpage=$num_perpage;
        else
            $this->_errors[]="分页参数没有正确设置";
    }

    private function set_num_perpage()
    {
        is_admin();
        $num_perpage=(int)$_POST['num_perpage'];
        $str="\n\$num_perpage='$num_perpage';";
        $this->_model->_writeover($this->_site_conf_file, $str, 'ab');
    }

    public function get_theme()
    {
        include $this->_site_conf_file;
        if(isset ($theme))
            $this->_theme=$theme;
        else
            $this->_errors[]="主题没有正确设置";
    }

    private function set_theme()
    {
        is_admin();
        $theme=in_array($_POST['theme'], $this->get_all_themes())?$_POST['theme']:'simple';
        $str="\n\$theme='$theme';";
        $this->_model->_writeover($this->_site_conf_file, $str, 'ab');
    }

    public function get_time_zone()
    {
        include $this->_site_conf_file;
        if(isset ($timezone))
            $this->_time_zone=$timezone;
        else
            $this->_errors[]="时区没有正确设置";
    }

    private function set_time_zone()
    {
        is_admin();
        $timezone=(isset($_POST['timezone']) && in_array($_POST['timezone'],array_keys(get_all_timezone())))?$_POST['timezone']:'0';
        $str="\n\$timezone='$timezone';";
        $this->_model->_writeover($this->_site_conf_file, $str, 'ab');
    }
    public function get_admin_name()
    {
        include $this->_site_conf_file;
        if(isset ($admin))
            $this->_admin_name=$admin;
        else
            $this->_errors[]="管理员名字没有正确设置";
    }

    private function set_admin_name()
    {
        is_admin();
        $str="\n\$admin='admin';";
        $this->_model->_writeover($this->_site_conf_file, $str, 'ab');
    }
    public function get_admin_password()
    {
        include $this->_site_conf_file;
        if(isset ($password))
            $this->_admin_password=$password;
        else
            $this->_errors[]="管理员密码没有正确设置";
    }

    private function set_admin_password()
    {
        is_admin();
        $password=isset($_POST['password']) && !empty($_POST['password'])?$this->maple_quotes($_POST['password']):$this->_admin_password;
        $str="\n\$password='$password';";
        $this->_model->_writeover($this->_site_conf_file, $str, 'ab');
    }

    function index()
    {
        if ($this->_mb_open==1)
            $this->show_message($this->_close_reason);
        $data=$this->get_all_data(TRUE,TRUE);
        $current_page=isset($_GET['pid'])?(int)$_GET['pid']:0;
        $nums=$this->_model->june_num_rows($data);
        $pages=ceil($nums/$this->_num_perpage);
        if($current_page>=$pages)
            $current_page=$pages-1;
        if($current_page<0)
            $current_page=0;
        if($this->_page_on)
            $data=$this->page_wrapper($data, $current_page);
        $smileys=$this->show_smileys_table();
        $admin=isset($_SESSION['admin'])?true:false;
        include 'themes/'.$this->_theme.'/templates/'."index.php";
    }

    function page_wrapper($data,$current_page)
    {
        $start=$current_page*$this->_num_perpage;
        $data=array_slice($data,$start,$this->_num_perpage);
        return $data;
    }
    
    function login()
    {
        if (isset($_SESSION['admin']))
        {
            header("location:index.php?action=control_panel");
            exit;
        }
        if(isset($_POST['user']) && isset($_POST['password']))
        {
			if($_POST['user']==$this->_admin_name && htmlspecialchars($_POST['password'],ENT_QUOTES)==$this->_admin_password)
			{
				$_SESSION['admin']=$_POST['user'];
				header("Location:index.php?action=control_panel");
				exit;
			}
			else
				$errormsg="错误：无效用户或密码.";
        }
		include 'themes/'.$this->_theme.'/templates/'."login.php";
        exit;
    }

    function logout()
    {
        $old_user='';
        if(isset($_SESSION['admin']))
        {
            $this->delete_backup_files();
            $old_user=$_SESSION['admin'];
            unset($_SESSION['admin']);
            session_destroy();
        }
        include 'themes/'.$this->_theme.'/templates/'."logout.php";
    }

    function post()
    {
        $new_message=$this->add_message_check();
        if (!$this->_model->june_connect())
		    die($this->_model->june_error());
		if (!$this->_model->june_select_db($this->_dbname))
		    die($this->_model->june_error());
		if (!$this->_model->june_query_insert($this->_message_table_name,$new_message))
		    die($this->_model->june_error());
        header("Location:index.php");
    }

	protected function loadModel()
	{
		if(!isset($_GET['mid']))
        {
            header("location:index.php?action=control_panel&subtab=message");
            exit;
        }
		$mid=(int)$_GET['mid'];
		if (!$this->_model->june_connect())
		    die($this->_model->june_error());
		if (!$this->_model->june_select_db($this->_dbname))
		    die($this->_model->june_error());
		$condition=array('id'=>$mid);
		$reply_data=$this->_model->june_query_select_byCondition($this->_reply_table_name,$condition);
		if ($reply_data===FALSE)
                $this->show_message("查询出错",TRUE,'index.php?action=control_panel&subtab=message');
        else
			return $reply_data;
	}
	//Reply
    function reply()
    {
        is_admin();
		if(isset($_POST['Submit']))
		{
			$mid=0;
			$mid=(int)$_POST['mid'];
			$reply_content = htmlspecialchars(trim($_POST['reply_content']));
			$reply_content = nl2br($reply_content);
			$reply_content = str_replace(array("\n", "\r\n", "\r"), '', $reply_content);
			$time=time();
			if (trim($reply_content)=='')
				$this->show_message('回复不可以为空',true,'index.php?action=control_panel&subtab=message',3);
			if(isset($_POST['update']))
			{
				$input=array($mid,$reply_content,$time);
				if (!$this->_model->june_connect())
				    die($this->_model->june_error());
				if (!$this->_model->june_select_db($this->_dbname))
				    die($this->_model->june_error());
				$condition=array('id'=>$mid);
				if(!$this->_model->june_query_modify($this->_reply_table_name,$condition,'U',$input))
				    die($this->_model->june_error());
			}
			else
			{
				$input=array($mid,$reply_content,$time);
				if (!$this->_model->june_connect())
				    die($this->_model->june_error());
				if (!$this->_model->june_select_db($this->_dbname))
				    die($this->_model->june_error());
				if (!$this->_model->june_query_insert($this->_reply_table_name,$input))
    				die($this->_model->june_error());
			}
			header("Location:index.php?action=control_panel&subtab=message");
		}
		$reply_data=$this->loadModel();
		$mid=(int)$_GET['mid'];
		include 'themes/'.$this->_theme.'/templates/'."reply.php";
    }

    function update()
    {
        is_admin();
		if(isset($_POST['Submit']))
		{
			$mid=0;
			$mid=(int)$_POST['mid'];
			$author=$_POST['author'];
			$m_time=$_POST['m_time'];
			$update_content = htmlspecialchars(trim($_POST['update_content']),ENT_COMPAT,'UTF-8');
			$update_content = nl2br($update_content);
			$update_content = str_replace(array("\n", "\r\n", "\r"), '', $update_content);
			$ip=$_POST['ip'];
			$input=array($mid,$author,$update_content,$m_time,$ip);
			if (!$this->_model->june_connect())
			    die($this->_model->june_error());
			if (!$this->_model->june_select_db($this->_dbname))
			    die($this->_model->june_error());
			$condition=array('id'=>$mid);
			if(!$this->_model->june_query_modify($this->_message_table_name,$condition,'U',$input))
			    die($this->_model->june_error());
			else
				header("Location:index.php?action=control_panel&subtab=message");
		}
		if(!isset($_GET['mid']))
		{
            header("location:index.php?action=control_panel&subtab=message");exit;
		}
        $mid=intval($_GET['mid']);
        if (!$this->_model->june_connect())
		    die($this->_model->june_error());
		if (!$this->_model->june_select_db($this->_dbname))
		    die($this->_model->june_error());
		$condition=array('id'=>$mid);
        $message_info=$this->_model->june_query_select_byCondition($this->_message_table_name,$condition);
        if(!$message_info)
            $this->show_message("查询出错",TRUE,'index.php?action=control_panel&subtab=message');
        include 'themes/'.$this->_theme.'/templates/'."update.php";
    }

    function delete_multi_messages()
    {
        is_admin();
        @$del_ids=$_POST['select_mid']?$_POST['select_mid']:array();
        // Check whether admin had selected some options
        if($del_ids==array())
        {
            header("location:index.php?action=control_panel&subtab=message");
            exit;
        }
        $del_num=count($del_ids);
        for($i=0;$i<$del_num;$i++)
        {
            $deleted_id=(int)$del_ids[$i];
            $this->delete_message($deleted_id);
            if ($_POST[$deleted_id]==1)
                $this->delete_reply($deleted_id);
        }
        header("Location:index.php?action=control_panel&subtab=message&randomvalue=".rand());
    }
    function delete_message($from_function=false)
    {
        is_admin();
        if($from_function)
            $mid=$from_function;
        else
            $mid=intval($_GET['mid']);
        if(isset($mid))
        {
        	if (!$this->_model->june_connect())
			    die($this->_model->june_error());
			if (!$this->_model->june_select_db($this->_dbname))
			    die($this->_model->june_error());
			$condition=array('id'=>$mid);
			if(!$this->_model->june_query_modify($this->_message_table_name,$condition,'D'))
			    die($this->_model->june_error());
        }
        //若回复中有关于此留言的记录，执行删除回复操作
        @$reply_del=(int)$_GET['reply'];
        if($reply_del==1)
            $this->delete_reply($mid);
        header("Location:index.php?action=control_panel&subtab=message&randomvalue=".rand());
    }

    function delete_reply($from_delete_message=false)
    {
        is_admin();
        if($from_delete_message)
            $mid=$from_delete_message;
        else
            $mid=intval($_GET['mid']);
        if(isset($mid))
        {
        	if (!$this->_model->june_connect())
			    die($this->_model->june_error());
			if (!$this->_model->june_select_db($this->_dbname))
			    die($this->_model->june_error());
			$condition=array('id'=>$mid);
			if(!$this->_model->june_query_modify($this->_reply_table_name,$condition,'D'))
			    die($this->_model->june_error());
        }
        header("Location:index.php?action=control_panel&subtab=message&randomvalue=".rand());
    }

    function clear_all()
    {
        is_admin();
        $message_table_path=$this->_model->_db_root_dir.$this->_dbname."/".$this->_message_table_name;
        
        $message_filename=$message_table_path.$this->_model->_data_ext;
        file_put_contents($message_filename, '');
        $this->clear_reply();
        header("location:index.php?action=control_panel&subtab=message");
    }

    function clear_reply()
    {
        is_admin();
        $reply_table_path=$this->_model->_db_root_dir.$this->_dbname."/".$this->_reply_table_name;
        $reply_filename=$reply_table_path.$this->_model->_data_ext;
        file_put_contents($reply_filename,'');
        header("location:index.php?action=control_panel&subtab=message");
    }

    function control_panel()
    {
        global $gd_exist,$isSafeMode,$isUrlOpen,$zip_support;
        is_admin();
        // Which tab should be displayed?
        $current_tab='overview';
        $tabs_array=array('overview','siteset','message','ban_ip');
        if(isset($_GET['subtab']))
        {
			if(in_array($_GET['subtab'],$tabs_array))
				$current_tab=$_GET['subtab'];
        }
        $themes=$this->get_all_themes();
        $data=$this->get_all_data();
        $reply_data=$this->get_all_reply();
        $ban_ip_info=$this->get_baned_ips();

        $nums=$this->_model->june_num_rows($data);
        $reply_num=$this->_model->june_num_rows($reply_data);

        if($gd_exist)
		{
            $gd_info=gd_info();
            if (defined(GD_VERSION))
				$gd_version=GD_VERSION;
            elseif ($gd_info)
                $gd_version=$gd_info['GD Version'];
            else
				$gd_version='<font color="red">未知</font>';
        }
        else
            $gd_version='<font color="red">GD不支持</font>';
        $isSafeMode=$isSafeMode ? 'On' : 'Off';
        $register_globals=ini_get("register_globals") ? 'On' : 'Off';
        $magic_quotes_gpc=ini_get("magic_quotes_gpc") ? 'On' : 'Off';
        $allow_url_fopen=ini_get("allow_url_fopen") ? 'On' : 'Off';
		$timezone_array=get_all_timezone();
        include 'themes/'.$this->_theme.'/templates/'."admin.php";
    }

    function backup()
    {
        is_admin();
        $dir="data/{$this->_dbname}/";
        if(!class_exists('ZipArchive'))
        {
            $this->show_message("你的服务器不支持此功能！",true,'index.php?action=control_panel&subtab=message');
            exit;
        }
        $zip = new ZipArchive();
        $filename = $dir."backup-".date('Ymd',time()).".zip";

        if ($zip->open($filename, ZIPARCHIVE::CREATE)!==TRUE)
			exit("cannot open <$filename>\n");
        $d=dir($dir);
        while(false!==($entry=$d->read()))
        {
            if(substr($entry,0,1)!='.')
                $zip->addFile($dir.$entry);
        }
        $d->close();
        $zip->close();
        header("location:$filename");
    }

    function ban()
    {
        is_admin();
        $ip='';
        $ip=$_GET['ip'];
        if (!isset($ip) || $ip=="" || ip_valid($ip)==false)
        {
            header("location:index.php?action=control_panel&subtab=message");
            exit;
        }
        if($this->is_baned($ip,TRUE))
        {
            header("location:index.php?action=control_panel&subtab=ban_ip");
            exit;
        }
        $insert_string=$ip."\n";
        $ip_filename=$this->_model->_db_root_dir.$this->_dbname.'/'.$this->_banedip_table_name.$this->_model->_data_ext;
        file_put_contents($ip_filename, $insert_string, FILE_APPEND | LOCK_EX);
        header("location:index.php?action=control_panel&subtab=ban_ip");
    }

    function ip_update()
    {
        is_admin();
        @$ip_update_array=$_POST['select_ip'];
        if(!$ip_update_array)
        {
            header("location:index.php?action=control_panel&subtab=ban_ip");
            exit;
        }
        $ip_array=$this->get_baned_ips();
        for($i=0,$c=count($ip_array);$i<$c;$i++)
        {
        	$ip_array[$i]=trim($ip_array[$i]["ip"]);
        }
        $new_ip_array=array_diff($ip_array,$ip_update_array);
        $new_ip_string=implode("\n",$new_ip_array);
        if ($new_ip_array) 
        	$new_ip_string.="\n";;        
        $ip_filename=$this->_model->_db_root_dir.$this->_dbname.'/'.$this->_banedip_table_name.$this->_model->_data_ext;
        file_put_contents($ip_filename, $new_ip_string);
        header("location:index.php?action=control_panel&subtab=ban_ip");
    }
	/**
	 * 显示表情
	 */
	function show_smileys_table()
	{
		return  <<<EOF
		<table cellpadding="4" cellspacing="0">
		<tr>
		<td><a href="javascript:void(0);" onclick="insert_smiley(':-)')"><img src="{$this->_smileys_dir}grin.gif" alt="grin" title="grin" class="smiley_img" /></a></td><td><a href="javascript:void(0);" onclick="insert_smiley(':lol:')"><img src="{$this->_smileys_dir}lol.gif" class="smiley_img" title="LOL" alt="lol"  /></a></td><td><a href="javascript:void(0);" onclick="insert_smiley(':cheese:')"><img src="{$this->_smileys_dir}cheese.gif" title="cheese" alt="cheese" class="smiley_img" /></a></td><td><a href="javascript:void(0);" onclick="insert_smiley(':)')"><img src="{$this->_smileys_dir}smile.gif" title="smile" alt="smile" class="smiley_img" /></a></td><td><a href="javascript:void(0);" onclick="insert_smiley(';-)')"><img src="{$this->_smileys_dir}wink.gif" title="wink" alt="wink" class="smiley_img" /></a></td><td><a href="javascript:void(0);" onclick="insert_smiley(':smirk:')"><img src="{$this->_smileys_dir}smirk.gif" title="smirk" alt="smirk" class="smiley_img"/></a></td><td><a href="javascript:void(0);" onclick="insert_smiley(':roll:')"><img src="{$this->_smileys_dir}rolleyes.gif" title="rolleyes" alt="rolleyes" class="smiley_img" /></a></td><td><a href="javascript:void(0);" onclick="insert_smiley(':-S')"><img src="{$this->_smileys_dir}confused.gif" title="confused" alt="confused" class="smiley_img" /></a></td></tr>
		<tr>
		<td><a href="javascript:void(0);" onclick="insert_smiley(':wow:')"><img src="{$this->_smileys_dir}surprise.gif" title="surprised" alt="surprised" class="smiley_img" /></a></td><td><a href="javascript:void(0);" onclick="insert_smiley(':bug:')"><img src="{$this->_smileys_dir}bigsurprise.gif" title="big surprise" alt="big surprise" class="smiley_img" /></a></td><td><a href="javascript:void(0);" onclick="insert_smiley(':-P')"><img src="{$this->_smileys_dir}tongue_laugh.gif" title="tongue laugh" alt="tongue laugh" class="smiley_img" /></a></td><td><a href="javascript:void(0);" onclick="insert_smiley('%-P')"><img src="{$this->_smileys_dir}tongue_rolleye.gif" class="smiley_img" title="tongue rolleye" alt="tongue rolleye" /></a></td><td><a href="javascript:void(0);" onclick="insert_smiley(';-P')"><img src="{$this->_smileys_dir}tongue_wink.gif" class="smiley_img" title="tongue wink" alt="tongue wink" style="border:0;" /></a></td><td><a href="javascript:void(0);" onclick="insert_smiley(':P')"><img src="{$this->_smileys_dir}raspberry.gif" class="smiley_img" title="raspberry" alt="raspberry" style="border:0;" /></a></td><td><a href="javascript:void(0);" onclick="insert_smiley(':blank:')"><img src="{$this->_smileys_dir}blank.gif" class="smiley_img" title="blank stare" alt="blank stare" style="border:0;" /></a></td><td><a href="javascript:void(0);" onclick="insert_smiley(':long:')"><img src="{$this->_smileys_dir}longface.gif" class="smiley_img" title="long face" alt="long face" style="border:0;" /></a></td></tr>
		<tr>
		<td><a href="javascript:void(0);" onclick="insert_smiley(':ohh:')"><img src="{$this->_smileys_dir}ohh.gif" class="smiley_img" title="ohh" alt="ohh" style="border:0;" /></a></td><td><a href="javascript:void(0);" onclick="insert_smiley(':grrr:')"><img src="{$this->_smileys_dir}grrr.gif" class="smiley_img" title="grrr" alt="grrr"  /></a></td><td><a href="javascript:void(0);" onclick="insert_smiley(':gulp:')"><img src="{$this->_smileys_dir}gulp.gif" class="smiley_img" title="gulp" alt="gulp"  /></a></td><td><a href="javascript:void(0);" onclick="insert_smiley('8-/')"><img src="{$this->_smileys_dir}ohoh.gif" class="smiley_img" title="oh oh" alt="oh oh"  /></a></td><td><a href="javascript:void(0);" onclick="insert_smiley(':down:')"><img src="{$this->_smileys_dir}downer.gif" class="smiley_img" title="downer" alt="downer"  /></a></td><td><a href="javascript:void(0);" onclick="insert_smiley(':red:')"><img src="{$this->_smileys_dir}embarrassed.gif" class="smiley_img" title="red face" alt="red face"  /></a></td><td><a href="javascript:void(0);" onclick="insert_smiley(':sick:')"><img src="{$this->_smileys_dir}sick.gif" class="smiley_img" title="sick" alt="sick"  /></a></td><td><a href="javascript:void(0);" onclick="insert_smiley(':shut:')"><img src="{$this->_smileys_dir}shuteye.gif" class="smiley_img" title="shut eye" alt="shut eye"  /></a></td></tr>
		<tr>
		<td><a href="javascript:void(0);" onclick="insert_smiley(':-/')"><img src="{$this->_smileys_dir}hmm.gif" class="smiley_img" title="hmmm" alt="hmmm"  /></a></td><td><a href="javascript:void(0);" onclick="insert_smiley('>:(')"><img src="{$this->_smileys_dir}mad.gif" class="smiley_img" title="mad" alt="mad"  /></a></td><td><a href="javascript:void(0);" onclick="insert_smiley('>:-(')"><img src="{$this->_smileys_dir}angry.gif" class="smiley_img" title="angry" alt="angry"  /></a></td><td><a href="javascript:void(0);" onclick="insert_smiley(':zip:')"><img src="{$this->_smileys_dir}zip.gif" class="smiley_img" title="zipper" alt="zipper"  /></a></td><td><a href="javascript:void(0);" onclick="insert_smiley(':kiss:')"><img src="{$this->_smileys_dir}kiss.gif" class="smiley_img" title="kiss" alt="kiss"  /></a></td><td><a href="javascript:void(0);" onclick="insert_smiley(':ahhh:')"><img src="{$this->_smileys_dir}shock.gif" class="smiley_img" title="shock" alt="shock"  /></a></td><td><a href="javascript:void(0);" onclick="insert_smiley(':coolsmile:')"><img src="{$this->_smileys_dir}shade_smile.gif" class="smiley_img" title="cool smile" alt="cool smile"  /></a></td><td><a href="javascript:void(0);" onclick="insert_smiley(':coolsmirk:')"><img src="{$this->_smileys_dir}shade_smirk.gif" class="smiley_img" title="cool smirk" alt="cool smirk"  /></a></td></tr>
		<tr>
		<td><a href="javascript:void(0);" onclick="insert_smiley(':coolgrin:')"><img src="{$this->_smileys_dir}shade_grin.gif" class="smiley_img" title="cool grin" alt="cool grin"  /></a></td><td><a href="javascript:void(0);" onclick="insert_smiley(':coolhmm:')"><img src="{$this->_smileys_dir}shade_hmm.gif" class="smiley_img" title="cool hmm" alt="cool hmm"  /></a></td><td><a href="javascript:void(0);" onclick="insert_smiley(':coolmad:')"><img src="{$this->_smileys_dir}shade_mad.gif" class="smiley_img" title="cool mad" alt="cool mad"  /></a></td><td><a href="javascript:void(0);" onclick="insert_smiley(':coolcheese:')"><img src="{$this->_smileys_dir}shade_cheese.gif" class="smiley_img" title="cool cheese" alt="cool cheese"  /></a></td><td><a href="javascript:void(0);" onclick="insert_smiley(':vampire:')"><img src="{$this->_smileys_dir}vampire.gif" class="smiley_img" title="vampire" alt="vampire"  /></a></td><td><a href="javascript:void(0);" onclick="insert_smiley(':snake:')"><img src="{$this->_smileys_dir}snake.gif" class="smiley_img" title="snake" alt="snake"  /></a></td><td><a href="javascript:void(0);" onclick="insert_smiley(':exclaim:')"><img src="{$this->_smileys_dir}exclaim.gif" class="smiley_img" title="excaim" alt="excaim"  /></a></td><td><a href="javascript:void(0);" onclick="insert_smiley(':question:')"><img src="{$this->_smileys_dir}question.gif" class="smiley_img" title="question" alt="question"  /></a></td></tr>
		</table>
EOF;
	}

    /**
     * 检查验证码
     */
    function checkImgcode() 
	{
        return $this->_imgcode->check($_POST['valid_code']);
    }

    /**
     * 替换被过滤的词语
     * @param array $filter_words
     */
    function fix_filter_string($filter_words)
    {
		$new_string=trim($filter_words,',');
		$new_string=str_replace(array("\t","\r","\n",'  ',' '),'',$new_string);
		return $new_string;
    }

    /**
     * 得到被禁止的IP列表
     */
    function get_baned_ips()
    {
    	if (!$this->_model->june_connect())
		    die($this->_model->june_error());
		if (!$this->_model->june_select_db($this->_dbname))
		{
		    die($this->_model->june_error());
		}
		if(($result=$this->_model->june_query_select_all($this->_banedip_table_name))===FALSE)
		{
		    die($this->_model->june_error());
		}		
		return $result;
    }

    /**
     * 检查新增信息，并返回处理后的字符串
     *
     * @return string
     */
    function add_message_check()
    {
        $new_data=array();
		$user=isset($_POST['user'])?$_POST['user']:'';
		$current_ip=$_SERVER['REMOTE_ADDR'];
		$user=htmlspecialchars(trim($user),ENT_COMPAT,'UTF-8');
		$admin_name_array=array('admin','root','administrator','管理员');
		if(!isset($_SESSION['admin']) && in_array(strtolower($user),$admin_name_array))
			$user='anonymous';
		$content =isset($_POST['content'])?htmlspecialchars(trim($_POST['content']),ENT_COMPAT,'UTF-8'):'';
		$content = nl2br($content);
		$content = str_replace(array("\n", "\r\n", "\r"), '', $content);
		$time=time();
		if(empty($user) or empty($content))
		{
			$this->show_message("你没有填写完成,现在正在<a href='./index.php'>返回</a>...",true,'index.php');
			exit;
		}
		if(strlen($content)>580)
		{
			$this->show_message("您的话语太多了，现在正在<a href='./index.php'>返回</a>...",true,'index.php');
			exit;
		}
		if($this->_valid_code_open==1)
		{
			if(!$this->checkImgcode())
				$this->show_message("验证码错误.现在正在<a href='./index.php'>返回</a>...",true,'index.php');
		}
		$new_data=array(NULL,$user,$content,$time,$current_ip);
		return $new_data;
    }

    function get_all_data($parse_smileys=true,$filter_words=false)
    {
        $data=array();
        if (!$this->_model->june_connect())
		    die($this->_model->june_error());
		
		if (!$this->_model->june_select_db($this->_dbname))
		    die($this->_model->june_error());
		
		if(($data=$this->_model->june_query_select_all($this->_message_table_name))===FALSE)
		    die($this->_model->june_error());
		    
        if(($reply_data=$this->_model->june_query_select_all($this->_reply_table_name))===FALSE)
		    die($this->_model->june_error());
		$new_reply_data=array();
		foreach($reply_data as $reply_data_item)
		{
			$new_key=$reply_data_item["id"];
			$new_reply_data[$new_key]=$reply_data_item;
		}
        foreach ($data as &$data_per)
        {
            if($filter_words)
                $data_per['content']=$this->filter_words($data_per['content']);
            $mid=intval($data_per['id']);
            if(isset($new_reply_data[$mid]))
            {
                $data_per['reply']=$new_reply_data[$mid];
                /*if($parse_smileys)
                    $data_per['reply']['reply_content']=$this->parse_smileys($data_per['reply']['reply_content'], $this->_smileys_dir,$this->_smileys);*/
            }        
              
        }
        $data=array_reverse($data);
        //var_dump($data);
        return $data;
    }
    /**
     * 过滤敏感词语
     * @param array $input
     */
    function filter_words($input)
    {
		$filter_array=explode(',',$this->_filter_words);
		$input=str_ireplace($filter_array,'***',$input);
		return $input;
    }

    /**
     * 删除服务器上的备份文件，会在管理员注销登录时执行
     */
    function delete_backup_files()
    {
		$d=dir($this->_model->_db_root_dir.$this->_dbname);
		while(false!==($entry=$d->read()))
		{
			if (strlen($entry)==19)
			{
				$d_file=$this->_model->_db_root_dir.$this->_dbname.'/'.$entry;
				@unlink($d_file);
			}
		}
		$d->close();
    }

    function get_all_reply()
    {
        $reply_data=array();
        if (!$this->_model->june_connect())
		    die($this->_model->june_error());
		
		if (!$this->_model->june_select_db($this->_dbname))
		    die($this->_model->june_error());
		
		if(($reply_data=$this->_model->june_query_select_all($this->_reply_table_name))===FALSE)
		    die($this->_model->june_error());
        return $reply_data;
    }
    /**
     * 将表情符号转换为表情图案
     * @param $str
     * @param $image_url
     * @param $smileys
     */
    function parse_smileys($str = '', $image_url = '', $smileys = NULL)
    {
		if ($image_url == '')
			return $str;

		if (!is_array($smileys))
			return $str;

		// Add a trailing slash to the file path if needed
		$image_url = preg_replace("/(.+?)\/*$/", "\\1/",  $image_url);
		foreach ($smileys as $key => $val)
		{
			$str = str_replace($key, "<img src=\"".$image_url.$smileys[$key][0]."\" width=\"".$smileys[$key][1]."\" height=\"".$smileys[$key][2]."\" title=\"".$smileys[$key][3]."\" alt=\"".$smileys[$key][3]."\" style=\"border:0;\" />", $str);
		}
		return $str;
    }
    
    function t($str)
    {
    	$lang=array(
    		'WELCOME'=>'欢迎访问 %s',
    		'WELCOME_POST'=>'欢迎留言'
    		//(Ctrl+Enter提交)
    	);
    	$lang=array(
    		'WELCOME_SYS'=>'Welcome to MapleLeaf',
    		'THANKS'=>'Thanks for using MapleLeaf.',
    		'STATS_INFO'=>'Statics Info',
    		'NUM_POSTS'=>'Number of Posts',
    		'NUM_REPLY'=>'Number of Replies',
    		'MP_VERSION'=>'MapleLeaf Version',
    		'SYS_INFO'=>'System Info',
    		'PHP_VERSION'=>'PHP Version',
    		'GD_VERSION'=>'GD Version',
    		'SAFE_MODE'=>'Safe Mode',
    		'SYS_CONF'=>'System Configuration',
    		'BOARD_NAME'=>'Board Name',
    		'CLOSE_BOARD'=>'Close Site?',
    		'CLOSE_REASON'=>'Close Reason',
    		'COPY_INFO'=>'Copyright Info',
    		'SYS_THEME'=>'Theme',
    		'TIMEZONE'=>'Timezone',
    		'POST_CONF'=>'Post Configuration',
    		'FILTER_WORDS'=>'Bad Words',
    		'ENABLE_CAPTCHA'=>'Enable Captcha?',
    		'ENABLE_PAGE'=>'Enable Pagination?',
    		'POST_PERPAGE'=>'Post each page',
    		'ADMIN_CONF'=>'Admin Account Configuration',
    		'CHANGE_PWD'=>'New Password',
    		'RESET'=>'Reset',
    		'SELECT'=>'Select',
    		'OPERATION'=>'Operation',
    		'REPLY'=>'Reply',
    		'UPDATE'=>'Update',
    		'BAN'=>'Ban',
    		'DELETE'=>'Delete',
    		'YOU_REPLIED'=>'<font color="red">You replied at %s :</font> %s',
    		'DELETE_THIS_REPLY'=>'Delete this reply',
    		'CHECK_ALL'=>'Check All',
    		'CHECK_NONE'=>'Check None',
    		'DELETE_CHECKED'=>'Delete Checked',
    		'DELETE_ALL'=>'Delete All',
    		'DELETE_ALL_REPLY'=>'Delete All Replies',
    		'BACKUP'=>'Backup',
    		'BAD_IP'=>'Bad IP',
    		'CANCEL'=>'Cancel',
    		'TIPS'=>'Tips',
    	////////////////////
    		'WELCOME'=>'Welcome to %s',
    		'WELCOME_POST'=>'Welcome to post',
    		'NICKNAME'=>'NickName',
    		'MESSAGE'=>'Message',
    		'TIME'=>'Time',
    		'ADMIN_REPLIED'=>'<font color="red">Admin replied at %s :</font> %s',
    		'CLICK_POST'=>'Click to post',
    		'CONTENT'=>'Content',
    		'SUBMIT'=>'Submit',
    		'POST_SHORTCUT'=>'Press Ctrl+Enter to post',
    		'ADMIN_EMAIL'=>'Email',
    		'ACP'=>'Admin',
    		'ACP_INDEX'=>'Admin Control Panel',
    		'VALIDATE_CODE'=>'CAPTCHA',
    		'ACP_LOGIN'=>'Admin Control Panel Login',
    		'BACK'=>'Back to Home',
    		'LOGIN'=>'Login',
    		'ADMIN_NAME'=>'Name',
    		'ADMIN_PWD'=>'Password',
    		'HOME'=>'Home',
    		'LOGOUT'=>'Logout',
    		'ACP_OVERVIEW'=>'Overview',
    		'ACP_CONFSET'=>'Configuration',
    		'ACP_MANAGE_POST'=>'Manage Posts',
    		'ACP_MANAGE_IP'=>'Bad Ips'
    	);
    	return str_replace($str,$lang[$str],$str);
    }
    
    function get_all_langs()
    {
    	$langs=array();
        $d=dir($this->_lang_directory);
        while(false!==($entry=$d->read()))
        {
            if(substr($entry,0,1)!='.')
                $langs[]=substr($entry,0,-4);
        }
        $d->close();
        return $langs;
    }
}

class MP_CONTROLLER extends Maple_Controller
{
    function run()
    {
        $action=isset($_GET['action'])?$_GET['action']:'index';
        if(!method_exists($this,$action) || !is_callable(array($this,$action))){$action='index';}
        parent::$action();
    }
}