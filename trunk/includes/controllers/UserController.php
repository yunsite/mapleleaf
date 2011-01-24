<?php
class UserController extends BaseController
{
    public $_model;
    public function  __construct()
    {
        $this->_model=new JuneTxtDB();
        $this->_model->select_db(DB);
    }
    public function actionCreate()
    {
        if(isset ($_SESSION['admin']) || isset ($_SESSION['user'])){
	    header("Location:index.php");exit;
	}
	if(isset ($_POST['register'])){
	    if(!empty ($_POST['user']) && !empty ($_POST['pwd']) && !empty ($_POST['email'])){
                if(strlen(trim($_POST['user']))>=2){
                    $user=ZFramework::maple_quotes($_POST['user']);
                    $pwd=ZFramework::maple_quotes($_POST['pwd']);
                    $email=$_POST['email'];
                    if(is_email($email)){
                        $user_exists=$this->_model->select(USERTABLE, array('user'=>$user));
                        if(!$user_exists && $user!= ZFramework::app()->admin){
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
                            $errorMsg=ZFramework::t('USERNAME_NOT_AVAILABLE');
                        }
                    }else{
                        $errorMsg=ZFramework::t('EMAIL_INVALID');
                    }
                }else{
                    $errorMsg=ZFramework::t('USERNAME_TOO_SHORT');
                }
	    }else{
		$errorMsg=ZFramework::t('FILL_NOT_COMPLETE');
	    }
	    if(isset ($_POST['ajax'])){
		die ($errorMsg);
	    }
	}
	include 'themes/'.ZFramework::app()->theme.'/templates/'."register.php";
    }
    public function actionUpdate()
    {
        if((!isset($_SESSION['admin']) && !isset($_SESSION['uid'])) || !isset($_GET['uid']) || (!isset($_SESSION['admin']) && $_GET['uid']!=$_SESSION['uid'])){
	    header("Location:index.php");exit;
	}
	$uid=$_GET['uid'];
	if(isset ($_POST['user'])){
	    if(!empty ($_POST['user']) && !empty ($_POST['pwd']) && !empty ($_POST['email'])){
		$user=ZFramework::maple_quotes($_POST['user']);
		$pwd=ZFramework::maple_quotes($_POST['pwd']);
		$email=$_POST['email'];
		if(is_email($email)){
		    $newdata=array($uid,$user,$pwd,$email);
		    $condition=array('uid'=>$uid);
		    if($this->_model->update(USERTABLE, $condition, $newdata)){
			header("Location:index.php");exit;
		    }else{
			$errorMsg=ZFramework::t('USERUPDATEFAILED');
		    }
		}else{
		    $errorMsg=ZFramework::t('EMAIL_INVALID');
		}
	    }else{
		$errorMsg=ZFramework::t('FILL_NOT_COMPLETE');
	    }
	}
	$user_data=$this->_model->select(USERTABLE, array('uid'=>$uid));
	$user_data=$user_data[0];
	include 'themes/'.ZFramework::app()->theme.'/templates/'."user_update.php";
    }
    public function actionDelete()
    {
        
    }
    public function actionLogin()
    {
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
            $user=ZFramework::maple_quotes($_POST['user']);
            $password=ZFramework::maple_quotes($_POST['password']);
	    if( ($user==ZFramework::app()->admin) && ($password==ZFramework::app()->password) )//若使用管理员帐户成功登录
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
		    $errormsg=ZFramework::t('LOGIN_ERROR');
		}
	    }
        }
	include 'themes/'.ZFramework::app()->theme.'/templates/'."login.php";
    }
    public function actionLogout()
    {
        if(isset ($_SESSION['user'])){
	    unset ($_SESSION['user']);
	    session_destroy();
	}
        if(isset($_SESSION['admin'])){
            BackupController::delete_backup_files();
            unset($_SESSION['admin']);
            session_destroy();
        }
        header("Location:index.php");exit;
    }
}