$(document).ready(function() {
	$.ajax({ type: "GET", url: 'index.php', data: { action: "getSysJSON" }, success: function(data){ languageTips=data;}, dataType: 'json'});
	/* jqModal */
	$('#ex2').jqm({ajax: '@href', trigger: 'a.ex2trigger'});
	$('#deleteallButton').click(function(){ if(window.confirm(languageTips.DEL_ALL_CONFIRM)){window.open('index.php?controller=post&action=deleteAll','_self'); }return false;});
	$('#deleteallreplyButton').click(function(){if(window.confirm(languageTips.DEL_ALL_REPLY_CONFIRM)){ window.open('index.php?controller=reply&action=deleteAll','_self'); } return false;	});
	$('#tags li').click(function(){	$('#tags li a').attr('href','javascript:void(0)'); $('#tags li').removeClass('selectTag');  $(this).addClass('selectTag');$('#tagContent div').hide();var indexOfTag=$('#tags li').index(this);$('#tagContent div:eq('+indexOfTag+')').show(); });
	$("td.admin_message >span").addClass("hidden");
	$("td.admin_message").hover(function(){	$(this).children("span").removeClass("hidden");	},function(){$(this).children("span").addClass("hidden");});
	$('#m_checkall').click(function(){$("input[name='select_mid[]']").each(function(){$(this).attr('checked',true)});});
	$('#m_checknone').click(function(){$("input[name='select_mid[]']").each(function(){$(this).attr('checked',false)});});
	$('#m_checkxor').click(function(){$("input[name='select_mid[]']").each(function(){$(this).attr('checked',!$(this).attr('checked'))});});
	$('#ip_checkall').click(function(){$("input[name='select_ip[]']").each(function(){$(this).attr('checked',true)});});
	$('#ip_checknone').click(function(){$("input[name='select_ip[]']").each(function(){$(this).attr('checked',false)});});
	$('#ip_checkxor').click(function(){$("input[name='select_ip[]']").each(function(){$(this).attr('checked',!$(this).attr('checked'))});});
});