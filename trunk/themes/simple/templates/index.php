<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="Cache-Control" content="no-cache,must-revalidate" />
<meta http-equiv="expires" content="0" />
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.8.2r1/build/reset-fonts-grids/reset-fonts-grids.css">
<link rel="stylesheet" href="<?php echo './themes/'.$this->_theme.'/scripts/common.css';?>" type="text/css"/>
<link type="text/css" rel="stylesheet" href="<?php echo './themes/'.$this->_theme.'/scripts/jqModal.css';?>" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo './themes/'.$this->_theme.'/scripts/jqModal.js';?>"></script>
<script type="text/javascript" src="<?php echo './themes/'.$this->_theme.'/scripts/index.js';?>"></script>
<title><?php echo sprintf($this->t('WELCOME'),$this->_board_name);?></title>
</head>

<body>
    <div id="doc">
        <div id="hd">
            <div id="user_nav">
                <span id="toggleForm"><?php echo $this->t('CLICK_POST');?></span>
                <?php
                if(!isset ($_SESSION['admin']) && !isset ($_SESSION['user'])){
                    echo '<a class="ex2trigger" href="?action=register">Register</a>&nbsp;<a href="?action=login">Login</a>';;
                }
                if(isset ($_SESSION['user']) || isset ($_SESSION['admin'])){
                    echo '<a href="?action=logout">Logout</a>';
                }
                if(isset ($_SESSION['user'])){
                    echo '&nbsp;<a class="ex2trigger" href="?action=user_update&amp;uid='.$_SESSION['uid'].'">Update</a>';
                }
                ?>
            </div>
            <h1><?php echo $this->t('WELCOME_POST');?></h1>
        </div><!--  header  -->
        <div id="bd">
            <div class="yui-g">
                <table id="main_table">
                    <tr class="header">
                        <td><?php echo $this->t('NICKNAME');?></td>
                        <td><?php echo $this->t('MESSAGE');?></td>
                        <td><?php echo $this->t('TIME');?></td>
                    </tr>
                    <?php foreach($data as $m){?>
                    <tr>
                        <td><?php echo str_replace($this->_admin_name,"<font color='red'>$this->_admin_name</font>",$m['user']);?></td>
                        <td><?php echo $this->parse_smileys($m['content'],$this->_smileys_dir,$this->_smileys);?><br />
                            <?php if(@$m['reply']){ echo sprintf($this->t('ADMIN_REPLIED'),date('m-d H:i',(int)$m['reply']['reply_time']+$this->_time_zone*60*60),$this->parse_smileys($m['reply']['reply_content'],$this->_smileys_dir,$this->_smileys));}?>
                        </td>
                        <td><?php echo date('m-d H:i',$m['time']+$this->_time_zone*60*60);?></td>
                    </tr>
                    <?php }?>
                </table>
            </div>
            <?php if($this->_page_on){?>
            <div class="yui-g">
                <?php echo sprintf($this->t('PAGE_NAV'),$nums,$pages);?>
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
                
                <form id="guestbook" name="guestbook" action="index.php?action=post" method="post">
                <input id="pid" type="hidden" name="pid" value="<?php echo $_GET['pid'];?>" />
                <table id="add_table">
                    <tr>
                        <td><?php echo $this->t('NICKNAME');?></td>
                        <td>
                            <?php if($admin == true){?>
                            <input name="user" id="user" type="hidden" maxlength="10" value="<?php echo $this->_admin_name;?>" /><?php echo $this->_admin_name;?>
                            <?php }elseif(isset($_SESSION['user'])){ ?>
                            <input name="user" id="user" type="hidden" maxlength="10" value="<?php echo $_SESSION['user'];?>" /><?php echo $_SESSION['user'];?>
                                    <?php }else{?>
                            <input name="user" id="user" type="text" maxlength="10" value="anonymous" />
                            <?php }?>
                        </td>
                        <td>&nbsp;<div id="user_msg"></div></td>
                    </tr>
                    <tr>
                        <td><?php echo $this->t('CONTENT');?></td>
                        <td><textarea id="content" name="content" cols="45" rows="8" ></textarea></td>
                        <td><div id='smileys'><?php echo $smileys;?></div>&nbsp;</td>
                    </tr>
                    <?php if($this->_valid_code_open){?>
                    <tr>
                        <td class="l"><?php echo $this->t('VALIDATE_CODE');?></td>
                        <td class="left"><input id="valid_code" type="text" name="valid_code" size="4" maxlength="4" />&nbsp;<img id="captcha_img" src="index.php?action=showCaptcha" title="<?php echo $this->t('CLICK_TO_REFRESH');?>" alt="<?php echo $this->t('CAPTCHA');?>" /></td>
                        <td class="left">&nbsp;</td>
                    </tr>
                    <?php }?>
                    <tr>
                        <td>&nbsp;</td>
                        <td colspan="2"><input id="submit" name="submit" type="submit"  value="<?php echo $this->t('SUBMIT');?>" /><?php echo $this->t('POST_SHORTCUT');?></td>
                    </tr>
                </table>
                </form>
            </div>
        </div><!-- body -->
        <div class="ft"><?php echo htmlspecialchars_decode($this->_copyright_info);?> <a href="mailto:<?php echo $this->_admin_email;?>"><?php echo $this->t('ADMIN_EMAIL');?></a> <a href="index.php?action=control_panel"><?php echo $this->t('ACP');?></a> Powered by <a href="http://mapleleaf.ourplanet.tk" target="_blank" title="Find More">MapleLeaf <?php echo MP_VERSION;?></a></div><!-- footer -->

	<!-- jqModal window -->
	<div class="jqmWindow" id="ex2">
	Please wait...
	</div>
	<!-- end of jqModal window -->
    </div>
</body>
</html>