<?php
class BaseController{
    public function render($tplFile,$vars=NULL){
        if ($vars)
            extract($vars);
        include $tplFile.'.php';
    }
}
class ZFramework{
    protected   $_controller;
    protected   $_action;
    protected   $_params;
    protected   $_controllerPath='controllers';
    public      $defaultController='SiteController';
    public      $defaultAction='actionIndex';
    static      $_instance;
    public      $_errors=array();//     * 保存错误信息

    public static function createApp(){
        if(!(self::$_instance instanceof  self)){
            self::$_instance=new self();
        }
        return self::$_instance;
    }

    public static function app()
    {
        return self::$_instance;
    }

    public function  __get($name) {
        include 'config.php';
        if(isset ($$name))
            return $$name;
        else
            return null;
    }

    private function  __construct(){
        $this->preloadAllControllers();
        $this->_controller=!empty ($_GET['controller'])?ucfirst($_GET['controller']).'Controller':$this->defaultController;
        $this->_action=!empty ($_GET['action'])?'action'.ucfirst($_GET['action']):$this->defaultAction;
        foreach ($_GET as $key=>$value) {
            $this->_params[$key]=$value;
        }
    }
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
    public function run(){
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
            if(defined('DEBUG_MODE')){
                echo $e->getMessage();
                echo '<pre>';
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
    /* 翻译核心信息 */
    public static function t($message){
        if(array_key_exists($message,self::getCoreMessage()))
            return strtr($message, self::getCoreMessage());
        else
            return strtr($message,self::getLangArray());
    }
    public static function getCoreMessage(){
        return include dirname(__FILE__).'/coreMessage.php';
    }
    public static function getLangArray(){
        return include 'themes/'.self::createApp()->theme.'/languages/'.self::createApp()->lang.'.php';
    }
    public static function getSmileys(){
        return include  dirname(__FILE__).'/smiley.php';
    }
    public static function maple_quotes($var,$charset='UTF-8')
    {
        return htmlspecialchars(trim($var),ENT_QUOTES,  $charset);
    }
    /**
     * 显示信息
     */
    public  function show_message($msg,$redirect=false,$redirect_url='index.php',$time_delay=3)
    {
        include 'themes/'.self::createApp()->theme.'/templates/'."show_message.php";
        exit;
    }
        /**
     * 得到所有可用的主题
     */
    public function get_all_themes()
    {
        $themes=array();
        $d=dir(THEMEDIR);
        while(false!==($entry=$d->read()))
        {
            if(substr($entry,0,1)!='.')
                $themes[$entry]=$entry;
        }
        $d->close();
        return $themes;
    }

    public function get_all_plugins(){
        $plugins=array();
        $d=dir(PLUGINDIR);
        while(false!==($entry=$d->read()))
        {
            if(substr($entry,0,1)!='.')
                $plugins[substr($entry,0,-4)]=substr($entry,0,-4);
        }
        $d->close();
        return $plugins;
    }
    public static  function get_all_langs()
    {
    	$langs=array();
        $d=dir(self::get_lang_directory());
        while(false!==($entry=$d->read()))
        {
            if(substr($entry,0,1)!='.')
                $langs[substr($entry,0,-4)]=substr($entry,0,-4);
        }
        $d->close();
        return $langs;
    }
    public  function get_all_timezone()
    {
        $timezone=  include THEMEDIR.self::app()->theme.'/languages/'.self::app()->lang.'.php';
    	return $timezone['TZ_ZONES'];
    }
    public function get_lang_directory(){
        return THEMEDIR.self::app()->theme.'/languages/';
    }
}