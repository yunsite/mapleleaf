<?php
if(!defined('IN_MP'))
{
	exit;
}

error_reporting(E_ALL);

$mp_root_path=dirname(str_replace(DIRECTORY_SEPARATOR, '/', __FILE__));
$mapleleaf_version='1.5';

require($mp_root_path.'/includes/functions.php');
require($mp_root_path.'/adm/site.conf.php');
@set_magic_quotes_runtime(0);
ini_set('date.timezone','Asia/Shanghai');
/**
 * 反设置所有被禁止的全局变量.
 */
function maple_unset_globals() {
  if (ini_get('register_globals')) {
    $allowed = array('_ENV' => 1, '_GET' => 1, '_POST' => 1, '_COOKIE' => 1, '_FILES' => 1, '_SERVER' => 1, '_REQUEST' => 1, 'GLOBALS' => 1);
    foreach ($GLOBALS as $key => $value) {
      if (!isset($allowed[$key])) {
        unset($GLOBALS[$key]);
      }
    }
  }
}
maple_unset_globals();
$isUrlOpen = @ini_get("allow_url_fopen");
$isSafeMode = @ini_get("safe_mode");
if( eregi('windows', @getenv('OS')) )
{
	$isSafeMode = false;
}
require($mp_root_path.'/includes/template_lite/class.template.php');
require($mp_root_path.'/maple.class.php');
?>
