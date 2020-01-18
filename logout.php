<?php 
session_start();
$_SESSION['u_id']="";
session_destroy();
header("location:login.php?s=fail");
 ?>