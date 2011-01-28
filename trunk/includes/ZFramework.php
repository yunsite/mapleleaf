<?php
class BaseController{
    public function render($tplFile,$vars=NULL){
        if ($vars)
            extract($vars);
        $tplDir='themes/'.ZFramework::app()->theme.'/templates/';
        $file=$tplDir.$tplFile;
        include $file.'.php';
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
    protected   $_db;

    public static function createApp(){
        if(!(self::$_instance instanceof  self)){
            self::$_instance=new self();
        }
        return self::$_instance;
    }

    public static function app(){
        return self::$_instance;
    }

    public function  __get($name) {
        $result=$this->_db->queryAll("SELECT * FROM sysvar WHERE varname='$name'");
        $result=@$result[0]['varvalue'];
        if($result)
            return $result;
        else
            return null;
    }

    private function  __construct(){
        global $db_url;
        $this->preloadAllControllers();
        
        $this->_controller=!empty ($_GET['controller'])?ucfirst($_GET['controller']).'Controller':$this->defaultController;
        $this->_action=!empty ($_GET['action'])?'action'.ucfirst($_GET['action']):$this->defaultAction;
        if($this->is_installed()){
            $this->performIPFilter();
            $this->_db=YDB::factory($db_url);
        }
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

    protected function performIPFilter(){
        if(is_baned(getIP()))
            die('Access denied!');
    }
    protected function is_installed(){
        global $db_url;
        
        if($db_url=='dummydb://username:password@localhost/databasename'){
            $this->_controller='SiteController';
            $this->_action='actionInstall';
            return false;
        }
        return true;
    }
    protected function is_closedMode(){
        $disabledAction=array('PostController/actionCreate','SiteController/actionIndex','UserController/actionCreate');
        if($this->site_close==1 && !isset ($_SESSION['admin']) && in_array($this->_controller.'/'.$this->_action, $disabledAction))
            self::show_message($this->close_reason);
    }
    public function run(){
        try {
            if($this->is_installed())
                $this->is_closedMode();
            if(class_exists($this->getController())){
                $rc=new ReflectionClass($this->getController());
                if($rc->isSubclassOf('BaseController')){
                    if($rc->hasMethod($this->getAction())){
                        
                        $controller=$rc->newInstance();
                        $method=$rc->getMethod($this->getAction());
                        $method->invoke($controller);
                        $this->performEvent();
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
    protected function performEvent(){
        global  $actionEvent;
        if(isset ($actionEvent["{$this->_controller}/{$this->_action}"])){
            foreach ($actionEvent["{$this->_controller}/{$this->_action}"] as $evt) {
                $evt();
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

    public static function t($message,$params=array(),$userSpecifiedLanguage=null){
        $messages=self::getLangArray($userSpecifiedLanguage);
        if(isset ($messages[$message]) && $messages[$message]!=='')
            $message=$messages[$message];
        return $params!==array()?strtr($message, $params):$message;
    }
    
    public static function getLangArray($userSpecifiedLanguage=null){
        if($userSpecifiedLanguage){
            if(file_exists(APPROOT.'/languages/'.$userSpecifiedLanguage.'.php')){
                return include APPROOT.'/languages/'.$userSpecifiedLanguage.'.php';
            }
        }
        return include APPROOT.'/languages/'.self::createApp()->lang.'.php';
    }
    public static function getSmileys(){
        return include  dirname(__FILE__).'/smiley.php';
    }
    public static function maple_quotes($var,$charset='UTF-8'){
        return htmlspecialchars(trim($var),ENT_QUOTES,  $charset);
    }
    /**
     * 显示信息
     */
    public static  function show_message($msg,$redirect=false,$redirect_url='index.php',$time_delay=3){
        include 'themes/'.self::createApp()->theme.'/templates/'."show_message.php"; exit;
    }
        /**
     * 得到所有可用的主题
     */
    public static  function get_all_themes(){
        $themes=array();
        $d=dir(THEMEDIR);
        while(false!==($entry=$d->read())){
            if(substr($entry,0,1)!='.')
                $themes[$entry]=$entry;
        }
        $d->close();
        return $themes;
    }


    public static  function get_all_langs(){
    	$langs=array();
        $d=dir(self::get_lang_directory());
        while(false!==($entry=$d->read())){
            if(substr($entry,0,1)!='.')
                $langs[substr($entry,0,-4)]=substr($entry,0,-4);
        }
        $d->close();
        return $langs;
    }
    public static  function get_all_timezone(){
        $timezone=  include APPROOT.'/languages/'.self::app()->lang.'.php';
    	return $timezone['TZ_ZONES'];
    }
    public function get_lang_directory(){
        return APPROOT.'/languages/';
    }
    /**
     * 替换被过滤的词语
     * @param array $filter_words
     */
    public static  function fix_filter_string($filter_words){
	$new_string=trim($filter_words,',');
	$new_string=str_replace(array("\t","\r","\n",'  ',' '),'',$new_string);
	return $new_string;
    }
}