$(document).ready(function() {
    var d = new Date()
    if(self.location!=parent.location){parent.location.replace(self.location);}
    $.ajax({type: "GET",url: 'index.php',data: {action: "getSysJSON",t:d.getTime()},dataType: 'json',cache:false,contentType: "application/json",success: function(data){languageTips=data;},error: function(xhr, status, error) {alert(error);}});
    //点击表情图案将对应代码写入留言中
    $('#smileys img').click(function(){imgId=String($(this).attr('id'));$('#content').val($('#content').val()+imgId);});
    //鼠标在验证码图案上时，使用小手鼠标手势
    $('#captcha_img').mouseover(function(){$(this).addClass('pointer');});
    //点击验证码，刷新
    $('#captcha_img').click(function(){$(this).attr('src',$(this).attr('src')+'&id='+Math.random());});
    //将代表ajax请求的隐藏字段写入表单
    $('<input type="hidden" name="ajax" value="true" />').insertAfter('#pid');
    /*同时按下 Enter + Ctrl 提交表单*/
    $(document).keypress(function(e){if(e.ctrlKey && e.which == 13 || e.which == 10) {$("#guestbook").submit();} else if (e.shiftKey && e.which==13 || e.which == 10) {$("#guestbook").submit();}});
    //显示默认隐藏的表情图案
    $('#smileys').css('display','block');
    //显示默认隐藏的“点击留言”
    $('#toggleForm').css('display','inline');
    //隐藏留言表单
    $("#add_table").hide();
    $("#search").click(function(){$(this).val('')});
    //显示“Press Ctrl+Enter to post”
    $('#post_shortcut').show();
    //为“点击留言”应用鼠标手势
    $("#toggleForm").hover(function(){$(this).addClass("pointer");});
    //点击“点击留言”，隐藏，显示表单
    $("#toggleForm").click( function() {$("#add_table").animate({height: 'show', opacity: 'show'}, 'slow');$('#toggleForm').fadeOut('slow');});
    var post={
        message:null,
        init:function(){
            $('form#guestbook').submit(function(e){
                e.preventDefault();
                if(post.validate()){
                    $.ajax({
                        type: "POST",
                        url: "index.php?controller=post&action=create",
                        data: $(this).serialize(),
                        beforeSend:function(xhr){
                            post.showInfo();
                            $('input#submit').attr('disabled','disabled');
                        },
                        success: function(data){
                            $('#captcha_img').attr('src',$('#captcha_img').attr('src')+'&id='+Math.random());
                            if(data == "OK"){
                                document.getElementById('guestbook').reset();//重置留言表单
                                post.showSuccess();
                                //刷新留言
                                $.getJSON('index.php',{ajax:'yes',pid:$('#pid').val()},function(data){
                                    $("#main_table tr:not('.header')").remove();
                                    $.each(data.messages,function(i,item){
                                        var trString="<tr>\n<td>"+ ((item.uid>0)?item.b_username:item.user) +"</td>\n<td><div style='word-wrap: break-word;word-break:break-all;width:450px;'>"+item.post_content+"<br />";
                                            if(item.reply){
                                                var _A = [languageTips.ADMIN_NAME_INDEX,item.reply.reply_time,item.reply.reply_content];
                                                var _B = ['{admin_name}','{reply_time}','{reply_content}'];
                                                var _C = languageTips.ADMIN_REPLIED;
                                                for(i=0;i<_A.length;i++){
                                                    var _C=_C.replace(_B[i],_A[i]);
                                                }
                                                trString += _C;
                                            }
                                        trString+="</div></td>\n<td>"+item.time+"</td>\n</tr>\n";
                                        $(".header").after(trString);  
                                    });
                                    //刷新分页（若开启）
                                    if(document.getElementById('pagination')){
                                        $('span#totalNum').html(data.total);
                                        $('span#totalPages').html(data.pagenum);
                                        var pagenumString='';
                                        for (i=0;i<data.pagenum;i++){
                                            pagenumString+= "<a href='index.php?pid="+i+"'>";
                                            if(i==data.current_page){
                                                pagenumString+= "<font size='+2'>"+ (i+1) +"</font>";
                                            }else{
                                                pagenumString+= (i+1);
                                            }
                                            pagenumString+="</a>&nbsp;";
                                        }
                                        $('span#pagenumString').html(pagenumString);
                                    };
									prettyPrint();//页面刷新后执行代码高亮
                                });
                            }else{
                                post.message=data;
                                post.showError();
                            }     
                        },
                        error:post.error,
                        complete:function(){
                            $('input#submit').attr('disabled','');
                        }
                    });
                }else{
                    post.emptyError();
                    post.showError();
                }
            });
        },
        showError:function(){
            $('#returnedError').removeClass('info');
            $('#returnedError').fadeIn("slow");
            $('#returnedError').addClass('error');
            $('#returnedError').html(post.message);
        },
        emptyError:function(){
            $('#returnedError').removeClass('info');
            $('#returnedError').removeClass('error');
            $('#returnedError').removeClass('success');
            $('#returnedError').html('');
        },
        showSuccess:function(){
            $('#returnedError').removeClass('info');
            $('#returnedError').addClass('success');
            $('#returnedError').html(languageTips.POST_OK);
            $('#returnedError').fadeIn("slow");
            $('#returnedError').fadeOut("slow");
        },
        showInfo:function(){
            $('#returnedError').addClass('info');
            $('#returnedError').html(languageTips.SENDING);
        },
        validate:function(){
            post.message='';
            var user = $.trim($('#user').val());
            var content = $.trim($('#content').val());
            if(!user){
                post.message+=languageTips.USERNAME_NOT_EMPTY+"<br />";
            }else{
                if (user.length < 2) {
                    post.message+=languageTips.USERNAME_TOO_SHORT+"<br />";
                }
            }
            if(!content.length){
                post.message+=languageTips.MESSAGE_NOT_EMPTY+'<br />';
            }
            if(document.getElementById('valid_code') && !$.trim($('#valid_code').val())){
                post.message+=languageTips.CAPTCHA_NOT_EMPTY+"<br />";
            }
            if (post.message.length > 0) {
                return false;
            } else {
                return true;
            }
        },
        error: function (xhr) {
            alert(xhr.statusText);
	}
    };
    post.init();

    var closeModal = function(hash){
        var $modalWindow = $(hash.w);
        //$('#jqmContent').attr('src', 'blank.html');
        $modalWindow.fadeOut('2000', function(){
            hash.o.remove();
            //refresh parent
            if (hash.refreshAfterClose === 'true'){
                window.location.href = document.location.href;
            }
        });
    };
    var openInFrame = function(hash){
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
        if (queryString != null && typeof queryString != 'undefined'){
            var queryVarsArray = queryString.split("&");
            for (var i = 0; i < queryVarsArray.length; i++){
                if (unescape(queryVarsArray[i].split("=")[0]) == 'width'){
                    var newWidth = queryVarsArray[i].split("=")[1];
                }
                if (escape(unescape(queryVarsArray[i].split("=")[0])) == 'height'){
                    var newHeight = queryVarsArray[i].split("=")[1];
                }
                if (escape(unescape(queryVarsArray[i].split("=")[0])) == 'jqmRefresh'){
                    // if true, launches a "refresh parent window" order after the modal is closed.
                    hash.refreshAfterClose = queryVarsArray[i].split("=")[1]
                } else{
                    hash.refreshAfterClose = false;
                }
            }
            // let's run through all possible values: 90%, nothing or a value in pixel
            if (newHeight != 0){
                if (newHeight.indexOf('%') > -1){
                    newHeight = Math.floor(parseInt($(window).height()) * (parseInt(newHeight) / 100));
                }
                var newTop = Math.floor(parseInt($(window).height() - newHeight) / 2);
            }else{
                newHeight = $modalWindow.height();
            }
            if (newWidth != 0){
                if (newWidth.indexOf('%') > -1){
                    newWidth = Math.floor(parseInt($(window).width() / 100) * parseInt(newWidth));
                }
                var newLeft = Math.floor(parseInt($(window).width() / 2) - parseInt(newWidth) / 2);
            }else{
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
        else{
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
	prettyPrint();//页面载入后执行代码高亮
});