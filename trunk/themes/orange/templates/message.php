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

          <form id="message_manage" action="index.php?action=delete_multi_messages" method="post">
            <table id="table2">
                <tr class="header">
                    <td width="51">选择</td><td width="55" class="nickname">昵称</td><td width="379" class="message">留言</td><td width="140">操作</td>
                </tr>
            <?php foreach($data as $m){?>

            <tr class='admin_message' onmouseover='javascript:this.bgColor="#ecd7f2"' onmouseout='javascript:this.bgColor="#f8f8f8"' bgColor="#f8f8f8">
                <td><input type='checkbox' name='select_mid[]' value='<?php echo $m['id'];?>' />
                    <input type='hidden' name='<?php echo $m['id'];?>' value='<?php if(@$m['reply']){ echo "1";}else{echo "0";}?>' />
                </td>
                <td class='left'>	<?php echo $m['user'];?></td>
                <td class='left' <?php if(@$m['reply']==true){?> onmouseover="document.getElementById('del_div_<?php echo $m['id'];?>').style.display=''" onmouseout="document.getElementById('del_div_<?php echo $m['id'];?>').style.display='none'" <?php }?>><?php echo $m['content'];?><br />时间：<?php echo date('m-d H:i',$m['time']);?> <?php if(@$m['reply']==true){?>  <br /><font color="red">您回复：</font> <?php echo $m['reply']['reply_content']." Time:".date('m-d H:i',(int)$m['reply']['reply_time']);?><span id='del_div_<?php echo $m['id'];?>'  style="display:none" >&nbsp;<a href="index.php?action=delete_reply&mid=<?php echo $m['id'];?>">删除回复</a></span><?php }?></td>
                <td><a href='index.php?action=delete_message&mid=<?php echo $m['id'];?>&reply=<?php if(@$m['reply']){ echo "1";}else{ echo "0";}?>'>删除</a>
                <a href='index.php?action=reply_window&mid=<?php echo $m['id'];?>&reply=<?php if(@$m['reply']){echo "1";}else{echo "0";}?>'>回复</a>
                <a href='index.php?action=update_message&mid=<?php echo $m['id'];?>'>更新</a>
                <a href='index.php?action=ban&ip=<?php echo $m['ip'];?>'>屏蔽</a></td>
            </tr>
           <?php }?>

            <tr><td colspan='4' align='left'><a href="#" onclick="changeAllCheckboxes('message_manage',true,'select_mid[]'); return false;">全选</a> &nbsp; <a href="#" onclick="changeAllCheckboxes('message_manage',false,'select_mid[]'); return false;">全不选</a> &nbsp;<a href="#" onclick="changeAllCheckboxes('message_manage','xor','select_mid[]'); return false;">反选</a>&nbsp;<input type='submit' value='删除所选' />&nbsp;<input type='button' value='清空所有留言'  onclick="javascript:if(window.confirm('你确实要删除所有留言吗？同时会删除所有回复'))window.open('index.php?action=clear_all','_self')" />&nbsp;<input type='button' value='清空所有回复' onclick="javascript:if(window.confirm('你确实要删除所有回复？'))window.open('index.php?action=clear_reply','_self')" /><input type='button' value='备份数据' onclick="javascript:window.open('index.php?action=backup','_self')" /></td></tr>

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




