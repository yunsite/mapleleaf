<?php
include 'header.php';
?>
<script type="text/javascript" src="./includes/login.js"></script>

<TABLE width="730" border=0 align=center cellPadding=0 cellSpacing=0 bgColor=#ffffff>
  <TBODY>
    <TR>
      <TD vAlign=top align=middle><TABLE cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
          <TBODY>
            <TR>

              <TD  align=left vAlign=center class=TitleBar_L  border="0"></TD>
              <TD vAlign=center class=TitleBar>&nbsp;</TD>
              <TD vAlign=center class=TitleBar_R></TD>
            </TR>
          </TBODY>
        </TABLE>
          <TABLE cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
            <TBODY>
              <TR>

                <TD class=Border_L></TD>
                <TD></TD>
                <TD>
<p id="backtoindex"><a href="index.php" title="不知道自己在哪？">&larr; 返回留言板</a></p>
<form name="form1" method="post" action="index.php?action=login" onsubmit="return login_check()">
<TABLE cellSpacing=0 cellPadding=0 width="50" align=center border=0>
  <TBODY>
    <TR>
        <TD><IMG src="<?php echo $this->_themes_directory.$this->_theme; ?>/images/user_login_main.gif" border=0>
    <TR>

      <TD align=middle  background="<?php echo $this->_themes_directory.$this->_theme; ?>/images/user_login_bg.gif">
                <?php if(@$errormsg)
                {
                	?>
						<div id="login_error"><?php echo $errormsg;?><br /></div>
				<?php
                }
                ?>
        <table border=0 align="center" cellpadding="0" cellspacing="0">
          <tbody>
            <tr>
              <td align=middle height=25>用户名称：&nbsp;
                  <input id="user" name="user" type="text" ></td>
            </tr>
            <tr>

              <td align=middle>登陆密码：&nbsp;
                  <input name="password" type="password" id="password"></td>
            </tr>
            <tr>
              <td align=center height=25>&nbsp;
                  <!--input type="submit" name="Submit" value="提交"-->
                  <input name="Submit"
                        type=image id="submit"
                        src="<?php echo $this->_themes_directory.$this->_theme; ?>/images/user_login_button.gif" alt="登陆"
                        align=absMiddle >
                  <input name="loginuser" type="hidden" id="loginuser" value="1"></td>

            </tr>
          </tbody>
        </table>
      <TR>
      <TD><IMG src="<?php echo $this->_themes_directory.$this->_theme; ?>/images/user_login_end.gif"  border=0><TD></TD>
    </TR>
  </TBODY>

</TABLE><br />
</form>
				
                </TD>
                <TD class=Border_R align="left"></TD>
              </TR>
            </TBODY>
          </TABLE>
          <TABLE width="100%" border=0 align=center cellPadding=0 cellSpacing=0>
            <TBODY>

              <TR>
                <TD class= Hemline_L></TD>
                <TD class=Hemline>&nbsp;</TD>
                <TD class=Hemline_R></TD>
              </TR>
            </TBODY>
        </TABLE></TD>
    </TR>
    <TR>

      <TD colSpan=2 height=2></TD>
    </TR>
  </TBODY>
</TABLE>       
<?php

include 'footer.php';
?>

</body>
</html>
