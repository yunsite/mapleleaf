<?php
class configuration extends BaseController{
    public $_siteController;
	/*
    static public  $_coreMessage_array=array(
		'THEMES_DIR_NOTEXISTS'=>'The directory of themes does not exists!',
		'SMILEY_DIR_NOTEXISTS'=>'The directory of smiley `%s` does not exists!',
		'CONFIG_FILE_NOTEXISTS'=>'The configuration file `%s` does not exists!',
		'CONFIG_FILE_NOTWRITABLE'=>'The configuration file `%s` does not writable!',

		'SITENAME_ERROR'=>'The sitename undefined!',
		'SITESTATUS_ERROR'=>'The status of site undefined!',
		'SITECLOSEREASON_ERROR'=>'The maintaince message undefined!',
		'ADMINEMAIL_ERROR'=>'Admin email undefined!',
		'COPYRIGHT_ERROR'=>'Coptyright undefined!',
		'BADWORDS_ERROR'=>'Bad words undefined!',
		'CAPTCHASTATUS_ERROR'=>'The status of CAPTCHA undefined!',
		'PAGINATIONSTATUS_ERROR'=>'The status of pagination undefined!',
		'TIMEZONE_ERROR'=>'Timezone undefined!',
		'PAGINATION_PARAMETER_ERROR'=>'The parameter of  pagination undefined!',
		'THEME_ERROR'=>'Theme undefined!',
		'ADMINNAME_ERROR'=>'Admin name undefined!',
		'ADMINPASS_ERROR'=>'admin password undefined!',
		'LANGUAGE_ERROR'=>'Language undefined!',
		'QUERY_ERROR'=>'Query error!',
	);
	*/
    public function  __construct() {
        $this->_siteController=new site();
    }
    public function  __call($name, $arguments) {
        $arguments=implode('', $arguments);
        return $this->_siteController->$name($arguments);
    }
    
    public  function set_config(){
        is_admin();
        $this->_admin_name=  self::get_admin_name();
        $this->_admin_password=self::get_admin_password();
        file_put_contents(CONFIGFILE, '<?php');
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
        header("Location:index.php?action=control_panel&subtab=siteset");
    }
    private function set_board_name()
    {
        is_admin();
        $board_name=$_POST['board_name']?$this->maple_quotes($_POST['board_name']):'MapleLeaf';
        $str='';
        $str="\n\$board_name='$board_name';";
	file_put_contents(CONFIGFILE, $str,FILE_APPEND);
    }


    private function set_mb_open()
    {
        is_admin();
        $mb_open=$_POST['mb_open']?(int)$_POST['mb_open']:0;
        $str="\n\$mb_open=$mb_open;";
        file_put_contents(CONFIGFILE, $str,FILE_APPEND);
    }


    private function set_close_reason()
    {
        is_admin();
        $close_reason=$this->maple_quotes($_POST['close_reason']);
        $str="\n\$close_reason='$close_reason';";
        file_put_contents(CONFIGFILE, $str,FILE_APPEND);
    }


    private function set_admin_email()
    {
        is_admin();
        $admin_email=$_POST['admin_email']?$this->maple_quotes($_POST['admin_email']):'rainyjune@live.cn';
        $str="\n\$admin_email='$admin_email';";
        file_put_contents(CONFIGFILE, $str,FILE_APPEND);
    }

    private function set_copyright_info()
    {
        is_admin();
        @$copyright_info=$_POST['copyright_info']?$this->maple_quotes($_POST['copyright_info']):'Copyright &copy; 2010 mapleleaf.ourplanet.tk';
        $str="\n\$copyright_info='$copyright_info';";
        file_put_contents(CONFIGFILE, $str,FILE_APPEND);
    }

    private function set_filter_words()
    {
        is_admin();
        $filter_words=$_POST['filter_words']?$this->fix_filter_string($_POST['filter_words']):'';
        $str="\n\$filter_words='$filter_words';";
        file_put_contents(CONFIGFILE, $str,FILE_APPEND);
    }


    private function set_valid_code_open()
    {
        is_admin();
        $valid_code_open=(int)$_POST['valid_code_open'];
        $str="\n\$valid_code_open='$valid_code_open';";
        file_put_contents(CONFIGFILE, $str,FILE_APPEND);
    }

    private function set_page_on()
    {
        is_admin();
        $page_on=(int)$_POST['page_on'];
        $str="\n\$page_on='$page_on';";
        file_put_contents(CONFIGFILE, $str,FILE_APPEND);
    }

    private function set_num_perpage()
    {
        is_admin();
        $num_perpage=(int)$_POST['num_perpage'];
        $str="\n\$num_perpage='$num_perpage';";
        file_put_contents(CONFIGFILE, $str,FILE_APPEND);
    }


    private function set_theme()
    {
        is_admin();
        $theme=in_array($_POST['theme'], $this->get_all_themes())?$_POST['theme']:'simple';
        $str="\n\$theme='$theme';";
        file_put_contents(CONFIGFILE, $str,FILE_APPEND);
    }


    private function set_time_zone()
    {
        is_admin();
        $timezone=(isset($_POST['timezone']) && in_array($_POST['timezone'],array_keys($this->get_all_timezone())))?$_POST['timezone']:'0';
        $str="\n\$timezone='$timezone';";
        file_put_contents(CONFIGFILE, $str,FILE_APPEND);
    }

    private function set_lang()
    {
        is_admin();
        $lang=(isset($_POST['lang']) && in_array($_POST['lang'],$this->get_all_langs()))?$_POST['lang']:'en';
        $str="\n\$lang='$lang';";
        file_put_contents(CONFIGFILE, $str,FILE_APPEND);
    }


    private function set_admin_name()
    {
        is_admin();
        $str="\n\$admin='".$this->_admin_name."';";
        file_put_contents(CONFIGFILE, $str,FILE_APPEND);
    }

    private function set_admin_password()
    {
        is_admin();
        $password=isset($_POST['password']) && !empty($_POST['password'])?$this->maple_quotes($_POST['password']):$this->_admin_password;
        $str="\n\$password='$password';";
        file_put_contents(CONFIGFILE, $str,FILE_APPEND);
    }
    public  function pluginset(){
        is_admin();
        $all_plugin=self::get_all_plugins();
        if(isset ($_POST['plugin']) && in_array($_POST['plugin'], $all_plugin)){
            include PLUGINDIR.$_POST['plugin'].'.php';
            $funcName=$_POST['plugin'].'_config';
            $funcName(FALSE,$_POST);
        }
        header("Location:index.php?action=control_panel&subtab=plugin");exit;
    }
}