<?php
/**
 * The Class used for access data,named JuneTxtDb.
 *
 * @author Kang Chen <dreamneverfall@gmail.com>
 * @link http://dreamneverfall.cn/junetxtdb/
 * @copyright &copy; 2010 DreamNeverFall Team
 * @license GPL 2
 * @version 0.2 2010-07-07
 */

class JuneTxtDb
{
    private $_version='0.2';
    private $_db_root_dir='data/';
    public $_delimiter='"';
    /**
     * @var string
     */
    public $_error;
    public $_frame_ext='.frm.txt';//Default to '.frm.txt'.
    public $_data_ext='.dat.txt';//Default to '.dat.txt'.
    public $_index_ext='.idx.txt';//Default to '.idx.txt'.
    /**
     * @var int
     */
    protected  $_insert_id;
    /**
     * @var string
     */
    protected $_currentDB;
    protected  $_charset='UTF-8';//Default to 'UTF-8'.
    private $_supported_characters=array('ISO-8859-1','ISO-8859-15','UTF-8','cp866','cp1251','cp1252','KOI8-R','BIG5','GB2312','BIG5-HKSCS','Shift_JIS','EUC-JP','      ISO8859-1','    ISO8859-15','ibm866','866','Windows-1251','win-1251','1251','Windows-1252','1252','koi8-ru','koi8r','950','936','SJIS','932','EUCJP');
       
        /**
         * set database root directory
         * @param string $rootdir
         * @return void
         */
        public function june_set_dbrootdir($rootdir)
        {
                $this->_db_root_dir=$rootdir;
        }
   
    /**
     * connect to the database,if no errors return TRUE,othewise FALSE
     * @return boolean
     */
    public function june_connect()
    {
        $errormsg='';
        if(!is_dir($this->_db_root_dir))
                $errormsg="$this->_db_root_dir is not a directory!";
        elseif (!is_readable($this->_db_root_dir))
                $errormsg="$this->_db_root_dir is not readable!";
        elseif (!is_writable($this->_db_root_dir))
                $errormsg="$this->_db_root_dir is not writable!";              
        /*elseif (!is_executable($this->_db_root_dir))
                $errormsg="$this->_db_root_dir is not executable!";*/
        if($errormsg)
        {
                $this->_trigger_error($errormsg);
                return FALSE;
        }
        return TRUE;
    }

    /**
     * Create One Database
     * @param string $dbname
     * @return boolean
     */
    public function june_create_db($dbname)
    {
        $error_string='';
        if(is_dir($this->_db_path($dbname)))
                $error_string="The database `$dbname` already exists!";
        elseif(!mkdir($this->_db_path($dbname), 0777, TRUE))
            $error_string="Could not create database `$dbname`,you should make the directory '$this->_db_root_dir' 777!";
        elseif(!chmod($this->_db_path($dbname),0777))
                $error_string="Could not chmod the directory '$this->_db_root_dir.$dbname' to 777!";
        if($error_string)
        {
                $this->_trigger_error($error_string);
            return false;
        }
        return true;
    }
   
    /**
     * Trigger error
     * @param string $errormsg
     */
    protected function _trigger_error($errormsg)
    {
        $this->_error=$errormsg;
    }
   
    /**
     * drop database
     * @param string $dbname
     * @return boolean
     */
    public function june_drop_db($dbname)
    {
        if (!$this->june_select_db($dbname))
            return FALSE;
        $tables=array();
        $table_exts=array($this->_index_ext,$this->_data_ext,$this->_frame_ext);
        $dbpath=$this->_db_root_dir.$dbname;
        if (!rmdirs($dbpath))
        {
            $errormsg="The database `$dbname` could not drop,you can remove it manually.";
                $this->_trigger_error($errormsg);
                return FALSE;
        }
        return TRUE;
    }

    /**
     * set the active database
     * @param string $dbname
     * @return boolean
     */
    public function june_select_db($dbname)
    {
        if (!$dbname)
        {
            $errormsg="You shold select one database first! ";
                $this->_trigger_error($errormsg);
            return FALSE;
        }
        if(!$this->_db_exists($dbname))
        {
                $errormsg="Database `$dbname` not exists! ";
                $this->_trigger_error($errormsg);
            return FALSE;
        }
        $this->_currentDB=$dbname;
        return TRUE;
    }

