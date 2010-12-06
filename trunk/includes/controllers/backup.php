<?php
class backup extends BaseController{
    public $_model;

    public  function  __construct() {
        $this->_model=new JuneTxtDB();
        $this->_model->select_db(DB);
    }

    public  function backupData(){
        is_admin();
        $dir="data/".DB.'/';
        if(!class_exists('ZipArchive'))
        {
            $this->show_message(FrontController::t('BACKUP_NOTSUPPORT'),true,'index.php?action=control_panel&subtab=message');
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

    
}
?>