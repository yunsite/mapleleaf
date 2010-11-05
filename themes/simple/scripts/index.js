$(document).ready(function() {
        $.ajax({
		    type: "GET",
		    url: 'index.php',
		    data: { action: "getSysJSON" },
		    success: function(data){ languageTips=data;},
		    dataType: 'json'
		});
	//alert(tsssssssf);
        //点击表情图案将对应代码写入留言中
        $('#smileysTable img').click(function(){
            imgId=String($(this).attr('id'));
            $('#content').html($('#content').val()+imgId);
        });
        //鼠标在验证码图案上时，使用小手鼠标手势
        $('#captcha_img').mouseover(function(){
            $(this).addClass('pointer');
        });
        //点击验证码，刷新
        $('#captcha_img').click(function(){
			$(this).attr('src',$(this).attr('src')+'&id='+Math.random());
	});
        //将代表ajax请求的隐藏字段写入表单
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
		var user = $.trim($('#user').val());
		var content = $.trim($('#content').val());
		//var valid_code=;
		if(!user){
		    $("#user_msg").html("<font color='red'>"+languageTips.USERNAME_NOT_EMPTY+"</font>");
		    return false;
		}
		if (user.length < 2) {
		    $("#user_msg").html("<font color='red'>"+languageTips.USERNAME_TOO_SHORT+"</font>");
		    return false;
		}
		if(!content.length){
			alert(languageTips.MESSAGE_NOT_EMPTY);
			$('#content').focus();
			return false;
		}
		if(document.getElementById('valid_code')){
		    //alert($('#valid_code'));
		    if(!$.trim($('#valid_code').val())){
			alert(languageTips.CAPTCHA_NOT_EMPTY);
			return false;
		    }
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
				$.get('index.php?action=ajaxIndex',{ajax:'yes',pid:$('#pid').val()},function(data){
					//alert(data);
					$("tr").remove(".message");
					$(".header").after(data);
				});
		   }
		});
		return false;
	});
        $('#user').focus(function(){if($(this).val()=='anonymous' ||  !$.trim($(this).val())){$(this).val('');$('#user_msg').html('');}});
        //显示默认隐藏的表情图案
	$('#smileys').css('display','block');
        //显示默认隐藏的“点击留言”
	$('#toggleForm').css('display','inline');
	//隐藏留言表单
	$("#add_table").hide();
        //为“点击留言”应用鼠标手势
	$("#toggleForm").hover(function(){
		$(this).addClass("pointer");
	});
        //点击“点击留言”，隐藏或重新留言表单
	$("#toggleForm").toggle( function() {
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