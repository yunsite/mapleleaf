<?php
class PostController extends BaseController{
    public  $_model;
    public  $_verifyCode;
    public function  __construct(){
        global $db_url;
        $this->_model=  YDB::factory($db_url);
        $this->_verifyCode=new FLEA_Helper_ImgCode();
    }
    public function actionCreate(){
        if(isset ($_POST)){
            $new_data=array();
            $user=isset($_POST['user'])?$_POST['user']:'';
            $current_ip=getIp();
            $user=ZFramework::maple_quotes($user);
            $admin_name_array=array(ZFramework::app()->admin);
            if(!isset($_SESSION['admin']) && in_array(strtolower($user),$admin_name_array))
                $user='anonymous';
            $allUsers=$this->_model->queryAll("SELECT * FROM user WHERE username='".$user."'");
            if($allUsers && (@$_SESSION['user']!=$_POST['user']))
                $user='anonymous';
            $content =isset($_POST['content'])?ZFramework::maple_quotes($_POST['content']):'';
            $content = nl2br($content);
            $content = str_replace(array("\n", "\r\n", "\r"), '', $content);
            $time=time();
            if(empty($user) or empty($content))
                $new_data_error_msg=ZFramework::t('FILL_NOT_COMPLETE');
            elseif(strlen($content)>580)
                $new_data_error_msg=ZFramework::t('WORDS_TOO_LONG');
            elseif(ZFramework::app()->valid_code_open==1 && gd_loaded()){
                if(!$this->_verifyCode->check($_POST['valid_code']))
                    $new_data_error_msg=ZFramework::t('CAPTCHA_WRONG');
            }
            if(isset ($new_data_error_msg)){
                if(isset($_POST['ajax'])){
                    die($new_data_error_msg);
                }else
                    ZFramework::show_message($new_data_error_msg,true,'index.php');
            }

            if(isset ($_SESSION['uid']))
                $sql_insert=sprintf("INSERT INTO post ( uid , content , post_time , ip ) VALUES ( %d , '%s' , %d , '%s' )",$_SESSION['uid'],$content,time(),  getIp());
            else
                $sql_insert=sprintf ("INSERT INTO post ( uname , content , post_time , ip ) VALUES ( '%s' , '%s' , %d , '%s')", $user,$content,  time (),  getIp ());
            if(!$this->_model->query($sql_insert))
                die($this->_model->error());
            if(isset($_POST['ajax'])){
                echo 'OK';
                return TRUE;
            }
        }
        header("Location:index.php");
    }
    public function actionUpdate(){
        is_admin();
	if(isset($_POST['Submit'])){
	    $mid=0;
	    $mid=(int)$_POST['mid'];
	    $update_content = ZFramework::maple_quotes($_POST['update_content']);
	    $update_content = nl2br($update_content);
	    $update_content = str_replace(array("\n", "\r\n", "\r"), '', $update_content);
            $this->_model->query("UPDATE post SET content='$update_content' WHERE pid=$mid");
            header("Location:index.php?action=control_panel&subtab=message");
	}
	if(!isset($_GET['mid'])){
	    header("location:index.php?action=control_panel&subtab=message");exit;
	}
        $mid=intval($_GET['mid']);
	$condition=array('id'=>$mid);
        $message_info=$this->_model->queryAll("SELECT * FROM post WHERE pid=$mid");
        if(!$message_info)
            ZFramework::show_message(ZFramework::t('QUERY_ERROR'),TRUE,'index.php?action=control_panel&subtab=message');
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
        $this->_model->query("DELETE FROM post WHERE pid=$mid");
        $this->_model->query("DELETE FROM reply WHERE pid=$mid");
        header("Location:index.php?action=control_panel&subtab=message&randomvalue=".rand());
    }
    public  function actionDelete_multi_messages(){
        is_admin();
        if(!isset($_POST['select_mid'])){header("location:index.php?action=control_panel&subtab=message");exit;}
	$del_ids=$_POST['select_mid'];
        $del_num=count($del_ids);
        for($i=0;$i<$del_num;$i++){
            $deleted_id=(int)$del_ids[$i];
            $this->_model->query("DELETE FROM post WHERE pid=$deleted_id");
            $this->_model->query("DELETE FROM reply WHERE pid=$deleted_id");
        }
        header("Location:index.php?action=control_panel&subtab=message&randomvalue=".rand());
    }

    public  function actionDeleteAll(){
        is_admin();
        $this->_model->query("DELETE FROM post");
        $this->_model->query("DELETE FROM reply");
        header("location:index.php?action=control_panel&subtab=message");
    }
}