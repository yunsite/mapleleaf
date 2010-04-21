<?php
if(!defined('IN_MP'))
{
	exit;
}
if(version_compare(PHP_VERSION,'5.1.0','<'))
{
	die('PHP Version 5.1.0+ required!');
}
date_default_timezone_set('UTC');
//error_reporting(E_ALL & ~E_DEPRECATED);
//若用于调试，请把上面一行注释掉，打开下面这一行
error_reporting(E_ALL);

/**
 * 尝试禁用magic_quotes_gpc，magic_quotes_runtime，magic_quotes_sybase
 */
@set_magic_quotes_runtime(false);
@ini_set('magic_quotes_sybase', 0);
if(get_magic_quotes_gpc())
{
	function stripslashes_deep($value)
	{
		$value=is_array($value)?array_map('stripslashes_deep',$value):stripslashes($value);
		return $value;
	}
	$_POST	=array_map('stripslashes_deep',$_POST);
	$_GET	=array_map('stripslashes_deep',$_GET);
	$_COOKIE=array_map('stripslashes_deep',$_COOKIE);
	$_REQUEST=array_map('stripslashes_deep',$_REQUEST);
}

/**
 * 反设置所有被禁止的全局变量.
 */
function maple_unset_globals()
{
	if (ini_get('register_globals'))
	{
	  $allowed = array('_ENV' => 1, '_GET' => 1, '_POST' => 1, '_COOKIE' => 1,'_SESSION'=>1,'_FILES' => 1, '_SERVER' => 1, '_REQUEST' => 1, 'GLOBALS' => 1);
	  foreach ($GLOBALS as $key => $value)
	  {
	    if (!isset($allowed[$key]))
	    {
	      unset($GLOBALS[$key]);
	    }
	  }
	}
}
maple_unset_globals();

define('MP_VERSION','1.7');
require 'includes/functions.php';
require 'includes/maple.controller.class.php';

$isUrlOpen = @ini_get("allow_url_fopen");
$isSafeMode=isSafeMode();
$gd_exist=gd_is_available();
$zip_support=class_exists('ZipArchive')?'On':'Off';
?>