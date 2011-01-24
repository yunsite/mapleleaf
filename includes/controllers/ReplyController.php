<?php
class ReplyController extends BaseController
{
    public $_model;
    public function  __construct()
    {
        $this->_model=new JuneTxtDB();
        $this->_model->select_db(DB);
    }
    /**
     * create and update
     */
    public function actionReply()
    {
        is_admin();
	if($_POST){
	    $mid=0;
	    $mid=(int)$_POST['mid'];
	    $reply_content = ZFramework::maple_quotes($_POST['reply_content']);
	    $reply_content = nl2br($reply_content);
	    $reply_content = str_replace(array("\n", "\r\n", "\r"), '', $reply_content);
	    $time=time();
	    if (trim($reply_content)=='')
		ZFramework::show_message(ZFramework::t('REPLY_EMPTY'),true,'index.php?action=control_panel&subtab=message',3);
	    if(isset($_POST['update'])){
		$input=array($mid,$reply_content,$time);
		$condition=array('id'=>$mid);
		if(!$this->_model->update(REPLYTABLE, $condition, $input))
		    die($this->_model->error());
	    }
	    else{
		$input=array($mid,$reply_content,$time);
		if(!$this->_model->insert(REPLYTABLE, $input))
		    die($this->_model->error());
	    }
	    header("Location:index.php?action=control_panel&subtab=message");exit;
	}
	$reply_data=$this->loadModel();
	$mid=(int)$_GET['mid'];
	include 'themes/'.ZFramework::app()->theme.'/templates/'."reply.php";
    }

    protected function loadModel()
    {
	if(!isset($_GET['mid']))
	{
	    header("location:index.php?action=control_panel&subtab=message");
	    exit;
	}
	$mid=(int)$_GET['mid'];
	$condition=array('id'=>$mid);
	$reply_data=$this->_model->select(REPLYTABLE, $condition);
	if ($reply_data===FALSE)
	    ZFramework::show_message(ZFramework::t("QUERY_ERROR"),TRUE,'index.php?action=control_panel&subtab=message');
	else
	    return $reply_data;
    }
    public  function actionDelete()
    {
        is_admin();
        $mid=isset($_GET['mid'])?(int)$_GET['mid']:null;
        if($mid!==null){
	    $condition=array('id'=>$mid);
	    if(!$this->_model->delete(REPLYTABLE, $condition))
		die($this->_model->error());
        }
        header("Location:index.php?action=control_panel&subtab=message&randomvalue=".rand());
    }
    public  function actionDeleteAll()
    {
        is_admin();
        $this->_model->truncate(DB, REPLYTABLE);
        header("location:index.php?action=control_panel&subtab=message");
    }
    
}