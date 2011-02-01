$(document).ready(function() {
	$.ajax({ type: "GET", url: 'index.php', data: { action: "getSysJSON" }, success: function(data){ languageTips=data;}, dataType: 'json'});
        $('span.check_span').show();
	/* jqModal */
	$('#ex2').jqm({ajax: '@href', trigger: 'a.ex2trigger'});
	$('#deleteallLink').click(function(){ if(window.confirm(languageTips.DEL_ALLUSER_CONFIRM)){window.open('index.php?controller=user&action=deleteAll','_self'); }return false;});
	$('#m_checkall').click(function(){$("input[name='select_uid[]']").each(function(){$(this).attr('checked',true)});});
	$('#m_checknone').click(function(){$("input[name='select_uid[]']").each(function(){$(this).attr('checked',false)});});
	$('#m_checkxor').click(function(){$("input[name='select_uid[]']").each(function(){$(this).attr('checked',!$(this).attr('checked'))});});
});