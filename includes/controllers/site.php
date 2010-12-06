<?php
class site extends BaseController
{
    public  $_imgcode; //FLEA_Helper_ImgCode 实例
    public  $_model;
    //public  $_errors=array();//     * 保存错误信息
	/*
    public static function translate($message){
            return strtr($message, FrontController::$_coreMessage_array);
    }
	*/
    //构造函数
    function  __construct()
    {
        FrontController::getInstance()->_imgcode=new FLEA_Helper_ImgCode();//实例化代表验证码的类
        FrontController::getInstance()->_model=new JuneTxtDb();//实例化模型
        if(!FrontController::getInstance()->_model->_db_exists(DB)){//若默认的数据库不存在，需要执行安装
            $this->install();exit;
        }
	FrontController::getInstance()->_model->select_db(DB);//选择默认的数据库
        //$this->load_config();//载入配置
        //if(FrontController::getInstance()->_errors)//若有错误显示错误信息
            //$this->show_message(FrontController::getInstance()->_errors);
    }

    public function getSysJSON(){
	$languageForJSON='{';
	foreach (FrontController::getInstance()->_lang_array as $key => $value) {
	    $languageForJSON.= '"'.$key.'":"'.addslashes((string)$value).'",';
	}
	$languageForJSON.='}';
	echo $languageForJSON;
    }

    public function install(){
	$installed=FALSE;
        if(!file_exists(CONFIGFILE))        //先检查配置文件是否存在和可写
            die(sprintf(FrontController::t('CONFIG_FILE_NOTEXISTS',true),CONFIGFILE));
        if(!is_writable(CONFIGFILE))
            die(FrontController::getInstance()->_errors[]=sprintf(FrontController::t('CONFIG_FILE_NOTWRITABLE',true),CONFIGFILE));
        if(!empty ($_POST['adminname']) && !empty($_POST['adminpass'])){
            $adminname=$this->maple_quotes($_POST['adminname']);
            $adminpass=$this->maple_quotes($_POST['adminpass']);
            $adminnameString="\n\$admin='$adminname';";
            $adminpassString="\n\$password='$adminpass';";
            file_put_contents(CONFIGFILE, $adminnameString,FILE_APPEND);
            file_put_contents(CONFIGFILE, $adminpassString,FILE_APPEND);
            if(!FrontController::getInstance()->_model->create_db(DB)){
                die (FrontController::getInstance()->_model->error());
            }
            FrontController::getInstance()->_model->select_db(DB);

            $tables=array(MESSAGETABLE,  REPLYTABLE,  BADIPTABLE, USERTABLE);
            $fields=array(
                        array(array('name'=>'id','auto_increment'=>true),array('name'=>'user'),array('name'=>'content'),array('name'=>'time'),array('name'=>'ip')),
                        array(array('name'=>'id'),array('name'=>'reply_content'),array('name'=>'reply_time')),
                        array(array('name'=>'ip')),
                        array(array('name'=>'uid','auto_increment'=>true),array('name'=>'user'),array('name'=>'pwd'),array('name'=>'email')),
                        );
            for($i=0,$t=count($tables);$i<$t;$i++){
                if(!FrontController::getInstance()->_model->create_table($tables[$i],$fields[$i])){
                    die(FrontController::getInstance()->_model->error());
                }
            }
	    $newData=array(NULL,$_POST['adminname'],'Welcome to MapleLeaf.:)',time(), getIp());
	    FrontController::getInstance()->_model->insert(MESSAGETABLE, $newData);
	    $installed=TRUE;
        }
	if(file_exists(dirname(dirname(__FILE__)).'/install.php')){
	    include dirname(dirname(__FILE__)).'/install.php';
	}
    }

