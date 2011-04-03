<?php
class Ysqlite extends YDBBase {
    protected $lnk;
    protected $database;
    public function Ysqlite($url){
        $dbdir=APPROOT.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR;
        $url = parse_url($url);

        // Check if SQLite support is present in PHP
        if (!function_exists('sqlite_open')) {
            die('Unable to use the SQLite database because the SQLite extension for PHP is not installed. Check your <code>php.ini</code> to see how you can enable it.');
        }

        $url['path'] = urldecode($url['path']);
        $dbname = substr($url['path'], 1);
        $connection = @sqlite_open($dbdir.$dbname.'.db',0666,$sqliteerror);
        if (!$connection) {
            die($sqliteerror);
        }
        $this->database=$dbname;
        $this->lnk=$connection;
    }
    public function query($sql){
        if($this->lnk){
            $query_result=sqlite_query($this->lnk,$sql);
            return $query_result;
        }else{
            return false;
        }
    }
    public function  escape_string($item) {
        return sqlite_escape_string($item);
    }
    public function queryAll($sql){
        $result=sqlite_array_query($this->lnk,$sql,SQLITE_ASSOC);
        return $result;
    }
    public function insert_id(){
        return sqlite_last_insert_rowid($this->lnk);
    }
    public function  error() {
        return sqlite_error_string(sqlite_last_error($this->lnk));
    }
    public function  server_version() {
        return sqlite_libversion();
    }
    public function  num_rows($result) {
        return sqlite_num_rows($result);
    }
    public function  num_fields($result) {
        return sqlite_num_fields($result);
    }
    public function  affected_rows() {
        return sqlite_changes($this->lnk);
    }
    public function  table_exists($table_name) {
        return (bool)@sqlite_fetch_column_types($table_name,  $this->lnk);
    }
    public function  close() {
        return sqlite_close($this->lnk);
    }
}
