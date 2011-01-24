<?php
if(!defined('IN_MP')){die('Access denied!');}
/**
 * 注意：由于飞信插件使用了第三方的服务，请自行评估安全性。
 * 由于取决于第三方的服务，可能不会立即收到短信，也有可能无法收到短信。
 */

function fetion_t($message){
    $fetion_en=array('ID'=>'Fetion ID','PWD'=>'Password','SUBMIT'=>'Submit','POSTED'=>'said');
    $fetion_zh_cn=array('ID'=>'飞信号码','PWD'=>'密码','SUBMIT'=>'提交','POSTED'=>'留言');
    $languageArrayName='fetion_'.ZFramework::app()->lang;
    return strtr($message, $$languageArrayName);
}
function fetion_config($showConfig=FALSE,$config=NULL){
    $filename=conf_path().'/.fetion.conf.php';
    if( isset($config) && $config['plugin'] =='fetion'){
        if(!empty($config['fetionID']) && !empty($config['fetionPASSWORD'])){
            $fetionID=$config['fetionID'];
            $fetionPWD=addslashes($_POST['fetionPASSWORD']);
            if(file_put_contents($filename,"<?php\n\$fetionID=".$fetionID.";\n\$fetionPWD='$fetionPWD';"))
                return true;
            return false;
        }else
            return false;
    }
    if($showConfig){
	if(file_exists($filename)){
	    include $filename;
	}
    }
    echo '<form action="index.php?controller=plugin&amp;action=config" method="POST">';
    echo "<input type='hidden' name='plugin' value='fetion' />";
    echo '<p>'.  fetion_t('ID').'<input type="text" name="fetionID" value="'.@$fetionID.'" /></p>';
    echo '<p>'. fetion_t('PWD').'<input type="password" name="fetionPASSWORD" value="'.@$fetionPWD.'" /></p>';
    echo '<p><input type="submit" value="'. fetion_t('SUBMIT').'" />&nbsp;';
    if(file_exists($filename))
        echo '<a href="index.php?controller=plugin&amp;action=deactivate&amp;id=fetion">Disable</a>';
    echo '</p>';
    echo '</form>';
}
function fetion_send(){
    @include conf_path().'/.fetion.conf.php';
    @$message=urlencode($_REQUEST['user'].' '.fetion_t('POSTED').':'.$_REQUEST['content']);
    @$result=file_get_contents('http://fetion.adwap.cn/restlet/fetion/'.$fetionID.'/'.$fetionPWD.'/'.$fetionID.'/'.$message);
    //if($result=='OK'){	return TRUE; }    //return FALSE;
}
attachEvent('PostController/actionCreate','fetion_send');
?>