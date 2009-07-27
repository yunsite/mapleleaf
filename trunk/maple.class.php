<?php
$mp_root_path=dirname(str_replace(DIRECTORY_SEPARATOR, '/', __FILE__));
require_once($mp_root_path.'/includes/Imgcode.php');
class Maple
{
	public 	$_m_file;
	public 	$_r_file;
	public  $_site_conf_file;
	public  $_board_name;
	public  $_admin_email;
	public  $_copyright_info;
	public  $_filter_words;
	public  $_valid_code_open;
	public  $_page_on;
	public  $_num_perpage;
	public  $_theme;
	public  $_smileys_dir;
	public  $_imgcode;
	public  $smileys = array(
		//	smiley			image name						width	height	title
		':-)'			=>	array('grin.gif',			'19',	'19',	'grin'),
		':lol:'			=>	array('lol.gif',			'19',	'19',	'LOL'),
		':cheese:'		=>	array('cheese.gif',			'19',	'19',	'cheese'),
		':)'			=>	array('smile.gif',			'19',	'19',	'smile'),
		';-)'			=>	array('wink.gif',			'19',	'19',	'wink'),
		';)'			=>	array('wink.gif',			'19',	'19',	'wink'),
		':smirk:'		=>	array('smirk.gif',			'19',	'19',	'smirk'),
		':roll:'		=>	array('rolleyes.gif',		'19',	'19',	'rolleyes'),
		':-S'			=>	array('confused.gif',		'19',	'19',	'confused'),
		':wow:'			=>	array('surprise.gif',		'19',	'19',	'surprised'),
		':bug:'			=>	array('bigsurprise.gif',	'19',	'19',	'big surprise'),
		':-P'			=>	array('tongue_laugh.gif',	'19',	'19',	'tongue laugh'),
		'%-P'			=>	array('tongue_rolleye.gif',	'19',	'19',	'tongue rolleye'),
		';-P'			=>	array('tongue_wink.gif',	'19',	'19',	'tongue wink'),
		':P'			=>	array('raspberry.gif',		'19',	'19',	'raspberry'),
		':blank:'		=>	array('blank.gif',			'19',	'19',	'blank stare'),
		':long:'		=>	array('longface.gif',		'19',	'19',	'long face'),
		':ohh:'			=>	array('ohh.gif',			'19',	'19',	'ohh'),
		':grrr:'		=>	array('grrr.gif',			'19',	'19',	'grrr'),
		':gulp:'		=>	array('gulp.gif',			'19',	'19',	'gulp'),
		'8-/'			=>	array('ohoh.gif',			'19',	'19',	'oh oh'),
		':down:'		=>	array('downer.gif',			'19',	'19',	'downer'),
		':red:'			=>	array('embarrassed.gif',	'19',	'19',	'red face'),
		':sick:'		=>	array('sick.gif',			'19',	'19',	'sick'),
		':shut:'		=>	array('shuteye.gif',		'19',	'19',	'shut eye'),
		':-/'			=>	array('hmm.gif',			'19',	'19',	'hmmm'),
		'>:('			=>	array('mad.gif',			'19',	'19',	'mad'),
		':mad:'			=>	array('mad.gif',			'19',	'19',	'mad'),
		'>:-('			=>	array('angry.gif',			'19',	'19',	'angry'),
		':angry:'		=>	array('angry.gif',			'19',	'19',	'angry'),
		':zip:'			=>	array('zip.gif',			'19',	'19',	'zipper'),
		':kiss:'		=>	array('kiss.gif',			'19',	'19',	'kiss'),
		':ahhh:'		=>	array('shock.gif',			'19',	'19',	'shock'),
		':coolsmile:'	=>	array('shade_smile.gif',	'19',	'19',	'cool smile'),
		':coolsmirk:'	=>	array('shade_smirk.gif',	'19',	'19',	'cool smirk'),
		':coolgrin:'	=>	array('shade_grin.gif',		'19',	'19',	'cool grin'),
		':coolhmm:'		=>	array('shade_hmm.gif',		'19',	'19',	'cool hmm'),
		':coolmad:'		=>	array('shade_mad.gif',		'19',	'19',	'cool mad'),
		':coolcheese:'	=>	array('shade_cheese.gif',	'19',	'19',	'cool cheese'),
		':vampire:'		=>	array('vampire.gif',		'19',	'19',	'vampire'),
		':snake:'		=>	array('snake.gif',			'19',	'19',	'snake'),
		':exclaim:'		=>	array('exclaim.gif',		'19',	'19',	'excaim'),
		':question:'	=>	array('question.gif',		'19',	'19',	'question') // no comma after last item
		);
	
