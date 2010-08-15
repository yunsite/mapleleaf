<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $this->t('UPDATE');?></title>
</head>

<body>
<form action="index.php?action=update" method="post">
<input type="hidden" name="mid" value="<?php echo $mid;?>" />
<input type="hidden" name="author" value="<?php echo $message_info[0][1];?>" />
<input type="hidden" name="m_time" value="<?php echo $message_info[0][3];?>" />
<input type="hidden" name="ip" value="<?php echo trim($message_info[0][4]);?>" />
<textarea name="update_content" cols="40" rows="9"><?php echo htmlspecialchars_decode($message_info[0][2],ENT_COMPAT);?></textarea>
<br />
<input type="submit" name="Submit" value="<?php echo $this->t('UPDATE');?>" /><input type="button" name="cancel" value="<?php echo $this->t('CANCEL');?>" onclick="javascript:window.open('index.php?action=control_panel&subtab=message','_self')" />
</form>
</body>
</html>