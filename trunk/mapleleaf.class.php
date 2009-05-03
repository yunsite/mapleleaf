<?php

class guestbook
{
	/**
	 * 构造函数，进行初始化
	 */
	function guestbook()
	{
		
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
		
		return $message_list;
	}
	
	/**
	 * 删除指定的留言
	 * @param $number
	 */
	function delete($number)
	{

	}
	
	/**
	 * 回复指定的留言
	 * @param $number 指定的留言
	 */
	function reply($number)
	{
		
	}
}

class text_db
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