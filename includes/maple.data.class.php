<?php
/**
 * 定义 用于读写文件的 类
 * @copyright Copyright (c) 2008 - 2009 Maple Group. (http://maple.dreamneverfall.cn)
 * @author maple group (maple.dreamneverfall.cn)
 * @version 2009-12-30  
 */
class Maple_Data_Processor
{
    public $_db_root_dir;//数据根目录
    public $_delimiter='"';//分割符
    public $_errors;//保存错误信息
    public $_ext='.txt';//所有数据文件的后缀名，默认是 .txt
    public $_frame_ext='.frame';//数据表定义文件的后缀名，紧跟于数据表名之后。默认是 .frame
    public $_data_ext='.data';//数据表内容文件的后缀名，紧跟于数据表名之后。默认是 .data
    public $_index_ext='.index';//数据表自增字段的记录文件的后缀名，紧跟于数据表名之后。默认是 .index
    public $_insert_id;//记录刚插入的数据的 id 值


    //构造函数，设置数据根目录
    function  __construct()
    {
        $this->_db_root_dir='data/';
    }
    //创建数据库
    function maple_create_db($dbname)
    {
        if(!@mkdir($this->_db_root_dir.$dbname, 0777, TRUE))
        {
            $error_string="Could not create database $dbname";
            if(is_dir($this->_db_root_dir.$dbname))
            {
                $error_string.="because $dbname exist!";
            }
            $this->maple_trigger_error($error_string);
            return false;
        }
    }

    //触发错误
    function maple_trigger_error($errormsg)
    {
        $this->_errors[]=$errormsg;
    }

    //删除指定数据库
    function maple_drop_db($dbname)
    {
        $d=dir($this->_db_root_dir.$dbname);
        while(false!==($entry=$d->read()))
        {
            if(substr($entry,0,1)!='.')
            {
                if(!unlink($entry))
                {
                    $this->maple_trigger_error("Could not delete table: $entry");
                }
            }
        }
        $d->close();
        if (rmdir($this->_db_root_dir.$dbname))
        {
            $this->maple_trigger_error("Could not delete database: $entry");
            return false;
        }
        return true;
    }

    //运行指定数据库的查询
    function maple_db_query($dbname,$tablename)
    {
        $tablepath=$this->_db_root_dir.$dbname."/$tablename";
        if(!$this->maple_check_table_exist($tablepath))
        {
            return false;
        }
        $result=$this->maple_select_all_in_onetable($tablepath);
        return $result;
    }

    //选择指定 id 的记录
    function maple_db_select_by_id($dbname,$tablename,$id)
    {
        $tablepath=$this->_db_root_dir.$dbname."/$tablename";
        if(!$this->maple_check_table_exist($tablepath))
        {
            return false;
        }
        $table_data_filename=$tablepath.$this->_data_ext.$this->_ext;
        $result=$this->_select_by_id($table_data_filename, $id);
        return $result;
    }

    //插入数据
    function maple_db_insert($dbname,$tablename,$insert_data)
    {
        $tablepath=$this->_db_root_dir.$dbname."/$tablename";
        if(!$this->maple_check_table_exist($tablepath))
        {
            return false;
        }
        $table_data_filename=$tablepath.$this->_data_ext.$this->_ext;
        //var_dump($insert_data);exit;
        $result=$this->maple_insert_data_into_onetable($table_data_filename,$insert_data);
        return $result;
    }

    //修改指定数据库，数据表的指定记录。包括更新和删除
    function maple_db_modify($dbname,$tablename,$id,$data)
    {
        $tablepath=$this->_db_root_dir.$dbname."/$tablename";
        if(!$this->maple_check_table_exist($tablepath))
        {
            return false;
        }
        if(!is_array($data))
        {
            $errormsg="您输入的数据不是一个数组!";
            $this->maple_trigger_error($errormsg);
            return false;
        }
        foreach($data as &$per_element)
        {
            $per_element=$this->maple_escape_string($per_element);
        }
        $data=implode($this->_delimiter,$data);
        if($data)
        {
            $data.="\n";
        }
        $table_data_filename=$tablepath.$this->_data_ext.$this->_ext;
        $result=$this->_modify($table_data_filename,$id,$data);
        return $result;
    }

