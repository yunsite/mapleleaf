$(document).ready(function(){
    $.ajax({ type: "GET",  url: 'index.php', data: { action: "getSysJSON" }, success: function(data){ languageTips=data;}, dataType: 'json'});
	$('form').submit(function(){
	    var username=$('#user').val();
	    var password=$('#password').val();
	    if(!$.trim(username)){
		alert(languageTips.USERNAME_NOT_EMPTY);
		return false;
	    }
	    if(!$.trim(password)){
		alert(languageTips.PWD_NOT_EMPTY);
		return false;
	    }
	    return true;
	});
});