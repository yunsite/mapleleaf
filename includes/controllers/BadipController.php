<?php
/**
 * Badip controller
 *
 * @author rainyjune <dreamneverfall@gmail.com>
 * @version $Id$
 */
class BadipController extends BaseController
{
    public $_model;
    public function  __construct()
	{
        global $db_url;
        $this->_model=  YDB::factory($db_url);
    }
    public function actionCreate()
	{
        is_admin();
        $ip=@$_GET['ip'];
        if (valid_ip($ip)==false)
		{
            header("Location:index.php?action=control_panel&subtab=message");exit;
        }
        if(is_baned($ip))
		{
            header("Location:index.php?action=control_panel&subtab=ban_ip");exit;
        }
        $this->_model->query(sprintf(parse_tbprefix("INSERT INTO <badip> ( ip ) VALUES ( '%s' )"),$ip));
        header("Location:index.php?action=control_panel&subtab=ban_ip");
    }
    public function actionUpdate()
	{
        is_admin();
        @$ip_update_array=$_POST['select_ip'];
        if(!$ip_update_array)
		{
            header("Location:index.php?action=control_panel&subtab=ban_ip");exit;
        }
        foreach ($ip_update_array as $_ip) 
		{
            $this->_model->query(sprintf(parse_tbprefix("DELETE FROM <badip> WHERE ip = '%s'"),$_ip));
        }
        header("Location:index.php?action=control_panel&subtab=ban_ip");
    }
}
