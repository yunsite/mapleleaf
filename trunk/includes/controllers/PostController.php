<?php
class PostController extends BaseController
{
    public  $_model;
    public function  __construct()
    {
        $this->_model=new JuneTxtDB();
        $this->_model->select_db(DB);
    }
    public function actionCreate()
    {
        if(isset ($_POST)){
            $new_data_status=TRUE;
            $new_data=array();
            $user=isset($_POST['user'])?$_POST['user']:'';
            $current_ip=getIp();
            $user=ZFramework::maple_quotes($user);
            $admin_name_array=array(ZFramework::app()->admin_name);
            if(!isset($_SESSION['admin']) && in_array(strtolower($user),$admin_name_array))
                $user='anonymous';
            $content =isset($_POST['content'])?ZFramework::maple_quotes($_POST['content']):'';
            $content = nl2br($content);
            $content = str_replace(array("\n", "\r\n", "\r"), '', $content);
            $time=time();
            if(empty($user) or empty($content))
            {
                $new_data_status=FALSE;
                $new_data_error_msg=ZFramework::t('FILL_NOT_COMPLETE');
            }
            elseif(strlen($content)>580)
            {
                $new_data_status=FALSE;
                $new_data_error_msg=ZFramework::t('WORDS_TOO_LONG');
            }
            elseif(ZFramework::app()->valid_code_open==1)
            {
                if(!$this->checkImgcode()){
                $new_data_status=FALSE;
                $new_data_error_msg=ZFramework::t('CAPTCHA_WRONG');
                }
            }
            if(!$new_data_status){
                if(isset($_POST['ajax'])){
                echo $new_data_error_msg;
                return FALSE;
                }else{
                    ZFramework::show_message($new_data_error_msg,true,'index.php');exit;
                }
            }

            $new_data=array(NULL,$user,$content,$time,$current_ip);
            if(!$this->_model->insert(MESSAGETABLE, $new_data))
                die($this->_model->error());
            if(isset($_POST['ajax'])){
                echo 'OK';
                return TRUE;
            }
        }
        header("Location:index.php");
    }
    public function actionUpdate()
    {
        
    }
    public function actionDelete()
    {
        
    }
}