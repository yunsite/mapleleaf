<?php
/**
 * Bootstrap file
 *
 * @author rainyjune <dreamneverfall@gmail.com>
 * @version $Id$
 */
if(!defined('IN_MP')){die('Access denied!');}
if(version_compare(PHP_VERSION,'5.1.0','<')){die('PHP Version 5.1.0+ required!');}
date_default_timezone_set('UTC');
error_reporting(E_ALL);
//载入函数
require 'functions.php';

/**
 * If you are sure that your server has been disabled magic quotes,you can remove these lines from 17 to 47 to improve performance. 
 */
ini_set('arg_separator.output',     '&amp;');
ini_set('magic_quotes_runtime',     0);
ini_set('magic_quotes_sybase',      0);
if(get_magic_quotes_gpc())
{
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
                unset($GLOBALS[$key]);
        }
    }
}
maple_unset_globals();

//载入文本数据库引擎
require APPROOT.'/includes/txt-db-api/txt-db-api.php';
//载入数据库类
require APPROOT.'/includes/database/YDB.php';
//载入配置文件，若尚未安装则载入默认的配置文件
if(file_exists(conf_path().'/config.php'))
    include_once conf_path().'/config.php';
else
    include './sites/default/default.config.php';
//定义常量
define('CONFIGFILE', conf_path().'/config.php');
define('MP_VERSION','2.0 alpha');
define('THEMEDIR', 'themes/');
define('SMILEYDIR', 'http://mapleleaf.googlecode.com/files/');

if (!function_exists('json_encode')){ include 'CJSON.php'; }
include_once 'Imgcode.php';
//载入框架类
require 'ZFramework.php';

//检查服务器支持情况
$gd_exist=gd_loaded();
$zip_support=class_exists('ZipArchive')?'On':'Off';

if(is_installed()){//若已经安装，执行IP检查
    if(is_baned(getIP()))
        die('Access denied!');
	is_closedMode();
}
elseif($_GET['action']!='install'){
	header("Location:index.php?action=install");exit;
}
