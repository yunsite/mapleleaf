<?php
class configuration {
    //public  $borad_name='test';
    public static function get_board_name(){
        include 'config.php';
        if(isset ($board_name)){
            return $board_name;
        }  else {
            throw new Exception(Maple_Controller::translate('SITENAME_ERROR'));
        }
    }
    public static function get_mb_open(){
        include 'config.php';
        if(isset ($mb_open))
            return $mb_open;
        else
            throw new Exception(Maple_Controller::translate('SITESTATUS_ERROR'));
    }
    public static function get_close_reason(){
        include 'config.php';
        if(isset ($close_reason))
            return $close_reason;
        else
            throw new Exception(Maple_Controller::translate('SITECLOSEREASON_ERROR'));
    }
    public static function get_admin_email(){
        include 'config.php';
        if(isset ($admin_email))
            return $admin_email;
        else
            throw new Exception(Maple_Controller::translate('ADMINEMAIL_ERROR'));
    }
    public static function get_copyright_info(){
        include 'config.php';
        if(isset ($copyright_info))
            return $copyright_info;
        else
            throw new Exception(Maple_Controller::translate('COPYRIGHT_ERROR'));
    }
    public static function get_filter_words(){
        include 'config.php';
        if(isset ($filter_words))
            return $filter_words;
        else
            throw new Exception(Maple_Controller::translate('BADWORDS_ERROR'));
    }
    public static function get_valid_code_open(){
        include 'config.php';
        if(isset ($valid_code_open))
            return $valid_code_open;
        else
            throw new Exception(Maple_Controller::translate('CAPTCHASTATUS_ERROR'));
    }
    public static function get_page_on(){
        include 'config.php';
        if(isset ($page_on))
            return $page_on;
        else
            throw new Exception(Maple_Controller::translate('PAGINATIONSTATUS_ERROR'));
    }
    public static function get_num_perpage(){
        include 'config.php';
        if(isset ($num_perpage))
            return $num_perpage;
        else
            throw new Exception(Maple_Controller::translate('PAGINATION_PARAMETER_ERROR'));
    }
    public static function get_theme(){
        include 'config.php';
        if(isset ($theme))
            return $theme;
        else
            throw new Exception(Maple_Controller::translate('THEME_ERROR'));
    }
    public static function get_admin_name(){
        include 'config.php';
        if(isset ($admin))
            return $admin;
        else
            throw new Exception(Maple_Controller::translate('ADMINNAME_ERROR'));
    }
    public static function get_admin_password(){
        include 'config.php';
        if(isset ($password))
            return $password;
        else
            throw new Exception(Maple_Controller::translate('ADMINPASS_ERROR'));
    }
    public static function get_time_zone(){
        include 'config.php';
        if(isset ($timezone))
            return $timezone;
        else
            throw new Exception(Maple_Controller::translate('TIMEZONE_ERROR'));
    }
    public static function get_current_lang(){
        include 'config.php';
        if(isset ($lang))
            return $lang;
        else
            throw new Exception(Maple_Controller::translate('ADMINNAME_ERROR'));
    }
}