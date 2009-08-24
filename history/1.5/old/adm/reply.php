<?php
session_start();
define('IN_MP',true);
require_once('../common.php');
if(!isset($_SESSION['admin']))//若通过验证，则执行删除
{
	header("location:index.php");
	exit;
}
$mid=(int)$_GET['mid'];
if(!isset($mid))
{
	header("location:index.php");
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>留言回复</title>
</head>

<body>
<form action="admin_process.php" method="post">
<input type="hidden" name="process_type" value="reply_process" />
<input type="hidden" name="mid" value="<?php echo $mid;?>" />
<textarea name="reply_content" cols="40" rows="9"></textarea>
<br />
<input type="submit" name="Submit" value="回复" /><input type="button" name="cancel" value="取消" onclick="javascript:window.open('index.php','_self')" />
</form>
</body>
</html>
