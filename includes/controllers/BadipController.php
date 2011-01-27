<?php
class BadipController extends BaseController{
    public $_model;
    public function  __construct(){
        global $db_url;
        $this->_model=  YDB::factory($db_url);
    }
    public function actionCreate(){
        is_admin();
        $ip=isset ($_GET['ip'])?$_GET['ip']:'';
        if (valid_ip($ip)==false){
            header("Location:index.php?action=control_panel&subtab=message");exit;
        }
        if(is_baned($ip)){
            header("Location:index.php?action=control_panel&subtab=ban_ip");exit;
        }
        $this->_model->query("INSERT INTO badip ( ip ) VALUES ( '$ip' )");
        header("Location:index.php?action=control_panel&subtab=ban_ip");
    }
    public function actionUpdate(){
        is_admin();
        @$ip_update_array=$_POST['select_ip'];
        if(!$ip_update_array){
            header("Location:index.php?action=control_panel&subtab=ban_ip");exit;
        }
        foreach ($ip_update_array as $_ip) {
            $this->_model->query("DELETE FROM badip WHERE ip = '$_ip'");
        }
        header("Location:index.php?action=control_panel&subtab=ban_ip");
    }
    
}