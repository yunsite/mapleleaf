<?php
class BaseController{
    public function render($tplFile,$vars=NULL){
        if ($vars)
            extract($vars);
        include $tplFile.'.php';
    }
}
class FrontController {
    protected   $_controller;
    protected   $_action;
    protected   $_params;
    protected   $_urlMode='full';
    protected   $_controllerPath='controllers';
    public      $defaultController='site';
    public      $defaultAction='index';
    static      $_instance;

    public static function getInstance($config=NULL){
        if(!(self::$_instance instanceof  self)){
            self::$_instance=new self($config);
        }
        return self::$_instance;
    }

    private function  __construct($config=NULL) {
        $this->preloadAllControllers();
        if($config){
            if (isset ($config['urlMode']))
                $this->_urlMode=$config['urlMode'];
        }
        if($this->_urlMode=='clean'){
            $request=$_SERVER['REQUEST_URI'];
            $splits=explode('/', trim($request,'/'));
            $this->_controller=!empty ($splits[0])?$splits[0]:$this->defaultController;
            $this->_action=!empty ($splits[1])?$splits[1]:$this->defaultAction;
            if(!empty ($splits[2])){
                $keys=$values=array();
                for($idx=2,$cnt=count($splits);$idx<$cnt;$idx++){
                    if($idx % 2==0){
                        $keys[]=$splits[$idx];
                    }  else {
                        $values[]=$splits[$idx];
                    }
                }
                $this->_params=array_combine($keys, $values);
            }
        }else{
            $this->_controller=!empty ($_GET['controller'])?$_GET['controller']:$this->defaultController;
            $this->_action=!empty ($_GET['action'])?$_GET['action']:$this->defaultAction;
            foreach ($_GET as $key=>$value) {
                $this->_params[$key]=$value;
            }
            unset ($this->_params['controller']);
            unset ($this->_params['action']);
        }
    }
    protected function preloadAllControllers(){
        $dir=dirname(__FILE__).'/'.$this->_controllerPath;
        $d=dir($dir);
        while(false !==($entry=$d->read())){
            if(substr($entry, 0, 1)!='.'){
                include $dir.'/'.$entry;
            }
        }
        $d->close();
    }
    public function run(){
        try {
            if(class_exists($this->getController())){
                $rc=new ReflectionClass($this->getController());
                if($rc->isSubclassOf('BaseController')){
                    if($rc->hasMethod($this->getAction())){
                        $controller=$rc->newInstance();
                        $method=$rc->getMethod($this->getAction());
                        $method->invoke($controller);
                        $action=  $this->getAction();
                        //plugin
                        $allPlugins=site::get_all_plugins();
                        foreach($allPlugins as $plugin){
                            include_once PLUGINDIR.$plugin.'.php';
                            @include PLUGINDIR.$plugin.'.conf.php';;
                        }
                        if(isset ($GLOBALS['actionEvent'][$action])){
                            foreach ($GLOBALS['actionEvent'][$action] as $evt) {
                                $evt();
                            }
                        }//end plugin
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
            if(defined('DEBUG_MODE')){
                echo $e->getMessage();
                echo '<pre>';
                var_dump(debug_backtrace());
                debug_print_backtrace();
            }else{
                header("Location:index.php");
            }
        }
    }
    public function getParams(){
        return $this->_params;
    }
    public function getController(){
        return $this->_controller;
    }
    public function getAction(){
        return $this->_action;
    }
}