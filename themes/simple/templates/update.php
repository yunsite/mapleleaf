<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo ZFramework::t('UPDATE');?></title>
</head>

<body>
    <form action="index.php?controller=post&amp;action=update" method="post">
<input type="hidden" name="mid" value="<?php echo $mid;?>" />
<input type="hidden" name="author" value="<?php echo $message_info['user'];?>" />
<input type="hidden" name="m_time" value="<?php echo $message_info['time'];?>" />
<input type="hidden" name="ip" value="<?php echo $message_info['ip'];?>" />
<textarea name="update_content" cols="40" rows="9"><?php echo $message_info['content'];?></textarea>
<br />
<input type="submit" name="Submit" value="<?php echo ZFramework::t('UPDATE');?>" />&nbsp;<a href="index.php?action=control_panel&amp;subtab=message"><?php echo ZFramework::t('CANCEL');?></a>
</form>
</body>
</html>