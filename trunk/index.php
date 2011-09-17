<?php
/**
 * @author rainyjune <dreamneverfall@gmail.com>
 * @link http://mapleleaf.googlecode.com/
 * @copyright Copyright &copy; 2008-2011 rainyjune
 * @license GPL2
 * @version $Id$
 * @since 1.9
 */

session_start();
define('IN_MP',true);
define('APPROOT', dirname(__FILE__));
define('DEBUG_MODE', true);
#define('DEBUG_MODE', false);
require_once('./includes/preload.php');
ZFramework::app()->run();
