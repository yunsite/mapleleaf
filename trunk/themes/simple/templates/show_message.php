<?php
@ob_end_clean();
function_exists('ob_gzhandler') ? ob_start('ob_gzhandler') : ob_start();
?>
<html>
<head><title><?php echo $this->t('TIPS');?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" >
<?php
if($redirect==true)
{
echo "<meta http-equiv='Refresh' content='$time_delay;URL=$redirect_url' />";
}
?>
<style type='text/css'>
p,body{font-family:tahoma,arial,sans-serif;font-size:11px;}
a { text-decoration: none;}
a:hover{ text-decoration: underline;}
td { border-right: 1px; border-top: 0px; font-size: 16pt; color: #000000;}
</style>
</head>
<body>
<table style='table-layout:fixed;'><tr><td>
<?php
echo '<pre>';
print_r($msg);
echo '</pre>';
?>
<br><br><?php echo htmlspecialchars_decode($this->_copyright_info);?>
</td></tr></table>
</body></html>