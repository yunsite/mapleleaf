<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2009-05-03 08:10:56 �й���׼ʱ�� */ ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="./includes/index.js"></script>
<link rel="stylesheet" href="./style/common.css" type="text/css">
<title>欢迎光临<?php echo $this->_vars['title']; ?>
</title>
</head>

<body>
<div id="container">
<h1>欢迎留言</h1>
<table id="table1">
	<tr class="header">
		<td class="ls">昵称</td>
		<td class="m">留言</td>
		<td>时间</td>
	</tr>
</table>
<br />


<div align="center">请您留言：</div>
<form name="guestbook" action="process.php" method="post"
	onsubmit="return checkall()">
<table id="table1">
	<tr>
		<td class="l">昵称</td>
		<td class="s">
		</td>
		<td class="left">&nbsp;
		<div id="user_msg"></div>
		</td>
	</tr>
	<tr>
		<td class="l" valign="top">留言</td>
		<td class="left">

		<textarea name="content" cols="40" rows="9"></textarea></td>
		<td class="left">
		
		<!-- begin 表情表格 -->
		<div id="smileys"><?php show_smileys_table()?></div>
		<!-- end 表情表格 -->
		
		</td>
	</tr>
	
	<tr>
		<td class="l">验证码</td>
		<td class="left"><input type="text" name="valid_code" size="4"
			maxlength="4" />&nbsp;<img src="./includes/showimgcode.php"
			border="0" align="absbottom" /></td>
		<td class="left">&nbsp;</td>
	</tr>
	
	<tr>
		<td>&nbsp;</td>
		<td colspan="2" class="left"><input name="submit" type="submit"
			value="提交留言" /></td>
	</tr>

</table>
</form>

<!-- begin footer -->
<div id="botton">&nbsp;<?php echo $this->_vars['copyright_info']; ?>

<a href="mailto:<?php echo $this->_vars['admin_email']; ?>
">站长信箱</a> <a
	href="./adm/index.php">管理</a><br />
Powered by <a href="http://maple.dreamneverfall.cn" target="_blank"
	title="Find More">MapleLeaf 1.5</a></div>
</div>
<!-- end foot -->
</body>
</html>
