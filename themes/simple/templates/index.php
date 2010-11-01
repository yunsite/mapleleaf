<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="Cache-Control" content="no-cache,must-revalidate" />
<meta http-equiv="expires" content="0" />
<link rel="stylesheet" href="<?php echo './themes/'.$this->_theme.'/common.css';?>" type="text/css"/>
<link rel="stylesheet" href="<?php echo './themes/'.$this->_theme.'/smiley_common.css';?>" type="text/css"/>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo './themes/'.$this->_theme.'/index.js';?>"></script>
<title><?php echo sprintf($this->t('WELCOME'),$this->_board_name);?></title>
<script type="text/javascript">
//<![CDATA[
function checkall() {
	var user = document.getElementById('user').value;
	
	var content = document.getElementById('content').value;
	
	if (user == "") {
		document.getElementById("user_msg").innerHTML = "<font color='red'><?php echo $this->t('USERNAME_NOT_EMPTY');?></font>";
		return false;
	}
	if (user.length < 2) {
		document.getElementById("user_msg").innerHTML = "<font color='red'><?php echo $this->t('USERNAME_TOO_SHORT');?></font>";
		return false;
	}
	if(content.length=="")
	{
		alert("<?php echo $this->t('MESSAGE_NOT_EMPTY');?>");
		document.getElementById('content').focus;
		return false;
	}
	if(document.getElementById('valid_code') != null)
	{
		if(document.getElementById('valid_code').value=="")
		{
			alert("<?php echo $this->t('CAPTCHA_NOT_EMPTY');?>");
			return false;
		}
	}
	return true;
}
//]]>
</script>
</head>

<body>
<div id="container">
    <div style="float: right">
	<?php
	if(!isset ($_SESSION['admin']) && !isset ($_SESSION['user'])){
	    echo '<a href="?action=register">Register</a>&nbsp;<a href="?action=login">Login</a>';;
	}
	if(isset ($_SESSION['user']) || isset ($_SESSION['admin'])){
	    echo '<a href="?action=logout">Logout</a>';
	}
	if(isset ($_SESSION['user'])){
	    echo '&nbsp;<a href="?action=user_update&amp;uid='.$_SESSION['uid'].'">Update</a>';
	}
	?>
    </div>
<h1><?php echo $this->t('WELCOME_POST');?></h1>
<table id="main_table" cellspacing="0" >
	<tr class="header">
		<td class="nickname"><?php echo $this->t('NICKNAME');?></td>
		<td class="message"><?php echo $this->t('MESSAGE');?></td>
		<td><?php echo $this->t('TIME');?></td>
	</tr>
	<?php foreach($data as $m){?>
    <tr class='message'>
    	<td class='left'><?php echo str_replace('Admin',"<font color='red'>Admin</font>",$m['user']);?></td>
        <td class='left'><div style="word-wrap: break-word;word-break:break-all;width:450px;"><?php echo $this->parse_smileys($m['content'],$this->_smileys_dir,$this->_smileys);?><br />
        				 <?php 
        				 	if(@$m['reply']){
        				 ?>
        				 <?php echo sprintf($this->t('ADMIN_REPLIED'),date('m-d H:i',(int)$m['reply']['reply_time']+$this->_time_zone*60*60),$this->parse_smileys($m['reply']['reply_content'],$this->_smileys_dir,$this->_smileys));?>
        				  
        				 <?php 
        				 }
        				 ?>
	    </div>
        </td>
        <td class='center'><?php echo date('m-d H:i',$m['time']+$this->_time_zone*60*60);?></td>
    </tr>
    <?php }?>
    <?php if($this->_page_on){?>
		<tr ><td colspan='3'   class="pager">
		
		<?php echo sprintf($this->t('PAGE_NAV'),$nums,$pages);?>
				
		<?php for($i=0;$i<$pages;$i++){?>
			<a href='index.php?pid=<?php echo $i;?>'>
                        <?php 
                            if($i==$current_page)
                            {
                                echo '<font size="+2">'.($i+1)."</font>";
                            }
                            else
                            { echo $i+1;}
                        ?>
                        </a>&nbsp;
        <?php }?>

		</td></tr>
	<?php }?>
</table>
<br />


<div align="center" id="pleasepost"><?php echo $this->t('CLICK_POST');?>&nbsp;</div>
<form id="guestbook" name="guestbook" action="index.php?action=post" method="post">
    <input id="pid" type="hidden" name="pid" value="<?php echo $_GET['pid'];?>" />
<table id="add_table">
	<tr>
		<td class="alignright"><?php echo $this->t('NICKNAME');?></td>
		<td class="alignleft"><?php if($admin == true){?>
        				<input name="user" id="user" type="hidden" maxlength="10" value="Admin" /><font color="red">Admin</font>
					<?php }elseif(isset($_SESSION['user'])){ ?>
					<input name="user" id="user" type="hidden" maxlength="10" value="<?php echo $_SESSION['user'];?>" /><font color="blue"><?php echo $_SESSION['user'];?></font>
						<?php }else{?>
                        <input name="user" id="user" type="text" maxlength="10"  onfocus="clear_user()" value="anonymous" />
					<?php }?>
		</td>
		<td class="left">&nbsp;
		<div id="user_msg"></div>
		</td>
	</tr>
	<tr>
		<td class="alignright" valign="top"><?php echo $this->t('CONTENT');?></td>
		<td class="left">

		<textarea id="content" name="content" cols="45" rows="8" ></textarea></td>
		<td  valign="top">
		<div id="smileys"><?php echo $smileys;?></div>&nbsp;
		</td>
	</tr>
	<?php if($this->_valid_code_open){?>
	<tr>
		<td class="l"><?php echo $this->t('VALIDATE_CODE');?></td>
		<td class="left">
			<input id="valid_code" type="text" name="valid_code" size="4" maxlength="4" />&nbsp;<img id="captcha_img" src="index.php?action=showCaptcha" border="0" align="absbottom" title="<?php echo $this->t('CLICK_TO_REFRESH');?>" style="cursor:pointer" alt="<?php echo $this->t('CAPTCHA');?>" />
		</td>
		<td class="left">&nbsp;</td>
	</tr>
	<?php }?>
	<tr>
		<td>&nbsp;</td>
		<td colspan="2" class="left"><input id="submit" name="submit" type="submit"
			value="<?php echo $this->t('SUBMIT');?>" /><font color="green" size="2"><?php echo $this->t('POST_SHORTCUT');?></font></td>
	</tr>

</table>
</form>


    <div class="botton">&nbsp;<?php echo htmlspecialchars_decode($this->_copyright_info);?>&nbsp;<a href="mailto:<?php echo $this->_admin_email;?>"><?php echo $this->t('ADMIN_EMAIL');?></a> <a href="index.php?action=control_panel"><?php echo $this->t('ACP');?></a><br />Powered by <a href="http://mapleleaf.ourplanet.tk" target="_blank" title="Find More">MapleLeaf <?php echo MP_VERSION;?></a>
    </div>
</div><!-- end container -->

</body>
</html>