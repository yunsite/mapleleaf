<?php
class SiteController extends BaseController
{
    protected   $_model;
    protected   $_verifyCode;
    public function  __construct()
    {
        $this->_model=new JuneTxtDB();
        $this->_model->select_db(DB);
        $this->_verifyCode=new FLEA_Helper_ImgCode();
    }
    //展示首页
    public function actionIndex()
    {
        $data=$this->get_all_data(TRUE,TRUE,TRUE,TRUE);
        $current_page=isset($_GET['pid'])?(int)$_GET['pid']:0;
        $nums=$this->_model->num_rows($data);
        $pages=ceil($nums/ZFramework::app()->num_perpage);
        if($current_page>=$pages)
            $current_page=$pages-1;
        if($current_page<0)
            $current_page=0;
        if(ZFramework::app()->page_on)
            $data=$this->page_wrapper($data, $current_page);
        if(isset ($_GET['ajax'])){
            $data=array_reverse($data);
            echo function_exists('json_encode') ? json_encode($data) : CJSON::encode($data);exit;
        }
        $admin=isset($_SESSION['admin'])?true:false;
        $adminName=  ZFramework::app()->admin;
        $smileys=$this->show_smileys_table();
        
        $this->render('index',array(
            'data'=>$data,
            'admin'=>$admin,
            'smileys'=>$smileys,
            'current_page'=>$current_page,
            'pages'=>$pages,
            'adminName'=>$adminName,
            'nums'=>$nums,
            ));
    }
    public function actionGetPagination(){
        $totalNumber= $this->_model->num_rows($this->_model->select(MESSAGETABLE));
        $pages=ceil($totalNumber/ZFramework::app()->num_perpage);
        $string=sprintf(ZFramework::t('PAGE_NAV'),$totalNumber,$pages);
        $current_page=isset($_GET['pid'])?(int)$_GET['pid']:0;
        if($current_page>=$pages)
            $current_page=$pages-1;
        if($current_page<0)
            $current_page=0;
        for($i=0;$i<$pages;$i++){
            $string.="<a href='index.php?pid=$i'>";
            if($i==$current_page){ $string.='<font size="+2">'.($i+1)."</font>";}else{ $string.=$i+1;}
            $string.="</a>&nbsp;";
        }
        echo $string;
    }
    //安装程序
    public function actionInstall()
    {
        $installed=FALSE;
        if(!file_exists(CONFIGFILE))        //先检查配置文件是否存在和可写
            $tips=sprintf(ZFramework::t('CONFIG_FILE_NOTEXISTS',true),CONFIGFILE);
        elseif(!is_writable(CONFIGFILE))
            $tips=sprintf(ZFramework::t('CONFIG_FILE_NOTWRITABLE',true),CONFIGFILE);
        if(!empty ($_POST['adminname']) && !empty($_POST['adminpass']) && !empty ($_POST['dbname']) && strlen(trim($_POST['adminname']))>2 ){
            $adminname=ZFramework::maple_quotes($_POST['adminname']);
            $adminpass=ZFramework::maple_quotes($_POST['adminpass']);
            $dbname=  ZFramework::maple_quotes($_POST['dbname']);
            $adminnameString="\n\$admin='$adminname';";
            $adminpassString="\n\$password='$adminpass';";
            $dbnameString="\n\$dbname='$dbname';";
            file_put_contents(CONFIGFILE, $adminnameString,FILE_APPEND);
            file_put_contents(CONFIGFILE, $adminpassString,FILE_APPEND);
            file_put_contents(CONFIGFILE, $dbnameString,FILE_APPEND);
            if(!$this->_model->create_db($dbname)){
                die ($this->_model->error());
            }
            $this->_model->select_db($dbname);

            $tables=array(MESSAGETABLE,  REPLYTABLE,  BADIPTABLE, USERTABLE);
            $fields=array(
                        array(array('name'=>'id','auto_increment'=>true),array('name'=>'user'),array('name'=>'content'),array('name'=>'time'),array('name'=>'ip')),
                        array(array('name'=>'id'),array('name'=>'reply_content'),array('name'=>'reply_time')),
                        array(array('name'=>'ip')),
                        array(array('name'=>'uid','auto_increment'=>true),array('name'=>'user'),array('name'=>'pwd'),array('name'=>'email')),
                        );
            for($i=0,$t=count($tables);$i<$t;$i++){
                if(!$this->_model->create_table($tables[$i],$fields[$i])){
                    die($this->_model->error());
                }
            }
	    $newData=array(NULL,$_POST['adminname'],'Welcome to MapleLeaf.:)',time(), getIp());
	    $this->_model->insert(MESSAGETABLE, $newData);
	    $installed=TRUE;
        }
	if(file_exists(dirname(dirname(__FILE__)).'/install.php')){
	    include dirname(dirname(__FILE__)).'/install.php';
	}
    }
    public function actionControl_panel(){
        global $gd_exist,$zip_support;
        is_admin();
        // Which tab should be displayed?
        $current_tab='overview';
        $tabs_array=array('overview','siteset','message','ban_ip','plugin');
	$tabs_name_array=array(ZFramework::t('ACP_OVERVIEW'),ZFramework::t('ACP_CONFSET'),ZFramework::t('ACP_MANAGE_POST'),ZFramework::t('ACP_MANAGE_IP'),ZFramework::t('PLUGIN'));
        if(isset($_GET['subtab']))
        {
	    if(in_array($_GET['subtab'],$tabs_array))
		    $current_tab=$_GET['subtab'];
        }
        $themes= ZFramework::get_all_themes();
        $plugins= ZFramework::get_all_plugins();
        $data=$this->get_all_data(TRUE,TRUE,TRUE,TRUE);
        $reply_data=  $this->_model->select(REPLYTABLE);
        $ban_ip_info=  $this->_model->select(BADIPTABLE);

        $nums=$this->_model->num_rows($data);
        $reply_num=$this->_model->num_rows($reply_data);

        if($gd_exist)
	{
            $gd_info=gd_version();
	    $gd_version=$gd_info?$gd_info:'<font color="red">'.ZFramework::t('UNKNOWN').'</font>';
        }
        else
            $gd_version='<font color="red">GD'.ZFramework::t('NOT_SUPPORT').'</font>';
        $register_globals=ini_get("register_globals") ? 'On' : 'Off';
        $magic_quotes_gpc=ini_get("magic_quotes_gpc") ? 'On' : 'Off';
        $languages= ZFramework::get_all_langs();
        $timezone_array=  ZFramework::get_all_timezone();
        $this->render('admin',array(
            'tabs_array'=>$tabs_array,
            'current_tab'=>$current_tab,
            'tabs_name_array'=>$tabs_name_array,
            'nums'=>$nums,
            'reply_num'=>$reply_num,
            'gd_version'=>$gd_version,
            'register_globals'=>$register_globals,
            'magic_quotes_gpc'=>$magic_quotes_gpc,
            'zip_support'=>$zip_support,
            'themes'=>$themes,
            'timezone_array'=>$timezone_array,
            'languages'=>$languages,
            'data'=>$data,
            'ban_ip_info'=>$ban_ip_info,
            'plugins'=>$plugins));
    }

