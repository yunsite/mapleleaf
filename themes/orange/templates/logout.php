<html>
<head>
<meta http-equiv="content-type" content="txt/html;charset=utf-8" />
<title>管理退出</title>
<link rel="stylesheet" type="text/css" href="<?php echo './themes/'.$this->_theme.'/logout.css';?>"  />
        <link href="<?php echo $this->_themes_directory; ?><?php echo $this->_theme; ?>/Skin/Orange/style.css" rel=stylesheet />
<script type="text/javascript" src="./includes/logout.js"></script>
<?php if($old_user==true)
{
	?>
        <meta http-equiv="Refresh" content="5;URL=index.php" />
<?php }?>
</head>
<body>
            <center>

            <table cellSpacing=0 cellPadding=0 width=728 bgColor=#ffffff border=0>
                <tbody>
                    <tr>
                        <td width=267><img height=80 src="<?php echo $this->_themes_directory; ?><?php echo $this->_theme; ?>/images/logo.gif" width=260 /></TD>
                        <td colspan="3">
                            <div id=Layer4
                                 style="Z-INDEX: 4; LEFT: 0px; POSITION: relative; TOP: 0px">
                                    <img height=80 src="<?php echo $this->_themes_directory; ?><?php echo $this->_theme; ?>/Skin/ads.jpg" width=460 border=0 />
                            </div>
                                <div id=Layer5
                                     style="Z-INDEX: 5; LEFT: 0px; POSITION: absolute; TOP: 0px">

                                </div>
                        </td>
                    </tr>

        </tbody></table>
</center>
<div id="lay">
<h1>退出管理</h1>
<?php if($old_user==true){?>
您已成功退出.<img src='<?php echo $this->_smileys_dir;?>smile.gif'alt="smile">
您将在 <span id="sss"></span> 秒后返回主页
<script type="text/javascript">
counts();
</script>
<?php }else{?>
您没有登录，所以您无需退出。 <img src='<?php echo $this->_smileys_dir;?>smile.gif'alt="smile">
<a href="./index.php">返回留言板首页</a>
<?php }?>

</div>
<div id="buttom_div">
Powered by MapleLeaf <?php echo MP_VERSION;?> 
</div>
</body>
</html>