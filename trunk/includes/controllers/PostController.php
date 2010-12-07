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
        is_admin();
	if(isset($_POST['Submit']))
	{
	    $mid=0;
	    $mid=(int)$_POST['mid'];
	    $author=$_POST['author'];
	    $m_time=$_POST['m_time'];
	    $update_content = FrontController::maple_quotes($_POST['update_content']);
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
            $this->show_message(FrontController::t('QUERY_ERROR'),TRUE,'index.php?action=control_panel&subtab=message');
	$message_info=$message_info[0];
        include 'themes/'.FrontController::getInstance()->_theme.'/templates/'."update.php";
    }
    public function actionDelete()
    {
        
    }
    public  function delete_multi_messages(){
        // Check whether admin had selected some options
        if(!isset($_POST['select_mid'])){
            header("location:index.php?action=control_panel&subtab=message");
            exit;
        }
	$del_ids=$_POST['select_mid'];
        $del_num=count($del_ids);
        for($i=0;$i<$del_num;$i++)
        {
            $deleted_id=(int)$del_ids[$i];
            $this->delete_message($deleted_id);
            if ($_POST[$deleted_id]==1)
                $this->delete_reply($deleted_id);
        }
        header("Location:index.php?action=control_panel&subtab=message&randomvalue=".rand());
    }
    public  function delete_message($from_function=false)
    {
        is_admin();
        if($from_function)
            $mid=$from_function;
        else
            $mid=intval($_GET['mid']);
        if(isset($mid))
        {
	    $condition=array('id'=>$mid);
	    if(!$this->_model->delete(MESSAGETABLE, $condition))
		die($this->_model->error());
        }
        //若回复中有关于此留言的记录，执行删除回复操作
        @$reply_del=(int)$_GET['reply'];
        if($reply_del==1)
            $this->delete_reply($mid);
        header("Location:index.php?action=control_panel&subtab=message&randomvalue=".rand());
    }
    public  function clear_all()
    {
        is_admin();
        $message_table_path=  $this->_model->_table_path(DB, MESSAGETABLE);
	$message_filename=$message_table_path.$this->_model->get_data_ext();
        file_put_contents($message_filename, '');
        $this->clear_reply();
        header("location:index.php?action=control_panel&subtab=message");
    }
}