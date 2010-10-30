<?php
/**
 * Controller
 * @author      rainyjune<dreamneverfall@gmail.com>
 * @copyright   Copyright (c) 2008 - 2010 OurPlanet Team (http://mapleleaf.ourplanet.tk/)
 * @license     GPL2
 * @version     2010-10-29
 */
include_once 'JuneTxtDB.class.php';
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
    public  $_lang_directory='lang';//语言包位置
    public  $_smileys_dir='misc/';//     * 表情图片所在的文件夹位置
    public  $_errors=array();//     * 保存错误信息
    public  $_dbname='mapleleaf';//数据库名称
    public  $_message_table_name='gb';//留言信息数据表名称
    public  $_reply_table_name='reply';//回复数据表名称
    public  $_banedip_table_name='ban';//被禁止的IP列表的数据表的名称
    public  $_current_lang;
    public  $_lang_array;//保存语言翻译信息
    public  $_smileys;
    private $_coreMessage_array;

    //构造函数
    function  __construct()
    {
	$this->_smileys=  require 'smiley.php';//将代表表情图案的数组导入到当前类的属性中
	$this->_coreMessage_array=  require 'coreMessage.php';//将代表核心信息的数组导入到当前类的属性中
        $this->_imgcode=new FLEA_Helper_ImgCode();//实例化代表验证码的类
        $this->_model=new JuneTxtDb();//实例化模型
	$this->_model->select_db($this->_dbname);//选择默认的数据库
        $this->load_config();//载入配置
        if($this->_errors)//若有错误显示错误信息
            $this->show_message($this->_errors);
        $this->is_baned($_SERVER['REMOTE_ADDR']);//检查是否被禁止登录
    }

    //载入配置
    function load_config()
    {
        if (!is_dir($this->_themes_directory))//检查主题目录是否存在
	    die($this->t('THEMES_DIR_NOTEXISTS',true));
        if(!file_exists($this->_site_conf_file))        //先检查配置文件是否存在和可写
            die(sprintf($this->t('CONFIG_FILE_NOTEXISTS',true),$this->_site_conf_file));
        if(!is_writable($this->_site_conf_file))
            die($this->_errors[]=sprintf($this->t('CONFIG_FILE_NOTWRITABLE',true),$this->_site_conf_file));
        //由于出现错误时需要调用 show_message 函数，而此函数依赖于当前的theme,所以需要先得到当前的 theme
    	$this->get_theme();
        if(!is_dir($this->_smileys_dir))
            $this->_errors[]=sprintf($this->t('SMILEY_DIR_NOTEXISTS',true),$this->_smileys_dir);//"您所指定的表情图案目录 {$this->_smileys_dir} 不存在";
        $this->get_all_info();
        if($this->_errors)
	    $this->show_message($this->_errors);
    }

    function get_all_info()
    {
    	
    	$this->get_lang();
        $this->get_board_name();
        $this->get_mb_open();
        $this->get_close_reason();
        $this->get_admin_email();
        $this->get_copyright_info();
        $this->get_filter_words();
        $this->get_valid_code_open();
        $this->get_page_on();
        $this->get_num_perpage();
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
            $this->show_message($this->t('LOGIN_DENIED'));
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
        $this->set_lang();
        header("location:index.php?action=control_panel&amp;subtab=siteset");
    }
    public function maple_quotes($var)
    {
        return htmlspecialchars(trim($var),ENT_QUOTES,  $this->_model->get_charset());
    }

    public function get_board_name()
    {
        include $this->_site_conf_file;
        if(isset ($board_name))
            $this->_board_name=$board_name;
        else
            $this->_errors[]=$this->t('SITENAME_ERROR',true);    
    }

    private function set_board_name()
    {
        is_admin();
        $board_name=$_POST['board_name']?$this->maple_quotes($_POST['board_name']):'MapleLeaf';
        $str='';
        $str="\n\$board_name='$board_name';";
	file_put_contents($this->_site_conf_file, $str,FILE_APPEND);
    }

    public function get_mb_open()
    {
        include $this->_site_conf_file;
        if(isset ($mb_open))
            $this->_mb_open=$mb_open;
        else
            $this->_errors[]=$this->t('SITESTATUS_ERROR',true);
    }

    private function set_mb_open()
    {
        is_admin();
        $mb_open=$_POST['mb_open']?(int)$_POST['mb_open']:0;
        $str="\n\$mb_open=$mb_open;";
        file_put_contents($this->_site_conf_file, $str,FILE_APPEND);
    }

    public function get_close_reason()
    {
        include $this->_site_conf_file;
        if(isset ($close_reason))
            $this->_close_reason=$close_reason;
        else
            $this->_errors[]=$this->t('SITECLOSEREASON_ERROR',true);
    }

    private function set_close_reason()
    {
        is_admin();
        $close_reason=$this->maple_quotes($_POST['close_reason']);
        $str="\n\$close_reason='$close_reason';";
        file_put_contents($this->_site_conf_file, $str,FILE_APPEND);
    }

    public function get_admin_email()
    {
        include $this->_site_conf_file;
        if(isset ($admin_email))
            $this->_admin_email=$admin_email;
        else
            $this->_errors[]=$this->t('ADMINEMAIL_ERROR',true);
    }

    private function set_admin_email()
    {
        is_admin();
        $admin_email=$_POST['admin_email']?$this->maple_quotes($_POST['admin_email']):'rainyjune@live.cn';
        $str="\n\$admin_email='$admin_email';";
        file_put_contents($this->_site_conf_file, $str,FILE_APPEND);
    }
    public function get_copyright_info()
    {
        include $this->_site_conf_file;
        if (isset ($copyright_info))
            $this->_copyright_info=$copyright_info;
        else
            $this->_errors[]=$this->t('COPYRIGHT_ERROR',true);
    }

    private function set_copyright_info()
    {
        is_admin();
        @$copyright_info=$_POST['copyright_info']?$this->maple_quotes($_POST['copyright_info']):'Copyright &copy; 2010 mapleleaf.ourplanet.tk';
        $str="\n\$copyright_info='$copyright_info';";
        file_put_contents($this->_site_conf_file, $str,FILE_APPEND);
    }
    public function get_filter_words()
    {
        include $this->_site_conf_file;
        if(isset ($filter_words))
            $this->_filter_words=$filter_words;
        else
            $this->_errors[]=$this->t('BADWORDS_ERROR',true);
    }

    private function set_filter_words()
    {
        is_admin();
        $filter_words=$_POST['filter_words']?$this->fix_filter_string($_POST['filter_words']):'';
        $str="\n\$filter_words='$filter_words';";
        file_put_contents($this->_site_conf_file, $str,FILE_APPEND);
    }

    public function get_valid_code_open()
    {
        include $this->_site_conf_file;
        if(isset ($valid_code_open))
            $this->_valid_code_open=$valid_code_open;
        else
            $this->_errors[]=$this->t('CAPTCHASTATUS_ERROR',true);
    }

    private function set_valid_code_open()
    {
        is_admin();
        $valid_code_open=(int)$_POST['valid_code_open'];
        $str="\n\$valid_code_open='$valid_code_open';";
        file_put_contents($this->_site_conf_file, $str,FILE_APPEND);
    }
    public function get_page_on()
    {
        include $this->_site_conf_file;
        if(isset ($page_on))
            $this->_page_on=$page_on;
        else
            $this->_errors[]=$this->t('PAGINATIONSTATUS_ERROR',true);
    }

    private function set_page_on()
    {
        is_admin();
        $page_on=(int)$_POST['page_on'];
        $str="\n\$page_on='$page_on';";
        file_put_contents($this->_site_conf_file, $str,FILE_APPEND);
    }
    public function get_num_perpage()
    {
        include $this->_site_conf_file;
        if(isset ($num_perpage))
            $this->_num_perpage=$num_perpage;
        else
            $this->_errors[]=$this->t('PAGINATION_PARAMETER_ERROR',true);
    }

    private function set_num_perpage()
    {
        is_admin();
        $num_perpage=(int)$_POST['num_perpage'];
        $str="\n\$num_perpage='$num_perpage';";
        file_put_contents($this->_site_conf_file, $str,FILE_APPEND);
    }

    public function get_theme()
    {
        include $this->_site_conf_file;
        if(isset ($theme))
            $this->_theme=$theme;
        else
            die($this->t('THEME_ERROR', TRUE));
    }

    private function set_theme()
    {
        is_admin();
        $theme=in_array($_POST['theme'], $this->get_all_themes())?$_POST['theme']:'simple';
        $str="\n\$theme='$theme';";
        file_put_contents($this->_site_conf_file, $str,FILE_APPEND);
    }

    public function get_time_zone()
    {
        include $this->_site_conf_file;
        if(isset ($timezone))
            $this->_time_zone=$timezone;
        else
            $this->_errors[]=$this->t('TIMEZONE_ERROR',true);
    }

    private function set_time_zone()
    {
        is_admin();
        $timezone=(isset($_POST['timezone']) && in_array($_POST['timezone'],array_keys($this->get_all_timezone())))?$_POST['timezone']:'0';
        $str="\n\$timezone='$timezone';";
        file_put_contents($this->_site_conf_file, $str,FILE_APPEND);
    }
    
    private function set_lang()
    {
        is_admin();
        $lang=(isset($_POST['lang']) && in_array($_POST['lang'],$this->get_all_langs()))?$_POST['lang']:'en';
        $str="\n\$lang='$lang';";
        file_put_contents($this->_site_conf_file, $str,FILE_APPEND);
    }
    
    public function get_admin_name()
    {
        include $this->_site_conf_file;
        if(isset ($admin))
            $this->_admin_name=$admin;
        else
            $this->_errors[]=$this->t('ADMINNAME_ERROR',true);
    }

    private function set_admin_name()
    {
        is_admin();
        $str="\n\$admin='admin';";
        file_put_contents($this->_site_conf_file, $str,FILE_APPEND);
    }
    public function get_admin_password()
    {
        include $this->_site_conf_file;
        if(isset ($password))
            $this->_admin_password=$password;
        else
            $this->_errors[]=$this->t('ADMINPASS_ERROR',true);
    }

    private function set_admin_password()
    {
        is_admin();
        $password=isset($_POST['password']) && !empty($_POST['password'])?$this->maple_quotes($_POST['password']):$this->_admin_password;
        $str="\n\$password='$password';";
        file_put_contents($this->_site_conf_file, $str,FILE_APPEND);
    }

    function ajaxIndex(){
	if(isset($_GET['ajax'])){
	    $data=$this->get_all_data(TRUE,TRUE);
	    $current_page=isset($_GET['pid'])?(int)$_GET['pid']:0;
	    $nums=$this->_model->num_rows($data);
	    $pages=ceil($nums/$this->_num_perpage);
	    if($current_page>=$pages)
		$current_page=$pages-1;
	    if($current_page<0)
		$current_page=0;
	    if($this->_page_on)
		$data=$this->page_wrapper($data, $current_page);
	    $string='';
	    foreach($data as $m){
		$string.="<tr class='message'>";
		$string.="<td class='left'>".str_replace('Admin',"<font color='red'>Admin</font>",$m['user'])."</td>";
		$string.="<td class='left'>".$this->parse_smileys(htmlspecialchars_decode($m['content']),$this->_smileys_dir,$this->_smileys)."<br />";		 
		if(@$m['reply']){							 
		    $string.=sprintf($this->t('ADMIN_REPLIED'),date('m-d H:i',(int)$m['reply']['reply_time']+$this->_time_zone*60*60),$this->parse_smileys($m['reply']['reply_content'],$this->_smileys_dir,$this->_smileys));
		}
		$string.="</td>";
		$string.="<td class='center'>".date('m-d H:i',$m['time']+$this->_time_zone*60*60)."</td>";
		$string.="</tr>";
	    }
	    echo $string;
	}else{
	    echo 'Error';
	}
    }
	
    function index()
    {
        if ($this->_mb_open==1)
            $this->show_message($this->_close_reason);
        $data=$this->get_all_data(TRUE,TRUE);
        $current_page=isset($_GET['pid'])?(int)$_GET['pid']:0;
        $nums=$this->_model->num_rows($data);
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

    function showCaptcha(){
	$this->_imgcode->image(2,4,900,array('borderColor'=>'#66CCFF','bgcolor'=>'#FFCC33'));
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
	    if($_POST['user']==$this->_admin_name && $this->maple_quotes($_POST['password'])==$this->_admin_password)
	    {
		$_SESSION['admin']=$_POST['user'];
		header("Location:index.php?action=control_panel");
		exit;
	    }
	    else
		$errormsg=$this->t('LOGIN_ERROR');
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
	$new_data_status=TRUE;
	$new_data=array();
	$user=isset($_POST['user'])?$_POST['user']:'';
	$current_ip=$_SERVER['REMOTE_ADDR'];
	$user=$this->maple_quotes($user);
	$admin_name_array=array('admin','root','administrator','管理员');
	if(!isset($_SESSION['admin']) && in_array(strtolower($user),$admin_name_array))
	    $user='anonymous';
	$content =isset($_POST['content'])?$this->maple_quotes($_POST['content']):'';
	$content = nl2br($content);
	$content = str_replace(array("\n", "\r\n", "\r"), '', $content);
	$time=time();
	if(empty($user) or empty($content))
	{
	    $new_data_status=FALSE;
	    $new_data_error_msg=$this->t('FILL_NOT_COMPLETE');
	}
	elseif(strlen($content)>580)
	{
	    $new_data_status=FALSE;
	    $new_data_error_msg=$this->t('WORDS_TOO_LONG');
	}
	elseif($this->_valid_code_open==1)
	{
	    if(!$this->checkImgcode()){
	    $new_data_status=FALSE;
	    $new_data_error_msg=$this->t('CAPTCHA_WRONG');
	    }
	}
	if(!$new_data_status){
	    if(isset($_POST['ajax'])){
	    echo $new_data_error_msg;
	    return FALSE;
	    }else{
		$this->show_message($new_data_error_msg,true,'index.php');exit;
	    }
	}

	$new_data=array(NULL,$user,$content,$time,$current_ip);
	if(!$this->_model->insert($this->_message_table_name, $new_data))
	    die($this->_model->error());
	if(isset($_POST['ajax'])){
	    echo 'OK';
	    return TRUE;
	}
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
	$condition=array('id'=>$mid);
	$reply_data=$this->_model->select($this->_reply_table_name, $condition);
	if ($reply_data===FALSE)
	    $this->show_message($this->t("QUERY_ERROR"),TRUE,'index.php?action=control_panel&subtab=message');
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
	    $reply_content = $this->maple_quotes($_POST['reply_content']);
	    $reply_content = nl2br($reply_content);
	    $reply_content = str_replace(array("\n", "\r\n", "\r"), '', $reply_content);
	    $time=time();
	    if (trim($reply_content)=='')
		$this->show_message($this->t('REPLY_EMPTY'),true,'index.php?action=control_panel&subtab=message',3);
	    if(isset($_POST['update']))
	    {
		$input=array($mid,$reply_content,$time);
		$condition=array('id'=>$mid);
		if(!$this->_model->update($this->_reply_table_name, $condition, $input))
		    die($this->_model->error());
	    }
	    else{
		$input=array($mid,$reply_content,$time);
		if(!$this->_model->insert($this->_reply_table_name, $input))
		    die($this->_model->error());
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
	    $update_content = $this->maple_quotes($_POST['update_content']);
	    $update_content = nl2br($update_content);
	    $update_content = str_replace(array("\n", "\r\n", "\r"), '', $update_content);
	    $ip=$_POST['ip'];
	    $input=array($mid,$author,$update_content,$m_time,$ip);
	    $condition=array('id'=>$mid);
	    if(!$this->_model->update($this->_message_table_name, $condition, $input))
		die($this->_model->error());
	    else
		header("Location:index.php?action=control_panel&subtab=message");
	}
	if(!isset($_GET['mid']))
	{
	    header("location:index.php?action=control_panel&subtab=message");exit;
	}
        $mid=intval($_GET['mid']);
	$condition=array('id'=>$mid);
	$message_info=$this->_model->select($this->_message_table_name, $condition);
        if(!$message_info)
            $this->show_message($this->t('QUERY_ERROR'),TRUE,'index.php?action=control_panel&subtab=message');
	$message_info=$message_info[0];
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
	    $condition=array('id'=>$mid);
	    if(!$this->_model->delete($this->_message_table_name, $condition))
		die($this->_model->error());
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
	    $condition=array('id'=>$mid);
	    if(!$this->_model->delete($this->_reply_table_name, $condition))
		die($this->_model->error());
        }
        header("Location:index.php?action=control_panel&subtab=message&randomvalue=".rand());
    }

    function clear_all()
    {
        is_admin();
        $message_table_path=  $this->_model->_table_path($this->_dbname, $this->_message_table_name);
	$message_filename=$message_table_path.$this->_model->get_data_ext();
        file_put_contents($message_filename, '');
        $this->clear_reply();
        header("location:index.php?action=control_panel&subtab=message");
    }

    function clear_reply()
    {
        is_admin();
        $reply_table_path=$this->_model->_table_path($this->_dbname,$this->_reply_table_name);
        $reply_filename=$reply_table_path.$this->_model->get_data_ext();
        file_put_contents($reply_filename,'');
        header("location:index.php?action=control_panel&subtab=message");
    }

    function control_panel()
    {
        global $gd_exist,$zip_support;
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

        $nums=$this->_model->num_rows($data);
        $reply_num=$this->_model->num_rows($reply_data);

        if($gd_exist)
	{
            $gd_info=gd_version();
	    $gd_version=$gd_info?$gd_info:'<font color="red">'.$this->t('UNKNOWN').'</font>';
        }
        else
            $gd_version='<font color="red">GD'.$this->t('NOT_SUPPORT').'</font>';
        $register_globals=ini_get("register_globals") ? 'On' : 'Off';
        $magic_quotes_gpc=ini_get("magic_quotes_gpc") ? 'On' : 'Off';
        $languages=$this->get_all_langs();
	$timezone_array=$this->get_all_timezone();
        include 'themes/'.$this->_theme.'/templates/'."admin.php";
    }

    function backup()
    {
        is_admin();
        $dir="data/{$this->_dbname}/";
        if(!class_exists('ZipArchive'))
        {
            $this->show_message($this->t('BACKUP_NOTSUPPORT'),true,'index.php?action=control_panel&subtab=message');
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
        if (!isset($ip) || $ip=="" || valid_ip($ip)==false)
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
	$ip_filename=$this->_model->_table_path($this->_dbname, $this->_banedip_table_name).$this->_model->get_data_ext();
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
        $ip_filename=$this->_model->_table_path($this->_dbname, $this->_banedip_table_name).$this->_model->get_data_ext();
        file_put_contents($ip_filename, $new_ip_string);
        header("location:index.php?action=control_panel&subtab=ban_ip");
    }
    /**
     * 显示表情
     */
    function show_smileys_table()
    {
	$smiley=  require 'showSmiley.php';
	return $smiley;
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
	$result=$this->_model->select($this->_banedip_table_name);
	return $result;
    }

    function get_all_data($parse_smileys=true,$filter_words=false)
    {
        $data=array();
	if(($data=$this->_model->select($this->_message_table_name))===FALSE)
	    die($this->_model->error());   
        if(($reply_data=$this->_model->select($this->_reply_table_name))===FALSE)
	    die($this->_model->error());
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
		$d_file=$this->_model->_db_path($this->_dbname).'/'.$entry;
		@unlink($d_file);
	    }
	}
	$d->close();
    }

    function get_all_reply()
    {
        $reply_data=array();
	if(($reply_data=$this->_model->select($this->_reply_table_name))===FALSE)
	    die($this->_model->error());
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
    
    function t($str,$isCoreMessage=false)
    {
    	$lang=($isCoreMessage)?$this->_coreMessage_array:$this->_lang_array;
    	return str_replace($str,$lang[$str],$str);
    }
    
    function get_all_timezone()
    {
    	$timezone=$this->_lang_array['TZ_ZONES'];
    	return $timezone;
    }
    
    function get_lang()
    {
    	include $this->_site_conf_file;
        if(isset ($lang) && in_array($lang,$this->get_all_langs()))
        {
	    $this->_current_lang=$lang;
	    include(dirname(dirname(__FILE__)).'/lang/'.$lang.'.php');
            $this->_lang_array=$lang;
        }
        else
            $this->_errors[]=$this->t('LANGUAGE_ERROR',true);
    }
    
    function get_all_langs()
    {
    	$langs=array();
        $d=dir($this->_lang_directory);
        while(false!==($entry=$d->read()))
        {
            if(substr($entry,0,1)!='.')
                $langs[substr($entry,0,-4)]=substr($entry,0,-4);
        }
        $d->close();
        return $langs;
    }
    function run()
    {
        $action=isset($_GET['action'])?$_GET['action']:'index';
        if(!method_exists($this,$action) || !is_callable(array($this,$action))){$action='index';}
        self::$action();
    }
}