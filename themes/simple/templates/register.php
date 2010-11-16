<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="Cache-Control" content="no-cache,must-revalidate" />
<meta http-equiv="expires" content="0" />
<title>注册用户</title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    $('<input type="hidden" name="ajax" value="true" />').insertAfter('#user');
    $.ajax({
		    type: "GET",
		    url: 'index.php',
		    data: { controller:"site",action: "getSysJSON" },
		    success: function(data){ languageTips=data;},
		    dataType: 'json'
		});

    $('#registerForm').submit(function(){
	var user=$('#user').val();
	var password=$('#password').val();
	var email=$('#email').val();
	//return false;
	if(!$.trim(user)){
	    $('#login_error').html(languageTips.USERNAME_NOT_EMPTY);return false;
	}
	if(!$.trim(password)){
	    $('#login_error').html(languageTips.PWD_NOT_EMPTY);return false;
	}
	if(!$.trim(email)){
	    $('#login_error').html(languageTips.EMAIL_INVALID);return false;
	}
	$.ajax({
		type: "POST",
		url: "index.php?controller=user&amp;action=register",
		data: $(this).serialize(),
		success: function(data){
			$('#login_error').html('');
			if(data != "OK"){
				$('#login_error').html(data);
			}else{
			    //window.location.reload();
			    parent.document.location.reload();
			    //document.location.re
			}
			return false;
		}
	});
	return false;
    });
});
</script>
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