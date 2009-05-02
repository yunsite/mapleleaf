// JavaScript Document
function login_check()
{
	var username=document.getElementById('user').value;
	var password=document.getElementById('password').value;
	if(username=='')
	{
		alert('用户名不可为空');	
		return false;
	}
	if(password=='')
	{
		alert('密码不可为空');	
		return false;
	}
	return true;
}