	function Maple()
	{
		$mp_root_path=dirname(str_replace(DIRECTORY_SEPARATOR, '/', __FILE__));
		$this->_m_file=$mp_root_path.'/data/gb.txt';
		$this->_r_file=$mp_root_path.'/data/reply.txt';
		$this->_site_conf_file=$mp_root_path.'/adm/site.conf.php';
		$this->_smileys_dir='./smileys/images/';
		$this->_imgcode=new  FLEA_Helper_ImgCode();
		if(!file_exists($this->_m_file) || !file_exists($this->_r_file))
		{
			$this->showerror('gb.txt 或 reply.txt不存在,请确认 data/gb.txt 和 data/reply.txt 是否存在。若不存在请手工创建');
		}
		if(!is_writable($this->_m_file) || !is_writable($this->_r_file))
		{
			$this->showerror('gb.txt 或 reply.txt不可写，请赋予 data/gb.txt 和 reply.txt 写入权限');
		}
		
		require($this->_site_conf_file);
		$this->_board_name=$board_name;
		$this->_admin_email=$admin_email;
		$this->_copyright_info=$copyright_info;
		$this->_filter_words=$filter_words;
		$this->_valid_code_open=$valid_code_open;
		$this->_page_on=$page_on;
		$this->_num_perpage=$num_perpage;
		$this->_theme=$theme;		
	}
	
	function count_messages()
	{
		$message_array=array();
		$message_array=file($this->_m_file);
		if(is_numeric(@$message_array[0]))
		{
			$m_num=0;
		}
		else
		{
			$m_num=count($message_array)-1;
		}
		return $m_num;
	}
	
	function count_reply()
	{
		$reply_array=array();
		$reply_array=file($this->_r_file);
		$r_num=count($reply_array);
		return $r_num;
	}
	
	function get_data($current_page=0,$mode='front')
	{
		$data=array();
		$reply_data=array();
		$data=$this->readover($this->_m_file);
		array_pop($data);
		$data=array_reverse($data);
		$reply_data=$this->readover($this->_r_file);
		$check_reply=$reply_data?true:false;
		$reply_num=count($reply_data);
		$nums=count($data);

		//检索相关留言和回复,并转化表情符号
		for($i=0;$i<$nums;$i++)
		{
		// 转换表情符号，只对留言进行转换，没有对回复进行转化
			$data[$i][2]=str_replace(array('&gt;:(','&gt;:-('),array('>:(','>:-('),$data[$i][2]);
			$data[$i][2] = $this->parse_smileys($data[$i][2], $this->_smileys_dir, $this->smileys);
			//过滤显示一些敏感词语
			if ($mode=='front')
			{
				$data[$i][2]=$this->filter_words($data[$i][2]);
			}
			// if we need retrieve reply for the message
			if($check_reply==true)
			{
				for($j=0;$j<$reply_num;$j++)
				{
					$reply_current_search=$reply_data[$j];
					$mid=$data[$i][0];
					$reply_index=$reply_current_search[0];
					if($reply_index==$mid)
					{
						$data[$i]['reply']=$reply_data[$j];
						break;
					}
				}
			}
		}
		if ($mode=='front')
		{
			if($this->_page_on==1)
			{
				$pages=ceil($nums/$this->_num_perpage);
				
				if(isset($_GET['pid']))
				{
					$current_page=(int)$_GET['pid'];
				}
				if($current_page>=$pages)
				{
					$current_page=$pages-1;
				}
				if($current_page<0)
				{
					$current_page=0;
				}
				$start=$current_page*$this->_num_perpage;
				$data=array_slice($data,$start,$this->_num_perpage);
			}
		}
			return $data;
	}
	
