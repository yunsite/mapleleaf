<?php
class UserController extends BaseController
{
    public $_model;
    public function  __construct()
    {
        $this->_model=new JuneTxtDB();
    }
    public function actionCreate()
    {
        
    }
    public function actionUpdate()
    {
        
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
            //echo $user.'--'.$password;exit;
            echo ZFramework::app()->admin_name;
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