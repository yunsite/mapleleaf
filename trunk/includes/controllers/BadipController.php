<?php
class BadipController extends BaseController
{
    public $_model;
    public function  __construct()
    {
        $this->_model=new JuneTxtDB();
        $this->_model->select_db(DB);
    }
    public function actionCreate()
    {
        is_admin();
        $ip=isset ($_GET['ip'])?$_GET['ip']:'';
        if (valid_ip($ip)==false){
            header("Location:index.php?action=control_panel&subtab=message");exit;
        }
        if(self::is_baned($ip)){
            header("Location:index.php?action=control_panel&subtab=ban_ip");exit;
        }
        $insert_string=$ip."\n";
	$ip_filename=$this->_model->_table_path(DB, BADIPTABLE).$this->_model->get_data_ext();
        file_put_contents($ip_filename, $insert_string, FILE_APPEND | LOCK_EX);
        header("Location:index.php?action=control_panel&subtab=ban_ip");
    }
    public function actionUpdate()
    {
        is_admin();
        @$ip_update_array=$_POST['select_ip'];
        if(!$ip_update_array){
            header("Location:index.php?action=control_panel&subtab=ban_ip");exit;
        }
        $ip_array=$this->_model->select(BADIPTABLE);
        foreach ($ip_array as &$value) {
            $value=$value['ip'];
        }
        $new_ip_array=array_diff($ip_array,$ip_update_array);
        $new_ip_string=implode("\n",$new_ip_array);
        if ($new_ip_array)
	    $new_ip_string.="\n";
        $ip_filename=$this->_model->_table_path(DB, BADIPTABLE).$this->_model->get_data_ext();
        file_put_contents($ip_filename, $new_ip_string);
        header("Location:index.php?action=control_panel&subtab=ban_ip");
    }
    public static  function is_baned($ip)
    {
        $all_baned_ips=array();
        $model=new JuneTxtDB();
        $model->select_db(DB);
        $all_baned_ips=$model->select(BADIPTABLE);
        foreach ($all_baned_ips as &$value) {
            $value=$value['ip'];
        }
        if (in_array($ip,$all_baned_ips))
            return TRUE;
        return FALSE;
    }
}