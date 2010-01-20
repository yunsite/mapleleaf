<?php
include 'header.php';
?>
<?php @is_admin();?>
<center>
<TABLE width="730" border=0 align=center cellPadding=0 cellSpacing=0 bgColor=#ffffff>
  <TBODY>
    <TR>

      <TD vAlign=top align=middle><TABLE cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
          <TBODY>
            <TR>
              <TD  align=left vAlign=center class=TitleBar_L  ></TD>
              <TD vAlign=center class=TitleBar>&nbsp;</TD>
              <TD vAlign=center class=TitleBar_R></TD>
            </TR>
          </TBODY>
        </TABLE>

<form action="index.php?action=update" method="post">
<input type="hidden" name="mid" value="<?php echo $mid;?>" />
<input type="hidden" name="author" value="<?php echo $message_info[1];?>" />
<input type="hidden" name="m_time" value="<?php echo $message_info[3];?>" />
<input type="hidden" name="ip" value="<?php echo trim($message_info[4]);?>" />
<textarea name="update_content" cols="40" rows="9"><?php echo $message_info[2];?></textarea>
<br />
<input type="submit" name="Submit" value="更新" /><input type="button" name="cancel" value="取消" onclick="javascript:window.open('index.php?action=control_panel&subtab=message','_self')" />
</form>

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
include 'powered.php';
?>
</center>
</body>

</html>