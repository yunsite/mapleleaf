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
        $data=$this->get_all_data(TRUE,TRUE,TRUE,TRUE);
        #echo '<pre>';var_dump($data);exit;
        $current_page=isset($_GET['pid'])?(int)$_GET['pid']:0;
        $nums=count($data);
        $pages=ceil($nums/ZFramework::app()->num_perpage);
        if($current_page>=$pages)
            $current_page=$pages-1;
        if($current_page<0)
            $current_page=0;
        if(ZFramework::app()->page_on)
            $data=$this->page_wrapper($data, $current_page);
        if(isset ($_GET['ajax'])){
            $data=array_reverse($data);
            $JSONDATA=array('messages'=>$data,'current_page'=>$current_page,'total'=>$nums,'pagenum'=>$pages);
            echo function_exists('json_encode') ? json_encode($JSONDATA) : CJSON::encode($JSONDATA);exit;
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

    //安装程序
    public function actionInstall(){
        $languages=ZFramework::get_all_langs();
        if(!isset($_GET['l']) || !in_array($_GET['l'],$languages) || $_GET['l']=='en'){	$language='en';}
        else
            $language=$_GET['l'];
        $installed=FALSE;
        if(!file_exists(CONFIGFILE))        //先检查配置文件是否存在和可写
            $tips=ZFramework::t('CONFIG_FILE_NOTEXISTS',array('{config_file}'=>CONFIGFILE),$language);
        elseif(!is_writable(CONFIGFILE))
            $tips=ZFramework::t('CONFIG_FILE_NOTWRITABLE',array('{config_file}'=>CONFIGFILE),$language);
        elseif(!is_writable(APPROOT.'/data/'))
            $tips= ZFramework::t ('DATADIR_NOT_WRITABLE', array(), $language);
        elseif(!is_writable(conf_path()))
            $tips= ZFramework::t ('CONFIGDIR_NOT_WRITABLE', array(), $language);
        if(!empty ($_POST['adminname']) && !empty($_POST['adminpass']) && !empty ($_POST['dbtype']) &&!empty ($_POST['dbusername']) && !empty ($_POST['dbname']) && !empty ($_POST['dbhost']) && strlen(trim($_POST['adminname']))>2 ){
            $adminname=ZFramework::maple_quotes($_POST['adminname']);
            $adminpass=ZFramework::maple_quotes($_POST['adminpass']);
            $dbname=  ZFramework::maple_quotes($_POST['dbname']);
            $tbprefix=$_POST['tbprefix'];
            $url=$_POST['dbtype'].'://'.$_POST['dbusername'].':'.$_POST['dbpwd'].'@'.$_POST['dbhost'].'/'.$_POST['dbname'];
            $db=YDB::factory($url);
            if(!$db){
                $formError=ZFramework::t('DB_CONNECT_ERROR', array(), $language);
            }else{
                $url_string="<?php\n\$db_url = '$url';\n\$db_prefix = '$tbprefix';\n?>";
                file_put_contents(CONFIGFILE, $url_string);
                $sql_file=APPROOT.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.$_POST['dbtype'].'.sql';
                $sql_array=file($sql_file);
                $translate=array('{time}'=>  time(),'{ip}'=>  getIP(),'{admin}'=>$adminname,'{adminpass}'=>$adminpass,'{lang}'=>$language,'<'=>$tbprefix,'>'=>'');
                foreach ($sql_array as $sql) {
                    $_sql=strtr(trim($sql),$translate);
                    $db->query($_sql);
                }
                $installed=TRUE;
            }
            //exit;
        }
	if(file_exists(dirname(dirname(__FILE__)).'/install.php')){
	    include dirname(dirname(__FILE__)).'/install.php';
	}
    }
    public function actionControl_panel(){
        global $gd_exist,$zip_support;
        is_admin();
        $current_tab='overview';
        $tabs_array=array('overview','siteset','message','ban_ip');
	$tabs_name_array=array(ZFramework::t('ACP_OVERVIEW'),ZFramework::t('ACP_CONFSET'),ZFramework::t('ACP_MANAGE_POST'),ZFramework::t('ACP_MANAGE_IP'));
        if(isset($_GET['subtab'])){
	    if(in_array($_GET['subtab'],$tabs_array))
		    $current_tab=$_GET['subtab'];
        }
        $themes= ZFramework::get_all_themes();

        $data=$this->get_all_data(TRUE,false,TRUE,TRUE);
        $reply_data=  $this->_model->queryAll(parse_tbprefix("SELECT * FROM <reply>"));
        $ban_ip_info=  $this->_model->queryAll(parse_tbprefix("SELECT * FROM <badip>"));

        $nums=count($data);
        $reply_num=count($reply_data);

        if($gd_exist){
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
            ));
    }

    public function actionRSS(){
        $data=$this->get_all_data(true, true);
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
    <lastBuildDate>$now</lastBuildDate>
    <docs>http://someurl.com</docs>
    <managingEditor>you@youremail.com</managingEditor>
    <webMaster>you@youremail.com</webMaster>\n
HERE;
        foreach ($data as $m) {
            $output .= "\t<item><title>";
            if((int)$m['uid']>0)
                $output.=htmlentities ($m['b_username']);
            else
                $output.=htmlentities ($m['user']);
            $output .= "</title><pubDate>".date("D, d M Y H:i:s T", $m['time'])."</pubDate><description><![CDATA[".$m['post_content'];
            if(@$m['reply_content'])
                $output.="<br />".strip_tags (ZFramework::t('ADMIN_REPLIED',array('{admin_name}'=>ZFramework::app()->admin,'{reply_time}'=>date("D, d M Y H:i:s T",$m['reply_time']),'{reply_content}'=>$m['reply_content'])));
            $output .="]]></description></item>\n";
        }
        $output.="\t</channel>\n</rss>";
        echo $output;
    }

    public  function get_all_data($parse_smileys=true,$filter_words=false,$processUsername=false,$processTime=false){
        $data=array();
        $data=$this->_model->queryAll(parse_tbprefix("SELECT p.pid AS id, p.ip AS ip , p.uid AS uid ,p.uname AS user,p.content AS post_content,p.post_time AS time,r.content AS reply_content,r.r_time AS reply_time ,u.username AS b_username FROM <post> AS p LEFT JOIN <reply> AS r ON p.pid=r.pid LEFT JOIN <user> AS u ON p.uid=u.uid ORDER BY p.post_time DESC"));
        foreach ($data as &$_data) {
            if($parse_smileys){
                $_data['post_content']=$this->parse_smileys ($_data['post_content'], SMILEYDIR,  ZFramework::getSmileys());
                $_data['reply_content']=$this->parse_smileys ($_data['reply_content'], SMILEYDIR,  ZFramework::getSmileys());
            }
            if($filter_words)
                $_data['post_content']=$this->filter_words($_data['post_content']);
            if($processUsername)
                $_data['user']=($_data['user']==ZFramework::app()->admin)?"<font color='red'>{$_data['user']}</font>":$_data['user'];
            if($processTime){
                $_data['time']=date('m-d H:i',$_data['time']+ZFramework::app()->timezone*60*60);
                $_data['reply_time']=date('m-d H:i',$_data['reply_time']+ZFramework::app()->timezone*60*60);
            }
            
        }   
        //echo '<pre>';
        //var_dump($data);exit;
        return $data;
    }
    /**
     * 过滤敏感词语
     * @param array $input
     */
    public  function filter_words($input){
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
    public  function parse_smileys($str = '', $image_url = '', $smileys = NULL){
	if ($image_url == '')
	    return $str;
	if (!is_array($smileys))
	    return $str;
	// Add a trailing slash to the file path if needed
	$image_url = preg_replace("/(.+?)\/*$/", "\\1/",  $image_url);
	foreach ($smileys as $key => $val){
	    $str = str_replace($key, "<img src=\"".$image_url.$smileys[$key][0]."\" width=\"".$smileys[$key][1]."\" height=\"".$smileys[$key][2]."\" title=\"".$smileys[$key][3]."\" alt=\"".$smileys[$key][3]."\" style=\"border:0;\" />", $str);
	}
	return $str;
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
        $langArray=ZFramework::getLangArray();
        $langArray['ADMIN_NAME_INDEX']=ZFramework::app()->admin;
        echo json_encode($langArray);
    }
    /**
     * 显示表情
     */
    public  function show_smileys_table(){
	$smiley=  require dirname(dirname(__FILE__)).'/showSmiley.php';
	return $smiley;
    } 
}