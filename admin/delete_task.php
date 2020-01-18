<?php 
require('config.php');
$task_row_id=$_REQUEST['id'];
mysqli_query($con,"update task_rows set delete_status=1 where id=$task_row_id");
header("location:view_task.php?s=deleted");
 ?>