    /**
     * @param string $tablename
     * @param array $fields
     * @example $fields=array(
     *                   array('name'=>'mid','auto_increment'=>true),
     *                   array('name'=>'author'),
     *                   array('name'=>'body')
     *                   );
     * @return Boolean
     */
    public function june_create_table($tablename,$fields)
    {
        //check if the database exists
        if(!$this->june_select_db($this->_currentDB))
                return FALSE;
        //check if the table exists
        if($this->_table_exists($this->_currentDB, $tablename))
        {
            $error_msg="`$this->_currentDB.$tablename` already exist!!";
            $this->_trigger_error($error_msg);
            return FALSE;
        }
        if(!is_writable($this->_db_path($this->_currentDB)))
        {
            $error_msg=$this->_currentDB." not writable!";
            $this->_trigger_error($error_msg);
            return FALSE;
        }
        if (!is_array($fields) || count($fields)<1)
        {
            $errmsg='The parameter of fields must be Array, and at least one field!';
            $this->_trigger_error($errmsg);
            return FALSE;
        }
        //parse the array to string,then write it into $frame_file
        $field_string=$this->_parse_fields_create($fields);
        $table_files=$this->_table_files($this->_currentDB,$tablename);
        $frame_status=file_put_contents($table_files['frame'], $field_string);
        $index_status=file_put_contents($table_files['index'], '1');
        $data_status=touch($table_files['data']);
        if($frame_status && $index_status && $data_status)
        {
                if(chmod($table_files['frame'],0777) && chmod($table_files['index'],0777) && chmod($table_files['data'],0777))
                        return TRUE;
            $errmsg="`$tablename` was created,but you need to chmod 777 to files:{$table_files['frame']},{$table_files['index']},{$table_files['data']}!";
                $this->_trigger_error($errmsg);
            return FALSE;
        }
        else
        {
                $errmsg="$tablename could not created properly!";
                $this->_trigger_error($errmsg);
            return FALSE;
        }
    }
   
    /**
     * get the filenames of one table
     * @param string $dbname
     * @param string $tablename
     * @return return the table info.
     */
    private function _table_files($dbname,$tablename)
    {
        $tbpath=$this->_table_path($dbname,$tablename);
        $tb_data_file=$tbpath.$this->_data_ext;
        $tb_index_file=$tbpath.$this->_index_ext;
        $tb_frm_file=$tbpath.$this->_frame_ext;
        $table=array();
        $table['index']=$tb_index_file;
        $table['frame']=$tb_frm_file;
        $table['data']=$tb_data_file;
        return $table;
    }


