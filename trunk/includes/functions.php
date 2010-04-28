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

    function get_all_timezone()
    {
		$timezone=array(
			'-12'=>'[UTC - 12] 贝克岛时间',
			'-11'=>'[UTC - 11] 纽埃岛时间，萨摩亚标准时间',
			'-10'=>'[UTC - 10] 夏威夷-阿留申标准时间，库克岛时间',
			'-9.5'=>'[UTC - 9:30] 马克萨斯群岛时间',
			'-9'=>'[UTC - 9] 阿拉斯加标准时间，甘岛时间',
			'-8'=>'[UTC - 8] 太平洋标准时间',
			'-7'=>'[UTC - 7] 山区标准时间',
			'-6'=>'[UTC - 6] 美国中部标准时间',
			'-5'=>'[UTC - 5] 美国东部标准时间',
			'-4.5'=>'[UTC - 4:30] 委内瑞拉标准时间',
			'-4'=>'[UTC - 4] 大西洋标准时间',
			'-3.5'=>'[UTC - 3:30] 纽芬兰标准时间',
			'-3'=>'[UTC - 3] 亚马逊标准时间，中部格陵兰时间',
			'-2'=>'[UTC - 2] 费尔南多蒂诺罗尼亚时间，南乔治亚岛和南桑威奇岛时间',
			'-1'=>'[UTC - 1] 亚述尔标准时间，佛得角时间，格陵兰东部时间',
			'0'=>'[UTC] 欧洲西部时间，格林尼治时间',
			'1'=>'[UTC + 1] 欧洲中部时间，非洲西部时间',
			'2'=>'[UTC + 2] 欧洲东部时间，非洲中部时间',
			'3'=>'[UTC + 3] 莫斯科标准时间，非洲东部时间',
			'3.5'=>'伊朗标准时间',
			'4'=>'[UTC + 4] 海湾标准时间，波斯湾标准时间，萨马拉标准时间',
			'5'=>'[UTC + 5] 巴基斯坦标准时间，叶卡捷琳堡标准时间',
			'6'=>'[UTC + 6] 孟加拉时间，不丹时间，新西伯利亚标准时间',
			'6.5'=>'[UTC + 6:30] 可可斯群岛时间，缅甸时间',
			'7'=>'[UTC + 7] 印度支那时间，克拉斯诺亚尔斯克标准时间',
			'8'=>'[UTC + 8] 中国标准时间，澳洲西部时间，伊尔库茨克标准时间',
			'8.75'=>'[UTC + 8:45] 澳洲西部东南标准时间',
			'9'=>'[UTC + 9] 日本标准时间，韩国标准时间，赤塔标准时间',
			'9.5'=>'澳洲中部标准时间',
			'10'=>'[UTC + 10] 澳洲东部标准时间，海参崴标准时间',
			'10.5'=>'[UTC + 10:30] 贺维标准时间',
			'11'=>'[UTC + 11] 所罗门群岛时间，马加丹标准时间',
			'11.5'=>'[UTC + 11:30] 诺福克群岛时间',
			'12'=>'[UTC + 12] 新西兰时间，斐济时间，勘察加半岛时间',
			'12.75'=>'[UTC + 12:45] 查塔姆群岛时间',
			'13'=>'[UTC + 13] 汤加时间，菲尼克斯群岛时间',
			'14'=>'[UTC + 14] 路线岛时间'
		);
        return $timezone;

    }
?>