<?php
//@ob_end_clean();
session_start();
$old_user='';
if(isset($_SESSION['admin']))
{
	$old_user=$_SESSION['admin'];
	unset($_SESSION['admin']);
	session_destroy();
}
?>
<html>
<head>
<meta http-equiv="content-type" content="txt/html;charset=utf-8" />
<title>管理退出</title>
<?php
if($old_user)
{
	echo '<meta http-equiv="Refresh" content="5;URL=../index.php" />';
}
?>
<style type="text/css">
body{
text-align:center;
}
#lay
{
margin: 0px auto;
border:#66CCFF;
border-width:thin;
margin-top:100px;
margin-left:auto;
margin-right:auto;
width:600px;
text-align:center;
}
</style>
</head>
<body>
<div id="lay">
<h1>退出管理</h1>
<?php
if($old_user)
{
	echo "您已成功退出.<img src='../smileys/images/smile.gif'>";
?>
您将在 <span id="sss"></span> 秒后返回主页
<script type="text/javascript">
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
counts();
</script>
<?php
}
else
{
echo "您没有登录，所以您无需退出。 <img src='../smileys/images/smile.gif'>";
?>
<a href="../index.php">返回留言板首页</a>
<?php
}
?>
</div>
</body>
</html>
