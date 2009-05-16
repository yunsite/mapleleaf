<?php
session_start();
require('../config.php');
if(isset($_SESSION['admin']) && $_SESSION['admin']==$admin)
{
	header("location:admin.php");
}
else
{
	header("location:login.php");
}
?>
