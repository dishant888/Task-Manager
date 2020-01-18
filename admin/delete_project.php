<?php 
require('config.php');
$id=$_REQUEST['id'];
mysqli_query($con,"update projects set delete_status=1 where id=$id");
mysqli_query($con,"update project_assigned_to set delete_status=1 where project_id=$id");
mysqli_query($con,"update tasks set delete_status=1 where project_id=$id");
$del=mysqli_query($con,"select * from tasks where project_id=".$id);
while($row=mysqli_fetch_array($del))
{
	mysqli_query($con,"update task_rows set delete_status=1 where task_id=".$row['id']);
}
header("location:add_project.php?s=Deleted");
 ?>