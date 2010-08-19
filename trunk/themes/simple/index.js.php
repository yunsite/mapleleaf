<?php ?>

$(document).ready(function() {
	$("#add_table").hide();
	$("#pleasepost").hover(function(){
		$(this).addClass("pointer");
	});
	$("#pleasepost").toggle( function() {
		$("#add_table").animate({
			height: 'show',
			opacity: 'show'
		}, 'slow');
		},
		function() {
		$("#add_table").animate({
			height: 'hide',
			opacity: 'hide'
		}, 'slow');
	});
});
function clear_user() {
	document.getElementById("user_msg").innerHTML = "";
	document.getElementById('user').value = '';
}

function insert_smiley(smiley)
{
	document.guestbook.content.value += " " + smiley;
}
function isKeyTrigger(e,keyCode){
    var argv = isKeyTrigger.arguments;
    var argc = isKeyTrigger.arguments.length;
    var bCtrl = false;
    if(argc > 2){
        bCtrl = argv[2];
    }
    var bAlt = false;
    if(argc > 3){
        bAlt = argv[3];
    }

    var nav4 = window.Event ? true : false;

    if(typeof e == 'undefined') {
        e = event;
    }

    if( bCtrl && 
        !((typeof e.ctrlKey != 'undefined') ? 
            e.ctrlKey : e.modifiers & Event.CONTROL_MASK > 0)){
        return false;
    }
    if( bAlt && 
        !((typeof e.altKey != 'undefined') ? 
            e.altKey : e.modifiers & Event.ALT_MASK > 0)){
        return false;
    }
    var whichCode = 0;
    if (nav4) whichCode = e.which;
    else if (e.type == "keypress" || e.type == "keydown")
        whichCode = e.keyCode;
    else whichCode = e.button;

    return (whichCode == keyCode);
}

function ctrlEnter(e){
    var ie =navigator.appName=="Microsoft Internet Explorer"?true:false; 
    if(ie){
        if(event.ctrlKey && window.event.keyCode==13){doSomething();}
    }else{
        if(isKeyTrigger(e,13,true)){doSomething();}
    }
}
function doSomething()
{
		if(checkall())
		{
			document.guestbook.submit.click();
		}
}
function checkall() {
	var user = document.getElementById('user').value;
	
	var content = document.getElementById('content').value;
	
	if (user == "") {
		document.getElementById("user_msg").innerHTML = "<font color='red'><?php echo $this->t('USERNAME_NOT_EMPTY');?></font>";
		return false;
	}
	if (user.length < 2) {
		document.getElementById("user_msg").innerHTML = "<font color='red'><?php echo $this->t('USERNAME_TOO_SHORT');?></font>";
		return false;
	}
	if(content.length=="")
	{
		alert("<?php echo $this->t('MESSAGE_NOT_EMPTY');?>");
		document.getElementById('content').focus;
		return false;
	}
	if(document.getElementById('valid_code') != null)
	{
		if(document.getElementById('valid_code').value=="")
		{
			alert("<?php echo $this->t('CAPTCHA_NOT_EMPTY');?>");
			return false;
		}
	}
	return true;
} 