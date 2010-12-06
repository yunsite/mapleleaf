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
        $data=  $this->_model->select(MESSAGETABLE);
        $admin=isset($_SESSION['admin'])?true:false;
        $smileys=$this->show_smileys_table();
        $tplFile='themes/simple/templates/test-index';
        
        $this->render($tplFile,array('data'=>$data,'admin'=>$admin,'smileys'=>$smileys));
    }
    //安装程序
    public function actionInstall()
    {
        $installed=FALSE;
        if(!file_exists(CONFIGFILE))        //先检查配置文件是否存在和可写
            die(sprintf(ZFramework::t('CONFIG_FILE_NOTEXISTS',true),CONFIGFILE));
        if(!is_writable(CONFIGFILE))
            die(sprintf(ZFramework::t('CONFIG_FILE_NOTWRITABLE',true),CONFIGFILE));
        if(!empty ($_POST['adminname']) && !empty($_POST['adminpass'])){
            $adminname=ZFramework::maple_quotes($_POST['adminname']);
            $adminpass=ZFramework::maple_quotes($_POST['adminpass']);
            $adminnameString="\n\$admin='$adminname';";
            $adminpassString="\n\$password='$adminpass';";
            file_put_contents(CONFIGFILE, $adminnameString,FILE_APPEND);
            file_put_contents(CONFIGFILE, $adminpassString,FILE_APPEND);
            if(!$this->_model->create_db(DB)){
                die ($this->_model->error());
            }
            $this->_model->select_db(DB);

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
        //$data=$this->get_all_data();
        $data=  $this->_model->select(MESSAGETABLE);
        //$reply_data=$this->get_all_reply();
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
        $tplFile='themes/simple/templates/test-admin';
        $this->render($tplFile,array('tabs_array'=>$tabs_array,'current_tab'=>$current_tab,'tabs_name_array'=>$tabs_name_array,'nums'=>$nums,'reply_num'=>$reply_num,'gd_version'=>$gd_version,'register_globals'=>$register_globals,'magic_quotes_gpc'=>$magic_quotes_gpc,'zip_support'=>$zip_support,'data'=>$data,'ban_ip_info'=>$ban_ip_info,'plugins'=>$plugins));
    }

    //显示验证码
    public function actionCaptcha()
    {
        
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