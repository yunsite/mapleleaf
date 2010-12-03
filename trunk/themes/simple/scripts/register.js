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
        if($.trim(user).length<2){
            $('#login_error').html(languageTips.USERNAME_TOO_SHORT);return false;
        }
	if(!$.trim(password)){
	    $('#login_error').html(languageTips.PWD_NOT_EMPTY);return false;
	}
	if(!$.trim(email)){
	    $('#login_error').html(languageTips.EMAIL_INVALID);return false;
	}
	$.ajax({
		type: "POST",
		url: "index.php?controller=user&action=register",
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