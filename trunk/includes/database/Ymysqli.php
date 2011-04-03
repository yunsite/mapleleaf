<?php
class Ymysqli extends YDBBase {
    public $lnk;
    protected $database;
    public function Ymysqli($url){
        $url = parse_url($url);

        // Check if mysqli extension support is present in PHP
        if (!class_exists('mysqli')) {
            die('Unable to use the mysqli extension because the mysqli extension for PHP is not installed. Check your <code>php.ini</code> to see how you can enable it.');
        }

        // Decode url-encoded information in the db connection string
        $url['user'] = urldecode($url['user']);
        // Test if database url has a password.
        $url['pass'] = isset($url['pass']) ? urldecode($url['pass']) : '';
        $url['host'] = urldecode($url['host']);
        $url['path'] = urldecode($url['path']);

        // Allow for non-standard MySQL port.
        if (isset($url['port'])) {
            $url['port'] = $url['port'];
        }else{
            $url['port'] = 3306;
        }
        
        @$connection = new mysqli($url['host'],$url['user'],$url['pass'],substr($url['path'], 1),$url['port']);
        if (mysqli_connect_errno()) {
            throw new Exception(mysqli_connect_error());
        }
        $this->database=substr($url['path'], 1);
        // Force MySQL to use the UTF-8 character set. Also set the collation, if a
        // certain one has been set; otherwise, MySQL defaults to 'utf8_general_ci'
        // for UTF-8.
        if (!empty($GLOBALS['db_collation'])) {
            $connection->query('SET NAMES utf8 COLLATE '.$GLOBALS['db_collation']);
        } else {
            $connection->query('SET NAMES utf8');
        }
        $this->lnk=$connection;
    }
    public function  query($sql) {
        if($this->lnk){
            $query_result=$this->lnk->query($sql);
            return $query_result;
        }
        return false;
    }
    public function  escape_string($item) {
        return $this->lnk->real_escape_string($item);
    }
    public function  queryAll($sql) {
        $query_result=$this->query($sql);
        if(is_object($query_result)){
            if($query_result->num_rows){
                $result_array=array();
                while($line=$query_result->fetch_array(MYSQLI_ASSOC)){
                    $result_array[]=$line;
                }
                return $result_array;
            }
        }
        return array();
    }
    public function  insert_id() {
        return $this->lnk->insert_id;
    }
    public function  error() {
        return $this->lnk->error;
    }
    public function  server_version() {
        list($version) = explode('-', $this->lnk->server_version);
        return $version;
    }
    public function  num_rows($result) {
        return $result->num_rows;
    }
    public function  num_fields($result) {
        return $result->field_count;
    }
    public function  affected_rows() {
        return $this->lnk->affected_rows;
    }
    public function  table_exists($table_name) {
        $tables=$this->queryAll("SHOW TABLES FROM ".$this->database);
        if($tables){
            foreach ($tables as &$v) {
                $v=$v[0];
            }
        }
        return in_array($table_name, $tables);
    }
    public function  close() {
        return $this->lnk->close();
    }
}
