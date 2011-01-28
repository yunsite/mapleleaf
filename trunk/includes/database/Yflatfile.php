<?php
class Yflatfile extends YDBBase {
    public $lnk;
    protected $affected_rows;
    public function Yflatfile($url){
        $url = parse_url($url);
        // Decode url-encoded information in the db connection string
        $url['path'] = urldecode($url['path']);
        //Check if the database exists
        $dbname=substr($url['path'], 1);
        if(!file_exists(DB_DIR.$dbname)){
            $db=new Database(ROOT_DATABASE);
            $create_db_sql="CREATE DATABASE $dbname";
            if(!$db->executeQuery($create_db_sql)){
                die(txtdbapi_get_last_error());
            }
        }
        $this->lnk=new Database($dbname);
    }
    
    public function query($sql){
        if($this->lnk){
            $query_result=$this->lnk->executeQuery($sql);
            $this->affected_rows=$query_result;
            return $query_result;
        }
        return false;
    }

    public function  escape_string($item) {
        return addslashes($item);
    }

    public function queryAll($sql){
        $query_result=$this->query($sql);
        if(is_object($query_result)){
            if($query_result->getRowCount()>0){
                $result_array=array();
                while($query_result->next()){
                    $result_array[]=$query_result->getCurrentValuesAsHash();
                }
                return $result_array;
            }
        }
        return array();
    }

    public function  insert_id() {
        return $this->lnk->getLastInsertId();
    }
    public function  error() {
        return txtdbapi_get_last_error();
    }
    public function  server_version() {
        return txtdbapi_version();
    }
    public function  num_rows($result) {
        return $result->getRowCount();
    }
    public function  num_fields($result) {
        return $result->getRowSize();
    }
    public function  affected_rows() {
        return $this->affected_rows;
    }
    public function  table_exists($table_name) {
        $_table=array("table"=>$table_name);
        $tables=$this->queryAll("LIST TABLES");
        return in_array($_table, $tables);
    }
    public function  close() {
        return true;
    }
}