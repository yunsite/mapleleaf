<?php
class user extends BaseController{
    public $_model;
    public function index(){
        header("Location:index.php");
    }
    public function  __construct() {
        $this->_model=new JuneTxtDB();
        $this->_model->select_db(DB);
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
            $user=FrontController::maple_quotes($_POST['user']);
            $password=FrontController::maple_quotes($_POST['password']);
	    if( ($user==FrontController::getInstance()->_admin_name) && ($password==FrontController::getInstance()->_admin_password) )//若使用管理员帐户成功登录
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
		    $errormsg=FrontController::t('LOGIN_ERROR');
		}
	    }
        }
	include 'themes/'.FrontController::getInstance()->_theme.'/templates/'."login.php";
    }

    public  function logout(){
	if(isset ($_SESSION['user'])){
	    unset ($_SESSION['user']);
	    session_destroy();
	}
        if(isset($_SESSION['admin'])){
            $this->delete_backup_files();
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
                if(strlen(trim($_POST['user']))>=2){
                    $user=FrontController::maple_quotes($_POST['user']);
                    $pwd=FrontController::maple_quotes($_POST['pwd']);
                    $email=$_POST['email'];
                    if(is_email($email)){
                        $user_exists=$this->_model->select(USERTABLE, array('user'=>$user));
                        if(!$user_exists && $user!= FrontController::getInstance()->_admin_name){
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
                            $errorMsg=FrontController::t('USERNAME_NOT_AVAILABLE');
                        }
                    }else{
                        $errorMsg=FrontController::t('EMAIL_INVALID');
                    }
                }else{
                    $errorMsg=FrontController::t('USERNAME_TOO_SHORT');
                }
	    }else{
		$errorMsg=FrontController::t('FILL_NOT_COMPLETE');
	    }
	    if(isset ($_POST['ajax'])){
		die ($errorMsg);
	    }
	}
	include 'themes/'.FrontController::getInstance()->_theme.'/templates/'."register.php";
    }

    public function user_update(){
	if((!isset($_SESSION['admin']) && !isset($_SESSION['uid'])) || !isset($_GET['uid']) || (!isset($_SESSION['admin']) && $_GET['uid']!=$_SESSION['uid'])){
	    header("Location:index.php");exit;
	}
	$uid=$_GET['uid'];
	if(isset ($_POST['user'])){
	    if(!empty ($_POST['user']) && !empty ($_POST['pwd']) && !empty ($_POST['email'])){
		$user=FrontController::maple_quotes($_POST['user']);
		$pwd=FrontController::maple_quotes($_POST['pwd']);
		$email=$_POST['email'];
		if(is_email($email)){
		    $newdata=array($uid,$user,$pwd,$email);
		    $condition=array('uid'=>$uid);
		    if($this->_model->update(USERTABLE, $condition, $newdata)){
			header("Location:index.php");exit;
		    }else{
			$errorMsg=FrontController::t('USERUPDATEFAILED');
		    }
		}else{
		    $errorMsg=FrontController::t('EMAIL_INVALID');
		}
	    }else{
		$errorMsg=FrontController::t('FILL_NOT_COMPLETE');
	    }
	}
	$user_data=$this->_model->select(USERTABLE, array('uid'=>$uid));
	$user_data=$user_data[0];
	include 'themes/'.FrontController::getInstance()->_theme.'/templates/'."user_update.php";
    }
	    /**
     * 删除服务器上的备份文件，会在管理员注销登录时执行
     */
    public  function delete_backup_files(){
        is_admin();
	$d=dir($this->_model->_db_path(DB));
	while(false!==($entry=$d->read()))
	{
	    if (strlen($entry)==19)
	    {
		$d_file=$this->_model->_db_path(DB).'/'.$entry;
		unlink($d_file);
	    }
	}
	$d->close();
    }
}