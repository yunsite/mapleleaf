<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
      <title><?php echo ZFramework::t('UPDATE');?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  </head>
  <body>
      <?php if(@$errorMsg):?>
	    <div id="login_error"><?php echo $errorMsg;?><br /></div>
	<?php  endif;?>
      <form action="index.php?controller=user&amp;action=update&amp;uid=<?php echo $_GET['uid'];?>" method="post">
	  <div class="inputbox">
		    <dl>
			<dt><?php echo ZFramework::t('USERNAME');?></dt>
			<dd><input type="text" readonly="readonly" value="<?php echo $user_data['user'];?>" name="user" id="user" size="20" onfocus="this.style.borderColor='#F93'" onblur="this.style.borderColor='#888'" />
			</dd>
		    </dl>
		    <dl>
			<dt><?php echo ZFramework::t('PASSWORD');?></dt>
			<dd><input type="password" value="<?php echo $user_data['pwd'];?>" id="password" name="pwd" size="20" onfocus="this.style.borderColor='#F93'" onblur="this.style.borderColor='#888'" />
			</dd>
		    </dl>
		    <dl>
			<dt><?php echo ZFramework::t('EMAIL');?></dt>
			<dd><input type="text" value="<?php echo $user_data['email'];?>" id="email" name="email" size="20" onfocus="this.style.borderColor='#F93'" onblur="this.style.borderColor='#888'" />
			</dd>
		    </dl>
						</div>
		<div class="butbox">
		    <dl>
                        <dt><input id="submit_button" name="submit" type="submit" value="<?php echo ZFramework::t('UPDATE');?>" />&nbsp;<a href="index.php"><?php echo ZFramework::t('CANCEL');?></a></dt>
		    </dl>
		</div>
      </form>
  </body>
</html>