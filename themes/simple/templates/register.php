<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="Cache-Control" content="no-cache,must-revalidate" />
<meta http-equiv="expires" content="0" />
<title><?php echo $this->t('REGISTER');?></title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo './themes/'.$this->_theme.'/scripts/register.js';?>"></script>
</head>
<body>
    <div class="main">
	    <div class="login_error" id="login_error"><?php echo @$errorMsg;?></div>
	<div class="login">
	    <form id="registerForm" action="index.php?controller=user&amp;action=register" method="post">
		<input type="hidden" name="register" value="true" />
		<div class="inputbox">
		    <dl>
			<dt><?php echo $this->t('USERNAME');?></dt>
			<dd><input type="text" name="user" id="user" size="20" onfocus="this.style.borderColor='#F93'" onblur="this.style.borderColor='#888'" />
			</dd>
		    </dl>
		    <dl>
			<dt><?php echo $this->t('PASSWORD');?></dt>
			<dd><input type="password" id="password" name="pwd" size="20" onfocus="this.style.borderColor='#F93'" onblur="this.style.borderColor='#888'" />
			</dd>
		    </dl>
		    <dl>
			<dt><?php echo $this->t('EMAIL');?></dt>
			<dd><input type="text" id="email" name="email" size="20" onfocus="this.style.borderColor='#F93'" onblur="this.style.borderColor='#888'" />
			</dd>
		    </dl>
						</div>
		<div class="butbox">
		    <dl>
		        <dt><input id="submit_button" name="submit" type="submit" value="<?php echo $this->t('REGISTER');?>" /></dt>
		    </dl>
		</div>
	    </form>
	</div>

    </div>
</body>
</html>