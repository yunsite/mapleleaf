<?php
    /**
     * @author FleaPHP Framework
     * @link http://www.fleaphp.org/
     * @copyright Copyright &copy; 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
     * @license http://www.yiiframework.com/license/
     */
    function rmdirs($dir)
    {
	$dir = realpath($dir);
	if ($dir == '' || $dir == '/' ||  (strlen($dir) == 3 && substr($dir, 1) == ':\\'))
	{
	    //we do not allowed to delete root directory.
	    return false;
	}

	if(false !== ($dh = opendir($dir))) {
	    while(false !== ($file = readdir($dh))) {
		if($file == '.' || $file == '..') { continue; }
		$path = $dir . DIRECTORY_SEPARATOR . $file;
		if (is_dir($path)) {
		    if (!rmdirs($path)) { return false; }
		} else {
		    unlink($path);
		}
	    }
	    closedir($dh);
	    rmdir($dir);
	    return true;
	} else {
	    return false;
	}
    }
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
    function getIp(){
	$ip = $_SERVER['REMOTE_ADDR'];
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
	    $ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
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
?>