<?php
/**
*
* @package mapleleaf
* @version 2009-01-15 
* @copyright (c) 2008 mapleleaf Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if(!defined('IN_MP'))
{
	exit;
}
require_once('Imgcode.php');
/**
 * Visual Confirmation - Show images
 *
 */
function userImgcode() {
     $imgcode =new  FLEA_Helper_ImgCode();
     $imgcode->image();
}
/**
 * Visual Confirmation - check
 *
 * @return bool
 */
function checkImgcode() {
     $imgcode =new  FLEA_Helper_ImgCode();
     return $imgcode->check($_POST['valid_code']);
}
/**
 *  read .txt file and get relevant info
 *
 * @param string $filename
 * @param string $method
 * @return array
 */
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
/**
 * write data into file
 *
 * @param string $filename
 * @param string $data
 * @param string $method
 * @param int $iflock
 * @param int $check
 * @param int $chmod
 */
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

/**
 * delelte specify messages or replies
 *  @param string $filename
 *	@param string $type
 *	@param int $id
 */
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
	writeover($filename,$outputing,'wb');
}
/**
 * Word censoring
 *
 * @param string $input
 * @return string
 */
function filter_words($input)
{
	global $filter_words;
	$filter_array=explode(',',$filter_words);
	$input=str_ireplace($filter_array,'***',$input);
	return $input;
}
/**
 * 规范化过滤词汇的格式
 *
 * @param string $filter_words
 * @return string
 */
function fix_filter_string($filter_words)
{
	$new_string=trim($filter_words,',');
	$new_string=str_replace(array("\t","\r","\n",'  ',' '),'',$new_string);
	return $new_string;
}

/**
 * show info,such as error info,notice...
 */
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
/**
* Checks if a path ($path) is absolute or relative
*
* @param string $path Path to check absoluteness of
* @return boolean
*/
function is_absolute($path)
{
	return ($path[0] == '/' || (DIRECTORY_SEPARATOR == '\\' && preg_match('#^[a-z]:/#i', $path))) ? true : false;
}

/**
* @author Chris Smith <chris@project-minerva.org>
* @copyright 2006 Project Minerva Team
* @param string $path The path which we should attempt to resolve.
* @return mixed
*/
function phpbb_own_realpath($path)
{
	// Now to perform funky shizzle

	// Switch to use UNIX slashes
	$path = str_replace(DIRECTORY_SEPARATOR, '/', $path);
	$path_prefix = '';

	// Determine what sort of path we have
	if (is_absolute($path))
	{
		$absolute = true;

		if ($path[0] == '/')
		{
			// Absolute path, *NIX style
			$path_prefix = '';
		}
		else
		{
			// Absolute path, Windows style
			// Remove the drive letter and colon
			$path_prefix = $path[0] . ':';
			$path = substr($path, 2);
		}
	}
	else
	{
		// Relative Path
		// Prepend the current working directory
		if (function_exists('getcwd'))
		{
			// This is the best method, hopefully it is enabled!
			$path = str_replace(DIRECTORY_SEPARATOR, '/', getcwd()) . '/' . $path;
			$absolute = true;
			if (preg_match('#^[a-z]:#i', $path))
			{
				$path_prefix = $path[0] . ':';
				$path = substr($path, 2);
			}
			else
			{
				$path_prefix = '';
			}
		}
		else if (isset($_SERVER['SCRIPT_FILENAME']) && !empty($_SERVER['SCRIPT_FILENAME']))
		{
			// Warning: If chdir() has been used this will lie!
			// Warning: This has some problems sometime (CLI can create them easily)
			$path = str_replace(DIRECTORY_SEPARATOR, '/', dirname($_SERVER['SCRIPT_FILENAME'])) . '/' . $path;
			$absolute = true;
			$path_prefix = '';
		}
		else
		{
			// We have no way of getting the absolute path, just run on using relative ones.
			$absolute = false;
			$path_prefix = '.';
		}
	}

	// Remove any repeated slashes
	$path = preg_replace('#/{2,}#', '/', $path);

	// Remove the slashes from the start and end of the path
	$path = trim($path, '/');

	// Break the string into little bits for us to nibble on
	$bits = explode('/', $path);

	// Remove any . in the path, renumber array for the loop below
	$bits = array_values(array_diff($bits, array('.')));

	// Lets get looping, run over and resolve any .. (up directory)
	for ($i = 0, $max = sizeof($bits); $i < $max; $i++)
	{
		// @todo Optimise
		if ($bits[$i] == '..' )
		{
			if (isset($bits[$i - 1]))
			{
				if ($bits[$i - 1] != '..')
				{
					// We found a .. and we are able to traverse upwards, lets do it!
					unset($bits[$i]);
					unset($bits[$i - 1]);
					$i -= 2;
					$max -= 2;
					$bits = array_values($bits);
				}
			}
			else if ($absolute) // ie. !isset($bits[$i - 1]) && $absolute
			{
				// We have an absolute path trying to descend above the root of the filesystem
				// ... Error!
				return false;
			}
		}
	}

	// Prepend the path prefix
	array_unshift($bits, $path_prefix);

	$resolved = '';

	$max = sizeof($bits) - 1;

	// Check if we are able to resolve symlinks, Windows cannot.
	$symlink_resolve = (function_exists('readlink')) ? true : false;

	foreach ($bits as $i => $bit)
	{
		if (@is_dir("$resolved/$bit") || ($i == $max && @is_file("$resolved/$bit")))
		{
			// Path Exists
			if ($symlink_resolve && is_link("$resolved/$bit") && ($link = readlink("$resolved/$bit")))
			{
				// Resolved a symlink.
				$resolved = $link . (($i == $max) ? '' : '/');
				continue;
			}
		}
		else
		{
			// Something doesn't exist here!
			// This is correct realpath() behaviour but sadly open_basedir and safe_mode make this problematic
			// return false;
		}
		$resolved .= $bit . (($i == $max) ? '' : '/');
	}

	// @todo If the file exists fine and open_basedir only has one path we should be able to prepend it
	// because we must be inside that basedir, the question is where...
	// @internal The slash in is_dir() gets around an open_basedir restriction
	if (!@file_exists($resolved) || (!is_dir($resolved . '/') && !is_file($resolved)))
	{
		return false;
	}

	// Put the slashes back to the native operating systems slashes
	$resolved = str_replace('/', DIRECTORY_SEPARATOR, $resolved);

	// Check for DIRECTORY_SEPARATOR at the end (and remove it!)
	if (substr($resolved, -1) == DIRECTORY_SEPARATOR)
	{
		return substr($resolved, 0, -1);
	}

	return $resolved; // We got here, in the end!
}

