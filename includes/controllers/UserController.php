<?php
class UserController extends BaseController{
    public $_model;
    public function  __construct(){
        global $db_url;
        $this->_model=  YDB::factory($db_url);
    }
    public function actionCreate(){
        if(isset ($_SESSION['admin']) || isset ($_SESSION['user'])){
	    header("Location:index.php");exit;
	}
	if(isset ($_POST['register'])){
	    if(!empty ($_POST['user']) && !empty ($_POST['pwd']) && !empty ($_POST['email'])){
                if(strlen(trim($_POST['user']))>=2){
                    $user=  $this->_model->escape_string($_POST['user']);
                    $pwd=  $this->_model->escape_string($_POST['pwd']);
                    $email=$_POST['email'];
                    $time=time();
                    if(is_email($email)){
                        $user_exists=$this->_model->queryAll(sprintf(parse_tbprefix("SELECT * FROM <user> WHERE username='%s'"),$user));
                        if(!$user_exists && $user!= ZFramework::app()->admin){
                            if($this->_model->query(sprintf(parse_tbprefix("INSERT INTO <user> ( username , password , email , reg_time ) VALUES ( '%s' , '%s' , '%s' , %d )"),$user,$pwd,$email,$time))){
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
    public function actionUpdate(){
        if(defined('API_MODE')){
            if(!isset ($_SESSION['admin']) && !isset ($_SESSION['uid']))
                $json_array=array('error_msg'=>  ZFramework::t('LOGIN_REQUIRED'));
            elseif(!isset ($_GET['uid']))
                $json_array=array('error_msg'=>  ZFramework::t ('PARAM_ERROR'));
            elseif ((!isset($_SESSION['admin']) && $_GET['uid']!=$_SESSION['uid'])) {
                $json_array=array('error_msg'=>  ZFramework::t('PARAM_ERROR'));
            }
            if(isset ($json_array))
                die (function_exists('json_encode') ? json_encode($json_array) : CJSON::encode($json_array));
        }
        if((!isset($_SESSION['admin']) && !isset($_SESSION['uid'])) || !isset($_GET['uid']) || (!isset($_SESSION['admin']) && $_GET['uid']!=$_SESSION['uid'])){
	    header("Location:index.php");exit;
	}
	$uid=$_GET['uid'];
	if(isset ($_POST['user'])){
	    if(!empty ($_POST['user']) && !empty ($_POST['pwd']) && !empty ($_POST['email'])){
                $user=  $this->_model->escape_string($_POST['user']);
                $pwd=  $this->_model->escape_string($_POST['pwd']);
		$email=$_POST['email'];
		if(is_email($email)){
		    if($this->_model->query(sprintf(parse_tbprefix("UPDATE <user> SET password = '%s' , email = '%s' WHERE uid = %d"),$pwd,$email,$uid))){
                        if(defined('API_MODE')){
                            $json_array=array('status'=>'OK');
                            die (function_exists('json_encode') ? json_encode($json_array) : CJSON::encode($json_array));
                        }
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
        $user_data=  $this->_model->queryAll(sprintf(parse_tbprefix("SELECT * FROM <user> WHERE uid=%d"),$uid));
	$user_data=$user_data[0];
        if(defined('API_MODE')){
            die (function_exists('json_encode') ? json_encode($user_data) : CJSON::encode($user_data));
        }
	include 'themes/'.ZFramework::app()->theme.'/templates/'."user_update.php";
    }
    public function actionDelete(){
        
    }
    public function actionLogin(){
        $session_name=session_name();
        if (isset($_SESSION['admin'])){//若管理员已经登录
            if(defined('API_MODE')){
                $json_array=array('admin'=>$_SESSION['admin'],'session_name'=>$session_name,'session_value'=>session_id());
                die (function_exists('json_encode') ? json_encode($json_array) : CJSON::encode($json_array));
            }
            header("Location:index.php?action=control_panel");exit;
        }
	if (isset($_SESSION['user'])){//若普通用户已经登录
            if(defined('API_MODE')){
                $json_array=array('user'=>$_SESSION['user'],'uid'=>$_SESSION['uid'],'session_name'=>$session_name,'session_value'=>session_id());
                die (function_exists('json_encode') ? json_encode($json_array) : CJSON::encode($json_array));
            }
            header("Location:index.php");exit;
        }
        //exit;
        if(isset($_REQUEST['user']) && isset($_REQUEST['password'])){//若用户提交了登录表单
            $user=  $this->_model->escape_string($_REQUEST['user']);
            $password=$this->_model->escape_string($_REQUEST['password']);
	    if( ($user==ZFramework::app()->admin) && ($password==ZFramework::app()->password) ){//若使用管理员帐户成功登录
                $_SESSION['admin']=$_REQUEST['user'];
                if(defined('API_MODE')){
                    $json_array=array('admin'=>$_SESSION['admin'],'session_name'=>$session_name,'session_value'=>session_id());
                    die (function_exists('json_encode') ? json_encode($json_array) : CJSON::encode($json_array));
                }
		
		header("Location:index.php?action=control_panel");
		exit;
	    }
	    else{//使用普通用户登录
                $user_result=  $this->_model->queryAll(sprintf(parse_tbprefix("SELECT * FROM <user> WHERE username='%s' AND password='%s'"),$user,$password));
		$user_result=@$user_result[0];
		if($user_result){
                    $_SESSION['user']=$_REQUEST['user'];
		    $_SESSION['uid']=$user_result['uid'];
                    if(defined('API_MODE')){
                        $json_array=array('user'=>$_REQUEST['user'],'uid'=>$user_result['uid'],'session_name'=>$session_name,'session_value'=>session_id());
                        die (function_exists('json_encode') ? json_encode($json_array) : CJSON::encode($json_array));
                    }	    
		    header("Location:index.php");exit;
		}else{
		    $errormsg=ZFramework::t('LOGIN_ERROR');
		}
	    }
        }
        if(defined('API_MODE')){
            if(isset ($errormsg)){
                $json_array=array('error_msg'=>$errormsg);
                die (function_exists('json_encode') ? json_encode($json_array) : CJSON::encode($json_array));
            }else{
                $json_array=array('error_msg'=>  ZFramework::t('LOGIN_REQUIRED'));
                die (function_exists('json_encode') ? json_encode($json_array) : CJSON::encode($json_array));
            }
        }
	include 'themes/'.ZFramework::app()->theme.'/templates/'."login.php";
    }
    public function actionLogout(){
        if(isset ($_SESSION['user'])){
	    unset ($_SESSION['user']);
	    session_destroy();
	}
        if(isset($_SESSION['admin'])){
            if(is_flatfile ())
                delete_backup_files ();
            unset($_SESSION['admin']);
            session_destroy();
        }
        header("Location:index.php");
    }
}