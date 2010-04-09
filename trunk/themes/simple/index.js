$(document).ready(function() {
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