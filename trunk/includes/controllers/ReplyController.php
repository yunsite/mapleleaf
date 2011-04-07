<?php
class ReplyController extends BaseController{
    public $_model;
    public function  __construct(){
        global $db_url;
        $this->_model=  YDB::factory($db_url);
    }
    public function actionReply(){
        is_admin();
	if($_POST){
	    $mid=(int)$_POST['mid'];
	    $reply_content = $this->_model->escape_string(str_replace(array("\n", "\r\n", "\r"), '', nl2br($_POST['content'])));
	    if (trim($reply_content)=='')
		show_message(t('REPLY_EMPTY'),true,'index.php?action=control_panel&subtab=message',3);
	    if(isset($_POST['update']))
                $this->_model->query(sprintf(parse_tbprefix("UPDATE <reply> SET content='%s' WHERE pid=%d"),$reply_content,$mid));
	    else
                $this->_model->query(sprintf(parse_tbprefix("INSERT INTO <reply> ( pid , content , r_time ) VALUES ( %d , '%s' , %d )"),$mid,$reply_content,time()));
	    header("Location:index.php?action=control_panel&subtab=message");exit;
	}
	$reply_data=$this->loadModel();
	$mid=(int)$_GET['mid'];
	include 'themes/'.ZFramework::app()->theme.'/templates/'."reply.php";
    }

    protected function loadModel(){
	if(!isset($_GET['mid'])){
	    header("location:index.php?action=control_panel&subtab=message");exit;
	}
	$mid=(int)$_GET['mid'];
        $reply_data=$this->_model->queryAll(sprintf(parse_tbprefix("SELECT * FROM <reply> WHERE pid=%d"),$mid));
        if($reply_data)
            $reply_data=$reply_data[0];
        return $reply_data;
    }
    public  function actionDelete(){
        is_admin();
        $mid=isset($_GET['mid'])?(int)$_GET['mid']:null;
        if($mid!==null){
            $this->_model->query(sprintf(parse_tbprefix("DELETE FROM <reply> WHERE pid=%d"),$mid));
        }
        header("Location:index.php?action=control_panel&subtab=message&randomvalue=".rand());
    }
    public  function actionDeleteAll(){
        is_admin();
        $this->_model->query(parse_tbprefix("DELETE FROM <reply>"));
        header("location:index.php?action=control_panel&subtab=message");
    }
}
