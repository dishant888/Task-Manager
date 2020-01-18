<?php 
session_start();
require('config.php');
$task_row_id=$_REQUEST['task_row_id'];
$id=$_REQUEST['project_id'];
$status=$_REQUEST['status_selection'];
$date=$_REQUEST['update_date'];
$description=$_REQUEST['description'];
$hours=$_REQUEST['hours'];
// $hours=str_replace(':', '.', $hours);
// $by=$_REQUEST['completed_by'];
mysqli_query($con,"update task_rows set status='$status',spent_hrs=$hours where id=$task_row_id");
mysqli_query($con,"insert into history(team_id,project_id,task_row_id,status,description,spent_hrs,created_date) values(".$_SESSION['u_id'].",$id,$task_row_id,'$status','$description',$hours,'$date')");
header("location:project_detailes.php?id=$id&success=1");
 ?>