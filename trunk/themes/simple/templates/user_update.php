<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
      <title><?php echo FrontController::t('UPDATE');?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  </head>
  <body>
      <?php if(@$errorMsg)
	{
	?>
	    <div id="login_error"><?php echo $errorMsg;?><br /></div>
	<?php
	}
	?>
      <form action="index.php?controller=user&amp;action=user_update&amp;uid=<?php echo $_GET['uid'];?>" method="post">
	  <div class="inputbox">
		    <dl>
			<dt><?php echo FrontController::t('USERNAME');?></dt>
			<dd><input type="text" readonly="readonly" value="<?php echo $user_data['user'];?>" name="user" id="user" size="20" onfocus="this.style.borderColor='#F93'" onblur="this.style.borderColor='#888'" />
			</dd>
		    </dl>
		    <dl>
			<dt><?php echo FrontController::t('PASSWORD');?></dt>
			<dd><input type="password" value="<?php echo $user_data['pwd'];?>" id="password" name="pwd" size="20" onfocus="this.style.borderColor='#F93'" onblur="this.style.borderColor='#888'" />
			</dd>
		    </dl>
		    <dl>
			<dt><?php echo FrontController::t('EMAIL');?></dt>
			<dd><input type="text" value="<?php echo $user_data['email'];?>" id="email" name="email" size="20" onfocus="this.style.borderColor='#F93'" onblur="this.style.borderColor='#888'" />
			</dd>
		    </dl>
						</div>
		<div class="butbox">
		    <dl>
		        <dt><input id="submit_button" name="submit" type="submit" value="<?php echo FrontController::t('UPDATE');?>" /></dt>
		    </dl>
		</div>
      </form>
  </body>
</html>