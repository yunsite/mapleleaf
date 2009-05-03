// JavaScript Document
function checkall() {
	var user = document.all.user.value;
	var content = document.getElementById('content').value;
	if (user == "") {
		document.getElementById("user_msg").innerHTML = "<font color='red'>用户名不可为空</font>";
		return false;
	}
	if (user.length < 2) {
		document.getElementById("user_msg").innerHTML = "<font color='red'>用户名太短</font>";
		return false;
	}
	if (content == "") {
		document.getElementById("content_msg").innerHTML = "<font color='red'>评论内容不可为空</font>";
		return false;
	}
	/*
	 * if(content.length<6){ document.getElementById("content_msg").innerHTML="<font
	 * color='red'>评论内容太少</font>"; return false; } if(content.length>380){
	 * document.getElementById("content_msg").innerHTML="<font
	 * color='red'>评论内容超出</font>"; return false; }
	 */
	return true;
}

function clear_user() {
	document.getElementById("user_msg").innerText = "";
	document.getElementById('user').value = '';
}

function clear_content() {
	document.getElementById("content_msg").innerText = "";
}

/**
 * 插入表情符号
 * @param smiley
 * @return
 */
function insert_smiley(smiley)
{
	document.guestbook.content.value += " " + smiley;
}
