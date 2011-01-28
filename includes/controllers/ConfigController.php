<?php
class ConfigController extends BaseController{
    private $_admin_password;
    public $_model;
    public function  __construct(){
        global $db_url;
        $this->_model=  YDB::factory($db_url);
    }
    public function actionUpdate(){
        is_admin();
        if(!$_POST){ header ("Location:index.php?action=control_panel");exit;}
        $this->_admin_password=  ZFramework::app()->password;
        
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
        $this->set_admin_password();
        $this->set_lang();
        $this->set_time_zone();
        
        header("Location:index.php?action=control_panel&subtab=siteset");
    }
    private function set_board_name(){
        $board_name=$_POST['board_name']?ZFramework::maple_quotes($_POST['board_name']):'MapleLeaf';
        $this->_model->query("UPDATE sysvar SET varvalue='$board_name' WHERE varname='board_name'");
    }

    private function set_site_close(){
        $site_close=$_POST['site_close']?(int)$_POST['site_close']:0;
        $this->_model->query("UPDATE sysvar SET varvalue='$site_close' WHERE varname='site_close'");
    }

    private function set_close_reason(){
        $close_reason=ZFramework::maple_quotes($_POST['close_reason']);
        $this->_model->query("UPDATE sysvar SET varvalue='$close_reason' WHERE varname='close_reason'");
    }

    private function set_admin_email(){
        $admin_email=$_POST['admin_email']?ZFramework::maple_quotes($_POST['admin_email']):'dreamneverfall@gmail.com';
        $this->_model->query("UPDATE sysvar SET varvalue='$admin_email' WHERE varname='admin_email'");
    }

    private function set_copyright_info(){
        @$copyright_info=$_POST['copyright_info']?ZFramework::maple_quotes($_POST['copyright_info']):'Copyright &copy; 2010 mapleleaf.googlecode.com';
        $this->_model->query("UPDATE sysvar SET varvalue='$copyright_info' WHERE varname='copyright_info'");
    }

    private function set_filter_words(){
        $filter_words=$_POST['filter_words']?  ZFramework::fix_filter_string($_POST['filter_words']):'';
        $this->_model->query("UPDATE sysvar SET varvalue='$filter_words' WHERE varname='filter_words'");
    }

    private function set_valid_code_open(){
        $valid_code_open=(int)$_POST['valid_code_open'];
        $this->_model->query("UPDATE sysvar SET varvalue='$valid_code_open' WHERE varname='valid_code_open'");
    }

    private function set_page_on(){
        $page_on=(int)$_POST['page_on'];
        $this->_model->query("UPDATE sysvar SET varvalue='$page_on' WHERE varname='page_on'");
    }

    private function set_num_perpage(){
        $num_perpage=(int)$_POST['num_perpage'];
        $this->_model->query("UPDATE sysvar SET varvalue='$num_perpage' WHERE varname='num_perpage'");
    }

    private function set_theme(){
        $theme=in_array($_POST['theme'], ZFramework::get_all_themes())?$_POST['theme']:'simple';
        $this->_model->query("UPDATE sysvar SET varvalue='$theme' WHERE varname='theme'");
    }

    private function set_time_zone(){
        $timezone=(isset($_POST['timezone']) && in_array($_POST['timezone'],array_keys(ZFramework::get_all_timezone())))?$_POST['timezone']:'0';
        $this->_model->query("UPDATE sysvar SET varvalue='$timezone' WHERE varname='timezone'");
    }

    private function set_lang(){
        $lang=(isset($_POST['lang']) && in_array($_POST['lang'],  ZFramework::get_all_langs()))?$_POST['lang']:'en';
        $this->_model->query("UPDATE sysvar SET varvalue='$lang' WHERE varname='lang'");
    }

    private function set_admin_password(){
        $password=isset($_POST['password']) && !empty($_POST['password'])?ZFramework::maple_quotes($_POST['password']):$this->_admin_password;
        $this->_model->query("UPDATE sysvar SET varvalue='$password' WHERE varname='password'");
    }
}