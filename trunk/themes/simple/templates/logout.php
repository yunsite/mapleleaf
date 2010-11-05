<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="txt/html;charset=utf-8" />
<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="Cache-Control" content="no-cache,must-revalidate" />
<meta http-equiv="expires" content="0" />
<title><?php echo $this->t('LOGOUT');?></title>
<link rel="stylesheet" type="text/css" href="<?php echo './themes/'.$this->_theme.'/scripts/logout.css';?>"  />
<script type="text/javascript" src="<?php echo './themes/'.$this->_theme.'/scripts/logout.js';?>"></script>
<?php if($old_user==true)
{
?>
	<meta http-equiv="Refresh" content="5;URL=index.php" />
<?php 
}
?>
</head>
<body>
<div id="lay">
<h1><?php echo $this->t('LOGOUT');?></h1>
<?php if($old_user==true){?>
<?php echo $this->t('LOGOUT_OK');?>
<img src='<?php echo $this->_smileys_dir;?>smile.gif'alt="smile" />
<?php }else{?>
<?php echo $this->t('LOGOUT_NONE');?>
<img src='<?php echo $this->_smileys_dir;?>wink.gif'alt="wink" />

<?php }?>

</div>
<div id="buttom_div">
Powered by MapleLeaf <?php echo MP_VERSION;?> Copyright &copy;2008-2010
</div>
</body>
</html>