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
<link type="text/css" rel="stylesheet" href="<?php echo './themes/'.ZFramework::app()->theme.'/scripts/jqModal.css';?>" />
<link type="text/css" rel="stylesheet" href="<?php echo './themes/'.ZFramework::app()->theme.'/scripts/index.css';?>" />
<link type="text/css" rel="stylesheet" href="<?php echo './themes/'.ZFramework::app()->theme.'/scripts/jqModal_frame.css';?>" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript" src="http://mapleleaf.googlecode.com/files/jqModal.js"></script>
<script type="text/javascript" src="<?php echo './themes/'.ZFramework::app()->theme.'/scripts/index.js';?>"></script>
<title><?php echo t('WELCOME',array('{site_name}'=>ZFramework::app()->board_name));?></title>
</head>

<body>
    <div class="container">
        <div id="hd">
            <div class="right">
                <?php if(ZFramework::app()->site_close):?><span class="notice"><?php echo t('OFF_LINE_MODE');?></span><?php endif;?>

                <?php
                if(!isset ($_SESSION['admin']) && !isset ($_SESSION['user']))
                    echo '<a class="thickbox" href="index.php?controller=user&amp;action=create&amp;width=630&amp;height=45%">'.t('REGISTER').'</a>&nbsp;<a href="index.php?controller=user&amp;action=login">'.t('LOGIN').'</a>';
                if(isset ($_SESSION['user']) || isset ($_SESSION['admin']))
                    echo '<a href="index.php?controller=user&amp;action=logout">'.t('LOGOUT').'</a>';
                if(isset ($_SESSION['user']))
                    echo '&nbsp;<a class="thickbox" href="index.php?controller=user&amp;action=update&amp;uid='.$_SESSION['uid'].'&amp;width=600&amp;height=50%">'.t('UPDATE').'</a>';
                ?>
            </div>
            <h3><a href="index.php">Home</a></h3>
        </div><!--  header  -->
        <div id="bd">
            <div class="yui-g">
                <?php if($nums>0):?>
                <p><?php echo t('SEARCH_FOUND',array('{result_num}'=>$nums));?></p>
                <table id="main_table">
                    <thead>
                        <tr class="header">
                            <th class="span-4"><?php echo t('NICKNAME');?></th>
                            <th class="span-17"><?php echo t('MESSAGE');?></th>
                            <th class="span-3"><?php echo t('TIME');?></th>
                        </tr>
                    </thead>
                    <?php foreach($data as $m){?>
                    <tr>
                        <td><?php echo (int)$m['uid']?$m['b_username']:$m['user'];?></td>
                        <td><div style='word-wrap: break-word;word-break:break-all;width:450px;'><?php echo nl2br($m['post_content']);?><br />
                            <?php if(@$m['reply_content']){ echo t('ADMIN_REPLIED',array('{admin_name}'=>ZFramework::app()->admin,'{reply_time}'=>$m['reply_time'],'{reply_content}'=>$m['reply_content']));}?></div>
                        </td>
                        <td><?php echo $m['time'];?></td>
                    </tr>
                    <?php }?>
                </table>
                <?php else:?>
                <p><?php echo t('SEARCH_NOTFOUND');?></p>
                <?php endif;?>
            </div>
                <div class="clear">
                    <form action="index.php?controller=search" method="post">
				<input id="search" type="text" size="10" value="Search" name="s">
				<input type="image" src="http://aguestbook.sourceforge.net/demo/images/posts/search.gif" value="Search" alt="Search" name="searchImg">
                    </form>
                </div>
        </div><!-- body -->
        <div class="clear"><?php echo htmlspecialchars_decode(ZFramework::app()->copyright_info);?> <a href="mailto:<?php echo ZFramework::app()->admin_email;?>"><?php echo t('ADMIN_EMAIL');?></a> <?php if(!isset($_SESSION['user'])): ?><a href="index.php?action=control_panel"><?php echo t('ACP');?></a><?php endif;?> Powered by <a href="http://mapleleaf.googlecode.com/" target="_blank" title="Find More">MapleLeaf <?php echo MP_VERSION;?></a></div><!-- footer -->

	<!-- jqModal window -->
	<div id="modalWindow" class="jqmWindow">
	    <div id="jqmTitle">
		<button class="jqmClose">X</button>
	    </div>
	    <iframe id="jqmContent" src=""></iframe>
	</div>
	<!-- end of jqModal window -->
    </div>
</body>
</html>
