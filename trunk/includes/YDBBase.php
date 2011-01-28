<?php
/**
 * Abstract class for all databases
 *
 * @author rainyjune
 * @license GPL 2
 * @link http://mapleleaf.googlecode.com/
 */
abstract  class YDBBase {
    abstract public function query($sql);
    abstract public function queryAll($sql);
    abstract public function insert_id();
    abstract public function error();
    abstract public function server_version();
    abstract public function escape_string($item);
    abstract public function num_rows($result);
    abstract public function num_fields($result);
    abstract public function affected_rows();
    abstract public function table_exists($table_name);
    abstract public function close();
}
?>
