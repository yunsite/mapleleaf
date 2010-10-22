$(document).ready(function() {
	$(document).keypress(function(e){
		if(e.ctrlKey && e.which == 13 || e.which == 10) {
			$("#guestbook").submit();
		} else if (e.shiftKey && e.which==13 || e.which == 10) {
			$("#guestbook").submit();
		}
	});
	$('#guestbook').submit(function(){
		checkall();
		$.ajax({
			type: "POST",
			url: "index.php?action=post",
			data: $(this).serialize(),
			success: function(){
			document.getElementById('guestbook').reset();
			$.get('index.php?action=ajaxIndex',{ajax:'yes'},function(data){
			//alert(data);
				$("tr").remove(".message");
				$(".header").after(data);
			 });
		   }
		});
		return false;
	});
	$('#smileys').css('display','block');
	$('#pleasepost').css('display','block');
	initAll();
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
//window.onload=initAll;
function initAll()
{
	//点击表情图像时将对应代码写入留言中
	var smileyLinks=document.getElementsByTagName("A");
	var captchaImg=document.getElementById("captcha_img");
	for(var i=0;i<smileyLinks.length;i++){
		if (smileyLinks[i].id) {
			smileyLinks[i].onclick = insert_smiley;
		}
	}
	//表单提交前执行检查
	//document.getElementById("guestbook").onsubmit=checkall;
	//document.getElementById("content").onkeyup=ctrlEnter;
	
	if(captchaImg){
		captchaImg.onclick=function(){
			captchaImg.src=captchaImg.src+"?";
		}
	}
}
function clear_user() {
	document.getElementById("user_msg").innerHTML = "";
	document.getElementById('user').value = '';
}

function insert_smiley()
{
	document.getElementById("content").value +=" " + this.id;
	return false;
}