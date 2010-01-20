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

          <TABLE cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
            <TBODY>
              <TR>
                <TD class=Border_L></TD>
                <TD></TD>
                <TD>
<TABLE cellSpacing=0 cellPadding=0 width=100% border=0 align="center">
                  <TBODY>
                    <TR>

                      <TD  vAlign=top align=left >

<table width=500 border=0 align="center" cellpadding=2 cellspacing=2>
   <tr><td colspan=2 align=center><br>┊ 当 前 环 境 ┊<hr size=1 width=70%></td></tr>

   <tr><td  width=30%>PHP 版本：</td><td width=70%><?php echo PHP_VERSION;?></td></tr>
   <tr><td>Register_Globals：</td><td><?php echo $register_globals;?></td></tr>
   <tr><td>GD版本：</td><td><?php echo $gd_version;?></td></tr>
   <tr><td>ZipArchive：</td><td><?php echo $zip_support;?></td></tr>
   <tr><td colspan=2 align=center><br>┊ 基 本 数 据 ┊<hr size=1 width=70%></td></tr>

   <tr><td>留言总数：</td>
   <td>(<?php echo $nums;?>) 条 </td>
   </tr>
   <!--
   <tr><td>悄悄话总数：</td>
   <td>(0）条</td>
   </tr>-->
   <tr><td>回复总数：</td>

   <td>(<?php echo $reply_num;?>）条

   </td>
   </tr>
   <tr><td>最新留言时间：</td>

   <td><?php echo date('Y-m-d H:i:s',$data[0]['time']);?></td>
   </tr>
   <tr><td>最旧留言时间：</td>
       <td><?php $oldest=array_pop($data);
              echo date('Y-m-d H:i:s',$oldest['time']);
            ?></td>
   </tr>
   <tr><td colspan=2 align=center><br>
   ┊ 当前版本 ┊
       <hr size=1 width=70%></td></tr>

   <tr>
       <td>当前版本：<?php echo MP_VERSION;?></td>
                        <td><b>枫叶留言板</b></td>
   </tr>
   <tr>
                        <td colspan=2>
                          <p>程序演示:<a href="http://maple.dreamneverfall.cn/demo/">http://maple.dreamneverfall.cn/demo/</a><br>

                            技术支持:<a href="http://maple.dreamneverfall.cn/">http://maple.dreamneverfall.cn/
                            </a></p>
                          <hr size=1></td>
   </tr>
</table>
	 </TD>
                    </TR>
                  </TBODY>
</TABLE>
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