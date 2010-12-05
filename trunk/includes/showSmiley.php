<?php
$smileyString="<table id='smileysTable' cellpadding='4'>\n";
$numPerRow=8;
$num=$numPerRow - count($this->_smileys) % $numPerRow;
$emptyElementArray=array_fill(0, $num, '');
$smileyArray=array_merge($this->_smileys, $emptyElementArray);
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