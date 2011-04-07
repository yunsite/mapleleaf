<?php if(!defined('IN_MP')){die('Access denied!');} ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="Cache-Control" content="no-cache,must-revalidate" />
<meta http-equiv="expires" content="0" />
<title><?php echo t('REGISTER');?></title>
<link rel="stylesheet" href="<?php echo './themes/'.ZFramework::app()->theme.'/scripts/';?>blueprint/screen.css" type="text/css" media="screen, projection" />
<link rel="stylesheet" href="<?php echo './themes/'.ZFramework::app()->theme.'/scripts/';?>blueprint/print.css" type="text/css" media="print" />
<!--[if lt IE 8]><link rel="stylesheet" href="<?php echo './themes/'.ZFramework::app()->theme.'/scripts/';?>blueprint/ie.css" type="text/css" media="screen, projection" /><![endif]-->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo './themes/'.ZFramework::app()->theme.'/scripts/register.js';?>"></script>
<style type="text/css"> .container { width: 500px;} </style>
</head>
<body>
    <div class="container">
	    <div class="login_error" id="login_error"><?php echo @$errorMsg;?></div>
	<div class="login">
	    <form id="registerForm" action="index.php?controller=user&amp;action=create" method="post">
                <fieldset>
                    <legend><?php echo t('REGISTER');?></legend>
		<input type="hidden" name="register" value="true" />
		<div class="inputbox">
		    <dl>
			<dt><?php echo t('USERNAME');?></dt>
			<dd><input type="text" name="user" id="user" size="20" onfocus="this.style.borderColor='#F93'" onblur="this.style.borderColor='#888'" />
			</dd>
		    </dl>
		    <dl>
			<dt><?php echo t('PASSWORD');?></dt>
			<dd><input type="password" id="password" name="pwd" size="20" onfocus="this.style.borderColor='#F93'" onblur="this.style.borderColor='#888'" />
			</dd>
		    </dl>
		    <dl>
			<dt><?php echo t('EMAIL');?></dt>
			<dd><input type="text" id="email" name="email" size="20" onfocus="this.style.borderColor='#F93'" onblur="this.style.borderColor='#888'" />
			</dd>
		    </dl>
		</div>
		<div class="butbox">
		    <dl>
                        <dt><input id="submit_button" name="submit" type="submit" value="<?php echo t('REGISTER');?>" />&nbsp;<a href="index.php"><?php echo t('CANCEL');?></a></dt>
		    </dl>
		</div>
                </fieldset>
	    </form>
	</div>

    </div>
</body>
</html>
