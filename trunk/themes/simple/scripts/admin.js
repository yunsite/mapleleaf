$(document).ready(function() {
        var containerDiv=new Array('overviewContainer','configContainer','message_container','ip_container');
	$.ajax({type: "GET", url: 'index.php', data: {action: "getSysJSON"}, success: function(data){languageTips=data;}, dataType: 'json'});
        $('span.check_span').show();
	/* jqModal */
	$('#ex2').jqm({ajax: '@href', trigger: 'a.ex2trigger'});
	$('#deleteallLink').click(function(){if(window.confirm(languageTips.DEL_ALL_CONFIRM)){window.open('index.php?controller=post&action=deleteAll','_self');}return false;});
	$('#deleteallreplyLink').click(function(){if(window.confirm(languageTips.DEL_ALL_REPLY_CONFIRM)){window.open('index.php?controller=reply&action=deleteAll','_self');}return false;});
	$('#tags li').click(function(){
            var indexOfTag=$('#tags li').index(this);
            if($('#'+containerDiv[indexOfTag]).length){
                $('#tags li:eq('+indexOfTag+') a').attr('href','javascript:void(0)');
                $('#tags li').removeClass('selectTag');
                $(this).addClass('selectTag');$('#tagContent div').hide();
                $('#'+containerDiv[indexOfTag]).show();
                $('#'+containerDiv[indexOfTag]+' div').show();
            }
                //$(this).
            
        });
	$("td.admin_message div >span").addClass("hidden");
	$("td.admin_message div").hover(function(){$(this).children("span").removeClass("hidden");},function(){$(this).children("span").addClass("hidden");});
	$('#m_checkall').click(function(){$("input[name='select_mid[]']").each(function(){$(this).attr('checked',true)});});
	$('#m_checknone').click(function(){$("input[name='select_mid[]']").each(function(){$(this).attr('checked',false)});});
	$('#m_checkxor').click(function(){$("input[name='select_mid[]']").each(function(){$(this).attr('checked',!$(this).attr('checked'))});});
	$('#ip_checkall').click(function(){$("input[name='select_ip[]']").each(function(){$(this).attr('checked',true)});});
	$('#ip_checknone').click(function(){$("input[name='select_ip[]']").each(function(){$(this).attr('checked',false)});});
	$('#ip_checkxor').click(function(){$("input[name='select_ip[]']").each(function(){$(this).attr('checked',!$(this).attr('checked'))});});
});