<?php
include 'header.php';
?>
<center>
    <table cellPadding=0 width=730 bgColor=#ffffff border=0>
  <tbody>
    <tr>

      <td width="60%" bgColor=#ebebeb height=2 ></td>
    </tr>
    <tr>
      <td vAlign=bottom height=23 class="TitleBar">
<a href="index.php">返回首页</a>
┊<a href="index.php?action=control_panel">查看状态</a>
┊<a href="index.php?action=control_panel&amp;task=blacklist">黑名单管理</a>
┊<a href="index.php?action=control_panel&amp;task=message">留言管理</a>
┊<a href="index.php?action=control_panel&amp;task=set">修改配置</a>
┊<a href="readme.html" target="_blank">发行说明</a>
┊<a href="index.php?action=logout">退出登录</a>
 </td>
    </tr>
  </tbody>
</table>
<?php
$task_allowed=array('status','blacklist','message','set');
$task=(isset ($_GET['task']) && in_array($_GET['task'], $task_allowed))?$_GET['task']:'';
if(!$task)
{
    $reflect_array=array('ban_ip'=>'blacklist','message'=>'message','siteset'=>'set');
    if(isset ($_GET['subtab'])&& isset ($reflect_array[$_GET['subtab']]))
    {
        $task=$reflect_array[$_GET['subtab']];
    }
    else
    {
        $task='status';
    }
    
}
    include $task.'.php';

?>


<?php include 'powered.php'; ?>
</center>


</body>
</html>