    /**
     * parse the Array to String ,so that we put it into frame file
     * @param Array $fields
     * @return string $str
     */
    public function _parse_fields_create($fields)
    {
        $str='';
        $auto_exists=NULL;
        foreach ($fields as $field)
        {
            if (isset ($field['name']) && is_string($field['name']))
            {
                $str.=$field['name'];
                if(isset ($field['auto_increment']))
                {   if(!$auto_exists)
                    {
                        $str.=':auto_increment';
                        $auto_exists=TRUE;
                    }
                }
                $str.="\n";
            }
        }
        return $str;
    }
    /**
     * insert data into table
     * @param string $tablename
     * @param array $data
     * @return boolean
     */
    public function june_query_insert($tablename,$data)
    {
        if(!$this->june_select_db($this->_currentDB))
                return FALSE;
        $table_files=$this->_table_files($this->_currentDB,$tablename);
        $frmf=$table_files['frame'];
        $datf=$table_files['data'];
        $idxf=$table_files['index'];
        if(!$this->_table_exists($this->_currentDB,$tablename))
        {
                $errmsg="Table `$tablename` not exists!";
                $this->_trigger_error($errmsg);
                return FALSE;
        }
        if(!is_array($data))
        {
                $errmsg="The data you insert must be Array!";
                $this->_trigger_error($errmsg);
                return FALSE;
        }
        $frm=$this->_get_frame($this->_currentDB,$tablename);
        //check if all field was filled
        if(count($data)!=count($frm))
        {
                $errmsg="The number of your data does not match with the number of fields!";
                $this->_trigger_error($errmsg);
                return FALSE;
        }
        for ($i=0,$num=count($data);$i<$num;$i++)
        {
                //if one field was set to NULL
                if(@in_array('auto_increment',$frm[$i]))
                        $data[$i]=NULL;
                if($data[$i]==NULL)
                {
                        //check if the field is auto_increment
                        if(!in_array('auto_increment',$frm[$i]))
                        {
                                $errmsg="The field `$frm[$i]` can not be NULL";
                                $this->_trigger_error($errmsg);
                                return FALSE;  
                        }
                        //replace NULL with the auto_inrement number,get from the index.txt
                        $auto_num=(int)file_get_contents($idxf);
                        $data[$i]=$auto_num;            
                }
                $data[$i]=$this->june_escape_string($data[$i]);
        }
        $insert_str=implode($this->_delimiter,$data);
        $insert_str.="\n";
        if(file_put_contents($datf,$insert_str,FILE_APPEND) && file_put_contents($idxf,$auto_num+1))
        {
            $this->_insert_id=$auto_num;
            return TRUE;
        }
        else
        {
            $errmsg='Data insert failed of permissions.';
            return FALSE;
        }
    }
    /**
     * execute query for select all data from one table
     * @param strig $tablename
     * @return mixed:FALSE or Array
     */
    public function june_query_select_all($tablename)
    {
        if (!$this->june_select_db($this->_currentDB))
            return FALSE;
        if(!$this->_table_exists($this->_currentDB,$tablename))
        {
                $errmsg="Table `$this->_currentDB.$tablename` does not exists!";
                $this->_trigger_error($errmsg);
                return FALSE;
        }
        $result=$this->_select_all_in_table($this->_currentDB,$tablename);
        return $result;        
    }
   
