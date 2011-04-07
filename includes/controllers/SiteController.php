<?php
class SiteController extends BaseController{
    protected   $_model;
    protected   $_verifyCode;
    public function  __construct(){
        global $db_url;
        if($db_url !='dummydb://username:password@localhost/databasename')
            $this->_model=  YDB::factory($db_url);
        $this->_verifyCode=new FLEA_Helper_ImgCode();
    }

    public function actionIndex(){
        $data=get_all_data(TRUE,TRUE,TRUE,TRUE);
        $current_page=isset($_GET['pid'])?(int)$_GET['pid']:0;
        $nums=count($data);
        $pages=ceil($nums/ZFramework::app()->num_perpage);
        if($current_page>=$pages)
            $current_page=$pages-1;
        if($current_page<0)
            $current_page=0;
        if(ZFramework::app()->page_on)
            $data=$this->page_wrapper($data, $current_page);
        if(isset ($_GET['ajax']) || defined('API_MODE')){
            $data=array_reverse($data);
            $JSONDATA=array('messages'=>$data,'current_page'=>$current_page,'total'=>$nums,'pagenum'=>$pages);
            die(function_exists('json_encode') ? json_encode($JSONDATA) : CJSON::encode($JSONDATA));
        }
        $admin=isset($_SESSION['admin'])?true:false;
        $adminName=  ZFramework::app()->admin;
        $smileys=show_smileys_table();
        
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

    //安装程序
    public function actionInstall(){
        $languages=get_all_langs();
        if(!isset($_GET['l']) || !in_array($_GET['l'],$languages) || $_GET['l']=='en'){	$language='en';}
        else
            $language=$_GET['l'];
        $installed=FALSE;
        if(!file_exists(CONFIGFILE))        //先检查配置文件是否存在和可写
            $tips=t('CONFIG_FILE_NOTEXISTS',array('{config_file}'=>CONFIGFILE),$language);
        elseif(!is_writable(CONFIGFILE))
            $tips=t('CONFIG_FILE_NOTWRITABLE',array('{config_file}'=>CONFIGFILE),$language);
        elseif(!is_writable(APPROOT.'/data/'))
            $tips= t ('DATADIR_NOT_WRITABLE', array(), $language);
        if(!empty ($_POST['adminname']) && !empty($_POST['adminpass']) && !empty ($_POST['dbtype']) &&!empty ($_POST['dbusername']) && !empty ($_POST['dbname']) && !empty ($_POST['dbhost']) && strlen(trim($_POST['adminname']))>2 ){
            $adminname=maple_quotes($_POST['adminname']);
            $adminpass=maple_quotes($_POST['adminpass']);
            $dbname=  maple_quotes($_POST['dbname']);
            $tbprefix=$_POST['tbprefix'];
            $url=$_POST['dbtype'].'://'.$_POST['dbusername'].':'.$_POST['dbpwd'].'@'.$_POST['dbhost'].'/'.$_POST['dbname'];
            #$db=YDB::factory($url);
            $formError='';
            try{
                $db=YDB::factory($url);
            }
            catch (Exception $e){
                $formError=$e->getMessage();
            }
            if(!$formError){
                $url_string="<?php\n\$db_url = '$url';\n\$db_prefix = '$tbprefix';\n?>";
                file_put_contents(CONFIGFILE, $url_string);
                $sql_file=APPROOT.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.$_POST['dbtype'].'.sql';
                $sql_array=file($sql_file);
                $translate=array('{time}'=>  time(),'{ip}'=>  getIP(),'{admin}'=>$adminname,'{adminpass}'=>$adminpass,'{lang}'=>$language,'<'=>$tbprefix,'>'=>'');
                foreach ($sql_array as $sql) {
                    $_sql=html_entity_decode(strtr(trim($sql),$translate),ENT_COMPAT,'UTF-8');
                    $db->query($_sql);
                }
                $installed=TRUE;
            }
        }
	if(file_exists(dirname(dirname(__FILE__)).'/install.php')){
	    include dirname(dirname(__FILE__)).'/install.php';
	}  else {
            die ('Access denied!');
        }
    }
    public function actionControl_panel(){
        global $gd_exist,$zip_support;
        is_admin();
        $current_tab='overview';
        $tabs_array=array('overview','siteset','message','ban_ip');
	$tabs_name_array=array(t('ACP_OVERVIEW'),t('ACP_CONFSET'),t('ACP_MANAGE_POST'),t('ACP_MANAGE_IP'));
        if(isset($_GET['subtab'])){
	    if(in_array($_GET['subtab'],$tabs_array))
		    $current_tab=$_GET['subtab'];
        }
        $themes= get_all_themes();

        $data=get_all_data(TRUE,false,TRUE,TRUE,false);
        $reply_data=  $this->_model->queryAll(parse_tbprefix("SELECT * FROM <reply>"));
        $ban_ip_info=  $this->_model->queryAll(parse_tbprefix("SELECT * FROM <badip>"));

        $nums=count($data);
        $reply_num=count($reply_data);

        if($gd_exist){
            $gd_info=gd_version();
	    $gd_version=$gd_info?$gd_info:'<font color="red">'.t('UNKNOWN').'</font>';
        }
        else
            $gd_version='<font color="red">GD'.t('NOT_SUPPORT').'</font>';
        $register_globals=ini_get("register_globals") ? 'On' : 'Off';
        $magic_quotes_gpc=ini_get("magic_quotes_gpc") ? 'On' : 'Off';
        $languages= get_all_langs();
        $timezone_array=  get_all_timezone();
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
            ));
    }

    public function actionRSS(){
        $data=get_all_data(true, true);
        header('Content-Type: text/xml; charset=utf-8', true);
        $now = date("D, d M Y H:i:s T");
        $borad_name=ZFramework::app()->board_name;
        $output =<<<HERE
<?xml version="1.0" encoding="utf-8"?>
<rss version="2.0">
    <channel>
    <title>$borad_name</title>
    <link>{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}</link>
    <language>en</language>
    <pubDate>$now</pubDate>
    <lastBuildDate>$now</lastBuildDate>\n
HERE;
        foreach ($data as $m) {
            $output .= "\t<item><title>";
            if((int)$m['uid']>0)
                $output.=htmlentities ($m['b_username']);
            else
                $output.=htmlentities ($m['user']);
            $output .= "</title><pubDate>".date("D, d M Y H:i:s T", $m['time'])."</pubDate><description><![CDATA[".$m['post_content'];
            if(@$m['reply_content'])
                $output.="<br />".strip_tags (t('ADMIN_REPLIED',array('{admin_name}'=>ZFramework::app()->admin,'{reply_time}'=>date("D, d M Y H:i:s T",$m['reply_time']),'{reply_content}'=>$m['reply_content'])));
            $output .="]]></description></item>\n";
        }
        $output.="\t</channel>\n</rss>";
        echo $output;
    }

    public  function page_wrapper($data,$current_page){
        $start=$current_page*ZFramework::app()->num_perpage;
        $data=array_slice($data,$start,  ZFramework::app()->num_perpage);
        return $data;
    }
    public  function actionCaptcha(){
	$this->_verifyCode->image(2,4,900,array('borderColor'=>'#66CCFF','bgcolor'=>'#FFCC33'));
    }
    public function actionGetSysJSON(){
        $langArray=getLangArray();
        $langArray['ADMIN_NAME_INDEX']=ZFramework::app()->admin;
        echo function_exists('json_encode') ? json_encode($langArray) : CJSON::encode($langArray);
    }
     
}
