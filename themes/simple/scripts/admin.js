$(document).ready(function() {
	$.ajax({
		    type: "GET",
		    url: 'index.php',
		    data: { action: "getSysJSON" },
		    success: function(data){ languageTips=data;},
		    dataType: 'json'
		});
	/* Test for jqModal */
	$('#ex2').jqm({ajax: '@href', trigger: 'a.ex2trigger'});
	$('#deleteallButton').click(function(){
	    if(window.confirm(languageTips.DEL_ALL_CONFIRM)){
		window.open('index.php?controller=post&action=deleteAll','_self');
	    }
	    return false;
	});
	$('#deleteallreplyButton').click(function(){
	    if(window.confirm(languageTips.DEL_ALL_REPLY_CONFIRM)){
		window.open('index.php?controller=reply&action=deleteAll','_self');
	    }
	    return false;
	});
	$('#tags li').click(function(){
		$('#tags li a').attr('href','javascript:void(0)');
    	//remove the selected class from all LI    
    	$('#tags li').removeClass('selectTag');
    
    	//Reassign the LI
    	$(this).addClass('selectTag');
    
    	//Hide all the DIV in .boxBody
		$('#tagContent div').hide();
    
    	//Look for the right DIV in boxBody according to the Navigation UL index, therefore, the arrangement is very important.
	    var indexOfTag=$('#tags li').index(this);//alert(indexOfTag);
	    //alert($("#tagContent div:eq("+indexOfTag+")").html());//return;
		$('#tagContent div:eq('+indexOfTag+')').show();
		//$('#tagContent div:eq(indexOfTag) div').show();
		//$('#tagContent' + $('#tags li').index(this)+' div').show();

  	});
	$("td.left >span").addClass("hidden");
	$("td.left").hover(function(){
			$(this).children("span").removeClass("hidden");
		},
		function(){
			$(this).children("span").addClass("hidden");
		}
	);
	$('#m_checkall').click(function(){
		$("input[name='select_mid[]']").each(function(){$(this).attr('checked',true)});
	});
	$('#m_checknone').click(function(){
		$("input[name='select_mid[]']").each(function(){$(this).attr('checked',false)});
	});
	$('#m_checkxor').click(function(){
		$("input[name='select_mid[]']").each(function(){
			$(this).attr('checked',!$(this).attr('checked'))
		});
	});
	$('#ip_checkall').click(function(){
		$("input[name='select_ip[]']").each(function(){$(this).attr('checked',true)});
	});
	$('#ip_checknone').click(function(){
		$("input[name='select_ip[]']").each(function(){$(this).attr('checked',false)});
	});
	$('#ip_checkxor').click(function(){
		$("input[name='select_ip[]']").each(function(){
			$(this).attr('checked',!$(this).attr('checked'))
		});
	});
});