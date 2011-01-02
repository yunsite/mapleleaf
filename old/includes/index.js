// JavaScript Document
function checkall() {
	var user = document.getElementById('user').value;
	var content = document.getElementById('content').value;
	if (user == "") {
		document.getElementById("user_msg").innerHTML = "<font color='red'>用户名不可为空</font>";
		return false;
	}
	if (user.length < 2) {
		document.getElementById("user_msg").innerHTML = "<font color='red'>用户名太短</font>";
		return false;
	}
	return true;
}

function clear_user() {
	document.getElementById("user_msg").innerHTML = "";
	document.getElementById('user').value = '';
}

function insert_smiley(smiley)
{
	document.guestbook.content.value += " " + smiley;
}