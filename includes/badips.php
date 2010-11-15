<?php
class badips{
        public  function ip_update(){
        is_admin();
        @$ip_update_array=$_POST['select_ip'];
        if(!$ip_update_array){
            header("Location:index.php?action=control_panel&subtab=ban_ip");exit;
        }
        $ip_array=$this->get_baned_ips();
        for($i=0,$c=count($ip_array);$i<$c;$i++){
	    $ip_array[$i]=trim($ip_array[$i]["ip"]);
        }
        $new_ip_array=array_diff($ip_array,$ip_update_array);
        $new_ip_string=implode("\n",$new_ip_array);
        if ($new_ip_array)
	    $new_ip_string.="\n";;
        $ip_filename=$this->_model->_table_path($this->_dbname, $this->_banedip_table).$this->_model->get_data_ext();
        file_put_contents($ip_filename, $new_ip_string);
        header("Location:index.php?action=control_panel&subtab=ban_ip");
    }
        /**
     * 得到被禁止的IP列表
     */
    public  function get_baned_ips()
    {
	$result=$this->_model->select($this->_banedip_table);
	return $result;
    }
        public  function ban()
    {
        is_admin();
        $ip='';
        $ip=$_GET['ip'];
        if (!isset($ip) || $ip=="" || valid_ip($ip)==false)
        {
            header("Location:index.php?action=control_panel&subtab=message");
            exit;
        }
        if($this->is_baned($ip,TRUE))
        {
            header("Location:index.php?action=control_panel&subtab=ban_ip");
            exit;
        }
        $insert_string=$ip."\n";
	$ip_filename=$this->_model->_table_path($this->_dbname, $this->_banedip_table).$this->_model->get_data_ext();
        file_put_contents($ip_filename, $insert_string, FILE_APPEND | LOCK_EX);
        header("Location:index.php?action=control_panel&subtab=ban_ip");
    }
}
?>