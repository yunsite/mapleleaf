<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>留言回复</title>
</head>

<body>
<form action="index.php?action=reply" method="post">
<input type="hidden" name="mid" value="<?php echo $mid;?>" />
<?php 
if($reply_data)
{
?>
<input type="hidden" name="update" value="update" />
<?php
}
?>
<textarea name="reply_content" cols="40" rows="9"><?php echo @$reply_data[0][1];?></textarea>
<br />
<input type="submit" name="Submit" value="回复" />
<input type="button" name="cancel" value="取消" onclick="javascript:window.open('index.php?action=control_panel&subtab=message','_self')" />
</form>
</body>
</html>