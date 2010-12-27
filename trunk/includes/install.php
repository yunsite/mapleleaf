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

<div id="custom-doc" class="yui-t7">
    <?php
    if($installed){
        echo ZFramework::t('FINISHED', array(), $language);
    }else{
    ?>
   <div id="hd" role="banner">
       <div id="language"><a href="index.php?action=install&amp;l=en">English</a>&nbsp;<a href="index.php?action=install&amp;l=zh_cn">中文</a></div>
       <h1><?php echo ZFramework::t('INSTALL_MP', array(), $language);?></h1>
   </div>
   <div id="bd" role="main">
	<div class="yui-g">
	<?php
        if(isset ($tips)){
            echo '<font color="red">'.$tips."</font>";
        }else{
        ?>
	    <form action="index.php?action=install&l=$language" method="post">
	    <?php echo ZFramework::t('ADMIN_USERNAME',array(),$language); ?> :<input type="text" name="adminname" /><br />
	    <?php echo ZFramework::t('ADMIN_PASSWORD',array(),$language); ?>:<input type="password" name="adminpass" /><br />
            <?php echo ZFramework::t('DB_NAME',array(),$language); ?>:<input type="text" name="dbname" maxlength="10" /><br />
            <input type="submit" value="<?php echo ZFramework::t('INSTALL', array(), $language);?>" />
	    </form>
        <?php
        }
	?>
	</div>

	</div>
    <div id="ft" role="contentinfo"><p>Powered by MapleLeaf <?php echo MP_VERSION;?></p></div>
    <?php }?>
</div>
</body>
</html>