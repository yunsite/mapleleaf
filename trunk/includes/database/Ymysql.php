<?php
class Ymysql extends YDBBase {
    protected $lnk;
    protected $database;
    public function Ymysql($url){
        $url = parse_url($url);

        // Check if MySQL support is present in PHP
        if (!function_exists('mysql_connect')) {
            die('Unable to use the MySQL database because the MySQL extension for PHP is not installed. Check your <code>php.ini</code> to see how you can enable it.');
        }

        // Decode url-encoded information in the db connection string
        $url['user'] = urldecode($url['user']);
        // Test if database url has a password.
        $url['pass'] = isset($url['pass']) ? urldecode($url['pass']) : '';
        $url['host'] = urldecode($url['host']);
        $url['path'] = urldecode($url['path']);

        // Allow for non-standard MySQL port.
        if (isset($url['port'])) {
            $url['host'] = $url['host'] .':'. $url['port'];
        }

        // - TRUE makes mysql_connect() always open a new link, even if
        //   mysql_connect() was called before with the same parameters.
        //   This is important if you are using two databases on the same
        //   server.
        // - 2 means CLIENT_FOUND_ROWS: return the number of found
        //   (matched) rows, not the number of affected rows.
        $connection = @mysql_connect($url['host'], $url['user'], $url['pass'], TRUE, 2);
        if (!$connection || !mysql_select_db(substr($url['path'], 1))) {
        // Show error screen otherwise
            throw new Exception(mysql_error());
        }
        $this->database=substr($url['path'], 1);
        // Force MySQL to use the UTF-8 character set. Also set the collation, if a
        // certain one has been set; otherwise, MySQL defaults to 'utf8_general_ci'
        // for UTF-8.
        if (!empty($GLOBALS['db_collation'])) {
            mysql_query('SET NAMES utf8 COLLATE '. $GLOBALS['db_collation'], $connection);
        }
        else {
            mysql_query('SET NAMES utf8', $connection);
        }
        $this->lnk=$connection;
    }
    public function query($sql){
        if($this->lnk){
            $query_result=mysql_query($sql,  $this->lnk);
            return $query_result;
        }
        return false;
    }
    public function  escape_string($item) {
        return mysql_real_escape_string($item,  $this->lnk);
    }
    public function queryAll($sql){
        $query_result=$this->query($sql);
        if(is_resource($query_result)){
            if(mysql_num_rows($query_result)>0){
                $result_array=array();
                while($line=  mysql_fetch_assoc($query_result)){
                    $result_array[]=$line;
                }
                return $result_array;
            }
        }
        return array();
    }
    public function insert_id(){
        return mysql_insert_id($this->lnk);
    }
    public function  error() {
        return mysql_error($this->lnk);
    }
    public function  server_version() {
        list($version) = explode('-', mysql_get_server_info());
        return $version;
    }
    public function  num_rows($result) {
        return mysql_num_rows($result);
    }
    public function  num_fields($result) {
        return mysql_num_fields($result);
    }
    public function  affected_rows() {
        return mysql_affected_rows($this->lnk);
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
        return mysql_close($this->lnk);
    }
}
