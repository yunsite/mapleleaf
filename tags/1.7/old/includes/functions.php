<?php
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
    function ip_valid($ip_addr)
    {
    	//$match=0;
    	$match=preg_match('/^[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}$/',$ip_addr);
    	if ($match)
    	{
    		return true;
    	}
    	else 
    	{
    		return false;
    	}
    }
?>