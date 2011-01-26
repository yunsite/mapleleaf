<?php if(!defined('IN_MP')){die('Access denied!');} ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="Cache-Control" content="no-cache,must-revalidate" />
<meta http-equiv="expires" content="0" />
<link rel="stylesheet" href="<?php echo './themes/'.ZFramework::app()->theme.'/scripts/';?>blueprint/screen.css" type="text/css" media="screen, projection" />
<link rel="stylesheet" href="<?php echo './themes/'.ZFramework::app()->theme.'/scripts/';?>blueprint/print.css" type="text/css" media="print" />
<!--[if lt IE 8]><link rel="stylesheet" href="<?php echo './themes/'.ZFramework::app()->theme.'/scripts/';?>blueprint/ie.css" type="text/css" media="screen, projection" /><![endif]-->
<link type="text/css" rel="stylesheet" href="<?php echo './themes/'.ZFramework::app()->theme.'/scripts/admin.css';?>" />
<link type="text/css" rel="stylesheet" href="<?php echo './themes/'.ZFramework::app()->theme.'/scripts/jqModal.css';?>" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript" src="http://mapleleaf.googlecode.com/files/jqModal.js"></script>
<script type="text/javascript" src="<?php echo './themes/'.ZFramework::app()->theme.'/scripts/admin.js';?>"></script>
<title><?php echo ZFramework::t('ACP_INDEX');?></title>
</head>

