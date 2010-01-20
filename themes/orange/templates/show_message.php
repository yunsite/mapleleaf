<html>
<head>
<meta http-equiv="content-type" content="txt/html;charset=utf-8" />
<title>提示信息</title>
<link rel="stylesheet" type="text/css" href="<?php echo './themes/'.$this->_theme.'/logout.css';?>"  />
        <link href="<?php echo $this->_themes_directory; ?><?php echo $this->_theme; ?>/Skin/Orange/style.css" rel=stylesheet />

<?php
if($redirect==true)
{
echo "<meta http-equiv='Refresh' content='$time_delay;URL=$redirect_url' />";
}
?>
</head>
<body>
            <center>

            <table cellSpacing=0 cellPadding=0 width=728 bgColor=#ffffff border=0>
                <tbody>
                    <tr>
                        <td width=267><img alt="logo" height=80 src="<?php echo $this->_themes_directory; ?><?php echo $this->_theme; ?>/images/logo.gif" width=260 /></TD>
                        <td colspan="3">
                            <div id=Layer4
                                 style="Z-INDEX: 4; LEFT: 0px; POSITION: relative; TOP: 0px">
                                <img alt="image" height=80 src="<?php echo $this->_themes_directory; ?><?php echo $this->_theme; ?>/Skin/ads.jpg" width=460 border=0 />
                            </div>
                                <div id=Layer5
                                     style="Z-INDEX: 5; LEFT: 0px; POSITION: absolute; TOP: 0px">

                                </div>
                        </td>
                    </tr>

        </tbody></table>
</center>
<div id="lay">

<?php
echo '<pre>';
print_r($msg);
echo '</pre>';
?>
    
</div>
<div id="buttom_div">
<?php echo htmlspecialchars_decode($this->_copyright_info);?>
</div>
</body>
</html>