<?php
class PostController extends BaseController
{
    public  $_model;
    public  $_verifyCode;
    public function  __construct()
    {
        $this->_model=new JuneTxtDB();
        $this->_model->select_db(DB);
        $this->_verifyCode=new FLEA_Helper_ImgCode();
    }
    public function actionCreate()
    {
        if(isset ($_POST)){
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
                $new_data_error_msg=ZFramework::t('FILL_NOT_COMPLETE');
            }
            elseif(strlen($content)>580)
            {
                $new_data_error_msg=ZFramework::t('WORDS_TOO_LONG');
            }
            elseif(ZFramework::app()->valid_code_open==1)
            {
                if(!$this->_verifyCode->check($_POST['valid_code'])){
                $new_data_error_msg=ZFramework::t('CAPTCHA_WRONG');
                }
            }
            if(isset ($new_data_error_msg)){
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
        is_admin();
	if(isset($_POST['Submit']))
	{
	    $mid=0;
	    $mid=(int)$_POST['mid'];
	    $author=$_POST['author'];
	    $m_time=$_POST['m_time'];
	    $update_content = ZFramework::maple_quotes($_POST['update_content']);
	    $update_content = nl2br($update_content);
	    $update_content = str_replace(array("\n", "\r\n", "\r"), '', $update_content);
	    $ip=$_POST['ip'];
	    $input=array($mid,$author,$update_content,$m_time,$ip);
	    $condition=array('id'=>$mid);
	    if(!$this->_model->update(MESSAGETABLE, $condition, $input))
		die($this->_model->error());
	    else
		header("Location:index.php?action=control_panel&subtab=message");
	}
	if(!isset($_GET['mid']))
	{
	    header("location:index.php?action=control_panel&subtab=message");exit;
	}
        $mid=intval($_GET['mid']);
	$condition=array('id'=>$mid);
	$message_info=$this->_model->select(MESSAGETABLE, $condition);
        if(!$message_info)
            ZFramework::show_message(ZFramework::t('QUERY_ERROR'),TRUE,'index.php?action=control_panel&subtab=message');
	$message_info=$message_info[0];
        $this->render('update', array(
            'message_info'=>$message_info,
            'mid'=>$mid,
        ));
    }
    public function actionDelete()
    {
        is_admin();
        $mid=isset ($_GET['mid'])?(int)$_GET['mid']:null;
        if(!$mid){
            header("Location:index.php?action=control_panel&amp;subtab=message");exit;
        }
        $condition=array('id'=>$mid);
        if(!$this->_model->delete(MESSAGETABLE, $condition))
            die($this->_model->error());
        if((int)$_GET['reply']){
            $condition=array('id'=>$mid);
            $this->_model->delete(REPLYTABLE, $condition);
        }
        header("Location:index.php?action=control_panel&subtab=message&randomvalue=".rand());
    }
    public  function actionDelete_multi_messages(){
        if(!isset($_POST['select_mid'])){header("location:index.php?action=control_panel&subtab=message");exit;}
	$del_ids=$_POST['select_mid'];
        $del_num=count($del_ids);
        for($i=0;$i<$del_num;$i++)
        {
            $deleted_id=(int)$del_ids[$i];
            $condition=array('id'=>$deleted_id);
            $this->_model->delete(MESSAGETABLE, $condition);
            if ($_POST[$deleted_id]==1)
                $this->_model->delete(REPLYTABLE, $condition);
        }
        header("Location:index.php?action=control_panel&subtab=message&randomvalue=".rand());
    }

    public  function actionDeleteAll()
    {
        is_admin();
        $message_table_path=  $this->_model->_table_path(DB, MESSAGETABLE);
	$message_filename=$message_table_path.$this->_model->get_data_ext();
        file_put_contents($message_filename, '');
        $reply_table_path=$this->_model->_table_path(DB,REPLYTABLE);
        $reply_filename=$reply_table_path.$this->_model->get_data_ext();
        file_put_contents($reply_filename, '');
        header("location:index.php?action=control_panel&subtab=message");
    }
}