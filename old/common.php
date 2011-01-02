<?php
if(!defined('IN_MP'))
{
	exit;
}
error_reporting(E_ALL ^ E_NOTICE);
//error_reporting(E_ALL);

$mp_root_path=dirname(str_replace(DIRECTORY_SEPARATOR, '/', __FILE__));
$mapleleaf_version='1.7';

require($mp_root_path.'/includes/functions.php');
require($mp_root_path.'/site.conf.php');

/**
 * 尝试禁用magic_quotes_gpc，magic_quotes_runtime，magic_quotes_sybase
 */
set_magic_quotes_runtime(false);
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

//设置时区
ini_set('date.timezone',$timezone);

/**
 * 反设置所有被禁止的全局变量.
 */
function maple_unset_globals() 
{
  if (ini_get('register_globals')) 
  {
    $allowed = array('_ENV' => 1, '_GET' => 1, '_POST' => 1, '_COOKIE' => 1, '_FILES' => 1, '_SERVER' => 1, '_REQUEST' => 1, 'GLOBALS' => 1);
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

$isUrlOpen	= @ini_get("allow_url_fopen");
$isSafeMode = @ini_get("safe_mode");
if( eregi('windows', @getenv('OS')) )
{
	$isSafeMode = false;
}
require($mp_root_path.'/includes/template_lite/class.template.php');
require($mp_root_path.'/includes/maple.class.php');
?>