    /**
     * 显示信息
     */
    public  function show_message($msg,$redirect=false,$redirect_url='index.php',$time_delay=3)
    {
        include 'themes/'.FrontController::getInstance()->_theme.'/templates/'."show_message.php";
        exit;
    }
/*
    public function maple_quotes($var)
    {
        return htmlspecialchars(trim($var),ENT_QUOTES,  FrontController::getInstance()->_model->get_charset());
    }
*/
    public  function index()
    {
        //if (FrontController::getInstance()->_mb_open==1)
            //$this->show_message(FrontController::getInstance()->_close_reason);
        $data=$this->get_all_data(TRUE,TRUE,TRUE,TRUE);
        $current_page=isset($_GET['pid'])?(int)$_GET['pid']:0;
        $nums=FrontController::getInstance()->_model->num_rows($data);
        $pages=ceil($nums/FrontController::getInstance()->_num_perpage);
        if($current_page>=$pages)
            $current_page=$pages-1;
        if($current_page<0)
            $current_page=0;
        if(FrontController::getInstance()->_page_on)
            $data=$this->page_wrapper($data, $current_page);
        if(isset ($_GET['ajax'])){
            $data=array_reverse($data);
            echo json_encode($data);exit;
        }
        $smileys=$this->show_smileys_table();
        $admin=isset($_SESSION['admin'])?true:false;
        include 'themes/'.FrontController::getInstance()->_theme.'/templates/'."index.php";
    }

    public  function page_wrapper($data,$current_page)
    {
        $start=$current_page*FrontController::getInstance()->_num_perpage;
        $data=array_slice($data,$start,FrontController::getInstance()->_num_perpage);
        return $data;
    }

    public  function showCaptcha(){
	FrontController::getInstance()->_imgcode->image(2,4,900,array('borderColor'=>'#66CCFF','bgcolor'=>'#FFCC33'));
    }



    public function post()
    {
	$new_data_status=TRUE;
	$new_data=array();
	$user=isset($_POST['user'])?$_POST['user']:'';
	$current_ip=getIp();
	$user=$this->maple_quotes($user);
	$admin_name_array=array(FrontController::getInstance()->_admin_name);
	if(!isset($_SESSION['admin']) && in_array(strtolower($user),$admin_name_array))
	    $user='anonymous';
	$content =isset($_POST['content'])?$this->maple_quotes($_POST['content']):'';
	$content = nl2br($content);
	$content = str_replace(array("\n", "\r\n", "\r"), '', $content);
	$time=time();
	if(empty($user) or empty($content))
	{
	    $new_data_status=FALSE;
	    $new_data_error_msg=FrontController::t('FILL_NOT_COMPLETE');
	}
	elseif(strlen($content)>580)
	{
	    $new_data_status=FALSE;
	    $new_data_error_msg=FrontController::t('WORDS_TOO_LONG');
	}
	elseif(FrontController::getInstance()->_valid_code_open==1)
	{
	    if(!$this->checkImgcode()){
	    $new_data_status=FALSE;
	    $new_data_error_msg=FrontController::t('CAPTCHA_WRONG');
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
	if(!FrontController::getInstance()->_model->insert(MESSAGETABLE, $new_data))
	    die(FrontController::getInstance()->_model->error());
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
	$reply_data=FrontController::getInstance()->_model->select(REPLYTABLE, $condition);
	if ($reply_data===FALSE)
	    $this->show_message(FrontController::t("QUERY_ERROR"),TRUE,'index.php?action=control_panel&subtab=message');
	else
	    return $reply_data;
    }
    //Reply
    public  function reply(){
        is_admin();
	if(isset($_POST['Submit'])){
	    $mid=0;
	    $mid=(int)$_POST['mid'];
	    $reply_content = $this->maple_quotes($_POST['reply_content']);
	    $reply_content = nl2br($reply_content);
	    $reply_content = str_replace(array("\n", "\r\n", "\r"), '', $reply_content);
	    $time=time();
	    if (trim($reply_content)=='')
		$this->show_message(FrontController::t('REPLY_EMPTY'),true,'index.php?action=control_panel&subtab=message',3);
	    if(isset($_POST['update'])){
		$input=array($mid,$reply_content,$time);
		$condition=array('id'=>$mid);
		if(!FrontController::getInstance()->_model->update(REPLYTABLE, $condition, $input))
		    die(FrontController::getInstance()->_model->error());
	    }
	    else{
		$input=array($mid,$reply_content,$time);
		if(!FrontController::getInstance()->_model->insert(REPLYTABLE, $input))
		    die(FrontController::getInstance()->_model->error());
	    }
	    header("Location:index.php?action=control_panel&subtab=message");exit;
	}
	$reply_data=$this->loadModel();
	$mid=(int)$_GET['mid'];
	include 'themes/'.FrontController::getInstance()->_theme.'/templates/'."reply.php";
    }

    /**
     * 更新留言
     */
    public function update()
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
	    if(!FrontController::getInstance()->_model->update(MESSAGETABLE, $condition, $input))
		die(FrontController::getInstance()->_model->error());
	    else
		header("Location:index.php?action=control_panel&subtab=message");
	}
	if(!isset($_GET['mid']))
	{
	    header("location:index.php?action=control_panel&subtab=message");exit;
	}
        $mid=intval($_GET['mid']);
	$condition=array('id'=>$mid);
	$message_info=FrontController::getInstance()->_model->select(MESSAGETABLE, $condition);
        if(!$message_info)
            $this->show_message(FrontController::t('QUERY_ERROR'),TRUE,'index.php?action=control_panel&subtab=message');
	$message_info=$message_info[0];
        include 'themes/'.FrontController::getInstance()->_theme.'/templates/'."update.php";
    }

