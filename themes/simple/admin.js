$(document).ready(function() {
	$("td.left >span").addClass("hidden");
	$("td.left").hover(function(){
			$(this).children("span").removeClass("hidden");
		},
		function(){
			$(this).children("span").addClass("hidden");
		}
	);
});
function selectTag(showContent,selfObj){
	
	//alert(selfObj+showContent);
	// 操作标签
	var tag = document.getElementById("tags").getElementsByTagName("li");
//	alert(tag);
	var taglength = tag.length;
//	alert(taglength);
	for(i=0; i<taglength; i++){
		tag[i].className = "";
	}
	selfObj.parentNode.className = "selectTag";
	// 操作内容
	for(i=0; j=document.getElementById("tagContent"+i); i++){
		j.style.display = "none";
	}
	document.getElementById(showContent).style.display = "block";
	
	
}

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