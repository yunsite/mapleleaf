<?php
class BackupController extends BaseController{
    public function actionCreate(){
        global $db_url;
        $url = parse_url($db_url);
        $url['path'] = urldecode($url['path']);
        $dbname=substr($url['path'], 1);
        is_admin();
        $dir="data/$dbname/";
        if(!class_exists('ZipArchive'))
            show_message(t('BACKUP_NOTSUPPORT'),true,'index.php?action=control_panel&subtab=message');
        if(!is_flatfile())
            show_message(t('BACKUP_TYPE_NOTSUPPORT'),true,'index.php?action=control_panel&subtab=message');
        $zip = new ZipArchive();
        $filename = $dir."backup-".date('Ymd',time()).".zip";
        if ($zip->open($filename, ZIPARCHIVE::CREATE)!==TRUE)
            exit("cannot open <$filename>\n");
        $d=dir($dir);
        while(false!==($entry=$d->read())){
            if(substr($entry,-4)=='.txt')
                $zip->addFile($dir.$entry);
        }
        $d->close();
        $zip->close();
        header("Location:$filename");
    }
}
