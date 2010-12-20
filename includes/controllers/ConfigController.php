<?php
class ConfigController extends BaseController
{
    private $_admin_name;
    private $_admin_password;
    private $_dbname;
    public function actionUpdate()
    {
        is_admin();
        if(!$_POST){ header ("Location:index.php?action=control_panel");exit;}
        $this->_admin_name=  ZFramework::app()->admin;
        $this->_admin_password=  ZFramework::app()->password;
        $this->_dbname=  ZFramework::app()->dbname;
        file_put_contents(CONFIGFILE, '<?php');
        $this->set_board_name();
        $this->set_site_close();
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
        $this->set_lang();
        $this->set_time_zone();
        $this->set_dbname();
        
        header("Location:index.php?action=control_panel&subtab=siteset");
    }
    private function set_board_name()
    {
        is_admin();
        $board_name=$_POST['board_name']?ZFramework::maple_quotes($_POST['board_name']):'MapleLeaf';
        $str='';
        $str="\n\$board_name='$board_name';";
	file_put_contents(CONFIGFILE, $str,FILE_APPEND);
    }


    private function set_site_close()
    {
        is_admin();
        $site_close=$_POST['site_close']?(int)$_POST['site_close']:0;
        $str="\n\$site_close=$site_close;";
        file_put_contents(CONFIGFILE, $str,FILE_APPEND);
    }


    private function set_close_reason()
    {
        is_admin();
        $close_reason=ZFramework::maple_quotes($_POST['close_reason']);
        $str="\n\$close_reason='$close_reason';";
        file_put_contents(CONFIGFILE, $str,FILE_APPEND);
    }


    private function set_admin_email()
    {
        is_admin();
        $admin_email=$_POST['admin_email']?ZFramework::maple_quotes($_POST['admin_email']):'dreamneverfall@gmail.com';
        $str="\n\$admin_email='$admin_email';";
        file_put_contents(CONFIGFILE, $str,FILE_APPEND);
    }

    private function set_copyright_info()
    {
        is_admin();
        @$copyright_info=$_POST['copyright_info']?ZFramework::maple_quotes($_POST['copyright_info']):'Copyright &copy; 2010 mapleleaf.ourplanet.tk';
        $str="\n\$copyright_info='$copyright_info';";
        file_put_contents(CONFIGFILE, $str,FILE_APPEND);
    }

    private function set_filter_words()
    {
        is_admin();
        $filter_words=$_POST['filter_words']?  ZFramework::fix_filter_string($_POST['filter_words']):'';
        $str="\n\$filter_words='$filter_words';";
        file_put_contents(CONFIGFILE, $str,FILE_APPEND);
    }


    private function set_valid_code_open()
    {
        is_admin();
        $valid_code_open=(int)$_POST['valid_code_open'];
        $str="\n\$valid_code_open=$valid_code_open;";
        file_put_contents(CONFIGFILE, $str,FILE_APPEND);
    }

    private function set_page_on()
    {
        is_admin();
        $page_on=(int)$_POST['page_on'];
        $str="\n\$page_on=$page_on;";
        file_put_contents(CONFIGFILE, $str,FILE_APPEND);
    }

    private function set_num_perpage()
    {
        is_admin();
        $num_perpage=(int)$_POST['num_perpage'];
        $str="\n\$num_perpage=$num_perpage;";
        file_put_contents(CONFIGFILE, $str,FILE_APPEND);
    }


    private function set_theme()
    {
        is_admin();
        $theme=in_array($_POST['theme'], ZFramework::get_all_themes())?$_POST['theme']:'simple';
        $str="\n\$theme='$theme';";
        file_put_contents(CONFIGFILE, $str,FILE_APPEND);
    }


    private function set_time_zone()
    {
        is_admin();
        $timezone=(isset($_POST['timezone']) && in_array($_POST['timezone'],array_keys(ZFramework::get_all_timezone())))?$_POST['timezone']:'0';
        $str="\n\$timezone='$timezone';";
        file_put_contents(CONFIGFILE, $str,FILE_APPEND);
    }

    private function set_lang()
    {
        is_admin();
        $lang=(isset($_POST['lang']) && in_array($_POST['lang'],  ZFramework::get_all_langs()))?$_POST['lang']:'en';
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
        $password=isset($_POST['password']) && !empty($_POST['password'])?ZFramework::maple_quotes($_POST['password']):$this->_admin_password;
        $str="\n\$password='$password';";
        file_put_contents(CONFIGFILE, $str,FILE_APPEND);
    }
    private function set_dbname()
    {
        is_admin();
        $str="\n\$dbname='{$this->_dbname}';";
        file_put_contents(CONFIGFILE, $str, FILE_APPEND);
    }
}