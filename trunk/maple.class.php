<?php
class Maple
{
	var	$_m_file;
	var	$_r_file;
	var $_site_conf_file;
	var $_board_name;
	var $_admin_email;
	var $_copyright_info;
	var $_filter_words;
	var $_valid_code_open;
	var $_page_on;
	var $_num_perpage;
	var $_theme;
	var $_smileys_dir;
	var $smileys = array(
		//	smiley			image name						width	height	alt
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
	
	function Maple($board_name,$admin_email,$copyright_info,$filter_words,$valid_code_open,$page_on,$num_perpage,$theme,$relative='no')
	{
		$mp_root_path=dirname(str_replace(DIRECTORY_SEPARATOR, '/', __FILE__));
		$this->_m_file=$mp_root_path.'/data/gb.txt';
		$this->_r_file=$mp_root_path.'/data/reply.txt';
		$this->_site_conf_file='./adm/site.conf.php';
		$this->_smileys_dir='./smileys/images/';
		if ($relative=='yes')
		{
			$this->_smileys_dir='../smileys/images/';
		}
		if(!file_exists($this->_m_file) || !file_exists($this->_r_file))
		{
			die('gb.txt 或 reply.txt不存在');
		}
		if(!is_writable($this->_m_file) || !is_writable($this->_r_file))
		{
			die('gb.txt 或 reply.txt不可写');
		}
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
	function add_message($new_message)
	{
		$file_data=array_reverse(file($this->_m_file));
		//var_dump($file_data);
		@$index_num=(int)trim($file_data[0]);
		$next_num=$index_num+1;
		//echo $index_num.'--'.$next_num;
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
	
	function readover($filename,$method='rb'){
	//strpos($filename,'..')!==false && exit('Forbidden');//判断文件名中是否含有‘..’
	$filedata = array();
	if ($handle = @fopen($filename,$method)) {
		flock($handle,LOCK_SH);
//		$filedata = @fread($handle,filesize($filename));
		while(!feof($handle)){
						 $row=fgets($handle,999);
						 if(is_string($row))
						 {
							 $filedata[]=explode('"',$row);
						 }
			}
		flock($handle,LOCK_UN);//解除锁定
		fclose($handle);
	}
	else
	{
		showerror("暂时不能打开文件，请稍候再试。");
		exit;
	}
	return $filedata;
}
	function writeover($filename,$data,$method="rb+",$iflock=1,$check=0,$chmod=0){
	$check && strpos($filename,'..')!==false && exit('Forbidden');
	// touch($filename);
	$handle=@fopen($filename,$method);
	if(!$handle)
	{
		showerror("暂时不能打开文件，请稍候再试。");
		exit;
	}
	if($iflock){
	flock($handle,LOCK_EX);
	}
	fwrite($handle,$data);
	if($method=="rb+") ftruncate($handle,strlen($data));
	flock($handle,LOCK_UN);
	fclose($handle);
	$chmod && @chmod($filename,0777);
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
		$str = str_replace($key, "<img src=\"".$image_url.$smileys[$key][0]."\" width=\"".$smileys[$key][1]."\" height=\"".$smileys[$key][2]."\" alt=\"".$smileys[$key][3]."\" style=\"border:0;\" />", $str);
	}

	return $str;
}

function showerror($msg,$redirect=false,$redirect_url='index.php',$time_delay=3) 
{
		@ob_end_clean();
		function_exists('ob_gzhandler') ? ob_start('ob_gzhandler') : ob_start();
		echo"<html>
			 	<head><title>错误信息</title>";
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
	
}
?>
