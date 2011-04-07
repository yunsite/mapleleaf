<?php
class ConfigController extends BaseController{
    private $_admin_password;
    public $_model;
    const FILTER_TRIPTAGS=1;
    const FILTER_ESCAPE=2;

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

        $this->set_filter_type();
        $this->set_allowed_tags();
        
        header("Location:index.php?action=control_panel&subtab=siteset");
    }
    private function set_board_name(){
        $board_name=$_POST['board_name']?maple_quotes($_POST['board_name']):'MapleLeaf';
        $this->_model->query(sprintf(parse_tbprefix("UPDATE <sysvar> SET varvalue='%s' WHERE varname='board_name'"),$board_name));
    }

    private function set_site_close(){
        $site_close=$_POST['site_close']?(int)$_POST['site_close']:0;
        $this->_model->query(sprintf(parse_tbprefix("UPDATE <sysvar> SET varvalue='%s' WHERE varname='site_close'"),$site_close));
    }

    private function set_close_reason(){
        $close_reason=maple_quotes($_POST['close_reason']);
        $this->_model->query(sprintf(parse_tbprefix("UPDATE <sysvar> SET varvalue='%s' WHERE varname='close_reason'"),$close_reason));
    }

    private function set_admin_email(){
        $admin_email=$_POST['admin_email']?maple_quotes($_POST['admin_email']):'dreamneverfall@gmail.com';
        $this->_model->query(sprintf(parse_tbprefix("UPDATE <sysvar> SET varvalue='%s' WHERE varname='admin_email'"),$admin_email));
    }

    private function set_copyright_info(){
        @$copyright_info=$_POST['copyright_info']?maple_quotes($_POST['copyright_info']):'Copyright &copy; 2011 mapleleaf.googlecode.com';
        $this->_model->query(sprintf(parse_tbprefix("UPDATE <sysvar> SET varvalue='%s' WHERE varname='copyright_info'"),$copyright_info));
    }

    private function set_filter_words(){
        $filter_words=$_POST['filter_words']?  fix_filter_string($_POST['filter_words']):'';
        $this->_model->query(sprintf(parse_tbprefix("UPDATE <sysvar> SET varvalue='%s' WHERE varname='filter_words'"),$filter_words));
    }

    private function set_valid_code_open(){
        $valid_code_open=(int)$_POST['valid_code_open'];
        $this->_model->query(sprintf(parse_tbprefix("UPDATE <sysvar> SET varvalue='%s' WHERE varname='valid_code_open'"),$valid_code_open));
    }

    private function set_page_on(){
        $page_on=(int)$_POST['page_on'];
        $this->_model->query(sprintf(parse_tbprefix("UPDATE <sysvar> SET varvalue='%s' WHERE varname='page_on'"),$page_on));
    }

    private function set_num_perpage(){
        $num_perpage=(int)$_POST['num_perpage'];
        $this->_model->query(sprintf(parse_tbprefix("UPDATE <sysvar> SET varvalue='%s' WHERE varname='num_perpage'"),$num_perpage));
    }

    private function set_theme(){
        $theme=in_array($_POST['theme'], get_all_themes())?$_POST['theme']:'simple';
        $this->_model->query(sprintf(parse_tbprefix("UPDATE <sysvar> SET varvalue='%s' WHERE varname='theme'"),$theme));
    }

    private function set_time_zone(){
        $timezone=(isset($_POST['timezone']) && in_array($_POST['timezone'],array_keys(get_all_timezone())))?$_POST['timezone']:'0';
        $this->_model->query(sprintf(parse_tbprefix("UPDATE <sysvar> SET varvalue='%s' WHERE varname='timezone'"),$timezone));
    }

    private function set_lang(){
        $lang=(isset($_POST['lang']) && in_array($_POST['lang'],  get_all_langs()))?$_POST['lang']:'en';
        $this->_model->query(sprintf(parse_tbprefix("UPDATE <sysvar> SET varvalue='%s' WHERE varname='lang'"),$lang));
    }

    private function set_admin_password(){
        $password=isset($_POST['password']) && !empty($_POST['password'])?maple_quotes($_POST['password']):$this->_admin_password;
        $this->_model->query(sprintf(parse_tbprefix("UPDATE <sysvar> SET varvalue='%s' WHERE varname='password'"),$password));
    }

    private function set_filter_type(){
        $this->_model->query(sprintf(parse_tbprefix("UPDATE <sysvar> SET varvalue='%s' WHERE varname='filter_type'"),  $this->_model->escape_string($_POST['filter_type'])));
    }

    private function set_allowed_tags(){
        $this->_model->query(sprintf(parse_tbprefix("UPDATE <sysvar> SET varvalue='%s' WHERE varname='allowed_tags'"),  $this->_model->escape_string($_POST['allowed_tags'])));
    }
}
