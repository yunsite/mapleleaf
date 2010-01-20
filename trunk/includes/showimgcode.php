<?php
define('IN_MP',true);
///define("MP_ROOT",dirname(dirname(str_replace(DIRECTORY_SEPARATOR, '/', __FILE__))));
require('./Imgcode.php');
$image_instant=new FLEA_Helper_ImgCode();
$image_instant->image(0,4,900,array('borderColor'=>'#66CCFF','bgcolor'=>'#FFCC33'));
?>