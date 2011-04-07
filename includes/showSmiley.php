<?php
if(!defined('IN_MP')){die('Access denied!');}
$smileyString="<table id='smileysTable' cellpadding='4'>\n";
$numPerRow=8;
$smileyArray=array_pad(getSmileys(), ceil(count(getSmileys())/$numPerRow)*$numPerRow, '');
$smileyArray=array_chunk($smileyArray,$numPerRow,true);
foreach ($smileyArray as $value){
    $smileyString.="<tr>\n";
    foreach ($value as $k => $v) {
        if($v)
            $smileyString.="<td><img id='".$k."' src='".SMILEYDIR.$v[0]."' alt='$v[3]' title='$v[3]' /></td>\n";
        else
            $smileyString.="<td>&nbsp;</td>";
    }
    $smileyString.="</tr>\n";
}
$smileyString.="</table>\n";
return $smileyString;
?>
