<?php 
$id=$_REQUEST['id'];
require('config.php');
mysqli_query($con,"update team set delete_status=1 where id=$id");
header("location:add_team.php?s=deleted");
 ?>