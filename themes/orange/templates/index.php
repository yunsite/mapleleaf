<?php
include 'header.php';
//var_dump($data);
?>

<body>
    <script type="text/javascript" src="./includes/index.js"></script>
<center>
<div id="container">

	<?php foreach($data as $m){?>
    <table width="730" border=0 align=center cellPadding=0 cellSpacing=0 bgColor=#ffffff style=" margin-top: 3px;"><tr><td>
<table cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
          <tbody>
            <tr>
              <td  align=left vAlign=center class=TitleBar_L></td>
              <td vAlign=center class=TitleBar>&nbsp;</td>
              <td vAlign=center class=TitleBar_R></td>
            </tr>
          </tbody>
        </table>

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
       <td style="BORDER: white 2px outset; PADDING: 1px; BACKGROUND-COLOR: #f8f4f0"><?php echo str_replace('Admin',"<font color='red'>Admin</font>",$m['user']);?>&nbsp;留言于:<font color="#336600"><?php echo date('m-d H:i',$m['time']);?>&nbsp;&nbsp;</font>&nbsp;</td>
  </tr>

  <tr>
    <td width=650><div  style="BORDER: white 2px outset; PADDING: 1px; BACKGROUND-COLOR: #f8f4f0; width:650">&nbsp; &nbsp; &nbsp; &nbsp;
				     <?php echo htmlspecialchars_decode($m['content']);?></div>
        <hr>
				  <!----------- replay --------------->
                                  <?php if(@$m['reply']){?>  
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                     <td><div style="BORDER: white 2px outset; PADDING: 1px; BACKGROUND-COLOR: #f8f4f0">
                             <img src="<?php echo $this->_themes_directory; ?><?php echo $this->_theme; ?>/images/dot.gif" width="21" height="10" alt="dot" /><font color="#ff8000">

                              Admin&nbsp;回复:</font><br />
                             <?php echo $m['reply']['reply_content'];?>
</div>
                      </td>
                    </tr>
      </table><?php }?>
			      </td>
  </tr>
</table>

        </td></tr></table>
	<?php }?>
<br />
    <?php if($this->_page_on){?>
        <table cellSpacing=0 cellPadding=0 width=600 bgColor=#ffffff border=0>
  <tbody>
  <tr>
    <td>
      <hr width=580 color=#f0f0f0 SIZE=1>
	       &nbsp;&nbsp;共计<?php echo $nums;?>条留言&nbsp;&nbsp;每页<SPAN id=pagesizer><?php echo $this->_num_perpage;?></SPAN>条留言: <SPAN
      id=fenye>&nbsp;
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
                        </SPAN>
      </td></tr></tbody></table>
	<?php }?>


<form name="guestbook" action="index.php?action=post" method="post"
	onsubmit="return checkall()">
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
		
		<!-- begin 表情表格 -->
		<div id="smileys"><?php echo $smileys;?></div>
		<!-- end 表情表格 -->
		
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

<?php
include 'footer.php';
?>
</div>
</center>
</body>
</html>