    /**
     * get data from one table specified by condition
     * @param string $tablename
     * @param array $condition
         * @example $condtion=array('id'=>1);$condition=array('author'=>'chen');
     * @return mixed:Returns $data on success or FALSE on failure.
     */
    public function june_query_select_byCondition($tablename,$condition)
    {
        if (!$this->june_select_db($this->_currentDB))
            return FALSE;
        if (!$this->_table_exists($this->_currentDB,$tablename))
        {
                $errmsg="Table `$this->_currentDB.$tablename` does not exists!";
                $this->_trigger_error($errmsg);
                return FALSE;
        }
        if(!is_array($condition) || count($condition)!=1 )
        {
                $errmsg='$condition Must be an array and Only has ONE element!';
                $this->_trigger_error($errmsg);
                return FALSE;
        }
        $field=key($condition);
        if ( ($key=$this->_field_exists($this->_currentDB,$tablename,$field)) === FALSE)
        {
                $errmsg="Field `$field` does not exists int table `$this->_currentDB.$tablename`";
                $this->_trigger_error($errmsg);
                return FALSE;                          
        }
        $datf=$this->_table_path($this->_currentDB,$tablename).$this->_data_ext;
        $data=$this->_select_by_field($datf,$key,$condition[$field]);
        if($data===FALSE)
        {
                $errmsg="The table '$tablename' could not open now,please try again later.";
                $this->_trigger_error($errmsg);
                return FALSE;
        }
        return $data;
    }
    /**
     * get field的data
     * Note:If the file was opened successfully,the value returned alwasy be an array。
                But array() was returned if no record that match the conditon was found.
     * @param string $file
     * @param string $key
     * @param mixed $value
     * @return  mixed:Returns $filedata on success or FALSE on failure.
     */
    private function _select_by_field($file,$key,$value)
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
                                if ($row_data[$key]==$value)//若找到指定 id 的记录，读取(查找)结束
                                    $filedata[]=$row_data;
                        }
                }
                flock($handle,LOCK_UN);//取消锁定
                fclose($handle);//关闭指针
        }
        else//若文件没有被正确打开，触发错误
                return FALSE;
        return $filedata;
    }
    /**
     * check if one field exists
     * @param string $dbname
     * @param string $tablename
     * @param string $field
     * @return mixed
     */
    private function _field_exists($dbname,$tablename,$field)
    {
        $fields=$this->_read_frame($dbname,$tablename);
        if(($key=array_search($field,$fields,TRUE)) === FALSE )
                return FALSE;  
        return $key;
    }
   
    /**
     * get all data from one table
     * @param $dbname
     * @param $tablename
     * @return FALSE or $data
     */
    public function _select_all_in_table($dbname,$tablename)
    {
                $frmf=$this->_table_path($dbname,$tablename).$this->_frame_ext;
                $datf=$this->_table_path($dbname,$tablename).$this->_data_ext;
                $data=$this->_readover($datf);
                if($data===FALSE){
                        $errmsg="Could not open file '$datf' now,please try later.";
                        $this->_trigger_error($errmsg);
                        return FALSE;
                }
                if ($data==array())
                        return array();
                $frame_data=$this->_read_frame($dbname,$tablename);
                $data=$this->_array_combine($frame_data,$data);
                return $data;          
    }
   
    /**
     * combine array
     * @param array $a
     * @param array $b
     * @return array
     */
    function _array_combine($a,$b)
    {
        foreach ($b as &$per_element)
        {
            $per_element=array_combine($a, $per_element);
        }
        return $b;
    }
   
    /**
     * get FRAME of one table
     * @param string $dbname
     * @param string $tablename
     * @return array $frm
     */
    private function _read_frame($dbname,$tablename)
    {
        $frm=$this->_get_frame($dbname,$tablename);
        foreach ($frm as &$f)
        {
                if(is_array($f))
                        $f=$f[0];
        }
        return $frm;
    }
    /**
     *  read table
     *  @param $filename;
     *  @param $method;
     *  @return $filedata or FALSE;
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
                                $now_array=explode($this->_delimiter,$row);
                                $filedata[]=$now_array;
                        }
                }
                flock($handle,LOCK_UN);
                fclose($handle);
        }
        else
                return FALSE;
        return $filedata;
    }
    /**
     * get frame
     * @param string $dbname
     * @param string $tablename
     * @return array $frm
     */
    public function _get_frame($dbname,$tablename)
    {
        $tbpath=$this->_table_path($dbname,$tablename);
        $tbfr=$tbpath.$this->_frame_ext;
        $str=trim(file_get_contents($tbfr));
        $frm=explode("\n",$str);
        foreach ($frm as &$v)
        {
                $tmp_ar=explode(':',$v);
                if(count($tmp_ar)>1)
                        $v=array($tmp_ar[0],$tmp_ar[1]);
        }
        return $frm;
    }


        /**
         * drop database
         * @param string $dbname
         * @return boolean
         */
    public function _drop_db($dbname)
    {
        $dbpath=$this->_db_path($dbname);
        $d=dir($dbpath);
        while(false!==($entry=$d->read()))
        {
            if(substr($entry,0,1)!='.')
            {
                if(!unlink($entry))
                {
                    $this->_trigger_error("Could not delete table: $entry");
                    return FALSE;
                }
            }
        }
        $d->close();
        if (!rmdir($dbpath))
        {
            $this->maple_trigger_error("Could not delete database: $entry");
            return false;
        }
        return true;
    }

    /**
     * check if one db exists
     * @param  string $dbname
     * @return boolean
     */
    public function _db_exists($dbname)
    {
        if (!is_dir($this->_db_path($dbname)))
            return FALSE;
        return TRUE;
    }

    /**
     * check if one table exists
     * @param $dbname
     * @param $tablename
     */
    public function _table_exists($dbname,$tablename)
    {
        $table_files=$this->_table_files($dbname,$tablename);
        foreach ($table_files as $file)
        {
                if (!file_exists($file))
                        return FALSE;          
        }
        return TRUE;
    }

    /**
     * return the path of table
     * @param string $dbname
     * @param string $tablename
     * @return string
     */
    public function _table_path($dbname,$tablename)
    {
        return $this->_db_path($dbname).'/'.$tablename;
    }
    /**
     * return the path of database
     * @ignore Tested 1st
     * @param string $dbname
     * @return string
     */
    public function _db_path($dbname)
    {
        return $this->_db_root_dir.$dbname;
    }

    /**
     * print the errors
     */
    public function june_error()
    {
        echo $this->_error;
    }
   
    /**
     * return the id just inserted
     */
    public function june_insert_id()
    {
        return $this->_insert_id;
    }
   
    /**
     * List databases available on the server.
     * @return array $db
     */
    public function june_list_dbs()
    {
        $db=array();
        $d=dir($this->_db_root_dir);
        while ($tmp=$d->read())
        {
                if(is_dir($this->_db_root_dir.$tmp) && substr($tmp,0,1)!='.' )
                        $db[]=$tmp;
        }      
        return $db;
    }
   
    /**
     * return tables of one database
     * @param string $dbname
     * @return mixed $tables or FALSE
     */
    public function june_list_tables($dbname)
    {
        if (!$this->june_select_db($dbname))
            return FALSE;
        $tables=array();
        $table_exts=array($this->_index_ext,$this->_data_ext,$this->_frame_ext);
        $dbpath=$this->_db_root_dir.$dbname;
        $d=dir($dbpath);
        while($tmp=$d->read())
        {
                if(is_file($dbpath.'/'.$tmp))
                {
                        $tmp=str_replace($table_exts,'',$tmp);
                        $tables[]=$tmp;
                }
        }
        $tables=array_unique($tables);
        return $tables;
    }
   
    /**
     * drop table
     * @param $tablename
     * @return boolean
     */
    public function june_drop_table($tablename)
    {
        if (!$this->june_select_db($this->_currentDB))
            return FALSE;
        if(!$this->_table_exists($this->_currentDB,$tablename))
        {
                $errmsg="Table `$this->_currentDB.$tablename` does not exists!";
                $this->_trigger_error($errmsg);
                return FALSE;
        }      
        $table_files=$this->_table_files($this->_currentDB,$tablename);
        foreach ($table_files as $file)
        {
                if(!unlink($file))
                {
                        $errmsg="table `$this->_currentDB.$tablename` does not deleted properly!";
                        $this->_trigger_error($errmsg);
                        return FALSE;
                }
        }
        return TRUE;
    }
   
    /**
     * delete or update one record from table
     * @param string $tablename
     * @param array $condition e.g:$condition=array('id'=>1)
     * @param string $action:D=Delete,U=update
     * @param array $data;
     * @return boolean
     * @bug:当查询条件中的字段是最后一个字段，不会删除此条记录！
     */
    public function june_query_modify($tablename,$condition,$action='D',$data=array())
    {
        if (!$this->june_select_db($this->_currentDB))
            return FALSE;
        if(!$this->_table_exists($this->_currentDB,$tablename))
        {
                $errmsg="Table `$this->_currentDB.$tablename` does not exists!";
                $this->_trigger_error($errmsg);
                return FALSE;
        }
        if(!is_array($condition) || count($condition)!=1 )
        {
                $errmsg='$condition Must be an array and Only has ONE element!';
                $this->_trigger_error($errmsg);
                return FALSE;
        }
        $field=key($condition);
        if ( ($key=$this->_field_exists($this->_currentDB,$tablename,$field)) === FALSE)
        {
                $errmsg="Field `$field` does not exists int table `$this->_currentDB.$tablename`";
                $this->_trigger_error($errmsg);
                return FALSE;                          
        }
        $datf=$this->_table_path($this->_currentDB,$tablename).$this->_data_ext;        
        if(!is_array($data))
        {
                $errmsg="The data you insert must be Array!";
                $this->_trigger_error($errmsg);
                return FALSE;
        }
        if($action!='D')
                $action='U';
        if($action=='D')
                $data=array();
        if($action=='U' && $data==array())
        {
            $errmsg="The data you updated Must NOT be array() !";
                $this->_trigger_error($errmsg);
                return FALSE;  
        }
        if($data==array())
                $string='';
        else
        {
                $frm=$this->_get_frame($this->_currentDB,$tablename);
                //check if all field was filled
                if(count($data)!=count($frm))
                {
                        $errmsg="The number of your data does not match with the field num!";
                        $this->_trigger_error($errmsg);
                        return FALSE;
                }
                foreach($data as &$per_element)
                {
                    $per_element=$this->june_escape_string($per_element);
                }
                $string=implode($this->_delimiter,$data);
                $string.="\n";
        }
        if(!$this->_modify($datf,$key,$condition[$field],$string))
        {
                $errmsg="Could not open the table `$this->_currentDB.$tablename` now,please try again later.";
                $this->_trigger_error($errmsg);
                return FALSE;
        }
        return TRUE;
    }
   


    /**
     * modify record
     * Note：always return true if the file was opened successfully
     * @param string $filename
     * @param string $key
     * @param mixed $value
     * @param string $string
         * @return boolean
     */
    function _modify($filename,$key,$value,$string)
    {
        $mode='rb+';
        $filesize=filesize($filename);//得到文件的size
        $str=file_get_contents($filename);//得到文件内容
        $handle=fopen($filename,$mode);//打开文件
        if(!$handle)//若无法打开文件，返回FALSE            
        {
                return FALSE;
        }
        //$id_len=strlen($mid);//get the length of $mid此处需要更新
        $file_point=array();
        $m_len=0;
        $find=FALSE;
        while(!feof($handle))
        {
                $file_point[]=ftell($handle);//记录当前的指针位置
                $str_line=fgets($handle,1024);
                        if(is_string($str_line))//若此记录是字符串
                {
                        $row_data=explode('"',$str_line);//将此行记录分离为一个数组
                    if ($row_data[$key]==$value)//若找到指定 key 的记录，读取(查找)结束
                    {
                        $find=TRUE;
                        $m_len=strlen($str_line);//$m_len 代表当前行的长度
                        break;//break the while
                    }
                }
        }
        if(!$find)
        {
                return TRUE;
        }
        $begin_point=end($file_point);// the start of modified line
        $offset=$begin_point+$m_len;//the lenth need to be modified
        fseek($handle,$offset);//移动指针到需要修改的内容之后无需修改的部分的开始
        $last_string=fread($handle,$filesize+1);//读取后面的内容
   
        $put_string=$string.$last_string;//需要写入的内容：新的字符串及原来无需修改的内容
   
        fseek($handle,$begin_point);//移动指针到需要修改的地方
        fwrite($handle,$put_string);//开始写入
   
        $new_all_len=$begin_point+strlen($put_string);//计算文件的新的长度
   
        ftruncate($handle,$new_all_len);//将文件截取到新的长度
        fclose($handle);
        return TRUE;
    }
   
    /**
     * Escape string
     * @param  string $string
     * @return string
     */
    function june_escape_string($string)
    {
        return htmlspecialchars(trim($string), ENT_QUOTES,$this->_charset);
    }
   
    /**
     * Sets the character set
     * @param $charset
     * @return void
     */
    public function june_set_charset($charset)
    {
        if (in_array($charset,$this->_supported_characters))
            $this->_charset=$charset;
    }
   
    /**
     * Returns the name of the character set
     * @return string :Returns the character set name.
     */
    public function june_get_charset()
    {
        return $this->_charset;
    }
   
    /**
     * return the version of JuneTxtDb
     * @return string
     */
    public function june_get_version_info()
    {
        return $this->_version;
    }
   
    /**
     * Get number of rows in result
     * @param $result
     * @return integer
     */
    public function june_num_rows($result)
    {
        return count($result);
    }
}
/**
 * @author FleaPHP Framework
 * @link http://www.fleaphp.org/
 * @copyright Copyright &copy; 2005 - 2008 QeeYuan China Inc. (http://www.qeeyuan.com)
 * @license http://www.yiiframework.com/license/  
 */
function rmdirs($dir)
{
    $dir = realpath($dir);
    if ($dir == '' || $dir == '/' ||  (strlen($dir) == 3 && substr($dir, 1) == ':\\'))
    {
        //we do not allowed to delete root directory.
        return false;
    }

    if(false !== ($dh = opendir($dir))) {
        while(false !== ($file = readdir($dh))) {
            if($file == '.' || $file == '..') { continue; }
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            if (is_dir($path)) {
                if (!rmdirs($path)) { return false; }
            } else {
                unlink($path);
            }
        }
        closedir($dh);
        rmdir($dir);
        return true;
    } else {
        return false;
    }
}
?>