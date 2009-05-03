<?php

class guestbook
{
	/**
	 * 定义用什么数据库，这里直接用text_db
	 * @var string
	 */
	var $db_string = 'text_db';
	/**
	 * 数据库对象
	 */
	var $db;
	var $template = '';

	/**
	 * 构造函数，进行初始化
	 */
	function guestbook()
	{
		// 生成数据库对象
		$this->db = new $this->db_string;
	}

	/**
	 * 读取配置
	 * @param $param 保存配置的数组
	 */
	function config($param)
	{

	}

	/**
	 * 读取留言
	 * @param $limit
	 * @param $offset
	 * @return message_list 按时间排序的留言，一个数组
	 */
	function read($limit, $offset)
	{
		$message_list = $this->db->select($limit, $offset);
		return $message_list;
	}

	/**
	 * 删除指定的留言
	 * @param $number
	 */
	function delete($number)
	{
		$this->db->delete($number);
	}

	/**
	 * 回复指定的留言
	 * @param $number 指定的留言
	 */
	function reply($number)
	{
			
	}
}

/**
 * 数据库接口，可以是文本数据库，也可以是其他的
 * 接口是php5开始才有的，那就不用interface了，用class吧，
 * 不知道php4中的面向对象有没有泛型，
 * 先这样写，出错了再改
 */
class db
{
	/**
	 * 数据库的初始化，
	 * 如果是文本数据库，则可以在这里初始化文本数据库的文件位置
	 * mysql可以在这里连接数据库
	 * @return unknown_type
	 */
	function db()
	{
		// dummy function
	}


	/**
	 * 删除指定的节点
	 * @param $number
	 * @return unknown_type
	 */
	function delete($number)
	{

	}

	/**
	 * 从尾部插入一个节点
	 * @param $data 存储数据的数组
	 * @return unknown_type
	 */
	function insert($data)
	{

	}

	/**
	 * 读取数据，尾部的一个节点的 offset 为 0
	 * @param $limit
	 * @param $offset
	 * @return unknown_type
	 */
	function select($limit = 15, $offset = 0)
	{

	}

	/**
	 *
	 * @param $limit
	 * @return unknown_type
	 */
	function limit($limit)
	{

	}

	/**
	 *
	 * @param $offset
	 * @return unknown_type
	 */
	function offset($offset)
	{
		
	}
}

/**
 * 文本数据库的具体实现
 *
 */
class text_db extends db
{
	var $offset;
	var $limit;

	function text_db()
	{

	}

	/**
	 * 删除指定的节点
	 * @param $number
	 * @return unknown_type
	 */
	function delete($number)
	{

	}

	/**
	 * 从尾部插入一个节点
	 * @param $data 存储数据的数组
	 * @return unknown_type
	 */
	function insert($data)
	{

	}

	/**
	 * 读取数据，尾部的一个节点的 offset 为 0
	 * @param $limit
	 * @param $offset
	 * @return unknown_type
	 */
	function select($limit = 15, $offset = 0)
	{
		$this->limit = $limit;
		$this->offset = $offset;
	}

	/**
	 *
	 * @param $limit
	 * @return unknown_type
	 */
	function limit($limit)
	{
		$this->limit = $limit;
	}

	/**
	 *
	 * @param $offset
	 * @return unknown_type
	 */
	function offset($offset)
	{
		$this->offset = $offset;
	}
}


?>