	function mp_del($filename,$type,$id)
	{
		$all=file($filename);//把留言内容转化为一个数组
		$num=count($all);
		$num_use=$num;
		if($type=='message')
		{
			$num_use=$num-1;
		}
		for($i=0;$i<$num_use;$i++)//得到当前要删除的留言是数组的行数
		{
			$row=$all[$i];//类型为 字符串!
			$m_array=array();
			$m_array=explode('"',$row);
			if($m_array[0]==$id)
			{
				$sp=$i;
				break;
			}
		}
			unset($all[$sp]);//把此行从数组中删除
		$outputing=implode('',$all);
		$this->writeover($filename,$outputing,'wb');
	}	
	/**   
	 *  经过测试，此函数比mp_del()执行速度更快   (测试不严格，且数据量非常小)
	 *  机制是：打开文件后，将指针指向要删除的留言的开始位置,   
	 *         然后,开始写入数据，写入的数据是要删除的留言的后面的需要保留的文字,   
	 *         最后，使用ftruncate()截取为需要的长度。   
	 *  @param $filename;制定文件名
	 *  @param $type;文件类型
	 *  @param $id;id号码
	 */ 
	function mp_del_new($filename,$type,$id)  
	{      
		$all=file_get_contents($filename);      
		$all_len=strlen($all);      
		$del_begin_pos=strpos($all,$id.'"');      
		$pattern='/\b'.$id.'"(.)+"(.)+"[0-9]+\n/';
		if($type=='reply')      
		{
			$pattern='/\b'.$id.'"(.)+"[0-9]+\n/';
		}
		preg_match($pattern,$all,$matches);      
		$del_str=$matches[0];      
		$del_str_len=strlen($del_str);      
		$write_str_begin=$del_begin_pos+$del_str_len;      
		$write_str=substr($all,$write_str_begin);      
		$new_len=$all_len-$del_str_len;            
		$this->writeover($filename,$write_str,'rb+',1,$del_begin_pos,$new_len);  
	} 
	
	function update($id,$input)
	{
		$string=file_get_contents($this->_m_file);
		$len=strlen($string);
		//echo '$id:'.$id."<br />";
		
		//echo nl2br($string);
		//echo "<hr />";
		//exit;
		//$id=2;
		preg_match('/\b'.$id.'"(.)+"(.)+"[0-9]+\n/',$string,$matches);
		//echo '<pre>';
		//var_dump($matches);
		//echo '</pre>';
		
		//var_dump($matches[0]);
		//echo '<hr />';
		$del_str_len=strlen($matches[0]);
		//echo '要修改的字符串的长度：'.$del_str_len."<br />";
		
		$pos=strpos($string,$matches[0]);
		//echo '开始写的位置：'.$pos."<br />";
		
		$lt=substr($string,$pos+strlen($matches[0]));
		//echo '要连接的字符串：'."<br />".nl2br($lt)."<br />";
		
		$write_data=trim($input)."\n".($lt);
		//$input=$id.'"Admin"aaa"1247638340'."\n";
		//echo '要写入的字符串:'."<br />".nl2br($write_data);
		//exit;
		
		
		$all_len=$len-$del_str_len+strlen($input)-1;
		$this->writeover($this->_m_file,$write_data,'rb+',1,$pos,$all_len);
		//$this->writeover('name','data','method','iflock','fseek_offset_value','ftruncate_value');;
	}
	
	function getmessagebyid($mid)
	{
		$filedata = array();
		$handle = @fopen($this->_m_file,'rb+');
		if($handle) 
		{
			flock($handle,LOCK_SH);
			while(!feof($handle))
			{
				$row=fgets($handle,999);
				if(is_string($row))
				{
					$filedata=explode('"',$row);
					if ($filedata[0]==$mid)
					{
						break;
					}
				}
			}
			flock($handle,LOCK_UN);
			fclose($handle);
		}
		else
		{
			$this->showerror("暂时不能打开文件 $filename，请稍候再试--。");
			exit;
		}
		return $filedata;
		
		
	}
	function add_message($new_message)
	{
		$file_data=array_reverse(file($this->_m_file));
		@$index_num=(int)trim($file_data[0]);
		$next_num=$index_num+1;
		$input=file($this->_m_file);
		array_pop($input);
		$input=implode('',$input);
		$input.="$index_num\"".$new_message."$next_num\n";

		$this->writeover($this->_m_file,$input);
	}
	
	function add_reply($mid,$new_reply)
	{
		if($new_reply=='')
		{
			$this->showerror("您回复为空？！<a href='".$_SERVER['HTTP_REFERER']."'>返回</a>中...",true,$_SERVER['HTTP_REFERER']);
			exit;
		}
		if($mid < 0)
		{
			$this->showerror("非法操作！<a href='".$_SERVER['HTTP_REFERER']."'>返回</a>中...",true,$_SERVER['HTTP_REFERER']);
			exit;
		}
		$this->writeover($this->_r_file,$new_reply,'ab');
	}
	
	function clear_messages()
	{
		$this->writeover($this->_m_file,'0');
		$this->writeover($this->_r_file,'');
	}
	
