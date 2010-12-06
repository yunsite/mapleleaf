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
    public      $_errors=array();//     * 保存错误信息
    public static   $_coreMessage_array=array(
            'THEMES_DIR_NOTEXISTS'=>'The directory of themes does not exists!',
            'SMILEY_DIR_NOTEXISTS'=>'The directory of smiley `%s` does not exists!',
            'CONFIG_FILE_NOTEXISTS'=>'The configuration file `%s` does not exists!',
            'CONFIG_FILE_NOTWRITABLE'=>'The configuration file `%s` does not writable!',

            'SITENAME_ERROR'=>'The sitename undefined!',
            'SITESTATUS_ERROR'=>'The status of site undefined!',
            'SITECLOSEREASON_ERROR'=>'The maintaince message undefined!',
            'ADMINEMAIL_ERROR'=>'Admin email undefined!',
            'COPYRIGHT_ERROR'=>'Coptyright undefined!',
            'BADWORDS_ERROR'=>'Bad words undefined!',
            'CAPTCHASTATUS_ERROR'=>'The status of CAPTCHA undefined!',
            'PAGINATIONSTATUS_ERROR'=>'The status of pagination undefined!',
            'TIMEZONE_ERROR'=>'Timezone undefined!',
            'PAGINATION_PARAMETER_ERROR'=>'The parameter of  pagination undefined!',
            'THEME_ERROR'=>'Theme undefined!',
            'ADMINNAME_ERROR'=>'Admin name undefined!',
            'ADMINPASS_ERROR'=>'admin password undefined!',
            'LANGUAGE_ERROR'=>'Language undefined!',
            'QUERY_ERROR'=>'Query error!',
    );

    public static function getInstance($config=NULL){
        if(!(self::$_instance instanceof  self)){
            self::$_instance=new self($config);
        }
        return self::$_instance;
    }
    public function  __get($propertyName) {
        $methodName='get'.$propertyName;
        try {
            $propertyValue=self::$methodName();
            return $propertyValue;
        }  catch (Exception $e){
            if(defined(DEBUG_MODE)){
                echo '<pre>';
                echo $e->getMessage();
                echo '</pre>';
                echo '<pre>';
                debug_print_backtrace();
                echo '</pre>';
                exit;
            }  else {
                header("Location:index.php");
            }
        }
    }
    public static function get_board_name(){
        include 'config.php';
        if(isset ($board_name)){
            return $board_name;
        }  else {
            throw new Exception(site::translate('SITENAME_ERROR'));
        }
    }
    public static function get_mb_open(){
        include 'config.php';
        if(isset ($mb_open))
            return $mb_open;
        else
            throw new Exception(site::translate('SITESTATUS_ERROR'));
    }
    public static function get_close_reason(){
        include 'config.php';
        if(isset ($close_reason))
            return $close_reason;
        else
            throw new Exception(site::translate('SITECLOSEREASON_ERROR'));
    }
    public static function get_admin_email(){
        include 'config.php';
        if(isset ($admin_email))
            return $admin_email;
        else
            throw new Exception(site::translate('ADMINEMAIL_ERROR'));
    }
    public static function get_copyright_info(){
        include 'config.php';
        if(isset ($copyright_info))
            return $copyright_info;
        else
            throw new Exception(site::translate('COPYRIGHT_ERROR'));
    }
    public static function get_filter_words(){
        include 'config.php';
        if(isset ($filter_words))
            return $filter_words;
        else
            throw new Exception(site::translate('BADWORDS_ERROR'));
    }
    public static function get_valid_code_open(){
        include 'config.php';
        if(isset ($valid_code_open))
            return $valid_code_open;
        else
            throw new Exception(site::translate('CAPTCHASTATUS_ERROR'));
    }
    public static function get_page_on(){
        include 'config.php';
        if(isset ($page_on))
            return $page_on;
        else
            throw new Exception(site::translate('PAGINATIONSTATUS_ERROR'));
    }
    public static function get_num_perpage(){
        include 'config.php';
        if(isset ($num_perpage))
            return $num_perpage;
        else
            throw new Exception(site::translate('PAGINATION_PARAMETER_ERROR'));
    }
    public static function get_theme(){
        include 'config.php';
        if(isset ($theme))
            return $theme;
        else
            throw new Exception(site::translate('THEME_ERROR'));
    }
    public static function get_admin_name(){
        include 'config.php';
        if(isset ($admin))
            return $admin;
        else
            throw new Exception(site::translate('ADMINNAME_ERROR'));
    }
    public static function get_admin_password(){
        include 'config.php';
        if(isset ($password))
            return $password;
        else
            throw new Exception(site::translate('ADMINPASS_ERROR'));
    }
    public static function get_time_zone(){
        include 'config.php';
        if(isset ($timezone))
            return $timezone;
        else
            throw new Exception(site::translate('TIMEZONE_ERROR'));
    }
    public static function get_current_lang(){
        include 'config.php';
        if(isset ($lang))
            return $lang;
        else
            throw new Exception(site::translate('ADMINNAME_ERROR'));
    }

    public static function get_lang_array(){
        if(in_array(self::get_current_lang(), self::get_all_langs())){
            include self::get_lang_directory().self::get_current_lang().'.php';
            return $lang;
        }else{
            throw new Exception(site::translate('LANGUAGE_ERROR'));
        }
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
    public function get_lang_directory(){
        return THEMEDIR.self::get_theme().'/languages/';
    }

    public function get_smileys(){
        $_smileys=  require dirname(__FILE__).'/smiley.php';//将代表表情图案的数组导入到当前类的属性中
        return $_smileys;
    }

    public  function get_all_timezone()
    {
        $timezone=self::get_lang_array();
    	return $timezone['TZ_ZONES'];
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
	//载入配置
    public  function load_config()
    {
        if (!is_dir(THEMEDIR))//检查主题目录是否存在
	    die($this->t('THEMES_DIR_NOTEXISTS',true));
        if(!file_exists(CONFIGFILE))        //先检查配置文件是否存在和可写
            die(sprintf($this->t('CONFIG_FILE_NOTEXISTS',true),CONFIGFILE));
        if(!is_writable(CONFIGFILE))
            die($this->_errors[]=sprintf($this->t('CONFIG_FILE_NOTWRITABLE',true),CONFIGFILE));
        if(!is_dir(SMILEYDIR))
            $this->_errors[]=sprintf($this->t('SMILEY_DIR_NOTEXISTS',true),SMILEYDIR);
        if($this->_errors)
	    $this->show_message($this->_errors);
    }
    private function  __construct($config=NULL) {
		$this->load_config();
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
                include_once $dir.'/'.$entry;
            }
        }
        $d->close();
    }
    public function run(){
        try {
            $model=new JuneTxtDb();//实例化模型
            if(!$model->_db_exists(DB))//若默认的数据库不存在，需要执行安装
            {
                $this->_controller='site';
                $this->_action='install';
            }
            if(class_exists($this->getController())){
                $rc=new ReflectionClass($this->getController());
                if($rc->isSubclassOf('BaseController')){
                    if($rc->hasMethod($this->getAction())){
                        $controller=$rc->newInstance();
                        $method=$rc->getMethod($this->getAction());
                        $method->invoke($controller);
                        /*
                        $action=  $this->getAction();
                        //plugin
                        $allPlugins=self::get_all_plugins();
                        foreach($allPlugins as $plugin){
                            include_once PLUGINDIR.$plugin.'.php';
                            @include PLUGINDIR.$plugin.'.conf.php';;
                        }
                        if(isset ($GLOBALS['actionEvent'][$action])){
                            foreach ($GLOBALS['actionEvent'][$action] as $evt) {
                                $evt();
                            }
                        }//end plugin
                         * 
                         */
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
    /* 翻译核心信息 */
    public static function t($message){
        if(array_key_exists($message,self::$_coreMessage_array))
            return strtr($message, self::$_coreMessage_array);
        else
            return strtr($message,self::get_lang_array());
    }
    public static function maple_quotes($var,$charset='UTF-8')
    {
        return htmlspecialchars(trim($var),ENT_QUOTES,  $charset);
    }
}