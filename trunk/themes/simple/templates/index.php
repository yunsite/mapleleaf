<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="Cache-Control" content="no-cache,must-revalidate" />
<meta http-equiv="expires" content="0" />
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.8.2r1/build/reset-fonts-grids/reset-fonts-grids.css">
<link rel="stylesheet" href="<?php echo './themes/'.ZFramework::app()->theme.'/scripts/common.css';?>" type="text/css"/>
<link type="text/css" rel="stylesheet" href="<?php echo './themes/'.ZFramework::app()->theme.'/scripts/jqModal.css';?>" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo './themes/'.ZFramework::app()->theme.'/scripts/jqModal.js';?>"></script>
<script type="text/javascript" src="<?php echo './themes/'.ZFramework::app()->theme.'/scripts/index.js';?>"></script>
<title><?php echo sprintf(ZFramework::t('WELCOME'),ZFramework::app()->board_name);?></title>
</head>

<body>
    <div id="doc">
        <div id="hd">
            <div id="user_nav">
                <span id="toggleForm"><?php echo ZFramework::t('CLICK_POST');?></span>
                <?php
                if(!isset ($_SESSION['admin']) && !isset ($_SESSION['user'])){
                    echo '<a class="thickbox" href="index.php?controller=user&amp;action=register&amp;width=600&amp;height=60%">'.ZFramework::t('REGISTER').'</a>&nbsp;<a href="index.php?controller=user&amp;action=login">'.ZFramework::t('LOGIN').'</a>';
                }
                if(isset ($_SESSION['user']) || isset ($_SESSION['admin'])){
                    echo '<a href="index.php?controller=user&amp;action=logout">'.ZFramework::t('LOGOUT').'</a>';
                }
                if(isset ($_SESSION['user'])){
                    echo '&nbsp;<a class="thickbox" href="index.php?controller=user&amp;action=user_update&amp;uid='.$_SESSION['uid'].'&amp;width=600&amp;height=60%">'.ZFramework::t('UPDATE').'</a>';
                }
                ?>
            </div>
            <h1><?php echo ZFramework::t('WELCOME_POST');?></h1>
        </div><!--  header  -->
        <div id="bd">
            <div class="yui-g">
                <table id="main_table">
                    <tr class="header">
                        <td><?php echo ZFramework::t('NICKNAME');?></td>
                        <td><?php echo ZFramework::t('MESSAGE');?></td>
                        <td><?php echo ZFramework::t('TIME');?></td>
                    </tr>
                    <?php foreach($data as $m){?>
                    <tr>
                        <td><?php echo $m['user'];?></td>
                        <td><div style='word-wrap: break-word;word-break:break-all;width:450px;'><?php echo $m['content'];?><br />
                            <?php if(@$m['reply']){ echo sprintf(ZFramework::t('ADMIN_REPLIED'),$m['reply']['reply_time'],$m['reply']['reply_content']);}?></div>
                        </td>
                        <td><?php echo $m['time'];?></td>
                    </tr>
                    <?php }?>
                </table>
            </div>
            <?php if(ZFramework::app()->page_on){?>
            <div class="yui-g">
                <?php echo sprintf(ZFramework::t('PAGE_NAV'),$nums,$pages);?>
                <?php for($i=0;$i<$pages;$i++){?>
                        <a href='index.php?pid=<?php echo $i;?>'>
                        <?php
                            if($i==$current_page){ echo '<font size="+2">'.($i+1)."</font>";}else{ echo $i+1;}
                        ?>
                        </a>&nbsp;
                <?php }?>
            </div>
            <?php }?>
            <div class="yui-g">

                <form id="guestbook" name="guestbook" action="index.php?controller=post&amp;action=create" method="post">
                <input id="pid" type="hidden" name="pid" value="<?php echo @$_GET['pid'];?>" />
                <table id="add_table">
                    <tr>
                        <td><?php echo ZFramework::t('NICKNAME');?></td>
                        <td>
                            <?php if($admin == true){?>
                            <input name="user" id="user" type="hidden" maxlength="10" value="<?php echo $adminName;?>" /><?php echo $adminName;?>
                            <?php }elseif(isset($_SESSION['user'])){ ?>
                            <input name="user" id="user" type="hidden" maxlength="10" value="<?php echo $_SESSION['user'];?>" /><?php echo $_SESSION['user'];?>
                                    <?php }else{?>
                            <input name="user" id="user" type="text" maxlength="10" value="anonymous" />
                            <?php }?>
                        </td>
                        <td>&nbsp;<div id="user_msg"></div></td>
                    </tr>
                    <tr>
                        <td><?php echo ZFramework::t('CONTENT');?></td>
                        <td><textarea id="content" name="content" cols="45" rows="8" ></textarea></td>
                        <td><div id='smileys'><?php echo $smileys;?></div>&nbsp;</td>
                    </tr>
                    <?php if(ZFramework::app()->valid_code_open){?>
                    <tr>
                        <td class="l"><?php echo ZFramework::t('VALIDATE_CODE');?></td>
                        <td class="left"><input id="valid_code" type="text" name="valid_code" size="4" maxlength="4" />&nbsp;<img id="captcha_img" src="index.php?action=captcha" title="<?php echo ZFramework::t('CLICK_TO_REFRESH');?>" alt="<?php echo ZFramework::t('CAPTCHA');?>" /></td>
                        <td class="left">&nbsp;</td>
                    </tr>
                    <?php }?>
                    <tr>
                        <td>&nbsp;</td>
                        <td colspan="2"><input id="submit" name="submit" type="submit"  value="<?php echo ZFramework::t('SUBMIT');?>" /><?php echo ZFramework::t('POST_SHORTCUT');?></td>
                    </tr>
                </table>
                </form>
            </div>
        </div><!-- body -->
        <div class="ft"><?php echo htmlspecialchars_decode(ZFramework::app()->copyright_info);?> <a href="mailto:<?php echo ZFramework::app()->admin_email;?>"><?php echo ZFramework::t('ADMIN_EMAIL');?></a> <a href="index.php?action=control_panel"><?php echo ZFramework::t('ACP');?></a> Powered by <a href="http://mapleleaf.ourplanet.tk" target="_blank" title="Find More">MapleLeaf <?php echo MP_VERSION;?></a></div><!-- footer -->

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