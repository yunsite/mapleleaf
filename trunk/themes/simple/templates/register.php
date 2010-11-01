<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="Cache-Control" content="no-cache,must-revalidate" />
<meta http-equiv="expires" content="0" />
<title>注册用户</title>
</head>
<body>
    <div class="main">
	<?php if(@$errorMsg)
	{
	?>
	    <div id="login_error"><?php echo $errorMsg;?><br /></div>
	<?php
	}
	?>

	<div class="login">
	    <form action="index.php?action=register" method="post">
		<input type="hidden" name="register" value="true" />
		<div class="inputbox">
		    <dl>
			<dt>username</dt>
			<dd><input type="text" name="user" id="user" size="20" onfocus="this.style.borderColor='#F93'" onblur="this.style.borderColor='#888'" />
			</dd>
		    </dl>
		    <dl>
			<dt>password</dt>
			<dd><input type="password" id="password" name="pwd" size="20" onfocus="this.style.borderColor='#F93'" onblur="this.style.borderColor='#888'" />
			</dd>
		    </dl>
		    <dl>
			<dt>email</dt>
			<dd><input type="text" id="email" name="email" size="20" onfocus="this.style.borderColor='#F93'" onblur="this.style.borderColor='#888'" />
			</dd>
		    </dl>
						</div>
		<div class="butbox">
		    <dl>
		        <dt><input id="submit_button" name="submit" type="submit" value="Register" /></dt>
		    </dl>
		</div>
	    </form>
	</div>

    </div>
</body>
</html>