<?php
class BackupController extends BaseController{
    public function actionCreate(){
        global $db_url;
        $url = parse_url($db_url);
        $url['path'] = urldecode($url['path']);
        $dbname=substr($url['path'], 1);
        is_admin();
        $dir="data/$dbname/";
        if(!class_exists('ZipArchive')){
            ZFramework::show_message(ZFramework::t('BACKUP_NOTSUPPORT'),true,'index.php?action=control_panel&subtab=message');
            exit;
        }
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
    /**
     * 删除服务器上的备份文件，会在管理员注销登录时执行
     */
    public static  function delete_backup_files(){
        global $db_url;
        $url = parse_url($db_url);
        $url['path'] = urldecode($url['path']);
        $dbname=substr($url['path'], 1);
        is_admin();
        is_admin();
        $dir=dirname(dirname(dirname(__FILE__))).'/data/'.$dbname;
	$d=dir($dir);
	while(false!==($entry=$d->read())){
	    if (strlen($entry)==19){
		$d_file=$dir.'/'.$entry;
		unlink($d_file);
	    }
	}
	$d->close();
    }
}