	function clear_reply()
	{
		$this->writeover($this->_r_file,'');
	}
	function add_message_check()
	{
		//session_start();
		$user=$_POST['user'];
		$content=$_POST['content'];
		$user=htmlspecialchars(trim($user),ENT_COMPAT,'UTF-8');
		$admin_name_array=array('admin','root','administrator','管理员');
		if(!isset($_SESSION['admin']) && in_array(strtolower($user),$admin_name_array))
		{
			$user='anonymous';
		}
		$content = htmlspecialchars(trim($_POST['content']));
		$content = nl2br($content);
		$content = str_replace(array("\n", "\r\n", "\r"), '', $content);
		$time=time();
		if(empty($user) or empty($content))
		{	
			$this->showerror("你没有填写完成,现在正在<a href='./index.php'>返回</a>...",true,'index.php');
		    exit;
		}
		if(strlen($content)>580)
		{
		     $this->showerror("您的话语太多了，现在正在<a href='./index.php'>返回</a>...",true,'index.php');
		     exit;
		}
		if($this->_valid_code_open==1)
		{
			if(!$this->checkImgcode())
			{
				$this->showerror("验证码错误.现在正在<a href='./index.php'>返回</a>...",true,'index.php');
				exit;
			}
		}
		return $user.'"'.$content.'"'.$time."\n";
	}
	
