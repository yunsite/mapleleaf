<form action="index.php?action=reply_update" method="post">
<input type="hidden" name="mid" value="<?php echo $mid;?>" />
<textarea name="reply_content" cols="40" rows="9"><?php echo $reply_data[1];?></textarea>
<br />
<input type="submit" name="Submit" value="提交" /><input type="button" name="cancel" value="取消" onclick="javascript:window.open('index.php?action=control_panel&subtab=message','_self')" />
</form>