<?php
    /**
    * Validate IP Address
    * Borrowed from CI
    * Updated version suggested by Geert De Deckere
    *
    * @access	public
    * @param	string
    * @return	string
    */
    function valid_ip($ip)
    {
	$ip_segments = explode('.', $ip);
	// Always 4 segments needed
	if (count($ip_segments) != 4)
	{
	    return FALSE;
	}
	// IP can not start with 0
	if ($ip_segments[0][0] == '0')
	{
	    return FALSE;
	}
	// Check each segment
	foreach ($ip_segments as $segment)
	{
	    // IP segments must be digits and can not be
	    // longer than 3 digits or greater then 255
	    if ($segment == '' OR preg_match("/[^0-9]/", $segment) OR $segment > 255 OR strlen($segment) > 3)
	    {
		return FALSE;
	    }
	}
	return TRUE;
    }
    /**
     * 检查当前用户是否是管理员
     */
    function is_admin()
    {
	if (!isset($_SESSION['admin']))
	{
	    header("Location:index.php?controller=user&action=login");
            exit;
        }
    }

    /**
     * Is GD Installed?
     * CI 1.7.2
     * @access	public
     * @return	bool
     */
    function gd_loaded()
    {
	if ( ! extension_loaded('gd'))
	{
	    if ( ! @dl('gd.so'))
	    {
		return FALSE;
	    }
	}
	return TRUE;
    }

    /**
     * Get GD version
     *
     * @access	public
     * @return	mixed
     */
    function gd_version()
    {
	$gd_version=FALSE;
	if (defined('GD_VERSION'))
	    $gd_version=GD_VERSION;
	elseif(function_exists('gd_info'))
	{
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
    
    function is_email($value){
        //return preg_match('/^[a-z0-9]+[._\-\+]*@([a-z0-9]+[-a-z0-9]*\.)+[a-z0-9]+$/i', $value);
        return preg_match('/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i', $value);
    }
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
    function is_flatfile(){
        global $db_url;
        if(substr($db_url, 0, 8)=='flatfile')
            return true;
        return false;
    }
    /**
     * 删除服务器上的备份文件，会在管理员注销登录时执行
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
    function is_baned($ip){
        global $db_url;
        $all_baned_ips=array();
        $db=YDB::factory($db_url);
        $result=$db->queryAll("SELECT * FROM badip WHERE ip='$ip'");
        if($result)
            return true;
        return false;
    }
?>