<?php
    /**
    * Validate IP Address
    * Borrowed from CI
    * Updated version suggested by Geert De Deckere
    *
    * @access public
    * @param string
    * @return string
    */
    function valid_ip($ip){
	$ip_segments = explode('.', $ip);
	// Always 4 segments needed
	if (count($ip_segments) != 4)
	    return FALSE;
	// IP can not start with 0
	if ($ip_segments[0][0] == '0')
	    return FALSE;
	// Check each segment
	foreach ($ip_segments as $segment){
	    // IP segments must be digits and can not be
	    // longer than 3 digits or greater then 255
	    if ($segment == '' OR preg_match("/[^0-9]/", $segment) OR $segment > 255 OR strlen($segment) > 3)
		return FALSE;
	}
	return TRUE;
    }
    
    /**
     * Finds wether the user is admin , redirect browser to the login page if not admin.
     *
     */
    function is_admin(){
	if (!isset($_SESSION['admin'])){
	    header("Location:index.php?controller=user&action=login");exit;
        }
    }

    /**
     * Is GD Installed?
     * CI 1.7.2
     * @access	public
     * @return	bool
     */
    function gd_loaded(){
	if ( ! extension_loaded('gd')){
	    if ( ! @dl('gd.so'))
		return FALSE;
	}
	return TRUE;
    }

    /**
     * Get GD version
     *
     * @access	public
     * @return	mixed
     */
    function gd_version(){
	$gd_version=FALSE;
	if (defined('GD_VERSION'))
	    $gd_version=GD_VERSION;
	elseif(function_exists('gd_info')){
	    $gd_version = @gd_info();
	    $gd_version = $gd_version['GD Version'];
	}
	return $gd_version;
    }
    
    /**
     * Get IP of visitor
     * 
     * @return string 
     */
    function getIP(){
	$ip = $_SERVER['REMOTE_ADDR'];
	if (!empty($_SERVER['HTTP_CLIENT_IP']))
	    $ip = $_SERVER['HTTP_CLIENT_IP'];
	elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
	    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	$ip=$ip?$ip:'127.0.0.1';
	return $ip;
    }

    /**
     * Finds whether a value is a valid email
     *
     * @param string $value
     * @return bool
     */
    function is_email($value){
        return preg_match('/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i', $value);
    }

    /**
     * Attach one event to an action
     *
     * @global array $actionEvent
     * @param string $action <p>The name of action</p>
     * @param string $evt <p>The name of event</p>
     */
    function attachEvent($action,$evt){
        global $actionEvent;
        if (!@in_array($evt, $actionEvent[$action]))
            $actionEvent[$action][]=$evt;
    }
    
    /**
     * Find the appropriate configuration directory.
     *
     * Try finding a matching configuration directory by stripping the website's
     * hostname from left to right and pathname from right to left. The first
     * configuration file found will be used; the remaining will ignored. If no
     * configuration file is found, return a default value '$confdir/default'.
     *
     * Example for a fictitious site installed at
     * http://www.drupal.org:8080/mysite/test/ the 'settings.php' is searched in
     * the following directories:
     *
     *  1. $confdir/8080.www.drupal.org.mysite.test
     *  2. $confdir/www.drupal.org.mysite.test
     *  3. $confdir/drupal.org.mysite.test
     *  4. $confdir/org.mysite.test
     *
     *  5. $confdir/8080.www.drupal.org.mysite
     *  6. $confdir/www.drupal.org.mysite
     *  7. $confdir/drupal.org.mysite
     *  8. $confdir/org.mysite
     *
     *  9. $confdir/8080.www.drupal.org
     * 10. $confdir/www.drupal.org
     * 11. $confdir/drupal.org
     * 12. $confdir/org
     *
     * 13. $confdir/default
     *
     * @param $require_settings
     *   Only configuration directories with an existing settings.php file
     *   will be recognized. Defaults to TRUE. During initial installation,
     *   this is set to FALSE so that Drupal can detect a matching directory,
     *   then create a new settings.php file in it.
     * @param reset
     *   Force a full search for matching directories even if one had been
     *   found previously.
     * @return
     *   The path of the matching directory.
     */
    function conf_path($require_settings = TRUE, $reset = FALSE) {
      static $conf = '';//静态变量

      if ($conf && !$reset) {//若静态变量有值，并且 $reset 为默认
        return $conf;//返回匹配的目录
      }

      $confdir = 'sites';//配置目录
      $uri = explode('/', $_SERVER['SCRIPT_NAME'] ? $_SERVER['SCRIPT_NAME'] : $_SERVER['SCRIPT_FILENAME']);//当前脚本的路径
      $server = explode('.', implode('.', array_reverse(explode(':', rtrim($_SERVER['HTTP_HOST'], '.')))));//将主机名分离为数组
      for ($i = count($uri) - 1; $i > 0; $i--) {//遍历
        for ($j = count($server); $j > 0; $j--) {
          $dir = implode('.', array_slice($server, -$j)) . implode('.', array_slice($uri, 0, $i));
          if (file_exists("$confdir/$dir/config.php") || (!$require_settings && file_exists("$confdir/$dir"))) {//若匹配文件找到或者，探测一个匹配的目录并且有指定的目录存在
            $conf = "$confdir/$dir";
            return $conf;
          }
        }
      }
      $conf = "$confdir/default";
      return $conf;
    }

    /**
     * Finds whether the database type of guest book is flatfile (Php Textfile DB API)
     *
     * @global string $db_url
     * @return bool
     */
    function is_flatfile(){
        global $db_url;
        if(substr($db_url, 0, 8)=='flatfile')
            return true;
        return false;
    }
    
    /**
     * Delete backuped data , only triggered by admin logout
     *
     * @global string $db_url 
     */
    function delete_backup_files(){
        global $db_url;
        is_admin();
        $url = parse_url($db_url);
        $url['path'] = urldecode($url['path']);
        $dbname=substr($url['path'], 1);
        $dir=APPROOT.'/data/'.$dbname;
	$d=dir($dir);
	while(false!==($entry=$d->read())){
	    if (strlen($entry)==19){
		$d_file=$dir.'/'.$entry;
		unlink($d_file);
	    }
	}
	$d->close();
    }

    /**
     * Finds whether an IP address is bloked by guest book
     *
     * @global string $db_url
     * @param string $ip
     * @return bool
     */
    function is_baned($ip){
        global $db_url;
        $all_baned_ips=array();
        $db=YDB::factory($db_url);
        $result=$db->queryAll(sprintf(parse_tbprefix("SELECT * FROM <badip> WHERE ip='%s'"),$db->escape_string($ip)));
        if($result)
            return true;
        return false;
    }
    /**
     * 
     *
     * @global string $db_prefix
     * @param string $str
     * @return string
     */
    function parse_tbprefix($str){
        global $db_prefix;
        return strtr($str,array('<'=>$db_prefix,'>'=>''));
    }

    function get_all_data($parse_smileys=true,$filter_words=false,$processUsername=false,$processTime=false,$apply_filter=true){
        global $db_url;
        $db=YDB::factory($db_url);
        $data=array();
        $data=$db->queryAll(parse_tbprefix("SELECT p.pid AS id, p.ip AS ip , p.uid AS uid ,p.uname AS user,p.content AS post_content,p.post_time AS time,r.content AS reply_content,r.r_time AS reply_time ,u.username AS b_username FROM <post> AS p LEFT JOIN <reply> AS r ON p.pid=r.pid LEFT JOIN <user> AS u ON p.uid=u.uid ORDER BY p.post_time DESC"));
        foreach ($data as &$_data) {
            if($apply_filter && ZFramework::app()->filter_type==ConfigController::FILTER_TRIPTAGS){
                $_data['post_content']=strip_tags ($_data['post_content'], ZFramework::app()->allowed_tags);
                $_data['reply_content']=strip_tags ($_data['reply_content'], ZFramework::app()->allowed_tags);
            }  else{
                $_data['post_content']=  htmlentities($_data['post_content'],ENT_COMPAT,'UTF-8');
                $_data['reply_content']=htmlentities($_data['reply_content'],ENT_COMPAT,'UTF-8');
            }
            if($parse_smileys){
                $_data['post_content']=parse_smileys ($_data['post_content'], SMILEYDIR,  ZFramework::getSmileys());
                $_data['reply_content']=parse_smileys ($_data['reply_content'], SMILEYDIR,  ZFramework::getSmileys());
            }
            if($filter_words)
                $_data['post_content']=filter_words($_data['post_content']);
            if($processUsername)
                $_data['user']=($_data['user']==ZFramework::app()->admin)?"<font color='red'>{$_data['user']}</font>":$_data['user'];
            if($processTime){
                $_data['time']=date('m-d H:i',$_data['time']+ZFramework::app()->timezone*60*60);
                $_data['reply_time']=date('m-d H:i',$_data['reply_time']+ZFramework::app()->timezone*60*60);
            }

        }
        //echo '<pre>';
        //var_dump($data);exit;
        return $data;
    }

    /**
     * 将表情符号转换为表情图案
     * @param $str
     * @param $image_url
     * @param $smileys
     */
    function parse_smileys($str = '', $image_url = '', $smileys = NULL){
	if ($image_url == '')
	    return $str;
	if (!is_array($smileys))
	    return $str;
	// Add a trailing slash to the file path if needed
	$image_url = preg_replace("/(.+?)\/*$/", "\\1/",  $image_url);
	foreach ($smileys as $key => $val){
	    $str = str_replace($key, "<img src=\"".$image_url.$smileys[$key][0]."\" width=\"".$smileys[$key][1]."\" height=\"".$smileys[$key][2]."\" title=\"".$smileys[$key][3]."\" alt=\"".$smileys[$key][3]."\" style=\"border:0;\" />", $str);
	}
	return $str;
    }
    /**
     * 过滤敏感词语
     * @param array $input
     */
    function filter_words($input){
	$filter_array=explode(',',  ZFramework::app()->filter_words);
	$input=str_ireplace($filter_array,'***',$input);
	return $input;
    }
    /**
     * 显示表情
     */
    function show_smileys_table(){
	$smiley=  require APPROOT.'/includes/showSmiley.php';
	return $smiley;
    }
    /**
     * 替换被过滤的词语
     * @param array $filter_words
     */
    function fix_filter_string($filter_words){
	$new_string=trim($filter_words,',');
	$new_string=str_replace(array("\t","\r","\n",'  ',' '),'',$new_string);
	return $new_string;
    }

    /**
     * Gets supported RDBMS type
     *
     * @return array
     */
    function get_supported_rdbms(){
        $supported_rdbms=array();
        $rdbms_functions=array('mysql'=>'mysql_connect','mysqli'=>'mysqli_connect','sqlite'=>'sqlite_open');
        $rdbms_names=array('mysql'=>'MySQL','mysqli'=>'MySQL Improved','sqlite'=>'SQLite');
        foreach ($rdbms_functions as $k => $v) {
            if(function_exists($v)){
                $supported_rdbms[$rdbms_names[$k]]=$k;
            }
        }
        return $supported_rdbms;
    }
?>
