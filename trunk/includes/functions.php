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
                    header("location:index.php?action=login_window");
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

//    function get_all_timezone()
//    {
//        return $timezone;
//        $timezone=[UTC - 12] Baker Island Time" value="-12">[UTC - 12] Baker Island Time</option>
//        <option title="[UTC - 11] Niue Time, Samoa Standard Time" value="-11">[UTC - 11] Niue Time, Samoa Standard Time</option>
//                <option title="[UTC - 10] Hawaii-Aleutian Standard Time, Cook Island Time" value="-10">[UTC - 10] Hawaii-Aleutian Standard Time, Cook ...</option>
//                <option title="[UTC - 9:30] Marquesas Islands Time" value="-9.5">[UTC - 9:30] Marquesas Islands Time</option>
//                <option title="[UTC - 9] Alaska Standard Time, Gambier Island Time" value="-9">[UTC - 9] Alaska Standard Time, Gambier Island ...</option>
//                <option title="[UTC - 8] Pacific Standard Time" value="-8">[UTC - 8] Pacific Standard Time</option>
//                <option title="[UTC - 7] Mountain Standard Time" value="-7">[UTC - 7] Mountain Standard Time</option>
//                <option title="[UTC - 6] Central Standard Time" value="-6">[UTC - 6] Central Standard Time</option>
//                <option title="[UTC - 5] Eastern Standard Time" value="-5">[UTC - 5] Eastern Standard Time</option>
//                <option title="[UTC - 4:30] Venezuelan Standard Time" value="-4.5">[UTC - 4:30] Venezuelan Standard Time</option>
//                <option title="[UTC - 4] Atlantic Standard Time" value="-4">[UTC - 4] Atlantic Standard Time</option>
//                <option title="[UTC - 3:30] Newfoundland Standard Time" value="-3.5">[UTC - 3:30] Newfoundland Standard Time</option>
//                <option title="[UTC - 3] Amazon Standard Time, Central Greenland Time" value="-3">[UTC - 3] Amazon Standard Time, Central Greenla...</option>
//                <option title="[UTC - 2] Fernando de Noronha Time, South Georgia &amp; the South Sandwich Islands Time" value="-2">[UTC - 2] Fernando de Noronha Time, South Georg...</option>
//                <option title="[UTC - 1] Azores Standard Time, Cape Verde Time, Eastern Greenland Time" value="-1">[UTC - 1] Azores Standard Time, Cape Verde Time...</option>
//                <option title="[UTC] Western European Time, Greenwich Mean Time" value="0" selected="selected">[UTC] Western European Time, Greenwich Mean Time</option>
//                <option title="[UTC + 1] Central European Time, West African Time" value="1">[UTC + 1] Central European Time, West African Time</option>
//                <option title="[UTC + 2] Eastern European Time, Central African Time" value="2">[UTC + 2] Eastern European Time, Central Africa...</option>
//                <option title="[UTC + 3] Moscow Standard Time, Eastern African Time" value="3">[UTC + 3] Moscow Standard Time, Eastern African...</option>
//                <option title="[UTC + 3:30] Iran Standard Time" value="3.5">[UTC + 3:30] Iran Standard Time</option>
//                <option title="[UTC + 4] Gulf Standard Time, Samara Standard Time" value="4">[UTC + 4] Gulf Standard Time, Samara Standard Time</option>
//                <option title="[UTC + 4:30] Afghanistan Time" value="4.5">[UTC + 4:30] Afghanistan Time</option>
//                <option title="[UTC + 5] Pakistan Standard Time, Yekaterinburg Standard Time" value="5">[UTC + 5] Pakistan Standard Time, Yekaterinburg...</option>
//                <option title="[UTC + 5:30] Indian Standard Time, Sri Lanka Time" value="5.5">[UTC + 5:30] Indian Standard Time, Sri Lanka Time</option>
//                <option title="[UTC + 5:45] Nepal Time" value="5.75">[UTC + 5:45] Nepal Time</option>
//                <option title="[UTC + 6] Bangladesh Time, Bhutan Time, Novosibirsk Standard Time" value="6">[UTC + 6] Bangladesh Time, Bhutan Time, Novosib...</option>
//                <option title="[UTC + 6:30] Cocos Islands Time, Myanmar Time" value="6.5">[UTC + 6:30] Cocos Islands Time, Myanmar Time</option>
//                <option title="[UTC + 7] Indochina Time, Krasnoyarsk Standard Time" value="7">[UTC + 7] Indochina Time, Krasnoyarsk Standard ...</option>
//                <option title="[UTC + 8] Chinese Standard Time, Australian Western Standard Time, Irkutsk Standard Time" value="8">[UTC + 8] Chinese Standard Time, Australian Wes...</option>
//                <option title="[UTC + 8:45] Southeastern Western Australia Standard Time" value="8.75">[UTC + 8:45] Southeastern Western Australia Sta...</option>
//                <option title="[UTC + 9] Japan Standard Time, Korea Standard Time, Chita Standard Time" value="9">[UTC + 9] Japan Standard Time, Korea Standard T...</option>
//                <option title="[UTC + 9:30] Australian Central Standard Time" value="9.5">[UTC + 9:30] Australian Central Standard Time</option>
//                <option title="[UTC + 10] Australian Eastern Standard Time, Vladivostok Standard Time" value="10">[UTC + 10] Australian Eastern Standard Time, Vl...</option>
//                <option title="[UTC + 10:30] Lord Howe Standard Time" value="10.5">[UTC + 10:30] Lord Howe Standard Time</option>
//                <option title="[UTC + 11] Solomon Island Time, Magadan Standard Time" value="11">[UTC + 11] Solomon Island Time, Magadan Standar...</option>
//                <option title="[UTC + 11:30] Norfolk Island Time" value="11.5">[UTC + 11:30] Norfolk Island Time</option>
//                <option title="[UTC + 12] New Zealand Time, Fiji Time, Kamchatka Standard Time" value="12">[UTC + 12] New Zealand Time, Fiji Time, Kamchat...</option><option title="[UTC + 12:45] Chatham Islands Time" value="12.75">[UTC + 12:45] Chatham Islands Time</option><option title="[UTC + 13] Tonga Time, Phoenix Islands Time" value="13">[UTC + 13] Tonga Time, Phoenix Islands Time</option><option title="[UTC + 14] Line Island Time" value="14">[UTC + 14] Line Island Time</option>
//
//
//    }
?>