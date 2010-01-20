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

<form action="index.php?action=set_config" method="post">
                <fieldset>
                <legend>整体设置</legend>
                <table cellpadding="0" cellspacing="0" width="600px">
                    <tr>
                        <td width="150px">留言板名称:</td><td align="left"><input name="board_name" type="text" size="20" value="<?php echo $this->_board_name;?>" /></td>
                    </tr>
                    <tr>
                        <td>关闭站点:</td><td align="left"><input name="mb_open" type="radio" value="1"
                <?php if($this->_mb_open==1){?> checked='checked' <?php }?> />是<input name="mb_open" type="radio" value="0" <?php if($this->_mb_open==0){?> checked='checked' <?php }?> />否</td>
                    </tr>
                    <tr>
                        <td>关闭原因:</td><td align="left"><textarea name="close_reason" cols="30" rows="3"><?php echo $this->_close_reason;?></textarea></td>
                    </tr>
                    <tr>
                        <td>站长信箱:</td><td align="left"><input name="admin_email" type="text" size="20" value="<?php echo $this->_admin_email;?>" /></td>
                    </tr>
                    <tr>
                        <td>版权信息:</td><td align="left"><textarea name="copyright_info" cols="30" rows="3"><?php echo $this->_copyright_info;?></textarea></td>
                    </tr>
                    <tr>
                        <td>外观主题:</td><td align="left"><select name="theme"><?php foreach ($themes as $per_theme){?><option value="<?php echo $per_theme;?>" <?php if($per_theme==$this->_theme){echo 'selected="selected"';}?>><?php echo $per_theme;?></option><?php }?></select></td>
                    </tr>
                    <tr>
                        <td>使用时区:</td><td align="left"><select name="timezone"><?php foreach ($timezone_array as $key=>$per_timezone){?><option value="<?php echo $key;?>" <?php if($key==$this->_time_zone){echo 'selected="selected"';}?>><?php echo $per_timezone;?></option><?php }?></select></td>
                    </tr>
                </table>
                </fieldset>
                <fieldset>
                <legend>留言设置</legend>
                <table cellpadding="0" cellspacing="0" width="600px">
                    <tr>
                        <td width="150px">过滤词汇：</td><td align="left"><textarea name="filter_words" cols="20" rows="3"><?php echo $this->_filter_words;?></textarea></td>
                    </tr>
                    <tr>
                        <td>启用验证码：</td><td align="left"><input name="valid_code_open" type="radio" value="1"
                <?php if($this->_valid_code_open==1){?> checked='checked' <?php }?> />启用<input name="valid_code_open" type="radio" value="0" <?php if($this->_valid_code_open==0){?> checked='checked' <?php }?> />关闭</td>
                    </tr>
                    <tr>
                        <td>启用分页功能：</td><td align="left"><input name="page_on" type="radio" value="1" <?php if($this->_page_on==1){?> checked='checked' <?php }?> />启用<input name="page_on" type="radio" value="0" <?php if($this->_page_on==0){?> checked='checked'<?php }?> />关闭</td>
                    </tr>
                    <tr>
                        <td>每页显示留言数：</td><td align="left"><input name="num_perpage" type="text" value="<?php echo $this->_num_perpage;?>" />(当分页启用后，此设置起效)</td>
                    </tr>
                </table>
                </fieldset>
                <fieldset>
                <legend>管理员帐户</legend>
                <table cellpadding="0" cellspacing="0" width="600px">
                    <tr>
                        <td>修改密码:</td><td align="left"><input name="password" type="password"  />&nbsp;如果您想修改您的密码请输入一个新密码，否则请留空</td>
                    </tr>
                </table>
                </fieldset>
            <input type="submit" value="提交" /><input type="reset" value="重设" />
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