<?php
    if (!function_exists("htmlspecialchars_decode")) {
        function htmlspecialchars_decode($string, $quote_style = ENT_COMPAT) {
            return strtr($string, array_flip(get_html_translation_table(HTML_SPECIALCHARS, $quote_style)));
        }
    }

    function ip_valid($ip_addr)
    {
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
    /**
     * 检查当前用户是否是管理员
     */
    function is_admin()
    {
            if (!isset($_SESSION['admin']))
            {
                    header("location:index.php?action=login");
                    exit;
            }
    }

    function gd_is_available()
    {
        if ($check = get_extension_funcs('gd'))
        {
            if (in_array('imagegd2', $check)) 
            {
                // GD2 support is available.
                return TRUE;
            }
        }
        return FALSE;
    }

    function isSafeMode()
    {
        $isSafeMode = @ini_get("safe_mode");
        if(strstr(strtolower(@getenv('OS')),'windows'))
        {
            $isSafeMode = false;
        }
        return $isSafeMode;
    }

	function mb_wordwrap($str, $width = 75, $break = "\n", $cut = false, $charset = null) 
	{
		if ($charset === null) $charset = mb_internal_encoding();

		$pieces = explode($break, $str);
		$result = array();
		foreach ($pieces as $piece) {
		  $current = $piece;
		  while ($cut && mb_strlen($current) > $width) {
			$result[] = mb_substr($current, 0, $width, $charset);
			$current = mb_substr($current, $width, 2048, $charset);
		  }
		  $result[] = $current;
		}
		return implode($break, $result);
	}
?>