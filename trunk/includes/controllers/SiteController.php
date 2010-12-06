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