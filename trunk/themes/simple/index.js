$(document).ready(function() {
	$('<input type="hidden" name="ajax" value="true" />').insertAfter('#user_msg');
	/*同时按下 Enter + Ctrl 提交表单*/
	$(document).keypress(function(e){
		if(e.ctrlKey && e.which == 13 || e.which == 10) {
			$("#guestbook").submit();
		} else if (e.shiftKey && e.which==13 || e.which == 10) {
			$("#guestbook").submit();
		}
	});
	/* 使用 Ajax 提交数据，然后使用 Ajax 刷新显示留言 */
	$('#guestbook').submit(function(e){
		if(!checkall()){
			return false;
		}
		$.ajax({
			type: "POST",
			url: "index.php?action=post",
			data: $(this).serialize(),
			success: function(data){
				$('#captcha_img').attr('src',$('#captcha_img').attr('src')+'&id='+Math.random());
				//alert(data);
				if(data != "OK"){
					alert(data);return false;
				}
				//alert('开始刷新！');
				document.getElementById('guestbook').reset();
				$.get('index.php?action=ajaxIndex',{ajax:'yes'},function(data){
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
	
	if(captchaImg){
		captchaImg.onclick=function(){
			captchaImg.src=captchaImg.src+'&id='+Math.random();
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