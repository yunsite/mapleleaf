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
	    header("location:index.php?action=login");
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
	    if ( ! dl('gd.so'))
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
	if (defined(GD_VERSION))
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
	if(isset ($action) && isset ($evt)){
	    $GLOBALS['actionEvent'][$action][]=$evt;
	}
    }
?>