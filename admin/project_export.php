<?php 
$output='';
require('config.php');
echo "<style>table,th,td{border:1px solid black}</style>";
if(isset($_REQUEST['Project_Export']))
{
		$project_id=$_REQUEST['project_id'];
		$i=0;$project_flag=1;
		$proj_names=mysqli_query($con,"select * from projects where id=$project_id");
		while($proj_name=mysqli_fetch_array($proj_names))
		{
			$output.="<table><tr><th>Project: ".$heading=$proj_name['name']."<th><tr><tr><th>Members";
		}
		$get_teams=mysqli_query($con,"select * from project_assigned_to where project_id=$project_id");
		while($get_team=mysqli_fetch_array($get_teams))
		{
			$get_names=mysqli_query($con,"select * from team where id=".$get_team['team_id']);
			while($get_name=mysqli_fetch_array($get_names))
			{
				$output.="<tr><td>".$members[$i++]=$get_name['name'];
			}
		}
		$output.="</table><table><tr><th>SN.<th>Description<th>Status<th>Spent_hrs<tr>";
		$get_tasks=mysqli_query($con,"select * from history where project_id=$project_id");$si=0;
		while($get_task=mysqli_fetch_array($get_tasks))
		{
			$output.='<td>'.++$si.'</td>';
			$output.='<td>'.$get_task['description'].'</td>';
			$output.='<td>'.$get_task['status'].'</td>';
			$output.='<td>'.str_replace('.', ':', $get_task['spent_hrs']).'</td></tr>';
		}
		$output.='</table>';
		header("Content-Type: application/xls");
		header("Content-Disposition: attachment; filename=report.xls");
		echo $output;
}
if(isset($_REQUEST['Team_Export']))
{
	$team_id=$_REQUEST['team_id'];
	$project_id=$_REQUEST['project_id'];
	$output='<table><tr><th>Name: ';
	$names=mysqli_query($con,"select * from team where id=$team_id");
		while($name=mysqli_fetch_array($names))
			{
				$output.=$name['name'];
			}
			$output.='<tr><th>Project: ';
			$project=mysqli_query($con,"select * from projects where id=$project_id");
			while($pro_name=mysqli_fetch_array($project))
			{
				$output.=$pro_name['name'];
			}
			$output.='</table><table><tr><th>Sn<th>Description<th>Status<th>Hrs<tr>';
			$get_tasks=mysqli_query($con,"select * from history where team_id=$team_id and project_id=$project_id");$i=0;
			while($get_task=mysqli_fetch_array($get_tasks))
			{
				$output.='<td>'.++$i.'</td>';
				$output.='<td>'.$get_task['description'].'</td>';
				$output.='<td>'.$get_task['status'].'</td>';
				$output.='<td>'.str_replace('.', ':', $get_task['spent_hrs']).'</td></tr>';
			}
			$output.='</table>';
		header("Content-Type: application/xls");
		header("Content-Disposition: attachment; filename=report.xls");
			echo $output;
}
if(isset($_REQUEST['Date_Export']))
{
	$from=$_REQUEST['from'];
	$to=$_REQUEST['to'];
	$name=mysqli_query($con,"select * from history where created_date between '$from' and '$to' group by team_id");
	while($name_row=mysqli_fetch_array($name))
	{
		$output='<table><tr><th colspan="4">Name: ';
		$get_name=mysqli_query($con,"select * from team where id=".$name_row['team_id']);
		while($get_name_row=mysqli_fetch_array($get_name)){$output.=$get_name_row['name'];}
		$output.='<th>Total Hrs: ';
		$total_hours=mysqli_query($con,"select spent_hrs from history where team_id=".$name_row['team_id']." and created_date between '$from' and '$to'");
						$total_seconds=0;
				while($t=mysqli_fetch_array($total_hours))
			 	{
			 		$seconds=0;
			 		$hour=floor($t[0]);
			 		$seconds=$seconds+$hour*60*60;
			 		$min=strstr($t[0],'.');
			 		$seconds=$seconds+$min*100*60;
			 		$total_seconds=$total_seconds+$seconds;
			 	}
			 	if($total_seconds>86400){
			 	$output.=gmdate('d',$total_seconds)." Days ";
			 	$output.=gmdate('H:i',$total_seconds)." hrs</tr>";
			 	}else{
			 	$output.=gmdate('H:i',$total_seconds)." hrs</tr>";
			 	}
			 	$output.='<tr><th>Date<th>Project<th>Task<th>Description<th>Hrs<tr>';
			 	$each=mysqli_query($con,"select * from history where team_id=".$name_row['team_id']." and created_date between '$from' and '$to'");
			while($each_row=mysqli_fetch_array($each))
			{
				$output.='<td>'.date("d-m-Y", strtotime($each_row['created_date'])).'</td>';
				$project_name=mysqli_query($con,"select * from projects where id=".$each_row['project_id']);
			 	while($project_name_row=mysqli_fetch_array($project_name)){
				$output.='<td>'.$project_name_row['name'].'</td>';}
				$tasks=mysqli_query($con,"Select * from task_rows where id=".$each_row['task_row_id']);
			 		while($task=mysqli_fetch_array($tasks)){
				$output.='<td>'.$task['title'].'</td>';}
				$output.='<td>'.$each_row['description'].'</td>';
				$output.='<td>'.str_replace('.', ':', $each_row['spent_hrs']).'</td></tr>';
			}
			$output.='</table><br><br>';
			header("Content-Type: application/xls");
			header("Content-Disposition: attachment; filename=report.xls");
			echo $output;
	}
}
if(isset($_REQUEST['Project_Date_Export']))
{
	$from=$_REQUEST['from'];
	$to=$_REQUEST['to'];
	$project_id=$_REQUEST['project_id'];
	$output='<table><tr><th colspan="4">Project: ';
	$name=mysqli_query($con,"select * from history where project_id=$project_id and created_date BETWEEN '$from' AND '$to' group by project_id");
	while($name_row=mysqli_fetch_array($name))
		{
			$get_name=mysqli_query($con,"select * from projects where id=".$name_row['project_id']);
						while($get_name_row=mysqli_fetch_array($get_name)){$output.=$get_name_row['name'];
						$output.='<th>Total Hrs: ';
		$total_hours=mysqli_query($con,"select spent_hrs from history where project_id=".$name_row['project_id']." and created_date between '$from' and '$to'");
						$total_seconds=0;
				while($t=mysqli_fetch_array($total_hours))
			 	{
			 		$seconds=0;
			 		$hour=floor($t[0]);
			 		$seconds=$seconds+$hour*60*60;
			 		$min=strstr($t[0],'.');
			 		$seconds=$seconds+$min*100*60;
			 		$total_seconds=$total_seconds+$seconds;
			 	}
			 	if($total_seconds>86400){
			 	$output.=gmdate('d',$total_seconds)." Days ";
			 	$output.=gmdate('H:i',$total_seconds)." hrs</tr>";
			 	}else{
			 	$output.=gmdate('H:i',$total_seconds)." hrs</tr>";
			 	}
					}
					$output.='<tr><th>Date<th>Team<th>Task<th>Description<th>Hrs</tr>';
		$each=mysqli_query($con,"select * from history where project_id=".$name_row['project_id']." and created_date between '$from' and '$to'");
			while($each_row=mysqli_fetch_array($each))
			{
				$output.='<td>'.date("d-m-Y", strtotime($each_row['created_date'])).'</td>';
				$project_name=mysqli_query($con,"select * from team where id=".$each_row['team_id']);
			 	while($project_name_row=mysqli_fetch_array($project_name)){
				$output.='<td>'.$project_name_row['name'].'</td>';}
				$tasks=mysqli_query($con,"Select * from task_rows where id=".$each_row['task_row_id']);
			 		while($task=mysqli_fetch_array($tasks)){
				$output.='<td>'.$task['title'].'</td>';}
				$output.='<td>'.$each_row['description'].'</td>';
				$output.='<td>'.str_replace('.', ':', $each_row['spent_hrs']).'hrs</td></tr>';
			}
		$output.='</table>';
		}
		header("Content-Type: application/xls");
		header("Content-Disposition: attachment; filename=report.xls");
	echo $output;
}
if(isset($_REQUEST['Team_Date_Export']))
{
	$from=$_REQUEST['from'];
	$to=$_REQUEST['to'];
	$team_id=$_REQUEST['team_id'];
	$output='<table><tr><th colspan="4">Name: ';
	$name=mysqli_query($con,"select * from history where team_id=$team_id and created_date BETWEEN '$from' AND '$to' group by team_id");
	while($name_row=mysqli_fetch_array($name))
	{
		$get_name=mysqli_query($con,"select * from team where id=".$name_row['team_id']);
						while($get_name_row=mysqli_fetch_array($get_name)){$output.=$get_name_row['name'];}
		$output.='<th>Total Hrs: ';
		$total_hours=mysqli_query($con,"select spent_hrs from history where team_id=".$name_row['team_id']." and created_date between '$from' and '$to'");
						$total_seconds=0;
				while($t=mysqli_fetch_array($total_hours))
			 	{
			 		$seconds=0;
			 		$hour=floor($t[0]);
			 		$seconds=$seconds+$hour*60*60;
			 		$min=strstr($t[0],'.');
			 		$seconds=$seconds+$min*100*60;
			 		$total_seconds=$total_seconds+$seconds;
			 	}
			 	if($total_seconds>86400){
			 	$output.=gmdate('d',$total_seconds)." Days ";
			 	$output.=gmdate('H:i',$total_seconds)." hrs</tr>";
			 	}else{
			 	$output.=gmdate('H:i',$total_seconds)." hrs</tr>";
			 	}
			 	$output.='<tr><th>Date<th>Team<th>Task<th>Description<th>Hrs</tr>';
			 	$each=mysqli_query($con,"select * from history where team_id=".$name_row['team_id']." and created_date between '$from' and '$to'");
			while($each_row=mysqli_fetch_array($each))
			{
				$output.='<td>'.date("d-m-Y", strtotime($each_row['created_date'])).'</td>';
				$project_name=mysqli_query($con,"select * from projects where id=".$each_row['project_id']);
			 	while($project_name_row=mysqli_fetch_array($project_name)){
				$output.='<td>'.$project_name_row['name'].'</td>';}
				$tasks=mysqli_query($con,"Select * from task_rows where id=".$each_row['task_row_id']);
			 		while($task=mysqli_fetch_array($tasks)){
				$output.='<td>'.$task['title'].'</td>';}
				$output.='<td>'.$each_row['description'].'</td>';
				$output.='<td>'.str_replace('.', ':', $each_row['spent_hrs']).'Hrs</td></tr>';
			}
	}
	header("Content-Type: application/xls");
	header("Content-Disposition: attachment; filename=report.xls");
	echo $output;
}
if(isset($_REQUEST['Project_Team_Date_Export']))
{
	$from=$_REQUEST['from'];
	$to=$_REQUEST['to'];
	$team_id=$_REQUEST['team_id'];
	$project_id=$_REQUEST['project_id'];
	$name=mysqli_query($con,"select * from history where team_id=$team_id and project_id=$project_id and created_date BETWEEN '$from' AND '$to' group by team_id");
	$output='<table><tr><th colspan="4">Name: ';
	while($name_row=mysqli_fetch_array($name))
	{
		$get_name=mysqli_query($con,"select * from team where id=".$name_row['team_id']);
						while($get_name_row=mysqli_fetch_array($get_name)){$output.=$get_name_row['name'];}
		$output.='<th>Total Hrs: ';
		$total_hours=mysqli_query($con,"select spent_hrs from history where team_id=".$name_row['team_id']." and project_id=$project_id and created_date between '$from' and '$to'");
						$total_seconds=0;
				while($t=mysqli_fetch_array($total_hours))
			 	{
			 		$seconds=0;
			 		$hour=floor($t[0]);
			 		$seconds=$seconds+$hour*60*60;
			 		$min=strstr($t[0],'.');
			 		$seconds=$seconds+$min*100*60;
			 		$total_seconds=$total_seconds+$seconds;
			 	}
			 	if($total_seconds>86400){
			 	$output.=gmdate('d',$total_seconds)." Days ";
			 	$output.=gmdate('H:i',$total_seconds)." hrs</tr>";
			 	}else{
			 	$output.=gmdate('H:i',$total_seconds)." hrs</tr>";
			 	}
			 	$output.='<th>Date<th>Project<th>Task<th>Description<th>Hrs</tr>';
			 	$each=mysqli_query($con,"select * from history where team_id=".$name_row['team_id']." and project_id=$project_id and created_date between '$from' and '$to'");
			while($each_row=mysqli_fetch_array($each))
			{
				$output.='<td>'.date("d-m-Y", strtotime($each_row['created_date'])).'</td>';
				$project_name=mysqli_query($con,"select * from projects where id=".$each_row['project_id']);
			 	while($project_name_row=mysqli_fetch_array($project_name)){
				$output.='<td>'.$project_name_row['name'].'</td>';}
				$tasks=mysqli_query($con,"Select * from task_rows where id=".$each_row['task_row_id']);
			 		while($task=mysqli_fetch_array($tasks)){
				$output.='<td>'.$task['title'].'</td>';}
				$output.='<td>'.$each_row['description'].'</td>';
				$output.='<td>'.str_replace('.', ':', $each_row['spent_hrs']).'hrs</td></tr>';
			}
	}
	header("Content-Type: application/xls");
	header("Content-Disposition: attachment; filename=report.xls");
	echo $output;
}
 ?>

