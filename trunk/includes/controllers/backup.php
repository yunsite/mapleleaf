<?php
class backupClass{
    public  function backup(){
        is_admin();
        $dir="data/{$this->_dbname}/";
        if(!class_exists('ZipArchive'))
        {
            $this->show_message($this->t('BACKUP_NOTSUPPORT'),true,'index.php?action=control_panel&subtab=message');
            exit;
        }
        $zip = new ZipArchive();
        $filename = $dir."backup-".date('Ymd',time()).".zip";

        if ($zip->open($filename, ZIPARCHIVE::CREATE)!==TRUE)
			exit("cannot open <$filename>\n");
        $d=dir($dir);
        while(false!==($entry=$d->read()))
        {
            if(substr($entry,0,1)!='.')
                $zip->addFile($dir.$entry);
        }
        $d->close();
        $zip->close();
        header("Location:$filename");
    }

        /**
     * 删除服务器上的备份文件，会在管理员注销登录时执行
     */
    protected  function delete_backup_files(){
	$d=dir($this->_model->_db_path($this->_dbname));
	while(false!==($entry=$d->read()))
	{
	    if (strlen($entry)==19)
	    {
		$d_file=$this->_model->_db_path($this->_dbname).'/'.$entry;
		@unlink($d_file);
	    }
	}
	$d->close();
    }
}
?>