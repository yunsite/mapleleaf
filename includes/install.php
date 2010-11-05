<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>Installation</title>
   <link rel="stylesheet" href="http://yui.yahooapis.com/2.8.0r4/build/reset-fonts-grids/reset-fonts-grids.css" type="text/css">
   <style type="text/css">
   #custom-doc { width: 62%; min-width: 250px; background-color: #CCCCCC; height: 10em;}
   div{text-align: center; }
   #language{float: right}
   </style>
</head>
<body>
<?php
$en=array(
    'INSTALL_MP'=>'MapleLeaf Installation',
    'ADMIN_USERNAME'=>'Admin Username',
    'ADMIN_PASSWORD'=>'Admin Password',
    'SUBMIT'=>'Install',
    'FINISHED'=>'<p>Installation finished! :) Go <a href="index.php">Index</a>, or Go <a href="index.php?action=control_panel">ACP</a><p>',
);
$zh=array(
    'INSTALL_MP'=>'安装 MapleLeaf',
    'ADMIN_USERNAME'=>'管理员用户名',
    'ADMIN_PASSWORD'=>'管理员密码',
    'SUBMIT'=>'安装',
    'FINISHED'=>'<p>安装完成！现在进入 <a href="index.php">前台</a>，或者登陆 <a href="index.php?action=control_panel">管理面板</a></p>',
);
$languages=array('en','zh');
//var_dump($languages);exit;
if(!isset($_GET['l']) || !in_array($_GET['l'],$languages) || $_GET['l']=='en'){	$language='en';}
else{	$language='zh';}
?>
<div id="custom-doc" class="yui-t7">
    <?php
    if($installed){
	echo str_replace(array_keys($$language),array_values($$language),'FINISHED');
    }else{
    ?>
   <div id="hd" role="banner">
       <div id="language"><a href="?l=en">English</a>&nbsp;<a href="?l=zh">中文</a></div>
       <h1><?php echo str_replace(array_keys($$language),array_values($$language),'INSTALL_MP');?></h1>
   </div>
   <div id="bd" role="main">
	<div class="yui-g">
	<?php
	    $string=<<<EOT
	    <form action="index.php?action=install&l=$language" method="post">
	    ADMIN_USERNAME:<input type="text" name="adminname" /><br />
	    ADMIN_PASSWORD:<input type="password" name="adminpass" /><br />
	    <input type="submit" value="SUBMIT" />
	    </form>
EOT;
	    echo str_replace(array_keys($$language),array_values($$language),$string);
	?>
	</div>

	</div>
    <div id="ft" role="contentinfo"><p>Powered by MapleLeaf <?php echo MP_VERSION;?></p></div>
    <?php }?>
</div>
</body>
</html>