    public  function delete_multi_messages(){
        // Check whether admin had selected some options
        if(!isset($_POST['select_mid'])){
            header("location:index.php?action=control_panel&subtab=message");
            exit;
        }
	$del_ids=$_POST['select_mid'];
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
    public  function delete_message($from_function=false)
    {
        is_admin();
        if($from_function)
            $mid=$from_function;
        else
            $mid=intval($_GET['mid']);
        if(isset($mid))
        {
	    $condition=array('id'=>$mid);
	    if(!FrontController::getInstance()->_model->delete(MESSAGETABLE, $condition))
		die(FrontController::getInstance()->_model->error());
        }
        //若回复中有关于此留言的记录，执行删除回复操作
        @$reply_del=(int)$_GET['reply'];
        if($reply_del==1)
            $this->delete_reply($mid);
        header("Location:index.php?action=control_panel&subtab=message&randomvalue=".rand());
    }

    public  function delete_reply($from_delete_message=false)
    {
        is_admin();
        if($from_delete_message)
            $mid=$from_delete_message;
        else
            $mid=intval($_GET['mid']);
        if(isset($mid))
        {
	    $condition=array('id'=>$mid);
	    if(!FrontController::getInstance()->_model->delete(REPLYTABLE, $condition))
		die(FrontController::getInstance()->_model->error());
        }
        header("Location:index.php?action=control_panel&subtab=message&randomvalue=".rand());
    }

    public  function clear_all()
    {
        is_admin();
        $message_table_path=  FrontController::getInstance()->_model->_table_path(DB, MESSAGETABLE);
	$message_filename=$message_table_path.FrontController::getInstance()->_model->get_data_ext();
        file_put_contents($message_filename, '');
        $this->clear_reply();
        header("location:index.php?action=control_panel&subtab=message");
    }

    public  function clear_reply()
    {
        is_admin();
        $reply_table_path=FrontController::getInstance()->_model->_table_path(DB,REPLYTABLE);
        $reply_filename=$reply_table_path.FrontController::getInstance()->_model->get_data_ext();
        file_put_contents($reply_filename,'');
        header("location:index.php?action=control_panel&subtab=message");
    }

    public  function control_panel()
    {
        global $gd_exist,$zip_support;
        is_admin();
        // Which tab should be displayed?
        $current_tab='overview';
        $tabs_array=array('overview','siteset','message','ban_ip','plugin');
	$tabs_name_array=array(FrontController::t('ACP_OVERVIEW'),FrontController::t('ACP_CONFSET'),FrontController::t('ACP_MANAGE_POST'),FrontController::t('ACP_MANAGE_IP'),FrontController::t('PLUGIN'));
        if(isset($_GET['subtab']))
        {
	    if(in_array($_GET['subtab'],$tabs_array))
		    $current_tab=$_GET['subtab'];
        }
        $themes=  FrontController::get_all_themes();
        $plugins=  FrontController::get_all_plugins();
        $data=$this->get_all_data();
        $reply_data=$this->get_all_reply();
        $ban_ip_info=  FrontController::getInstance()->_model->select(BADIPTABLE);

        $nums=FrontController::getInstance()->_model->num_rows($data);
        $reply_num=FrontController::getInstance()->_model->num_rows($reply_data);

        if($gd_exist)
	{
            $gd_info=gd_version();
	    $gd_version=$gd_info?$gd_info:'<font color="red">'.FrontController::t('UNKNOWN').'</font>';
        }
        else
            $gd_version='<font color="red">GD'.FrontController::t('NOT_SUPPORT').'</font>';
        $register_globals=ini_get("register_globals") ? 'On' : 'Off';
        $magic_quotes_gpc=ini_get("magic_quotes_gpc") ? 'On' : 'Off';
        $languages=  FrontController::get_all_langs();
        $timezone_array=FrontController::get_all_timezone();

        include 'themes/'.FrontController::getInstance()->_theme.'/templates/'."admin.php";
    }

    /**
     * 显示表情
     */
    public  function show_smileys_table()
    {
	$smiley=  require dirname(dirname(__FILE__)).'/showSmiley.php';
	return $smiley;
    }

    /**
     * 检查验证码
     */
    public  function checkImgcode()
    {
	return FrontController::getInstance()->_imgcode->check($_POST['valid_code']);
    }

    /**
     * 替换被过滤的词语
     * @param array $filter_words
     */
    public  function fix_filter_string($filter_words)
    {
	$new_string=trim($filter_words,',');
	$new_string=str_replace(array("\t","\r","\n",'  ',' '),'',$new_string);
	return $new_string;
    }



    public  function get_all_data($parse_smileys=true,$filter_words=false,$processUsername=false,$processTime=false)
    {
        $data=array();
	if(($data=FrontController::getInstance()->_model->select(MESSAGETABLE))===FALSE)
	    die(FrontController::getInstance()->_model->error());
        if(($reply_data=FrontController::getInstance()->_model->select(REPLYTABLE))===FALSE)
	    die(FrontController::getInstance()->_model->error());
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
            if($parse_smileys)
                $data_per['content']=  $this->parse_smileys ($data_per['content'], SMILEYDIR, FrontController::getInstance()->_smileys);
            if($processUsername)
                $data_per['user']=($data_per['user']==FrontController::getInstance()->_admin_name)?"<font color='red'>{$data_per['user']}</font>":$data_per['user'];
            if($processTime)
                $data_per['time']=date('m-d H:i',$data_per['time']+FrontController::getInstance()->_time_zone*60*60);
            $mid=intval($data_per['id']);
            if(isset($new_reply_data[$mid]))
            {
                $data_per['reply']=$new_reply_data[$mid];
                /*if($parse_smileys)
                    $data_per['reply']['reply_content']=$this->parse_smileys($data_per['reply']['reply_content'], FrontController::getInstance()->_smileys_dir,FrontController::getInstance()->_smileys);*/
            }
        }
        $data=array_reverse($data);
        return $data;
    }
    /**
     * 过滤敏感词语
     * @param array $input
     */
    public  function filter_words($input)
    {
	$filter_array=explode(',',FrontController::getInstance()->_filter_words);
	$input=str_ireplace($filter_array,'***',$input);
	return $input;
    }



    public  function get_all_reply()
    {
        $reply_data=array();
	if(($reply_data=FrontController::getInstance()->_model->select(REPLYTABLE))===FALSE)
	    die(FrontController::getInstance()->_model->error());
        return $reply_data;
    }
    /**
     * 将表情符号转换为表情图案
     * @param $str
     * @param $image_url
     * @param $smileys
     */
    public  function parse_smileys($str = '', $image_url = '', $smileys = NULL)
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
	/*
    public function t($str,$isCoreMessage=false)
    {
    	$lang=($isCoreMessage)?  FrontController::$_coreMessage_array:FrontController::getInstance()->_lang_array;
        return strtr($str,$lang);
    } 
		*/
}