<?php
$smileyString="<table id='smileysTable' cellpadding='4'>\n";
$smileyArray=$this->_smileys;
$numPerRow=8;
for($i=0,$c=ceil(count($smileyArray)/$numPerRow);$i<$c;$i++){

    $smileyString.="<tr>\n";
    $rowArray=array_slice($smileyArray, $i*$numPerRow, $numPerRow);
    foreach ($rowArray as $key=>$perSmiley){
        $smileyString.="<td><img id='".$key."' src='".$this->_smileys_dir.$perSmiley[0]."' alt='$perSmiley[3]' title='$perSmiley[3]' /></td>\n";
    }
    $emptyStr='';
    if(count($rowArray)<$numPerRow){
        $emptyNum=$numPerRow-count($rowArray);
        $emptyStr=str_repeat('<td>&nbsp;</td>', $emptyNum);
        $smileyString.=$emptyStr;
    }
    $smileyString.="</tr>\n";
}
$smileyString.="</table>\n";
return $smileyString;
?>