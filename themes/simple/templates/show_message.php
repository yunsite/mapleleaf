<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><title><?php echo FrontController::t('TIPS');?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
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
<h2>Message:</h2>
<table style='table-layout:fixed;'><tr><td>
<?php
echo '<pre>';
print_r($msg);
echo '</pre>';
?>
<br /><?php echo (FrontController::getInstance()->_copyright_info)?htmlspecialchars_decode(FrontController::getInstance()->_copyright_info):"Powered by MapleLeaf";?>
</td></tr></table>
</body></html>