<?php
include 'header.php';
?>
<center>

    <?php @is_admin();?>
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

<form action="index.php?action=reply" method="post">
<input type="hidden" name="mid" value="<?php echo $mid;?>" />
<textarea name="reply_content" cols="40" rows="9"></textarea>
<br />
<input type="submit" name="Submit" value="回复" /><input type="button" name="cancel" value="取消" onclick="javascript:window.open('index.php?action=control_panel&subtab=message','_self')" />
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