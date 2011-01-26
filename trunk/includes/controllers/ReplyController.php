<?php
class ReplyController extends BaseController{
    public $_model;
    public function  __construct(){
        global $db_url;
        $this->_model=  YDB::factory($db_url);
    }
    /**
     * create and update
     */
    public function actionReply(){
        is_admin();
	if($_POST){
	    $mid=0;
	    $mid=(int)$_POST['mid'];
	    $reply_content = ZFramework::maple_quotes($_POST['content']);
	    $reply_content = nl2br($reply_content);
	    $reply_content = str_replace(array("\n", "\r\n", "\r"), '', $reply_content);
	    $time=time();
	    if (trim($reply_content)=='')
		ZFramework::show_message(ZFramework::t('REPLY_EMPTY'),true,'index.php?action=control_panel&subtab=message',3);
	    if(isset($_POST['update'])){
                $this->_model->query("UPDATE reply SET content='$reply_content' WHERE pid=$mid");
	    }
	    else{
                $this->_model->query("INSERT INTO reply ( pid , content , r_time ) VALUES ( $mid , '$reply_content' , $time )");
	    }
	    header("Location:index.php?action=control_panel&subtab=message");exit;
	}
	$reply_data=$this->loadModel();
	$mid=(int)$_GET['mid'];
	include 'themes/'.ZFramework::app()->theme.'/templates/'."reply.php";
    }

    protected function loadModel(){
	if(!isset($_GET['mid'])){
	    header("location:index.php?action=control_panel&subtab=message");
	    exit;
	}
	$mid=(int)$_GET['mid'];
        $reply_data=$this->_model->queryAll("SELECT * FROM reply WHERE pid=$mid");
        if($reply_data)
            $reply_data=$reply_data[0];
        return $reply_data;
	    #ZFramework::show_message(ZFramework::t("QUERY_ERROR"),TRUE,'index.php?action=control_panel&subtab=message');

    }
    public  function actionDelete(){
        is_admin();
        $mid=isset($_GET['mid'])?(int)$_GET['mid']:null;
        if($mid!==null){
            $this->_model->query("DELETE FROM reply WHERE pid=$mid");
        }
        header("Location:index.php?action=control_panel&subtab=message&randomvalue=".rand());
    }
    public  function actionDeleteAll(){
        is_admin();
        $this->_model->query("DELETE FROM reply");
        header("location:index.php?action=control_panel&subtab=message");
    }
}