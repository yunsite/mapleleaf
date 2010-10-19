$(document).ready(function() {
	$('#tags >li').click(function(){
    	//remove the selected class from all LI    
    	$('#tags >li').removeClass('selectTag');
    
    	//Reassign the LI
    	$(this).addClass('selectTag');
    
    	//Hide all the DIV in .boxBody
    	//$('#tagContent div').slideUp('1500');
		$('#tagContent div').hide();
    
    	//Look for the right DIV in boxBody according to the Navigation UL index, therefore, the arrangement is very important.
    	//$('#tagContent div:eq(' + $('#tags > li').index(this) + ')').slideDown('1500');
		//alert('#tagContent' + $('#tags > li').index(this));
		$('#tagContent' + $('#tags > li').index(this)).show('slow');
		$('#tagContent' + $('#tags > li').index(this)+' >div').show('slow');
		//alert($('#tags > li').index(this));

  	});
	$("td.left >span").addClass("hidden");
	$("td.left").hover(function(){
			$(this).children("span").removeClass("hidden");
		},
		function(){
			$(this).children("span").addClass("hidden");
		}
	);
});

function changeAllCheckboxes(formid,checked,elementid)
{
	var entriesForm = document.getElementById(formid);
	for (i = 0; i < entriesForm.length; i++) 
	{
		if(entriesForm.elements[i].name == elementid)
		{	
			if(checked=='xor')
			{
				entriesForm.elements[i].checked=!entriesForm.elements[i].checked;
			}
			else
			{
				entriesForm.elements[i].checked = checked;
			}
		}
	}
}