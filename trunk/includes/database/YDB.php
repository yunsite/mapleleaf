<?php
include APPROOT.'/includes/database/YDBBase.php';
class YDB {
    public static $allowdDBMS=array('mysql','mysqli','sqlite','flatfile','dummydb');
    public static function factory($url){
        $_url = parse_url($url);
        $dbms=$_url['scheme'];
        if(in_array($dbms, self::$allowdDBMS)){
            $DBClass='Y'.$dbms;
            include_once APPROOT.'/includes/database/'.$DBClass.'.php';
            return new $DBClass($url);
        }else{
            die("DBMS '$dbms' is not supported.");
        }
    }
}