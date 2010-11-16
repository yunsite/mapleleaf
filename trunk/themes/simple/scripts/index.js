$(document).ready(function() {
    if(self.location!=parent.location){
        parent.location.replace(self.location);
    }
        $.ajax({
		    type: "GET",
		    url: 'index.php',
		    data: { action: "getSysJSON" },
		    success: function(data){ languageTips=data;},
		    dataType: 'json'
		});

        //点击表情图案将对应代码写入留言中
        $('#smileys img').click(function(){
            imgId=String($(this).attr('id'));
            $('#content').val($('#content').val()+imgId);
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
					$("#main_table tr:not('.header')").remove();
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

	var closeModal = function(hash)
	{
	    var $modalWindow = $(hash.w);

	    //$('#jqmContent').attr('src', 'blank.html');
	    $modalWindow.fadeOut('2000', function()
	    {
		hash.o.remove();
		//refresh parent

		if (hash.refreshAfterClose === 'true')
		{

		    window.location.href = document.location.href;
		}
	    });
	};
	var openInFrame = function(hash)
	{
	    var $trigger = $(hash.t);
	    var $modalWindow = $(hash.w);
	    var $modalContainer = $('iframe', $modalWindow);
	    var myUrl = $trigger.attr('href');
	    var myTitle = $trigger.attr('title');
	    var newWidth = 0, newHeight = 0, newLeft = 0, newTop = 0;
	    $modalContainer.html('').attr('src', myUrl);
	    $('#jqmTitleText').text(myTitle);
	    myUrl = (myUrl.lastIndexOf("#") > -1) ? myUrl.slice(0, myUrl.lastIndexOf("#")) : myUrl;
	    var queryString = (myUrl.indexOf("?") > -1) ? myUrl.substr(myUrl.indexOf("?") + 1) : null;
	    //alert(queryString);return;
	    //$modalWindow.jqmShow();return;
	    if (queryString != null && typeof queryString != 'undefined')
	    {
		var queryVarsArray = queryString.split("&");
		for (var i = 0; i < queryVarsArray.length; i++)
		{
		    if (unescape(queryVarsArray[i].split("=")[0]) == 'width')
		    {
			var newWidth = queryVarsArray[i].split("=")[1];
		    }
		    if (escape(unescape(queryVarsArray[i].split("=")[0])) == 'height')
		    {
			var newHeight = queryVarsArray[i].split("=")[1];
		    }
		    if (escape(unescape(queryVarsArray[i].split("=")[0])) == 'jqmRefresh')
		    {
			// if true, launches a "refresh parent window" order after the modal is closed.

			hash.refreshAfterClose = queryVarsArray[i].split("=")[1]
		    } else
		    {

			hash.refreshAfterClose = false;
		    }
		}
		// let's run through all possible values: 90%, nothing or a value in pixel
		if (newHeight != 0)
		{
		    if (newHeight.indexOf('%') > -1)
		    {

			newHeight = Math.floor(parseInt($(window).height()) * (parseInt(newHeight) / 100));

		    }
		    var newTop = Math.floor(parseInt($(window).height() - newHeight) / 2);
		}
		else
		{
		    newHeight = $modalWindow.height();
		}
		if (newWidth != 0)
		{
		    if (newWidth.indexOf('%') > -1)
		    {
			newWidth = Math.floor(parseInt($(window).width() / 100) * parseInt(newWidth));
		    }
		    var newLeft = Math.floor(parseInt($(window).width() / 2) - parseInt(newWidth) / 2);

		}
		else
		{
		    newWidth = $modalWindow.width();
		}
		//$modalWindow.jqmShow();
		// do the animation so that the windows stays on center of screen despite resizing
		//alert(newTop);//return;
		$modalWindow.css({
		    width: newWidth,
		    height: newHeight,
		    opacity: 0
		}).jqmShow().animate({
		    width: newWidth,
		    height: newHeight,
		    top: newTop,
		    left: newLeft,
		    marginLeft: 0,
		    opacity: 1
		}, 'slow');
	    }
	    else
	    {
		// don't do animations
		$modalWindow.jqmShow();
	    }

	}

	$('#modalWindow').jqm({
	    //overlay: 70,
	    //modal: true,
	    trigger: 'a.thickbox',
	    target: '#jqmContent',
	    onHide: closeModal,
	    onShow: openInFrame
	});

});