if (!function_exists('realpath'))
{
	/**
	* A wrapper for realpath
	* @ignore
	*/
	function phpbb_realpath($path)
	{
		return phpbb_own_realpath($path);
	}
}
else
{
	/**
	* A wrapper for realpath
	*/
	function phpbb_realpath($path)
	{
		$realpath = realpath($path);

		// Strangely there are provider not disabling realpath but returning strange values. :o
		// We at least try to cope with them.
		if ($realpath === $path || $realpath === false)
		{
			return phpbb_own_realpath($path);
		}

		// Check for DIRECTORY_SEPARATOR at the end (and remove it!)
		if (substr($realpath, -1) == DIRECTORY_SEPARATOR)
		{
			$realpath = substr($realpath, 0, -1);
		}

		return $realpath;
	}
}

if (!function_exists("htmlspecialchars_decode")) {
    function htmlspecialchars_decode($string, $quote_style = ENT_COMPAT) {
        return strtr($string, array_flip(get_html_translation_table(HTML_SPECIALCHARS, $quote_style)));
    }
}
  /**  
    *   Replace   str_ireplace()  
    *  
    *   @category         PHP  
    *   @package           PHP_Compat  
    *   @link                 http://php.net/function.str_ireplace  
    *   @author             Aidan   Lister   <aidan@php.net>  
    *   @version           $Revision:   1.18   $  
    *   @since               PHP   5  
    *   @require           PHP   4.0.0   (user_error)  
    *   @note                 count   not   by   returned   by   reference,   to   enable  
    *                             change   '$count   =   null'   to   '&$count'  
    */  
  if   (!function_exists('str_ireplace'))   {  
          function   str_ireplace($search,   $replace,   $subject,   $count   =   null)  
          {  
                  //   Sanity   check  
                  if   (is_string($search)   &&   is_array($replace))   {  
                          user_error('Array   to   string   conversion',   E_USER_NOTICE);  
                          $replace   =   (string)   $replace;  
                  }  
   
                  //   If   search   isn't   an   array,   make   it   one  
                  if   (!is_array($search))   {  
                          $search   =   array   ($search);  
                  }  
                  $search   =   array_values($search);  
   
                  //   If   replace   isn't   an   array,   make   it   one,   and   pad   it   to   the   length   of   search  
                  if   (!is_array($replace))   {  
                          $replace_string   =   $replace;  
   
                          $replace   =   array   ();  
                          for   ($i   =   0,   $c   =   count($search);   $i   <   $c;   $i++)   {  
                                  $replace[$i]   =   $replace_string;  
                          }  
                  }  
                  $replace   =   array_values($replace);  
   
                  //   Check   the   replace   array   is   padded   to   the   correct   length  
                  $length_replace   =   count($replace);  
                  $length_search   =   count($search);  
                  if   ($length_replace   <   $length_search)   {  
                          for   ($i   =   $length_replace;   $i   <   $length_search;   $i++)   {  
                                  $replace[$i]   =   '';  
                          }  
                  }  
   
                  //   If   subject   is   not   an   array,   make   it   one  
                  $was_array   =   false;  
                  if   (!is_array($subject))   {  
                          $was_array   =   true;  
                          $subject   =   array   ($subject);  
                  }  
   
                  //   Loop   through   each   subject  
                  $count   =   0;  
                  foreach   ($subject   as   $subject_key   =>   $subject_value)   {  
                          //   Loop   through   each   search  
                          foreach   ($search   as   $search_key   =>   $search_value)   {  
                                  //   Split   the   array   into   segments,   in   between   each   part   is   our   search  
                                  $segments   =   explode(strtolower($search_value),   strtolower($subject_value));  
   
                                  //   The   number   of   replacements   done   is   the   number   of   segments   minus   the   first  
                                  $count   +=   count($segments)   -   1;  
                                  $pos   =   0;  
   
                                  //   Loop   through   each   segment  
                                  foreach   ($segments   as   $segment_key   =>   $segment_value)   {  
                                          //   Replace   the   lowercase   segments   with   the   upper   case   versions  
                                          $segments[$segment_key]   =   substr($subject_value,   $pos,   strlen($segment_value));  
                                          //   Increase   the   position   relative   to   the   initial   string  
                                          $pos   +=   strlen($segment_value)   +   strlen($search_value);  
                                  }  
   
                                  //   Put   our   original   string   back   together  
                                  $subject_value   =   implode($replace[$search_key],   $segments);  
                          }  
   
                          $result[$subject_key]   =   $subject_value;  
                  }  
   
                  //   Check   if   subject   was   initially   a   string   and   return   it   as   a   string  
                  if   ($was_array   ===   true)   {  
                          return   $result[0];  
                  }  
   
                  //   Otherwise,   just   return   the   array  
                  return   $result;  
          }  
  }   
?>