    //插入数据到指定数据表中
    function maple_insert_data_into_onetable($table,$data)
    {
        if(!is_array($data))
        {
            $errormsg="您输入的数据不是一个数组!";
            $this->maple_trigger_error($errormsg);
            return false;
        }
        //get index value
        $table_index_filename=str_replace($this->_data_ext, $this->_index_ext, $table);
        $index_id=file_get_contents($table_index_filename);
        $next_id=intval($index_id)+1;
        foreach($data as &$per_element)
        {
            $per_element=$this->maple_escape_string($per_element);
        }
        $data=implode($this->_delimiter,$data);
        $data.="\n";
        $insert_string=$index_id.$this->_delimiter.$data;
        if($this->_writeover($table, $insert_string, 'ab'))
        {
            file_put_contents($table_index_filename, $next_id);
            $this->maple_set_inset_id($index_id);
            return TRUE;
        }
        else
        {
            return false;
        }
        
    }

    //设置刚插入的 id 值
    function maple_set_inset_id($index_id)
    {
        $this->_insert_id=$index_id;
    }

    //查询得到一个数据表中的所有记录
    function maple_select_all_in_onetable($table)
    {
        $data_filename=$table.$this->_data_ext.$this->_ext;
        $frame_filename=$table.$this->_frame_ext.$this->_ext;
        $data=$this->_readover($data_filename);
        $frame_data=$this->_read_frame($frame_filename);
        $data=$this->maple_array_combine($frame_data, $data);
        return $data;
    }

    //联合数组
    function maple_array_combine($a,$b)
    {
        foreach ($b as &$per_element)
        {
            $per_element=array_combine($a, $per_element);
        }
        return $b;
    }

    //读取一个数据表的字段
    function _read_frame($filename)
    {
        $frame_string=trim(file_get_contents($filename));
        $frame_data=explode($this->_delimiter, $frame_string);
        return $frame_data;
    }
    
    /**
     *	读取数据
     * 	@param $filename;
     *  @param $method;
     *  @return $filedata;
     */
    function _readover($filename,$method='rb')
    {
            $filedata = array();
            $handle = @fopen($filename,$method);
            if($handle)
            {
                    flock($handle,LOCK_SH);
                    while(!feof($handle))
                    {
                            $row=fgets($handle,999);
                            if(is_string($row))
                            {
                                    //$filedata[]=explode('"',$row);
                                    $now_array=explode($this->_delimiter,$row);
                                    $filedata_current_key=$now_array[0];
                                    $filedata[$filedata_current_key]=$now_array;
                            }
                    }
                    flock($handle,LOCK_UN);
                    fclose($handle);
            }
            else
            {
                    $errormsg="暂时不能打开文件 $filename，请稍候再试。";
                    $this->maple_trigger_error($errormsg);
                    return false;
            }
            return $filedata;
    }
    /**
     * 等到指定ID的留言
     * 注意：若文件被正确打开，返回值总是一个数组。但没有找到符合条件的记录会返回一个空数组！
     * @param string $file
     * @param integer $id
     * @return  mixed:Returns $filedata on success or FALSE on failure.
     */
    function _select_by_id($file,$id)
    {
            $filedata = array();//初始化变量，用于存储数据
            $handle = @fopen($file,'rb+');//以rb+方式打开文件
            if($handle)//若文件被正确打开
            {
                    flock($handle,LOCK_SH);//分享锁定（读取）
                    while(!feof($handle))//若没有到达文件结尾
                    {
                            $row=fgets($handle,999);//得到一行记录
                            if(is_string($row))//若此记录是字符串
                            {
                                    $row_data=explode('"',$row);//将此行记录分离为一个数组
                                    if ($row_data[0]==$id)//若找到指定 id 的记录，读取(查找)结束
                                    {
                                        $filedata=$row_data;
                                        break;
                                    }
                            }
                    }
                    flock($handle,LOCK_UN);//取消锁定
                    fclose($handle);//关闭指针
            }
            else//若文件没有被正确打开，触发错误
            {
                    $errormsg="暂时不能打开文件 $filename，请稍候再试--。";
                    $this->maple_trigger_error($errormsg);
                    return false;
            }
            return $filedata;
    }
    
