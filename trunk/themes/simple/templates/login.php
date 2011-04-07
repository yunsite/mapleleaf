<?php if(!defined('IN_MP')){die('Access denied!');} ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="Cache-Control" content="no-cache,must-revalidate" />
<meta http-equiv="expires" content="0" />
<title><?php echo t('LOGIN');?></title>
<link rel="stylesheet" href="<?php echo './themes/'.ZFramework::app()->theme.'/scripts/';?>blueprint/screen.css" type="text/css" media="screen, projection" />
<link rel="stylesheet" href="<?php echo './themes/'.ZFramework::app()->theme.'/scripts/';?>blueprint/print.css" type="text/css" media="print" />
<!--[if lt IE 8]><link rel="stylesheet" href="<?php echo './themes/'.ZFramework::app()->theme.'/scripts/';?>blueprint/ie.css" type="text/css" media="screen, projection" /><![endif]-->
<link rel="stylesheet" type="text/css" href="<?php echo './themes/'.ZFramework::app()->theme.'/scripts/login-bp.css';?>"  />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo './themes/'.ZFramework::app()->theme.'/scripts/login.js';?>"></script>
</head>
<body>
<div id="backtoindex"><a href="index.php" title="<?php echo t('WHERE_AM_I');?>">&larr; <?php echo t('BACK');?></a></div>
    <div class="container">
	<?php if(@$errormsg){?>
	    <div id="login_error" class="error"><?php echo $errormsg;?><br /></div>
	<?php } ?>

	<div id="loginForm">
            <form action="<?php echo $_SERVER['PHP_SELF'];?>?controller=user&amp;action=login" method="post">
                <table>
                    <tr>
		    <td><label><?php echo t('USERNAME');?></label></td>
                    <td><input type="text" name="user" id="user" size="20" /></td>
		    </tr>
		    <tr>
                        <td><label><?php echo t('ADMIN_PWD');?></label></td>
			<td><input type="password" id="password" name="password" size="20" /></td>
		    </tr>
                    <tr>
		    <td colspan="2">
		        <input id="submit_button" name="submit" type="submit" value="<?php echo t('SUBMIT');?>" />
		    </td>
                    </tr>
                </table>
	    </form>
	</div>

    </div>
    <div class="copyright">
	    Powered by MapleLeaf <?php echo MP_VERSION;?>  Copyright &copy;2008-2011
    </div>
</body>
</html>
