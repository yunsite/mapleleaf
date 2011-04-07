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
<script type="text/javascript" src="<?php echo './themes/'.ZFramework::app()->theme.'/scripts/user_index.js';?>"></script>
<title><?php echo t('ACP_INDEX');?></title>
</head>

<body>
    <div class="container">
	<div id="hd">
		<?php if(ZFramework::app()->site_close):?><span class="notice"><?php echo t('OFF_LINE_MODE');?></span><?php endif;?><a href="index.php"><?php echo t('HOME');?></a>&nbsp;<a href="index.php?controller=user&amp;action=logout" title="<?php echo t('LOGOUT');?>"><?php echo t('LOGOUT');?></a>
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
		    
		      <div id="user_container" class="tagContent selectTag">
			<!-- 用户管理 -->
                        <form id="user_manage" action="index.php?controller=user&amp;action=delete_multi" method="post">
			<table width="800px">
                            <thead>
                                <tr class="header">
                                    <th class="span-1"><?php echo t('SELECT');?></th><th class="span-3"><?php echo t('NICKNAME');?></th><th class="span-6"><?php echo t('EMAIL');?></th><th><?php echo t('OPERATION');?></th>
                                </tr>
                            </thead>
			<?php foreach($users as $u){?>
			<tr>
			    <td><input type='checkbox' name='select_uid[]' value='<?php echo $u['uid'];?>' /></td>
			    <td><?php echo $u['username'];?></td>
			    <td><?php echo $u['email'];?></td>
                            <td><a href='index.php?controller=user&amp;action=delete&amp;uid=<?php echo $u['uid'];?>'><?php echo t('DELETE');?></a>    
                                <a class="ex2trigger" href='index.php?controller=user&amp;action=update&amp;uid=<?php echo $u['uid'];?>'><?php echo t('UPDATE');?></a>
			    </td>
			</tr>
		       <?php }?>

			<tr>
			    <td colspan='4'>
                                <span class="check_span"><a href="#" id="m_checkall"><?php echo t('CHECK_ALL');?></a> &nbsp;
				<a href="#" id="m_checknone"><?php echo t('CHECK_NONE');?></a> &nbsp;
				<a href="#" id="m_checkxor"><?php echo t('CHECK_INVERT');?></a>&nbsp;</span>
				<input type='submit' value='<?php echo t('DELETE_CHECKED');?>' />&nbsp;
				<a id="deleteallLink" href="index.php?controller=post&amp;action=deleteAll"><?php echo t('DELETE_ALL');?></a>&nbsp;
                                <?php if(is_flatfile()):?><a href="index.php?controller=backup&amp;action=create"><?php echo t('BACKUP');endif;?></a>
			    </td></tr>

			</table>
			</form>
		    </div><!-- Messages -->
		    
		</div>
	    </div><!-- yui-g  -->
	</div><!-- body -->
	<div class="ft">
	    Powered by <a href="http://mapleleaf.googlecode.com/">MapleLeaf <?php echo MP_VERSION;?></a>
	</div><!-- footer -->
	<!-- jqModal window -->
	<div class="jqmWindow" id="ex2">
	Please wait...
	</div>
	<!-- end of jqModal window -->
    </div>
</body>
</html>
