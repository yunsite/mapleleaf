var interval = 1000;//每次滚动时间间隔
var seq=5;
function counts()
{
	document.getElementById('sss').innerHTML=seq;
	seq--;
	if(seq>=0)
	{
		window.setTimeout("counts();", interval );	
	}
	
}