<?php require_once('/var/www/MapleLeaf-1-5/includes/template_lite/plugins/function.math.php'); $this->register_function("math", "tpl_function_math");  require_once('/var/www/MapleLeaf-1-5/includes/template_lite/plugins/modifier.date.php'); $this->register_modifier("date", "tpl_modifier_date");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2009-05-09 13:07:21 CST */ ?>

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
    <?php if (count((array)$this->_vars['data'])): foreach ((array)$this->_vars['data'] as $this->_vars['m']): ?>
    <tr class='message'>
    	<td class='left'><?php echo $this->_vars['m']['1']; ?>
</td>
        <td class='left'><?php echo $this->_vars['m']['2']; ?>
<br /><?php if ($this->_vars['m']['reply']):  echo $this->_vars['m']['reply']['1']; ?>
 Time:<?php echo $this->_vars['m']['reply']['2'];  endif; ?></td>
        <td class='left'><?php echo $this->_run_modifier($this->_vars['m']['3'], 'date', 'plugin', 1, "n/j/Y g:ia"); ?>
</td>
    </tr>
    <?php endforeach; endif; ?>
    <?php if ($this->_vars['page_on']): ?>
		<tr><td colspan='3'>共 <?php echo $this->_vars['pages']; ?>
 页 <?php echo $this->_vars['nums']; ?>
 条留言
		<?php for($for1 = 0; ((0 < $this->_vars['pages']) ? ($for1 < $this->_vars['pages']) : ($for1 > $this->_vars['pages'])); $for1 += ((0 < $this->_vars['pages']) ? 1 : -1)):  $this->assign('current', $for1); ?>
			<a href='index2.php?pid=<?php echo $this->_vars['current']; ?>
'><?php echo tpl_function_math(array('equation' => "x + 1",'x' => $this->_vars['current']), $this);?></a>&nbsp;
        <?php endfor; ?>

		</td></tr>
	<?php endif; ?>
</table>
<br />


<div align="center">请您留言：</div>
<form name="guestbook" action="process.php" method="post"
	onsubmit="return checkall()">
<table id="table1">
	<tr>
		<td class="l">昵称</td>
		<td class="s"><?php if ($this->_vars['admin'] == true): ?>
        				<input name="user" id="user" type="hidden" maxlength="10"  onfocus="clear_user()" value="Admin" /><font color="red">Admin</font>
						<?php else: ?>
                        <input name="user" id="user" type="text" maxlength="10"  onfocus="clear_user()" value="anonymous" />
					<?php endif; ?>
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
	<?php if ($this->_vars['valid_code_open']): ?>
	<tr>
		<td class="l">验证码</td>
		<td class="left"><input type="text" name="valid_code" size="4"
			maxlength="4" />&nbsp;<img src="./includes/showimgcode.php"
			border="0" align="absbottom" /></td>
		<td class="left">&nbsp;</td>
	</tr>
	<?php endif; ?>
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
