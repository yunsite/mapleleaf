<?php
class ReplyController extends BaseController
{
    public $_model;
    public function  __construct()
    {
        $this->_model=new JuneTxtDB();
        $this->_model->select_db(DB);
    }
    public function actionCreate()
    {
        is_admin();
	if(isset($_POST['Submit'])){
	    $mid=0;
	    $mid=(int)$_POST['mid'];
	    $reply_content = FrontController::maple_quotes($_POST['reply_content']);
	    $reply_content = nl2br($reply_content);
	    $reply_content = str_replace(array("\n", "\r\n", "\r"), '', $reply_content);
	    $time=time();
	    if (trim($reply_content)=='')
		$this->show_message(FrontController::t('REPLY_EMPTY'),true,'index.php?action=control_panel&subtab=message',3);
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
	include 'themes/'.FrontController::getInstance()->_theme.'/templates/'."reply.php";
    }
    public function actionUpdate()
    {
        
    }
    public function actionDelete()
    {
        
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
	    $this->show_message(FrontController::t("QUERY_ERROR"),TRUE,'index.php?action=control_panel&subtab=message');
	else
	    return $reply_data;
    }
    public  function delete_reply($from_delete_message=false)
    {
        is_admin();
        if($from_delete_message)
            $mid=$from_delete_message;
        else
            $mid=intval($_GET['mid']);
        if(isset($mid))
        {
	    $condition=array('id'=>$mid);
	    if(!$this->_model->delete(REPLYTABLE, $condition))
		die($this->_model->error());
        }
        header("Location:index.php?action=control_panel&subtab=message&randomvalue=".rand());
    }
    public  function clear_reply()
    {
        is_admin();
        $reply_table_path=$this->_model->_table_path(DB,REPLYTABLE);
        $reply_filename=$reply_table_path.$this->_model->get_data_ext();
        file_put_contents($reply_filename,'');
        header("location:index.php?action=control_panel&subtab=message");
    }
    public  function get_all_reply()
    {
        $reply_data=array();
	if(($reply_data=$this->_model->select(REPLYTABLE))===FALSE)
	    die($this->_model->error());
        return $reply_data;
    }
}