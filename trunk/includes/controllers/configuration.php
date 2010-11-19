<?php
class configuration extends BaseController{
    public $_siteController;
    public function  __construct() {
        $this->_siteController=new site();
    }
    public function  __call($name, $arguments) {
        $arguments=implode('', $arguments);
        return $this->_siteController->$name($arguments);
    }
    public static function get_board_name(){
        include 'config.php';
        if(isset ($board_name)){
            return $board_name;
        }  else {
            throw new Exception(site::translate('SITENAME_ERROR'));
        }
    }
    public static function get_mb_open(){
        include 'config.php';
        if(isset ($mb_open))
            return $mb_open;
        else
            throw new Exception(site::translate('SITESTATUS_ERROR'));
    }
    public static function get_close_reason(){
        include 'config.php';
        if(isset ($close_reason))
            return $close_reason;
        else
            throw new Exception(site::translate('SITECLOSEREASON_ERROR'));
    }
    public static function get_admin_email(){
        include 'config.php';
        if(isset ($admin_email))
            return $admin_email;
        else
            throw new Exception(site::translate('ADMINEMAIL_ERROR'));
    }
    public static function get_copyright_info(){
        include 'config.php';
        if(isset ($copyright_info))
            return $copyright_info;
        else
            throw new Exception(site::translate('COPYRIGHT_ERROR'));
    }
    public static function get_filter_words(){
        include 'config.php';
        if(isset ($filter_words))
            return $filter_words;
        else
            throw new Exception(site::translate('BADWORDS_ERROR'));
    }
    public static function get_valid_code_open(){
        include 'config.php';
        if(isset ($valid_code_open))
            return $valid_code_open;
        else
            throw new Exception(site::translate('CAPTCHASTATUS_ERROR'));
    }
    public static function get_page_on(){
        include 'config.php';
        if(isset ($page_on))
            return $page_on;
        else
            throw new Exception(site::translate('PAGINATIONSTATUS_ERROR'));
    }
    public static function get_num_perpage(){
        include 'config.php';
        if(isset ($num_perpage))
            return $num_perpage;
        else
            throw new Exception(site::translate('PAGINATION_PARAMETER_ERROR'));
    }
    public static function get_theme(){
        include 'config.php';
        if(isset ($theme))
            return $theme;
        else
            throw new Exception(site::translate('THEME_ERROR'));
    }
    public static function get_admin_name(){
        include 'config.php';
        if(isset ($admin))
            return $admin;
        else
            throw new Exception(site::translate('ADMINNAME_ERROR'));
    }
    public static function get_admin_password(){
        include 'config.php';
        if(isset ($password))
            return $password;
        else
            throw new Exception(site::translate('ADMINPASS_ERROR'));
    }
    public static function get_time_zone(){
        include 'config.php';
        if(isset ($timezone))
            return $timezone;
        else
            throw new Exception(site::translate('TIMEZONE_ERROR'));
    }
    public static function get_current_lang(){
        include 'config.php';
        if(isset ($lang))
            return $lang;
        else
            throw new Exception(site::translate('ADMINNAME_ERROR'));
    }

    public static function get_lang_array(){
        if(in_array(self::get_current_lang(), self::get_all_langs())){
            include self::get_lang_directory().self::get_current_lang().'.php';
            return $lang;
        }else{
            throw new Exception(site::translate('LANGUAGE_ERROR'));
        }
    }
    public static  function get_all_langs()
    {
    	$langs=array();
        $d=dir(self::get_lang_directory());
        while(false!==($entry=$d->read()))
        {
            if(substr($entry,0,1)!='.')
                $langs[substr($entry,0,-4)]=substr($entry,0,-4);
        }
        $d->close();
        return $langs;
    }
    public function get_lang_directory(){
        return THEMEDIR.self::get_theme().'/languages/';
    }

    public function get_smileys(){
        $_smileys=  require dirname(dirname(__FILE__)).'/smiley.php';//将代表表情图案的数组导入到当前类的属性中
        return $_smileys;
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
}