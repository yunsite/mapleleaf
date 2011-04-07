<?php
/**
 * 控制器基础类
 *
 *
 */
class BaseController{
    public function render($tplFile,$vars=NULL){
        if ($vars)
            extract($vars);
        $tplDir='themes/'.ZFramework::app()->theme.'/templates/';
        $file=$tplDir.$tplFile;
        include $file.'.php';
    }
}

/**
 * 框架类，核心是 URL 路由分配
 *
 */
class ZFramework{
    protected   $_controller;
    protected   $_action;
    protected   $_params;
    protected   $_controllerPath='controllers';
    public      $defaultController='SiteController';
    public      $defaultAction='actionIndex';
    static      $_instance;
    protected   $allow_request_api=array(
        'SiteController/actionIndex',
        'SiteController/actionCaptcha',
        'SiteController/actionRss',
        'PostController/actionCreate',
        'PostController/actionDelete',
        'PostController/actionDeleteAll',
        'PostController/actionDelete_multi_messages',
        'PostController/actionUpdate',
        'ReplyController/actionReply',
        'ReplyController/actionDelete',
        'ReplyController/actionDeleteAll',
        'SearchController/actionIndex',
        'UserController/actionCreate',
        'UserController/actionLogin',
        'UserController/actionLogout',
        'UserController/actionUpdate',
        );

	/**
	 * 返回一个本类的实例
	 *
	 */
    public static function app(){
        if(!(self::$_instance instanceof  self)){
            self::$_instance=new self();
        }
        return self::$_instance;
    }
	
	/**
	 * getConfigVar 的代理函数
	 * @param $name 
	 * @return mixed
	 */
    public function  __get($name) {
		return getConfigVar($name);
    }

	/**
	 * 构造函数
	 *
	 */
    private function  __construct(){
        $this->preloadAllControllers();
        $this->_controller=!empty ($_GET['controller'])?ucfirst($_GET['controller']).'Controller':$this->defaultController;
        $this->_action=!empty ($_GET['action'])?'action'.ucfirst($_GET['action']):$this->defaultAction;
        foreach ($_GET as $key=>$value) {
            $this->_params[$key]=$value;
        }
    }
	
	/**
	 * 预载入所有 controller
	 *
	 */
    protected function preloadAllControllers(){
        $dir=dirname(__FILE__).'/'.$this->_controllerPath;
        $d=dir($dir);
        while(false !==($entry=$d->read())){
            if(substr($entry, 0, 1)!='.'){
                include_once $dir.'/'.$entry;
            }
        }
        $d->close();
    }

	/**
	 * 执行任务
	 *
	 */
    public function run(){
        global $API_CODE;
        if (defined('API_MODE') && !in_array($this->_controller.'/'.$this->_action, $this->allow_request_api)){
            $error_array=array('error_code'=>'403','error'=>$API_CODE['403'],'error_detail'=>self::t('API_REQUEST_ERROR'));
            die(function_exists('json_encode') ? json_encode($error_array) : CJSON::encode($error_array));
        }
        try {
            if(class_exists($this->getController())){
                $rc=new ReflectionClass($this->getController());
                if($rc->isSubclassOf('BaseController')){
                    if($rc->hasMethod($this->getAction())){                        
                        $controller=$rc->newInstance();
                        $method=$rc->getMethod($this->getAction());
                        $method->invoke($controller);
                    }else{
                        throw new Exception("Controller <font color='blue'>".$this->getController()."</font> does not have the action named <font color='red'>{$this->getAction()}</font>");
                    }
                }else{
                    throw new Exception("<font color='red'>".$this->getController().'</font> is not a valid Controller');
                }
            } else {
                throw new Exception("Controller <font color='red'>{$this->getController()}</font> not exists!");
            }
        }
        catch (Exception $e){
            if(defined('API_MODE')){
                $error_array=array('error_code'=>'403','error'=>$API_CODE['403'],'error_detail'=>'Request is not allowed.');
                die(function_exists('json_encode') ? json_encode($error_array) : CJSON::encode($error_array));
            }
            if(defined('DEBUG_MODE')){
                echo $e->getMessage();
                echo '<pre>';
                debug_print_backtrace();
            }else{
                header("Location:index.php");
            }
        }
    }

	/**
	 * 得到 GET 参数
	 *
	 * @return array
	 */
    public function getParams(){
        return $this->_params;
    }
	
	/**
	 * 返回当前的 controller
	 *
	 * @return string
	 */
    public function getController(){
        return $this->_controller;
    }

	/**
	 * 返回当前的 action
	 * @return string
	 */
    public function getAction(){
        return $this->_action;
    }
}
