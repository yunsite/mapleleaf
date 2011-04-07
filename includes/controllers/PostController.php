<?php
class PostController extends BaseController{
    public  $_model;
    public  $_verifyCode;
    public function  __construct(){
        global $db_url;
        $this->_model=  YDB::factory($db_url);
        $this->_verifyCode=new FLEA_Helper_ImgCode();
    }
    /**
     *
     * @return mixed
     */
    public function actionCreate(){
        global $API_CODE;
        if(isset ($_POST['user'])){//只允许 POST 方式
            //插入到数据库前的验证
            $new_data_error_msg='';
            if ( !strlen(trim($_POST['user'])) || !strlen(trim($_POST['content'])))
                $new_data_error_msg=t('FILL_NOT_COMPLETE');
            elseif(strlen($_POST['content']>580))
                $new_data_error_msg=t('WORDS_TOO_LONG');
            elseif (ZFramework::app()->valid_code_open==1 && gd_loaded() && !$this->_verifyCode->check($_POST['valid_code']))
                $new_data_error_msg=t('CAPTCHA_WRONG');
            if($new_data_error_msg){
                if(defined('API_MODE')){
                    $error_array=array('error_code'=>'400','error'=>$API_CODE['400'],'error_detail'=>$new_data_error_msg);
                    die(function_exists('json_encode') ? json_encode($error_array) : CJSON::encode($error_array));
                }
                if(!empty ($_POST['ajax']))
                    die ($new_data_error_msg);
                else
                    show_message ($new_data_error_msg, true, 'index.php');
            }
            //准备插入数据
            $user=  $this->_model->escape_string($_POST['user']);
            if(!isset($_SESSION['admin']) && $_POST['user']==ZFramework::app()->admin )
                $user='anonymous';
            $userExists=  $this->_model->queryAll(sprintf(parse_tbprefix("SELECT * FROM <user> WHERE username='%s'"),  $this->_model->escape_string($_POST['user'])));
            if($userExists && (@$_SESSION['user']!=$_POST['user']))
                $user='anonymous';
            $content = $this->_model->escape_string($_POST['content']);
            if(isset ($_SESSION['uid']))
                $sql_insert= sprintf (parse_tbprefix("INSERT INTO <post> ( uid , content , post_time , ip ) VALUES ( %d , '%s' , %d , '%s' )"), $_SESSION['uid'],$content,  time (),  getIp ());
            else
                $sql_insert = sprintf (parse_tbprefix("INSERT INTO <post> ( uname , content , post_time , ip ) VALUES ( '%s' ,'%s' , %d , '%s')"), $user,$content,  time (),  getIp ());
            //写入数据库
            if(!$this->_model->query($sql_insert))
                die($this->_model->error());
            if(defined('API_MODE')){
                $json_array=array('insert_id'=>  $this->_model->insert_id());
                die (function_exists('json_encode') ? json_encode($json_array) : CJSON::encode($json_array));
            }
            if(isset($_POST['ajax'])){
                echo 'OK';
                return TRUE;
            }
        }
        if(defined('API_MODE')){
            $error_array=array('error_code'=>'400','error'=>$API_CODE['400'],'error_detail'=>t('ONLY_POST'));
            die(function_exists('json_encode') ? json_encode($error_array) : CJSON::encode($error_array));
        }
        header("Location:index.php");
    }
    public function actionUpdate(){
        is_admin();
	if(isset($_POST['Submit'])){
	    $mid=(int)$_POST['mid'];
	    $update_content = $this->_model->escape_string(str_replace(array("\n", "\r\n", "\r"), '', nl2br($_POST['update_content'])));
            $this->_model->query(sprintf(parse_tbprefix("UPDATE <post> SET content='%s' WHERE pid=%d"),$update_content,$mid));
            header("Location:index.php?action=control_panel&subtab=message");
	}
        $mid=(int)$_GET['mid'];
        $message_info=$this->_model->queryAll(sprintf(parse_tbprefix("SELECT * FROM <post> WHERE pid=%d"),$mid));
        if(!$message_info)
            show_message(t('QUERY_ERROR'),TRUE,'index.php?action=control_panel&subtab=message');
	$message_info=$message_info[0];
        $this->render('update', array(
            'message_info'=>$message_info,
            'mid'=>$mid,
        ));
    }
    public function actionDelete(){
        is_admin();
        $mid=isset ($_GET['mid'])?(int)$_GET['mid']:null;
        if(!$mid){
            header("Location:index.php?action=control_panel&amp;subtab=message");exit;
        }
        $this->_model->query(parse_tbprefix("DELETE FROM <post> WHERE pid=$mid"));
        $this->_model->query(parse_tbprefix("DELETE FROM <reply> WHERE pid=$mid"));
        header("Location:index.php?action=control_panel&subtab=message&randomvalue=".rand());
    }
    public  function actionDelete_multi_messages(){
        is_admin();
        if(!isset($_POST['select_mid'])){header("location:index.php?action=control_panel&subtab=message");exit;}
	$del_ids=$_POST['select_mid'];
        foreach($del_ids as $deleted_id){
            $this->_model->query(parse_tbprefix("DELETE FROM <post> WHERE pid=$deleted_id"));
            $this->_model->query(parse_tbprefix("DELETE FROM <reply> WHERE pid=$deleted_id"));
        }
        header("Location:index.php?action=control_panel&subtab=message&randomvalue=".rand());
    }

    public  function actionDeleteAll(){
        is_admin();
        $this->_model->query(parse_tbprefix("DELETE FROM <post>"));
        $this->_model->query(parse_tbprefix("DELETE FROM <reply>"));
        header("location:index.php?action=control_panel&subtab=message");
    }
}