    public  function get_all_data($parse_smileys=true,$filter_words=false,$processUsername=false,$processTime=false)
    {
        $data=array();
	if(($data=$this->_model->select(MESSAGETABLE))===FALSE)
	    die($this->_model->error());
        if(($reply_data=$this->_model->select(REPLYTABLE))===FALSE)
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
            if($parse_smileys)
                $data_per['content']=  $this->parse_smileys ($data_per['content'], SMILEYDIR, ZFramework::getSmileys());
            if($processUsername)
                $data_per['user']=($data_per['user']==ZFramework::app()->admin)?"<font color='red'>{$data_per['user']}</font>":$data_per['user'];
            if($processTime)
                $data_per['time']=date('m-d H:i',$data_per['time']+ZFramework::app()->timezone*60*60);
            $mid=intval($data_per['id']);
            if(isset($new_reply_data[$mid]))
            {
                $data_per['reply']=$new_reply_data[$mid];
                if($processTime)
                    $data_per['reply']['reply_time']=date('m-d H:i',$data_per['reply']['reply_time']+ZFramework::app()->timezone*60*60);
                if($parse_smileys)
                    $data_per['reply']['reply_content']=$this->parse_smileys($data_per['reply']['reply_content'], SMILEYDIR, ZFramework::getSmileys());
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
	$filter_array=explode(',',  ZFramework::app()->filter_words);
	$input=str_ireplace($filter_array,'***',$input);
	return $input;
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
    public  function page_wrapper($data,$current_page)
    {
        $start=$current_page*ZFramework::app()->num_perpage;
        $data=array_slice($data,$start,  ZFramework::app()->num_perpage);
        return $data;
    }
    public  function actionCaptcha(){
	$this->_verifyCode->image(2,4,900,array('borderColor'=>'#66CCFF','bgcolor'=>'#FFCC33'));
    }
    public function actionGetSysJSON(){
        $languageForJSON='{';
	foreach (ZFramework::getLangArray() as $key => $value) {
	    $languageForJSON.= '"'.$key.'":"'.addslashes((string)$value).'",';
	}
        $languageForJSON=substr($languageForJSON, 0,-1);
	$languageForJSON.='}';
	echo $languageForJSON;
    }
    /**
     * 显示表情
     */
    public  function show_smileys_table()
    {
	$smiley=  require dirname(dirname(__FILE__)).'/showSmiley.php';
	return $smiley;
    }
    
}