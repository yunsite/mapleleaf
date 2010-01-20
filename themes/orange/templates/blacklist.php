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

          <form id="banip_manage" action="index.php?action=ip_update" method="post">
    
    <table id="table2">
                    <tr class="header">
                        <td>选择</td><td>被屏蔽的IP地址</td>
                    </tr>
                <?php foreach($ban_ip_info as $m){?>
                <tr class='admin_message' onmouseover='javascript:this.bgColor="#ecd7f2"' onmouseout='javascript:this.bgColor="#f8f8f8"' bgColor="#f8f8f8">
                    <td><input type='checkbox' name='select_ip[]' value='<?php echo $m;?>' /></td>
                    <td><?php echo $m;?></td>
                </tr>
                <?php }?>

                <tr><td colspan='2' align='left'><a href="#" onclick="changeAllCheckboxes('banip_manage',true,'select_ip[]'); return false;">全选</a> &nbsp; <a href="#" onclick="changeAllCheckboxes('banip_manage',false,'select_ip[]'); return false;">全不选</a> &nbsp;<a href="#" onclick="changeAllCheckboxes('banip_manage','xor','select_ip[]'); return false;">反选</a>&nbsp;<input type='submit' value='删除所选' /></td></tr>

                </table>

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

