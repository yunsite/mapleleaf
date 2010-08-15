<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="pragma" content="no-cache" />
        <meta http-equiv="Cache-Control" content="no-cache,   must-revalidate" />
        <meta http-equiv="expires"   content="0" />
<title>管理员登录</title>
<link rel="stylesheet" type="text/css" href="<?php echo './themes/'.$this->_theme.'/login.css';?>"  />
<script type="text/javascript" src="./includes/login.js"></script>
</head>
<body>
<p id="backtoindex"><a href="index.php" title="不知道自己在哪？">&larr; 返回留言板</a></p>
        <div class="main">
        
                <div class="title">
                        登陆
                </div>
                <?php if(@$errormsg)
                {
                	?>
						<div id="login_error"><?php echo $errormsg;?><br /></div>
				<?php 
                }
                ?>

                <div class="login">
                <form action="index.php?action=login" method="post" onsubmit="return login_check()">
            <div class="inputbox">
                                <dl>
                                        <dt>用户名</dt>
                                        <dd><input type="text" name="user" id="user" size="20" onfocus="this.style.borderColor='#F93'" onblur="this.style.borderColor='#888'" />
                                        </dd>
                                </dl>
                                <dl>
                                        <dt>密码</dt>
                                        <dd><input type="password" id="password" name="password" size="20" onfocus="this.style.borderColor='#F93'" onblur="this.style.borderColor='#888'" />
                                        </dd>
                                </dl>
                                            </div>
            <div class="butbox">
            <dl>
                                        <dt><input name="submit" type="submit" value="" /></dt>
                        
                                </dl>
                        </div>
                </form>
                </div>  
                
        </div>
        
        <div class="copyright">
                Powered by MapleLeaf <?php echo MP_VERSION;?>  Copyright &copy;2008-2010
        </div>

</body>
</html>
