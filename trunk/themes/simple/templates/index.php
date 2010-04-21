<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="Cache-Control" content="no-cache,must-revalidate" />
<meta http-equiv="expires" content="0" />
<script type="text/javascript" src="./includes/index.js"></script>
<link rel="stylesheet" href="<?php echo './themes/'.$this->_theme.'/common.css';?>" type="text/css"/>
<link rel="stylesheet" href="<?php echo './themes/'.$this->_theme.'/smiley_common.css';?>" type="text/css"/>
<script type="text/javascript" src="./includes/jquery.js"></script>
<script type="text/javascript" src="<?php echo './themes/'.$this->_theme.'/index.js';?>"></script>
<title>欢迎光临<?php echo $this->_board_name;?></title>
</head>

<body>
<div id="container">
<h1>欢迎留言</h1>
<table id="main_table" cellspacing="0" >
	<tr class="header">
		<td class="nickname">昵称</td>
		<td class="message">留言</td>
		<td>时间</td>
	</tr>
	<?php foreach($data as $m){?>
    <tr class='message'>
    	<td class='left'><?php echo str_replace('Admin',"<font color='red'>Admin</font>",$m['user']);?></td>
        <td class='left'><?php echo htmlspecialchars_decode($m['content']);?><br /><?php if(@$m['reply']){?><font color='red'>Admin于<?php echo date('m-d H:i',(int)$m['reply']['reply_time']+$this->_time_zone*60*60);?>回复：</font><?php echo $m['reply']['reply_content'];?> <?php }?></td>
        <td class='center'><?php echo date('m-d H:i',$m['time']+$this->_time_zone*60*60);?></td>
    </tr>
    <?php }?>
    <?php if($this->_page_on){?>
		<tr ><td colspan='3'   class="pager">共 <?php echo $pages;?> 页 <?php echo $nums;?> 条留言
		<?php for($i=0;$i<$pages;$i++){?>
			<a href='index.php?pid=<?php echo $i;?>'>
                        <?php 
                            if($i==$current_page)
                            {
                                echo '<font size="+2">'.($i+1)."</font>";
                            }
                            else
                            { echo $i+1;}
                        ?>
                        </a>&nbsp;
        <?php }?>

		</td></tr>
	<?php }?>
</table>
<br />


<div align="center" id="pleasepost">点击留言：</div>
<form name="guestbook" action="index.php?action=post" method="post"	onsubmit="return checkall()">
<table id="add_table">
	<tr>
		<td class="alignright">昵称</td>
		<td class="alignleft"><?php if($admin == true){?>
        				<input name="user" id="user" type="hidden" maxlength="10"  onfocus="clear_user()" value="Admin" /><font color="red">Admin</font>
						<?php }else{?>
                        <input name="user" id="user" type="text" maxlength="10"  onfocus="clear_user()" value="anonymous" />
					<?php }?>
		</td>
		<td class="left">&nbsp;
		<div id="user_msg"></div>
		</td>
	</tr>
	<tr>
		<td class="alignright" valign="top">留言</td>
		<td class="left">

		<textarea id="content" name="content" cols="45" rows="8" onkeyup="javascript:return ctrlEnter(event);"></textarea></td>
		<td  valign="top">
		<div id="smileys"><?php echo $smileys;?></div>
		</td>
	</tr>
	<?php if($this->_valid_code_open){?>
	<tr>
		<td class="l">验证码</td>
		<td class="left">
			<input id="valid_code" type="text" name="valid_code" size="4"
			maxlength="4" />&nbsp;<img src="./includes/showimgcode.php"
                        border="0" align="absbottom" onclick="this.src=this.src+'?'" title="点击刷新" style="cursor:pointer" alt="验证码图像" /></td>
		<td class="left">&nbsp;</td>
	</tr>
	<?php }?>
	<tr>
		<td>&nbsp;</td>
		<td colspan="2" class="left"><input id="submit" name="submit" type="submit"
			value="提交留言" /><font color="green" size="2">(Ctrl+Enter提交)</font></td>
	</tr>

</table>
</form>


    <div class="botton">&nbsp;<?php echo htmlspecialchars_decode($this->_copyright_info);?>&nbsp;<a href="mailto:<?php echo $this->_admin_email;?>">站长信箱</a> <a href="index.php?action=control_panel">管理</a><br />Powered by <a href="http://maple.dreamneverfall.cn" target="_blank" title="Find More">MapleLeaf <?php echo MP_VERSION;?></a>
    </div>
</div><!-- end container -->

</body>
</html>