    /**
     * 写入数据
     * @param $filename
     * @param $data
     * @param $method
     * @param $iflock
     * @param $fseek_offset_value
     * @param $ftruncate_value
     */
    function _writeover($filename,$data,$method="rb+",$iflock=1,$fseek_offset_value=0,$ftruncate_value=0)
    {
            $handle=@fopen($filename,$method);
            if(!$handle)
            {
                    $errormsg="暂时不能打开文件，请稍候再试。";
                    $this->maple_trigger_error($errormsg);
                    return false;
            }
            if($iflock)
            {
                    flock($handle,LOCK_EX);
            }
            if($fseek_offset_value!=0)
            {
                    fseek($handle,$fseek_offset_value);
            }
            fwrite($handle,$data);
            if($method=="rb+")
            {
                    if($ftruncate_value!=0)
                    {
                            ftruncate($handle,$ftruncate_value);
                    }
                    else
                    {
                            ftruncate($handle,strlen($data));
                    }
            }
            flock($handle,LOCK_UN);
            fclose($handle);
            return true;
    }

    //检查一个数据表是否存在
    function maple_check_table_exist($tablepath)
    {
        $one_table=array($tablepath.$this->_frame_ext.$this->_ext,
                         $tablepath.$this->_data_ext.$this->_ext,
                         $tablepath.$this->_index_ext.$this->_ext);
        foreach ($one_table as $file)
        {
            if(!file_exists($file))
            {
                $errormsg="$file 不存在";
                $this->maple_trigger_error($errormsg);
                continue;
            }
            if(!is_writable($file))
            {
                $errormsg="$file 不可写";
                $this->maple_trigger_error($errormsg);
            }
        }
        if($this->_errors)
        {
            return false;
        }
        return true;
    }

    //返回错误信息
    function maple_error()
    {
        return $this->_errors;
    }

    //转义字符串
    function maple_escape_string($string)
    {
        $string=htmlspecialchars(trim($string), ENT_QUOTES,'UTF-8');
        return $string;
    }

    /**
     * 更改留言内容，如更改，删除留言
     * 注意：删除总是返回true,即使不存在指定id的记录
     * @param string $filename
     * @param integer $mid
     * @param string $string
     * @param string $mode
     */
    function _modify($filename,$mid,$string,$mode='rb+')
    {
            $filesize=filesize($filename);
            $str=file_get_contents($filename);
            $handle=fopen($filename,$mode);
            if(!$handle)
            {
                    $errormsg='Could not open the file '.$filename;
                    $this->maple_trigger_error($errormsg);
                    return false;
            }
            $id_len=strlen($mid);
            $file_point=array();
            $m_len=0;
            while(!feof($handle))
            {
                    $file_point[]=ftell($handle);
                    $str_line=fgets($handle,1024);
                    if(substr($str_line,0,$id_len)==$mid)
                    {
                            $m_len=strlen($str_line);
                            break;
                    }
            }
            $begin_point=end($file_point);
            $offset=$begin_point+$m_len;
            fseek($handle,$offset);
            $last_string=fread($handle,$filesize+1);

            $put_string=$string.$last_string;

            fseek($handle,$begin_point);
            fwrite($handle,$put_string);

            $new_all_len=$begin_point+strlen($put_string);

            ftruncate($handle,$new_all_len);
            fclose($handle);
            return true;
    }

    //得到刚插入数据的id
    function maple_insert_id()
    {
        return $this->_insert_id;
    }

    //结果集合的数目
    function maple_number_rows($result)
    {
        $num=count($result);
        return $num;
    }
}
?>