<html>
<head>
<meta http-equiv="content-type" content="txt/html;charset=utf-8" />
        <meta http-equiv="pragma" content="no-cache" />
        <meta http-equiv="Cache-Control" content="no-cache,   must-revalidate" />
        <meta http-equiv="expires"   content="0" />
<title><?php echo $this->t('LOGOUT');?></title>
<link rel="stylesheet" type="text/css" href="<?php echo './themes/'.$this->_theme.'/logout.css';?>"  />
<script type="text/javascript" src="./includes/logout.js"></script>
<?php if($old_user==true)
{
	?>
        <meta http-equiv="Refresh" content="5;URL=index.php" />
<?php }?>
</head>
<body>
<div id="lay">
<h1><?php echo $this->t('LOGOUT');?></h1>
<?php if($old_user==true){?>
您已成功退出.<img src='<?php echo $this->_smileys_dir;?>smile.gif'alt="smile">
您将在 <span id="sss"></span> 秒后返回主页
<script type="text/javascript">
counts();
</script>
<?php }else{?>
您没有登录，所以您无需退出。 <img src='<?php echo $this->_smileys_dir;?>wink.gif'alt="wink">
<a href="./index.php">返回留言板首页</a>
<?php }?>

</div>
<div id="buttom_div">
Powered by MapleLeaf <?php echo MP_VERSION;?> Copyright &copy;2008-2010
</div>
</body>
</html>