	/**
	 *	读取数据
	 * 	@param $filename;
	 *  @param $method;
	 *  @return $filedata;
	 */
	function readover($filename,$method='rb')
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
					$filedata[]=explode('"',$row);
				}
			}
			flock($handle,LOCK_UN);
			fclose($handle);
		}
		else
		{
			$this->showerror("暂时不能打开文件 $filename，请稍候再试。");
			exit;
		}
		return $filedata;
	}
	
	function writeover($filename,$data,$method="rb+",$iflock=1,$fseek_offset_value=0,$ftruncate_value=0)
	{
		$handle=@fopen($filename,$method);
		if(!$handle)
		{
			$this->showerror("暂时不能打开文件，请稍候再试。");
			exit;
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
	}
	
	function parse_smileys($str = '', $image_url = '', $smileys = NULL)
{
	if ($image_url == '')
	{
		return $str;
	}

	if ( ! is_array($smileys))
	{
			return $str;
	}

	// Add a trailing slash to the file path if needed
	$image_url = preg_replace("/(.+?)\/*$/", "\\1/",  $image_url);

	foreach ($smileys as $key => $val)
	{
		$str = str_replace($key, "<img src=\"".$image_url.$smileys[$key][0]."\" width=\"".$smileys[$key][1]."\" height=\"".$smileys[$key][2]."\" title=\"".$smileys[$key][3]."\" style=\"border:0;\" />", $str);
	}

	return $str;
}

function showerror($msg,$redirect=false,$redirect_url='index.php',$time_delay=3) 
{
		@ob_end_clean();
		function_exists('ob_gzhandler') ? ob_start('ob_gzhandler') : ob_start();
		echo"<html>
			 	<head><title>错误信息</title>";
		echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" >';
		if($redirect==true)		
		{
			echo "<meta http-equiv='Refresh' content='$time_delay;URL=$redirect_url' />";
		}
		echo "<style type='text/css'>
			  P,BODY{FONT-FAMILY:tahoma,arial,sans-serif;FONT-SIZE:11px;}
			  A { TEXT-DECORATION: none;}
			  a:hover{ text-decoration: underline;}
			  TD { BORDER-RIGHT: 1px; BORDER-TOP: 0px; FONT-SIZE: 16pt; COLOR: #000000;}
			  </style>
			  </head>
			  <body>\n\n";
		echo"<table style='TABLE-LAYOUT:fixed;WORD-WRAP: break-word'><tr><td>$msg";
		echo"<br><br><b>You Can Get Help In</b>:<br><a target=_blank href=http://maple.dreamneverfall.cn/>maple.dreamneverfall.cn</a>";
		echo"</td></tr></table></body></html>";
		exit;
}
  function show_smileys_table()
{
	return  <<<EOF
		<table border="0" cellpadding="4" cellspacing="0">
		<tr>
		<td><a href="javascript:void(0);" onClick="insert_smiley(':-)')"><img src="./smileys/images/grin.gif" width="19" height="19" title="grin" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':lol:')"><img src="./smileys/images/lol.gif" width="19" height="19" title="LOL" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':cheese:')"><img src="./smileys/images/cheese.gif" width="19" height="19" title="cheese" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':)')"><img src="./smileys/images/smile.gif" width="19" height="19" title="smile" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(';-)')"><img src="./smileys/images/wink.gif" width="19" height="19" title="wink" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':smirk:')"><img src="./smileys/images/smirk.gif" width="19" height="19" title="smirk" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':roll:')"><img src="./smileys/images/rolleyes.gif" width="19" height="19" title="rolleyes" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':-S')"><img src="./smileys/images/confused.gif" width="19" height="19" title="confused" style="border:0;" /></a></td></tr>
		<tr>
		<td><a href="javascript:void(0);" onClick="insert_smiley(':wow:')"><img src="./smileys/images/surprise.gif" width="19" height="19" title="surprised" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':bug:')"><img src="./smileys/images/bigsurprise.gif" width="19" height="19" title="big surprise" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':-P')"><img src="./smileys/images/tongue_laugh.gif" width="19" height="19" title="tongue laugh" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley('%-P')"><img src="./smileys/images/tongue_rolleye.gif" width="19" height="19" title="tongue rolleye" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(';-P')"><img src="./smileys/images/tongue_wink.gif" width="19" height="19" title="tongue wink" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':P')"><img src="./smileys/images/raspberry.gif" width="19" height="19" title="raspberry" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':blank:')"><img src="./smileys/images/blank.gif" width="19" height="19" title="blank stare" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':long:')"><img src="./smileys/images/longface.gif" width="19" height="19" title="long face" style="border:0;" /></a></td></tr>
		<tr>
		<td><a href="javascript:void(0);" onClick="insert_smiley(':ohh:')"><img src="./smileys/images/ohh.gif" width="19" height="19" title="ohh" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':grrr:')"><img src="./smileys/images/grrr.gif" width="19" height="19" title="grrr" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':gulp:')"><img src="./smileys/images/gulp.gif" width="19" height="19" title="gulp" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley('8-/')"><img src="./smileys/images/ohoh.gif" width="19" height="19" title="oh oh" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':down:')"><img src="./smileys/images/downer.gif" width="19" height="19" title="downer" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':red:')"><img src="./smileys/images/embarrassed.gif" width="19" height="19" title="red face" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':sick:')"><img src="./smileys/images/sick.gif" width="19" height="19" title="sick" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':shut:')"><img src="./smileys/images/shuteye.gif" width="19" height="19" title="shut eye" style="border:0;" /></a></td></tr>
		<tr>
		<td><a href="javascript:void(0);" onClick="insert_smiley(':-/')"><img src="./smileys/images/hmm.gif" width="19" height="19" title="hmmm" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley('>:(')"><img src="./smileys/images/mad.gif" width="19" height="19" title="mad" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley('>:-(')"><img src="./smileys/images/angry.gif" width="19" height="19" title="angry" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':zip:')"><img src="./smileys/images/zip.gif" width="19" height="19" title="zipper" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':kiss:')"><img src="./smileys/images/kiss.gif" width="19" height="19" title="kiss" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':ahhh:')"><img src="./smileys/images/shock.gif" width="19" height="19" title="shock" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':coolsmile:')"><img src="./smileys/images/shade_smile.gif" width="19" height="19" title="cool smile" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':coolsmirk:')"><img src="./smileys/images/shade_smirk.gif" width="19" height="19" title="cool smirk" style="border:0;" /></a></td></tr>
		<tr>
		<td><a href="javascript:void(0);" onClick="insert_smiley(':coolgrin:')"><img src="./smileys/images/shade_grin.gif" width="19" height="19" title="cool grin" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':coolhmm:')"><img src="./smileys/images/shade_hmm.gif" width="19" height="19" title="cool hmm" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':coolmad:')"><img src="./smileys/images/shade_mad.gif" width="19" height="19" title="cool mad" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':coolcheese:')"><img src="./smileys/images/shade_cheese.gif" width="19" height="19" title="cool cheese" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':vampire:')"><img src="./smileys/images/vampire.gif" width="19" height="19" title="vampire" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':snake:')"><img src="./smileys/images/snake.gif" width="19" height="19" title="snake" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':exclaim:')"><img src="./smileys/images/exclaim.gif" width="19" height="19" title="excaim" style="border:0;" /></a></td><td><a href="javascript:void(0);" onClick="insert_smiley(':question:')"><img src="./smileys/images/question.gif" width="19" height="19" title="question" style="border:0;" /></a></td></tr>
		</table>
EOF;
}
function userImgcode() {
     $this->_imgcode->image(0,4,900,array('borderColor'=>'#66CCFF','bgcolor'=>'#FFCC33'));
}

function checkImgcode() {
     return $this->_imgcode->check($_POST['valid_code']);
}
function filter_words($input)
{
	$filter_array=explode(',',$this->_filter_words);
	$input=str_ireplace($filter_array,'***',$input);
	return $input;
}
function fix_filter_string($filter_words)
{
	$new_string=trim($filter_words,',');
	$new_string=str_replace(array("\t","\r","\n",'  ',' '),'',$new_string);
	return $new_string;
}
}
?>