<body>
    <div class="container">
	<div id="hd">
		<?php if(ZFramework::app()->site_close):?><span class="notice"><?php echo ZFramework::t('OFF_LINE_MODE');?></span><?php endif;?><a href="index.php"><?php echo ZFramework::t('HOME');?></a>&nbsp;<a href="index.php?controller=user&amp;action=logout" title="<?php echo ZFramework::t('LOGOUT');?>"><?php echo ZFramework::t('LOGOUT');?></a>
	</div><!-- header -->
	<div id="bd">
	    <div class="yui-g">
		<ul id="tags">
		    <?php
		    for($i=0,$c=count($tabs_array);$i<$c;$i++) {
			$class=($current_tab==$tabs_array[$i])?'selectTag':'';
			echo "\n<li class='$class'><a href='index.php?action=control_panel&subtab={$tabs_array[$i]}'>{$tabs_name_array[$i]}</a></li>\n";
		    }
		    ?>
		</ul>
	    </div><!-- yui-g -->
	    <div class="yui-g">
		<div id="tagContent">
		    <div id="overviewContainer" <?php if($current_tab=='overview'){?> class="tagContent selectTag" <?php } else {?> class="tagContent" <?php }?> >
			<table>
			    <tr>
				<td><h1><?php echo ZFramework::t('WELCOME_SYS');?></h1></td>
			    </tr>
			    <tr>
				<td ><?php echo ZFramework::t('THANKS');?></td>
			    </tr>
			</table>
			<table>
			    <tr>
				<td colspan="2" ><b><?php echo ZFramework::t('STATS_INFO');?></b></td>
			    </tr>
			    <tr>
				<td ><?php echo ZFramework::t('NUM_POSTS');?>：</td><td align="right"><?php echo $nums;?></td>
			    </tr>
			    <tr>
				<td ><?php echo ZFramework::t('NUM_REPLY');?>：</td><td align="right"><?php echo $reply_num;?></td>
			    </tr>
			    <tr>
				<td ><?php echo ZFramework::t('MP_VERSION');?>：</td><td align="right"><?php echo MP_VERSION;?></td>
			    </tr>
			    <tr>
				<td  colspan="2"><b><?php echo ZFramework::t('SYS_INFO');?></b></td>
			    </tr>
			    <tr>
				<td ><?php echo ZFramework::t('PHP_VERSION');?>：</td><td align="right"><?php echo PHP_VERSION;?></td>
			    </tr>
			    <tr>
				<td ><?php echo ZFramework::t('GD_VERSION');?>： </td><td align="right"><?php echo $gd_version;?></td>
			    </tr>
			    <tr>
				<td >Register_Globals：</td><td align="right"><?php echo $register_globals;?></td>
			    </tr>
			    <tr>
				<td >Magic_Quotes_Gpc：</td><td align="right"><?php echo $magic_quotes_gpc;?></td>
			    </tr>
			    <tr>
				<td >ZipArchive：</td><td align="right"><?php echo $zip_support;?></td>
			    </tr>
			</table>
		    </div><!-- Overview -->
		    <div id="configContainer" <?php if($current_tab=='siteset'){?> class="tagContent selectTag" <?php } else {?> class="tagContent" <?php }?>>
			<form action="index.php?controller=config&amp;action=update" method="post">
			    <fieldset>
			    <legend><?php echo ZFramework::t('SYS_CONF');?></legend>
			    <table>
				<tr>
				    <td><?php echo ZFramework::t('BOARD_NAME');?>:</td><td><input name="board_name" type="text" size="20" value="<?php echo ZFramework::app()->board_name;?>" /></td>
				</tr>
				<tr>
				    <td><?php echo ZFramework::t('CLOSE_BOARD');?>:</td><td><input name="site_close" type="radio" value="1"
			    <?php if(ZFramework::app()->site_close==1){?> checked='checked' <?php }?> /><?php echo ZFramework::t('YES');?><input name="site_close" type="radio" value="0" <?php if(ZFramework::app()->site_close==0){?> checked='checked' <?php }?> /><?php echo ZFramework::t('NO');?></td>
				</tr>
				<tr>
				    <td><?php echo ZFramework::t('CLOSE_REASON');?>:</td><td><textarea  class="span-9" name="close_reason" cols="30" rows="3"><?php echo ZFramework::app()->close_reason;?></textarea></td>
				</tr>
				<tr>
				    <td><?php echo ZFramework::t('ADMIN_EMAIL');?>:</td><td><input name="admin_email" type="text" size="20" value="<?php echo ZFramework::app()->admin_email;?>" /></td>
				</tr>
				<tr>
				    <td><?php echo ZFramework::t('COPY_INFO');?>:</td><td><textarea class="span-9" name="copyright_info" cols="30" rows="3"><?php echo ZFramework::app()->copyright_info;?></textarea></td>
				</tr>
				<tr>
				    <td><?php echo ZFramework::t('SYS_THEME');?>:</td><td><select name="theme"><?php foreach ($themes as $per_theme){?><option value="<?php echo $per_theme;?>" <?php if($per_theme==ZFramework::app()->theme){echo 'selected="selected"';}?>><?php echo $per_theme;?></option><?php }?></select></td>
				</tr>
				<tr>
				    <td><?php echo ZFramework::t('TIMEZONE');?>:</td>
				    <td>
					    <select name="timezone">

				    <?php foreach ($timezone_array as $key=>$per_timezone)
					    {
				    ?>
				    <option value="<?php echo $key;?>" <?php if($key==ZFramework::app()->timezone){echo 'selected="selected"';}?>>
				    <?php echo $per_timezone;?></option>
				    <?php }?>

					    </select>
				    </td>
				</tr>
				<tr>
				    <td><?php echo ZFramework::t('LANG');?>:</td><td><select name="lang"><?php foreach ($languages as $language){?><option value="<?php echo $language;?>" <?php if($language==ZFramework::app()->lang){echo 'selected="selected"';}?>><?php echo $language;?></option><?php }?></select></td>
				</tr>
			    </table>
			    </fieldset>
			    <fieldset>
			    <legend><?php echo ZFramework::t('POST_CONF');?></legend>
			    <table>
				<tr>
				    <td><?php echo ZFramework::t('FILTER_WORDS');?>：</td><td><textarea class="span-9" name="filter_words" cols="20" rows="3"><?php echo ZFramework::app()->filter_words;?></textarea></td>
				</tr>
				<tr>
				    <td><?php echo ZFramework::t('ENABLE_CAPTCHA');?>：</td>
                                    <td>
                                        <?php if(gd_loaded()):?>
                                        <input name="valid_code_open" type="radio" value="1" <?php if(ZFramework::app()->valid_code_open==1){?> checked='checked' <?php }?> /><?php echo ZFramework::t('YES');?><input name="valid_code_open" type="radio" value="0" <?php if(ZFramework::app()->valid_code_open==0){?> checked='checked' <?php }?> /><?php echo ZFramework::t('NO');?>
                                        <?php else: ?>
                                        <input name="valid_code_open" type="radio" value="1" /><?php echo ZFramework::t('YES');?><input name="valid_code_open" type="radio" value="0" checked='checked' /><?php echo ZFramework::t('NO');?><?php echo ZFramework::t('GD_DISABLED_NOTICE');?>
                                        <?php endif;?>
                                    </td>
				</tr>
				<tr>
				    <td><?php echo ZFramework::t('ENABLE_PAGE');?>：</td><td><input name="page_on" type="radio" value="1" <?php if(ZFramework::app()->page_on==1){?> checked='checked' <?php }?> /><?php echo ZFramework::t('YES');?><input name="page_on" type="radio" value="0" <?php if(ZFramework::app()->page_on==0){?> checked='checked'<?php }?> /><?php echo ZFramework::t('NO');?></td>
				</tr>
				<tr>
				    <td><?php echo ZFramework::t('POST_PERPAGE');?>：</td><td><input name="num_perpage" type="text" value="<?php echo ZFramework::app()->num_perpage;?>" /><?php echo ZFramework::t('PAGINATION_TIP');?></td>
				</tr>
			    </table>
			    </fieldset>
			    <fieldset>
			    <legend><?php echo ZFramework::t('ADMIN_CONF');?></legend>
			    <table>
				<tr>
				    <td><?php echo ZFramework::t('CHANGE_PWD');?>:</td><td ><input name="password" type="password"  />&nbsp;<?php echo ZFramework::t('PWD_TIP');?></td>
				</tr>
			    </table>
			    </fieldset>
			<input type="submit" value="<?php echo ZFramework::t('SUBMIT');?>" /><input type="reset" value="<?php echo ZFramework::t('RESET');?>" />
			</form>
		    </div><!-- Configuration -->
		    <div id="message_container" <?php if($current_tab=='message'){?>	class="tagContent selectTag" <?php } else {?>	class="tagContent" <?php }?>>
			<!-- 留言管理 -->
                        <form id="message_manage" action="index.php?controller=post&amp;action=delete_multi_messages" method="post">
			<table width="800px">
                            <thead>
                                <tr class="header">
                                    <th class="span-1"><?php echo ZFramework::t('SELECT');?></th><th class="span-3"><?php echo ZFramework::t('NICKNAME');?></th><th class="span-15"><?php echo ZFramework::t('MESSAGE');?></th><th><?php echo ZFramework::t('OPERATION');?></th>
                                </tr>
                            </thead>
			<?php foreach($data as $m){?>
			<tr>
			    <td><input type='checkbox' name='select_mid[]' value='<?php echo $m['id'];?>' />
				<input type='hidden' name='<?php echo $m['id'];?>' value='<?php if(@$m['reply']){ echo "1";}else{echo "0";}?>' />
			    </td>
			    <td><?php echo $m['user']?$m['user']:$m['b_username'];?></td>
			    <td  class='admin_message'>
                               <div style='word-wrap: break-word;word-break:break-all;width:590px;'>
                                    <?php echo $m['post_content'];?><br /><?php echo ZFramework::t('TIME');?>：<?php echo date('y-m-d H:i',$m['time']);?>
                                    <?php if($m['reply_content']){?>
                                    <br />
                                     <?php echo ZFramework::t('YOU_REPLIED',array('{reply_time}'=>date('y-m-d H:i',(int)$m['reply_time']+ ZFramework::app()->timezone * 60 *60),'{reply_content}'=>$m['reply_content']));?>
                                    <span>&nbsp;<a href="index.php?controller=reply&amp;action=delete&amp;mid=<?php echo $m['id'];?>"><?php echo ZFramework::t('DELETE_THIS_REPLY');?></a></span>
                                    <?php }?>
                               </div>
                            </td>
                            <td><a href='index.php?controller=post&amp;action=delete&amp;mid=<?php echo $m['id'];?>&amp;reply=<?php if(@$m['reply']){ echo "1";}else{ echo "0";}?>'><?php echo ZFramework::t('DELETE');?></a>
                                <a class="ex2trigger" href='index.php?controller=reply&amp;action=reply&amp;mid=<?php echo $m['id'];?>'><?php echo ZFramework::t('REPLY');?></a>
                            <a class="ex2trigger" href='index.php?controller=post&amp;action=update&amp;mid=<?php echo $m['id'];?>'><?php echo ZFramework::t('UPDATE');?></a>
			    <a href='index.php?controller=badip&amp;action=create&amp;ip=<?php echo $m['ip'];?>'><?php echo ZFramework::t('BAN');?></a></td>
			</tr>
		       <?php }?>

			<tr>
			    <td colspan='4'>
                                <span class="check_span"><a href="#" id="m_checkall"><?php echo ZFramework::t('CHECK_ALL');?></a> &nbsp;
				<a href="#" id="m_checknone"><?php echo ZFramework::t('CHECK_NONE');?></a> &nbsp;
				<a href="#" id="m_checkxor"><?php echo ZFramework::t('CHECK_INVERT');?></a>&nbsp;</span>
				<input type='submit' value='<?php echo ZFramework::t('DELETE_CHECKED');?>' />&nbsp;
				<a id="deleteallLink" href="index.php?controller=post&amp;action=deleteAll"><?php echo ZFramework::t('DELETE_ALL');?></a>&nbsp;
                                <a id="deleteallreplyLink" href="index.php?controller=reply&amp;action=deleteAll"><?php echo ZFramework::t('DELETE_ALL_REPLY');?></a>
                                <?php if(is_flatfile()):?><a href="index.php?controller=backup&amp;action=create"><?php echo ZFramework::t('BACKUP');endif;?></a>
			    </td></tr>

			</table>
			</form>
		    </div><!-- Messages -->

		    <div id="ip_container" <?php if($current_tab=='ban_ip'){?> class="tagContent selectTag" <?php } else {?> class="tagContent" <?php }?>>
			<!-- IP管理 -->
			<form id="banip_manage" action="index.php?controller=badip&amp;action=update" method="post">
			    <table class="table2">
                                <thead>
                                    <tr class="header">
                                        <th><?php echo ZFramework::t('SELECT');?></th><th><?php echo ZFramework::t('BAD_IP');?></th>
                                    </tr>
                                </thead>
				<?php foreach($ban_ip_info as $m){?>
				<tr class='admin_message'>
				    <td><input type='checkbox' name='select_ip[]' value='<?php echo $m["ip"];?>' /></td>
				    <td><?php echo $m["ip"];?></td>
				</tr>
				<?php }?>
				<tr><td colspan='2' align='left'><span class="check_span"><a href="#" id="ip_checkall"><?php echo ZFramework::t('CHECK_ALL');?></a> &nbsp; <a href="#" id="ip_checknone"><?php echo ZFramework::t('CHECK_NONE');?></a> &nbsp;<a href="#" id="ip_checkxor"><?php echo ZFramework::t('CHECK_INVERT');?></a>&nbsp;</span><input type='submit' value='<?php echo ZFramework::t('DELETE_CHECKED');?>' /></td></tr>
			    </table>
			</form>
		    </div><!-- Bad IPs -->
		    <div id="pluginContainer" <?php if($current_tab=='plugin'){?> class="tagContent selectTag" <?php } else {?> class="tagContent" <?php }?>>
			
			    <?php foreach($plugins as $plugin): ?>
			    <h2><?php echo $plugin;?></h2>
			    <?php
				include_once PLUGINDIR.$plugin.'.php';
				$configFuncName=$plugin.'_config';
				$configFuncName(true);
			    ?>    
			    <?php endforeach; ?>
			
		    </div><!-- Plugins -->
		</div>
	    </div><!-- yui-g  -->
	</div><!-- body -->
	<div class="ft">
	    Powered by <a href="http://mapleleaf.ourplanet.tk">MapleLeaf <?php echo MP_VERSION;?></a>
	</div><!-- footer -->
	<!-- jqModal window -->
	<div class="jqmWindow" id="ex2">
	Please wait...
	</div>
	<!-- end of jqModal window -->
    </div>
</body>
</html>