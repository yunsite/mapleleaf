<?php
class user extends BaseController{
    public $_siteController;
    public $_backupClass;
    public $_model;

    public function  __construct() {
        $this->_siteController=new site();
        $this->_model=new JuneTxtDB();
        $this->_model->select_db(DB);
        $this->_backupClass=new backup();
    }
    public function  __get($propertyName) {
        $methodName='get'.$propertyName;
        try {
            $propertyValue=configuration::$methodName();
            return $propertyValue;
        }  catch (Exception $e){
            die($e);
        }
    }
    public function  __call($name, $arguments) {
        $arguments=implode('', $arguments);
        return $this->_siteController->$name($arguments);
    }
    public  function login(){
        if (isset($_SESSION['admin']))//若管理员已经登录
	{
            header("Location:index.php?action=control_panel");exit;
        }
	if (isset($_SESSION['user']))//若普通用户已经登录
        {
            header("Location:index.php");exit;
        }
        if(isset($_POST['user']) && isset($_POST['password']))//若用户提交了登录表单
        {
            $user=$this->maple_quotes($_POST['user']);
            $password=$this->maple_quotes($_POST['password']);
	    if( ($user==$this->_admin_name) && ($password==$this->_admin_password) )//若使用管理员帐户成功登录
	    {
		$_SESSION['admin']=$_POST['user'];
		header("Location:index.php?action=control_panel");
		exit;
	    }
	    else{//使用普通用户登录
		$user_result=$this->_model->select(USERTABLE,array('user'=>$user));
		$user_result=@$user_result[0];
		if($user_result && $password==$user_result['pwd']){
		    $_SESSION['user']=$_POST['user'];
		    $_SESSION['uid']=$user_result['uid'];
		    header("Location:index.php");exit;
		}else{
		    $errormsg=$this->t('LOGIN_ERROR');
		}
	    }
        }
	include 'themes/'.$this->_theme.'/templates/'."login.php";
    }

    public  function logout(){
	if(isset ($_SESSION['user'])){
	    unset ($_SESSION['user']);
	    session_destroy();
	}
        if(isset($_SESSION['admin'])){
            //$this->delete_backup_files();
            $this->_backupClass->delete_backup_files();
            unset($_SESSION['admin']);
            session_destroy();
        }
        header("Location:index.php");exit;
    }
        /* User Management */
    public function register(){
	if(isset ($_SESSION['admin']) || isset ($_SESSION['user'])){
	    header("Location:index.php");exit;
	}
	if(isset ($_POST['register'])){
	    if(!empty ($_POST['user']) && !empty ($_POST['pwd']) && !empty ($_POST['email'])){
		$user=$this->maple_quotes($_POST['user']);
		$pwd=$this->maple_quotes($_POST['pwd']);
		//if(is_email($_POST['ema']))
		$email=$_POST['email'];
		if(is_email($email)){
		    $user_exists=$this->_model->select(USERTABLE, array('user'=>$user));
		    if(!$user_exists && $user!= $this->_admin_name){
			$user_data=array(NULL,$user,$pwd,$email);
			if($this->_model->insert(USERTABLE, $user_data)){
			    $_SESSION['user']=$user;
			    $_SESSION['uid']=  $this->_model->insert_id();
			    if(isset ($_POST['ajax'])){
				die ('OK');
			    }
			    header("Location:index.php");exit;
			}else{
			    die($this->_model->error());
			}
		    }else{
			$errorMsg=$this->t('USERNAME_NOT_AVAILABLE');
		    }
		}else{
		    $errorMsg=$this->t('EMAIL_INVALID');
		}
	    }else{
		$errorMsg=$this->t('FILL_NOT_COMPLETE');
	    }
	    if(isset ($_POST['ajax'])){
		die ($errorMsg);
	    }
	}
	include 'themes/'.$this->_theme.'/templates/'."register.php";
    }

    public function user_update(){
	if((!isset($_SESSION['admin']) && !isset($_SESSION['uid'])) || !isset($_GET['uid']) || (!isset($_SESSION['admin']) && $_GET['uid']!=$_SESSION['uid'])){
	    header("Location:index.php");exit;
	}
	$uid=$_GET['uid'];
	if(isset ($_POST['user'])){
	    if(!empty ($_POST['user']) && !empty ($_POST['pwd']) && !empty ($_POST['email'])){
		$user=$this->maple_quotes($_POST['user']);
		$pwd=$this->maple_quotes($_POST['pwd']);
		//if(is_email($_POST['ema']))
		$email=$_POST['email'];
		if(is_email($email)){
		    $newdata=array($uid,$user,$pwd,$email);
		    $condition=array('uid'=>$uid);
		    if($this->_model->update(USERTABLE, $condition, $newdata)){
			header("Location:index.php");exit;
		    }else{
			$errorMsg='Update Failed!';
		    }
		}else{
		    $errorMsg='Email invalid!';
		}
	    }else{
		$errorMsg="填写未完成";
	    }
	}
	$user_data=$this->_model->select(USERTABLE, array('uid'=>$uid));
	$user_data=$user_data[0];
	include 'themes/'.$this->_theme.'/templates/'."user_update.php";
    }
}