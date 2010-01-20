<?php
@ob_end_clean();
function_exists('ob_gzhandler') ? ob_start('ob_gzhandler') : ob_start();
?>
<html>
<head><title>提示信息</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" >
<?php
if($redirect==true)
{
echo "<meta http-equiv='Refresh' content='$time_delay;URL=$redirect_url' />";
}
?>
<style type='text/css'>
P,BODY{FONT-FAMILY:tahoma,arial,sans-serif;FONT-SIZE:11px;}
A { TEXT-DECORATION: none;}
a:hover{ text-decoration: underline;}
TD { BORDER-RIGHT: 1px; BORDER-TOP: 0px; FONT-SIZE: 16pt; COLOR: #000000;}
</style>
</head>
<body>
<table style='TABLE-LAYOUT:fixed;'><tr><td>
<?php
echo '<pre>';
print_r($msg);
echo '</pre>';
?>
<br><br><?php echo htmlspecialchars_decode($this->_copyright_info);?>
</td></tr></table></body></html>