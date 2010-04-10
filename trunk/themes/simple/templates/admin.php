<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="Cache-Control" content="no-cache,must-revalidate" />
<meta http-equiv="expires" content="0" />
<link type="text/css" rel="stylesheet" href="<?php echo './themes/'.$this->_theme.'/admin.css';?>" />
<script type="text/javascript" src="./includes/admin.js"></script>
<title>管理员控制面板首页</title>
</head>

<body>
<div id="admin_header">
	<a href="index.php">首页</a>&nbsp;<a href="index.php?action=logout" title="注销">注销</a>
</div>
<div id="con">
    <ul id="tags">
        <li <?php if($current_tab=='overview'){?> class="selectTag"<?php }?>><a id="overview" onclick="selectTag('tagContent0',this)" 
          href="javascript:void(0)" title="显示综合信息">综合</a> </li>
        <li <?php if($current_tab=='siteset'){?> class="selectTag"<?php }?>><a onclick="selectTag('tagContent1',this)" 
          href="javascript:void(0)" title="配置您的站点">站点设置</a> </li>
        <li <?php if($current_tab=='message'){?> class="selectTag"<?php }?>><a id="message_m" onclick="selectTag('tagContent2',this)" 
          href="javascript:void(0)" title="管理站点留言">留言管理</a> </li>
        <li <?php if($current_tab=='ban_ip'){?> class="selectTag"<?php }?>><a id="ip_m" onclick="selectTag('tagContent3',this)" 
          href="javascript:void(0)" title="IP黑名单功能">IP地址管理</a> </li>
    </ul>
    <div id="tagContent">
        <div id="tagContent0" <?php if($current_tab=='overview'){?> class="tagContent selectTag" <?php } else {?>	class="tagContent" <?php }?> >
            <table>
                <tr>
                    <td><h1>欢迎来到MapleLeaf</h1></td>
                </tr>
                <tr>
                    <td align="left">感谢您选择MapleLeaf作为留言板解决方案. 这个界面将显示您的留言板的总体信息.上方的链接允许您管理留言板.</td>
                </tr>
            </table>
            <table  width="256px" align="left" cellpadding="0" cellspacing="0" style="margin-top:5px;">
                <tr>
                    <td align="left"><b>统计信息</b></td>
                </tr>
                <tr>
                    <td align="left">留言数量：</td><td align="right"><?php echo $nums;?></td>
                </tr>
                <tr>
                    <td align="left">回复数量：</td><td align="right"><?php echo $reply_num;?></td>
                </tr>
                <tr>
                    <td align="left">当前版本：</td><td align="right"><?php echo MP_VERSION;?></td>
                </tr>
                <tr>
                    <td align="left"><b>系统信息</b></td>
                </tr>
                <tr>
                    <td align="left">PHP版本：</td><td align="right"><?php echo PHP_VERSION;?></td>
                </tr>
                <tr>
                    <td align="left">GD版本： </td><td align="right"><?php echo $gd_version;?></td>
                </tr>
                <tr>
                    <td align="left">安全模式：</td><td align="right"><?php echo $isSafeMode;?></td>
                </tr>
                <tr>
                    <td align="left">Register_Globals：</td><td align="right"><?php echo $register_globals;?></td>
                </tr>
                <tr>
                    <td align="left">Magic_Quotes_Gpc：</td><td align="right"><?php echo $magic_quotes_gpc;?></td>
                </tr>
                <tr>
                    <td align="left">allow_url_fopen：</td><td align="right"><?php echo $allow_url_fopen;?></td>
                </tr>
                <tr>
                    <td align="left">ZipArchive：</td><td align="right"><?php echo $zip_support;?></td>
                </tr>
            </table>
        </div>
        <div id="tagContent1" <?php if($current_tab=='siteset'){?> class="tagContent selectTag" <?php } else {?> class="tagContent" <?php }?>>
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
        
        </div>
        <div  id="tagContent2" <?php if($current_tab=='message'){?>	class="tagContent selectTag" <?php } else {?>	class="tagContent" <?php }?>>
            <div id="message_container">
            <!-- 留言管理 -->
            <form id="message_manage" action="index.php?action=delete_multi_messages" method="post">
            <table class="table2">
                <tr class="header">
                    <td width="51">选择</td><td width="55" class="nickname">昵称</td><td width="379" class="message">留言</td><td width="140">操作</td>
                </tr>
            <?php foreach($data as $m){?>    
            
            <tr class='admin_message'>
                <td><input type='checkbox' name='select_mid[]' value='<?php echo $m['id'];?>' />
                    <input type='hidden' name='<?php echo $m['id'];?>' value='<?php if(@$m['reply']){ echo "1";}else{echo "0";}?>' />
                </td>
                <td class='left'>	<?php echo $m['user'];?></td>
                <td class='left'><?php echo $m['content'];?><br />时间：<?php echo date('m-d H:i',$m['time']);?> <?php if(@$m['reply']==true){?>  <br /><font color="red">您回复：</font> <?php echo $m['reply']['reply_content']." Time:".date('m-d H:i',(int)$m['reply']['reply_time']);?><span>&nbsp;<a href="index.php?action=delete_reply&amp;mid=<?php echo $m['id'];?>">删除回复</a></span><?php }?></td>
                <td><a href='index.php?action=delete_message&amp;mid=<?php echo $m['id'];?>&amp;reply=<?php if(@$m['reply']){ echo "1";}else{ echo "0";}?>'>删除</a>
                <a href='index.php?action=reply_window&amp;mid=<?php echo $m['id'];?>&amp;reply=<?php if(@$m['reply']){echo "1";}else{echo "0";}?>'>回复</a>
                <a href='index.php?action=update_message&amp;mid=<?php echo $m['id'];?>'>更新</a>
                <a href='index.php?action=ban&amp;ip=<?php echo $m['ip'];?>'>屏蔽</a></td>
            </tr>
           <?php }?>
            
            <tr><td colspan='4' align='left'><a href="#" onclick="changeAllCheckboxes('message_manage',true,'select_mid[]'); return false;">全选</a> &nbsp; <a href="#" onclick="changeAllCheckboxes('message_manage',false,'select_mid[]'); return false;">全不选</a> &nbsp;<a href="#" onclick="changeAllCheckboxes('message_manage','xor','select_mid[]'); return false;">反选</a>&nbsp;<input type='submit' value='删除所选' />&nbsp;<input type='button' value='清空所有留言'  onclick="javascript:if(window.confirm('你确实要删除所有留言吗？同时会删除所有回复'))window.open('index.php?action=clear_all','_self')" />&nbsp;<input type='button' value='清空所有回复' onclick="javascript:if(window.confirm('你确实要删除所有回复？'))window.open('index.php?action=clear_reply','_self')" /><input type='button' value='备份数据' onclick="javascript:window.open('index.php?action=backup','_self')" /></td></tr>
            
            </table>
            </form>
            </div>
        </div>
        <div  id="tagContent3" <?php if($current_tab=='ban_ip'){?> class="tagContent selectTag" <?php } else {?> class="tagContent" <?php }?>>
            <div id="ip_container">
            <!-- IP管理 -->
                <form id="banip_manage" action="index.php?action=ip_update" method="post">
                <table class="table2">
                    <tr class="header">
                        <td>选择</td><td>被屏蔽的IP地址</td>
                    </tr>
                <?php foreach($ban_ip_info as $m){?>    
                <tr class='admin_message'>
                    <td><input type='checkbox' name='select_ip[]' value='<?php echo $m;?>' /></td>
                    <td><?php echo $m;?></td>
                </tr>
                <?php }?>
                
                <tr><td colspan='2' align='left'><a href="#" onclick="changeAllCheckboxes('banip_manage',true,'select_ip[]'); return false;">全选</a> &nbsp; <a href="#" onclick="changeAllCheckboxes('banip_manage',false,'select_ip[]'); return false;">全不选</a> &nbsp;<a href="#" onclick="changeAllCheckboxes('banip_manage','xor','select_ip[]'); return false;">反选</a>&nbsp;<input type='submit' value='删除所选' /></td></tr>
                
                </table>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="botton">Powered by <a href="http://maple.dreamneverfall.cn">MapleLeaf<?php echo MP_VERSION;?></a></div>
</body>
</html>