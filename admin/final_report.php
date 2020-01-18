<?php 
require('config.php');
require('header.php');
$members[]="";
$project_flag=0;
$team_flag=0;
$team_project_flag=0;
$heading="";
$date_flag=0;
$date_project_flag=0;
$date_team_flag=0;
$date_project_team_flag=0;
$main_flag=0;
$head="";
?>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
$( ".datepicker" ).datepicker({ dateFormat: 'dd-mm-yy' });
		$('#project').on('change', function(event) {
			var value=$(this).val();
			$.ajax({
					url: 'get_team2.php',
					type: 'GET',
					data: {id: value},
					success:function(data){
					//console.log(data);
					$('#team').html(data);
				}
			});	
		});
	});
</script>
<div class="container-fluid">
	<div class="row">
		<div class="col-12">
			<h1>Daily Status Report</h1><br>
		</div>
		<div class="col-12">
			<form action="final_report.php" method="get" class="form-inline">
		<table class="table border table-responsive-xl">
			<tr>
				<td>
					<select class="form-control" name="project_selection" id="project">
				<option value="0">-------Select Project-------</option>
				<?php 
				$project=mysqli_query($con,"select * from projects where delete_status=0");
				while($project_row=mysqli_fetch_array($project))
				{
					if(isset($_REQUEST['project_selection']))
					{
						if($_REQUEST['project_selection']==$project_row['id'])	
						{
						?>
						 <option value="<?=$project_row['id']?>" selected><?=$project_row['name']?></option>
						<?php	
						}
						else{
						?>
				 <option value="<?=$project_row['id']?>"><?=$project_row['name']?></option>
						<?php		
						}	
					}else{
				 ?>
				 <option value="<?=$project_row['id']?>"><?=$project_row['name']?></option>
				<?php } }?>
			</select>
				</td>
				<td>
					<select class="form-control ml-2" name="team_selection" id="team">
				<option value="0">---Select Team---</option>
				<?php 
				$team=mysqli_query($con,"select * from team where delete_status=0");
				while($team_row=mysqli_fetch_array($team))
				{
					if(isset($_REQUEST['team_selection']))
					{
						if($_REQUEST['team_selection']==$team_row['id'])	
						{
						?>
						 <option value="<?=$team_row['id']?>" selected><?=$team_row['name']?></option>
						<?php	
						}
						else{
							?>
				 <option value="<?=$team_row['id']?>"><?=$team_row['name']?></option>
							<?php
						}	
					}else{
				 ?>
				 <option value="<?=$team_row['id']?>"><?=$team_row['name']?></option>
		  <?php } }?>
			</select>
				</td>
				<td>
					<b>From:</b><input type="text" class="datepicker form-control ml-2" name="from_date" value="<?=date('d-m-Y')?>">
				</td>
				<td>
					<b>To:</b><input type="text" class="datepicker form-control ml-2" name="to_date" value="<?=date('d-m-Y')?>">
				</td>
				<td>
					<input type="submit" name="filter" value="Search" class="btn btn-info ml-2">
				</td>
			</tr>
		</table>
		</form>
	</div>
		
		<div class="col-12">
<?php 
if(isset($_REQUEST['filter']))
{
	$project_id=$_REQUEST['project_selection'];
	$team_id=$_REQUEST['team_selection'];
	$from=$_REQUEST['from_date'];
	$to=$_REQUEST['to_date'];
	if(!empty($from)&&!empty($to))
	{
	$from = date("Y-m-d", strtotime($from));
	$to = date("Y-m-d", strtotime($to));
	}
	if(!empty($project_id)&& empty($team_id)&& empty($to)&& empty($from))
	{$i=0;$project_flag=1;
		$proj_names=mysqli_query($con,"select * from projects where id=$project_id");
		while($proj_name=mysqli_fetch_array($proj_names))
		{
			$heading=$proj_name['name'];
		}
		$get_teams=mysqli_query($con,"select * from project_assigned_to where project_id=$project_id");
		while($get_team=mysqli_fetch_array($get_teams))
		{
			$get_names=mysqli_query($con,"select * from team where id=".$get_team['team_id']);
			while($get_name=mysqli_fetch_array($get_names))
			{
				$members[$i++]=$get_name['name'];
			}
		}
	}
	else if(!empty($team_id)&& empty($project_id)&&empty($to)&& empty($from))
	{$team_flag=1;
		$names=mysqli_query($con,"select * from team where id=$team_id");
		while($name=mysqli_fetch_array($names))
			{$head=$name['name'];}
	}
	else if(!empty($team_id)&&!empty($project_id)&&empty($to)&&empty($from))
	{$team_project_flag=1;
		$proj_names=mysqli_query($con,"select * from projects where id=$project_id");
		while($proj_name=mysqli_fetch_array($proj_names))
		{
			$heading=$proj_name['name'];
		}
		$names=mysqli_query($con,"select * from team where id=$team_id");
		while($name=mysqli_fetch_array($names))
			{$head=$name['name'];}
	}
	else if(!empty($from) && !empty($to) &&empty($project_id)&&empty($team_id))
	{  
	$name=mysqli_query($con,"select * from history where created_date between '$from' and '$to' group by team_id");
	$date_flag=mysqli_num_rows($name);
	}
	else if(!empty($from)&&!empty($to)&&!empty($project_id)&&empty($team_id))
	{
		$name=mysqli_query($con,"select * from history where project_id=$project_id and created_date BETWEEN '$from' AND '$to' group by project_id");
		$date_project_flag=mysqli_num_rows($name);
	}
	else if(!empty($from)&&!empty($to)&&!empty($team_id)&&empty($project_id))
	{
		$name=mysqli_query($con,"select * from history where team_id=$team_id and created_date BETWEEN '$from' AND '$to' group by team_id");
		$date_team_flag=mysqli_num_rows($name);
	}
	else if(!empty($team_id)&&!empty($project_id)&&!empty($to)&&!empty($from))
	{
		$name=mysqli_query($con,"select * from history where team_id=$team_id and project_id=$project_id and created_date BETWEEN '$from' AND '$to' group by team_id");
		$date_project_team_flag=mysqli_num_rows($name);
	}
	else{
		$main_flag=1;
	}
}
?>
<?php if($project_flag==1){ ?>
<br>
<div class="container text-center bg-light border shadow">
	<div class="row">
		<div class="col-6">
			<h3 class="mt-3">Project:<?=" ".$heading?></h3>
		</div>
		<div class="col-6">
			<fieldset class="border">
				<legend class="w-auto">Members</legend>
				<?php $team_count=0;
				foreach ($members as $member) {
					$team_count++;
					if(count($members)!=$team_count)
					echo "<b class='h6'>".$member."</b>,";
					else
						echo "<b class='h6'>".$member."</b>";
				}
				 ?>
			</fieldset>
		</div>
		<div class="col-12">
			<table class="w-100 table-striped mt-3 mb-3 border">
				<thead class="border bg-dark text-white border-dark">
					<td>SN.</td>
					<td>Task</td>
					<td>Status</td>
					<td>Hrs</td>
				</thead>
				<tbody class="table-bordered">
					<tr>
		<?php 
		$get_tasks=mysqli_query($con,"select * from history where project_id=$project_id");$i=0;
		while($get_task=mysqli_fetch_array($get_tasks))
		{
		 ?>
		 <td><?=++$i?></td>
		 <td><?=wordwrap($get_task['description'],75,'<br>',true)?></td>
		 <td><?=$get_task['status']?></td>
		 <td><?=$get_task['spent_hrs']==0?'--':str_replace('.', ':', $get_task['spent_hrs'])." hrs"?></td>
		</tr>
		<?php } if($i==0){echo "<tr><td colspan='5'><h2 class='text-danger'>No Updates...</h2></td></tr>";} ?>
		<?php if($i!=0) {?>
		<tr>
			<td colspan="2"></td>
			<td>Total:</td>
			<td>
				<?php 
				$sum=mysqli_query($con,"select spent_hrs from history where project_id=$project_id");
				$total_seconds=0;
				while($t=mysqli_fetch_array($sum))
			 	{
			 		$seconds=0;
			 		$hour=floor($t[0]);
			 		$seconds=$seconds+$hour*60*60;
			 		$min=strstr($t[0],'.');
			 		$seconds=$seconds+$min*100*60;
			 		$total_seconds=$total_seconds+$seconds;
			 	}
			 	if($total_seconds>86400){
			 	echo gmdate('d',$total_seconds)." Days ";
			 	echo gmdate('H:i',$total_seconds)." hrs";
			 	}else{
			 	echo gmdate('H:i',$total_seconds)." hrs";
			 	}
				 ?>
			</td>
		</tr><?php } ?>
	</tbody>
	</table>
	</div>
	</div>
	<form action="project_export.php" method="post">
		<input type="hidden" name="project_id" value="<?=$project_id?>">
	<input type="submit" name="Project_Export" value="Export" class="btn btn-success"><br><br>
    </form>
</div><br><br>
<?php } ?>
<?php if($team_flag==1){ ?>
	<br>
<div class="container bg-light border shadow">
	<div class="row">
		<div class="col-12">
			<h4 class="mt-3">Name:<?=" ".$head?></h4>
		</div>
		<?php 
		$get_projects=mysqli_query($con,"SELECT `projects`.`name`,`projects`.`id` FROM `projects` INNER JOIN `project_assigned_to` ON `projects`.`id`=`project_assigned_to`.`project_id` WHERE `project_assigned_to`.`team_id`= $team_id group by `projects`.`id`");
		while($get_project=mysqli_fetch_array($get_projects))
		{$pi=$get_project['id'];
		 ?>
		 <div class="col-12">
		 	<h4><?=$get_project['name']?></h4>
		 	<table class="w-100 table-striped mt-3 mb-3 border text-center">
		 		<thead class="border bg-dark text-white border-dark">
		 			<tr>
		 				<td>SN.</td>
		 				<td>Task</td>
		 				<td>Status</td>
		 				<td>Hrs</td>
		 			</tr>
		 		</thead>
		 		<tbody><tr>
		 <?php 
		 $get_tasks=mysqli_query($con,"select * from history where team_id=$team_id and project_id=$pi");$i=0;
		while($get_task=mysqli_fetch_array($get_tasks))
		{
		 	?><td><?=++$i?></td>
		 <td><?=wordwrap($get_task['description'],75,'<br>',true)?></td>
		 <td><?=$get_task['status']?></td>
		 <td><?=$get_task['spent_hrs']==0?'--':str_replace('.', ':', $get_task['spent_hrs'])." hrs"?></td>
		</tr>
		<?php } if($i==0){echo "<tr><td colspan='5'><h2 class='text-danger'>No Updates...</h2></td></tr>";} ?>
		<?php if($i!=0) {?>
		<tr>
			<td colspan="2"></td>
			<td>Total:</td>
			<td>
				<?php 
				$sum=mysqli_query($con,"select spent_hrs from history where team_id=$team_id and project_id=$pi");
				$total_seconds=0;
				while($t=mysqli_fetch_array($sum))
			 	{
			 		$seconds=0;
			 		$hour=floor($t[0]);
			 		$seconds=$seconds+$hour*60*60;
			 		$min=strstr($t[0],'.');
			 		$seconds=$seconds+$min*100*60;
			 		$total_seconds=$total_seconds+$seconds;
			 	}
			 	if($total_seconds>86400){
			 	echo gmdate('d',$total_seconds)." Days ";
			 	echo gmdate('H:i',$total_seconds)." hrs";
			 	}else{
			 	echo gmdate('H:i',$total_seconds)." hrs";
			 	}
				 ?>
			</td>
		</tr>
		<tr>
			<td colspan="20" align="center">
		 	<form action="project_export.php" method="post">
		 		<input type="hidden" name="team_id" value="<?=$team_id?>">
		 		<input type="hidden" name="project_id" value="<?=$pi?>">
		 		<input type="submit" name="Team_Export" value="Export" class="btn btn-success">
		 	</form>
			</td>
		</tr>
	<?php } ?>
		 		</tbody>
		 	</table>
		 </div>
  <?php } ?>
	</div>
</div><br><br>
	<?php } ?>
	<?php if($team_project_flag==1){ ?>
		<div class="container bg-light border shadow mt-3">
			<div class="row">
				<div class="col-6">
					<h3 class="mt-3">Project:<?=" ".$heading?></h3>
				</div>
				<div class="col-6 text-right">
					<h4 class="mt-4"><?=$head?></h4>
				</div>
				<table class="w-100 table-striped mt-3 mb-3 border text-center">
		 		<thead class="border bg-dark text-white border-dark">
		 			<tr>
		 				<td>SN.</td>
		 				<td>Task</td>
		 				<td>Status</td>
		 				<td>Hrs</td>
		 			</tr>
		 		</thead>
		 		<tbody><tr>
		 			<?php 
		 $get_tasks=mysqli_query($con,"select * from history where team_id=$team_id and project_id=$project_id");$i=0;
		while($get_task=mysqli_fetch_array($get_tasks))
		{
		 	?><td><?=++$i?></td>
		 <td><?=wordwrap($get_task['description'],75,'<br>',true)?></td>
		 <td><?=$get_task['status']?></td>
		 <td><?=$get_task['spent_hrs']==0?'--':str_replace('.', ':', $get_task['spent_hrs'])." hrs"?></td>
		</tr>
		<?php } if($i==0){echo "<tr><td colspan='5'><h2 class='text-danger'>No Updates...</h2></td></tr>";} ?>
		<?php if($i!=0) {?>
		<tr>
			<td colspan="2"></td>
			<td>Total:</td>
			<td>
				<?php 
				$sum=mysqli_query($con,"select spent_hrs from history where team_id=$team_id and project_id=$project_id");
				$total_seconds=0;
				while($t=mysqli_fetch_array($sum))
			 	{
			 		$seconds=0;
			 		$hour=floor($t[0]);
			 		$seconds=$seconds+$hour*60*60;
			 		$min=strstr($t[0],'.');
			 		$seconds=$seconds+$min*100*60;
			 		$total_seconds=$total_seconds+$seconds;
			 	}
			 	if($total_seconds>86400){
			 	echo gmdate('d',$total_seconds)." Days ";
			 	echo gmdate('H:i',$total_seconds)." hrs";
			 	}else{
			 	echo gmdate('H:i',$total_seconds)." hrs";
			 	}
				 ?>
			</td>
		</tr>
		<tr>
			<td colspan="20" align="center">
			<form action="project_export.php" method="post">
				<input type="hidden" name="project_id" value="<?=$project_id?>">
				<input type="hidden" name="team_id" value="<?=$team_id?>">
				<input type="submit" name="Team_Export" value="Export" class="btn btn-success">
			</form></td>
		</tr>
	<?php } ?>
			</tbody>
		</table>
			</div>
		</div><br><br>
	<?php } ?>
	<?php if($date_flag!=0){
		?>
		<form action="project_export.php" method="post">
			<input type="hidden" name="from" value="<?=$from?>">
			<input type="hidden" name="to" value="<?=$to?>">
		<input type="submit" name="Date_Export" value="Export" class="btn btn-success ml-5 mb-2">
		</form>
		<?php
		while($name_row=mysqli_fetch_array($name))
		  {
	 ?><br>
	 <div class="container border bg-light shadow-sm">
				<div class="row mb-2">
					<div class="col-10 mt-2">
						<b class="h5">Name:</b><label class="ml-2 h5"><?php
						$get_name=mysqli_query($con,"select * from team where id=".$name_row['team_id']);
						while($get_name_row=mysqli_fetch_array($get_name)){echo $get_name_row['name'];}
						?></label>
						
					</div>
					<div class="col-2 mt-2">
						<b>Total Hrs:</b><label class="ml-2"><?php 
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
			 	echo gmdate('d',$total_seconds)." Days ";
			 	echo gmdate('H:i',$total_seconds)." hrs";
			 	}else{
			 	echo gmdate('H:i',$total_seconds)." hrs";
			 	}
						 ?></label>
					</div>
					<div class="col-3">
						<b>Project</b>
					</div>
					<div class="col-4">
						<b>Task</b>
					</div>
					<div class="col-4">
						<b>Description</b>
					</div>
					<div class="col-1">
						<b>Hrs</b>	
					</div>
					<div class="col-12"><hr class="border border-secondary"></div>
					<?php 
			$each=mysqli_query($con,"select * from history where team_id=".$name_row['team_id']." and created_date between '$from' and '$to'");
			while($each_row=mysqli_fetch_array($each))
			{
			 ?>
			 <div class="col-3">
			 	<label><?php echo "<b>".date("d-m-Y", strtotime($each_row['created_date']))."</b><br>";
			 	$project_name=mysqli_query($con,"select * from projects where id=".$each_row['project_id']);
			 	while($project_name_row=mysqli_fetch_array($project_name)){echo $project_name_row['name'];}
			 	?></label>
			 </div>
			 <div class="col-4">
			 	<label>
			 		<?php 
			 		$tasks=mysqli_query($con,"Select * from task_rows where id=".$each_row['task_row_id']);
			 		while($task=mysqli_fetch_array($tasks))
			 		{echo wordwrap($task['title'],35,'<br>',true);}
			 		 ?>
			 	</label>
			 </div>
			 <div class="col-4">
			 	<label><?=wordwrap($each_row['description'],40,'<br>',true)?></label>
			 </div>
			 <div class="col-1">
			 	<label><?=str_replace('.', ':', $each_row['spent_hrs'])." hrs"?></label>
			 </div>
			 <div class="col-12"><hr></div>
	  <?php }?>
	  		</div>
			</div><br>
	<?php } ?>
	<?php } ?>
	<?php if($date_project_flag!=0){ 
		while($name_row=mysqli_fetch_array($name))
		  {
	 ?><br>
	 <form action="project_export.php" method="post">
	 	<input type="hidden" name="from" value="<?=$from?>">
	 	<input type="hidden" name="to" value="<?=$to?>">
	 	<input type="hidden" name="project_id" value="<?=$project_id?>">
	 <input type="submit" name="Project_Date_Export" value="Export" class="btn btn-success ml-5 mb-2">
	</form>
	 <div class="container border bg-light shadow-sm">
				<div class="row mb-2">
					<div class="col-10 mt-2">
						<b class="h5">Project:</b><label class="ml-2 h5"><?php
						$get_name=mysqli_query($con,"select * from projects where id=".$name_row['project_id']);
						while($get_name_row=mysqli_fetch_array($get_name)){echo $get_name_row['name'];}
						?></label>
						
					</div>
					<div class="col-2 mt-2">
						<b>Total Hrs:</b><label class="ml-2"><?php 
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
			 	echo gmdate('d',$total_seconds)." Days ";
			 	echo gmdate('H:i',$total_seconds)." hrs";
			 	}else{
			 	echo gmdate('H:i',$total_seconds)." hrs";
			 	}
						 ?></label>
					</div>
					<div class="col-3">
						<b>Team</b>
					</div>
					<div class="col-4">
						<b>Task</b>
					</div>
					<div class="col-4">
						<b>Description</b>
					</div>
					<div class="col-1">
						<b>Hrs</b>
					</div>
					<div class="col-12"><hr class="border border-secondary"></div>
					<?php 
			$each=mysqli_query($con,"select * from history where project_id=".$name_row['project_id']." and created_date between '$from' and '$to'");
			while($each_row=mysqli_fetch_array($each))
			{
			 ?>
			 <div class="col-3">
			 	<label><?php echo "<b>".date("d-m-Y", strtotime($each_row['created_date']))."</b><br>";
			 	$project_name=mysqli_query($con,"select * from team where id=".$each_row['team_id']);
			 	while($project_name_row=mysqli_fetch_array($project_name)){echo $project_name_row['name'];}
			 	?></label>
			 </div>
			 <div class="col-4">
			 	<label>
			 		<?php 
			 		$tasks=mysqli_query($con,"Select * from task_rows where id=".$each_row['task_row_id']);
			 		while($task=mysqli_fetch_array($tasks))
			 		{echo wordwrap($task['title'],35,'<br>',true);}
			 		 ?>
			 	</label>
			 </div>
			 <div class="col-4">
			 	<label><?=wordwrap($each_row['description'],40,'<br>',true)?></label>
			 </div>
			 <div class="col-1">
			 	<label><?=str_replace('.', ':', $each_row['spent_hrs'])." hrs"?></label>
			 </div>
			 <div class="col-12"><hr class="border"></div>
	  <?php }?>
	  		</div>
			</div><br>
	<?php } ?>
	<?php } ?>
	<?php if($date_team_flag!=0){
		while($name_row=mysqli_fetch_array($name))
		  {
	 ?><br>
	 <form action="project_export.php" method="post">
	 	<input type="hidden" name="from" value="<?=$from?>">
	 	<input type="hidden" name="to" value="<?=$to?>">
	 	<input type="hidden" name="team_id" value="<?=$team_id?>">
	 	<input type="submit" name="Team_Date_Export" value="Export" class="btn btn-success ml-5 mb-2">
	 </form>
	 <div class="container border bg-light shadow-sm">
				<div class="row mb-2">
					<div class="col-10 mt-2">
						<b class="h5">Name:</b><label class="ml-2 h5"><?php
						$get_name=mysqli_query($con,"select * from team where id=".$name_row['team_id']);
						while($get_name_row=mysqli_fetch_array($get_name)){echo $get_name_row['name'];}
						?></label>
					</div>
					<div class="col-2 mt-2">
						<b>Total Hrs:</b><label class="ml-2"><?php 
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
			 	echo gmdate('d',$total_seconds)." Days ";
			 	echo gmdate('H:i',$total_seconds)." hrs";
			 	}else{
			 	echo gmdate('H:i',$total_seconds)." hrs";
			 	}
						 ?></label>
					</div>
					<div class="col-3">
						<b>Project</b>
					</div>
					<div class="col-4">
						<b>Task</b>
					</div>
					<div class="col-4">
						<b>Description</b>
					</div>
					<div class="col-1">
						<b>Hrs</b>
					</div>
					<div class="col-12"><hr class="border border-secondary"></div>
					<?php 
			$each=mysqli_query($con,"select * from history where team_id=".$name_row['team_id']." and created_date between '$from' and '$to'");
			while($each_row=mysqli_fetch_array($each))
			{
			 ?>
			 <div class="col-3">
			 	<label><?php echo "<b>".date("d-m-Y", strtotime($each_row['created_date']))."</b><br>";
			 	$project_name=mysqli_query($con,"select * from projects where id=".$each_row['project_id']);
			 	while($project_name_row=mysqli_fetch_array($project_name)){echo $project_name_row['name'];}
			 	?></label>
			 </div>
			 <div class="col-4">
			 	<label>
			 		<?php 
			 		$tasks=mysqli_query($con,"Select * from task_rows where id=".$each_row['task_row_id']);
			 		while($task=mysqli_fetch_array($tasks))
			 		{echo wordwrap($task['title'],35,'<br>',true);}
			 		 ?>
			 	</label>
			 </div>
			 <div class="col-4">
			 	<label><?=wordwrap($each_row['description'],40,'<br>',true)?></label>
			 </div>
			 <div class="col-1">
			 	<label><?=str_replace('.', ':', $each_row['spent_hrs'])." hrs"?></label>
			 </div>
			 <div class="col-12"><hr></div>
	  <?php }?>
	  		</div>
			</div><br>
	<?php } ?>
	<?php } ?>
	<?php if($date_project_team_flag!=0){ 
		while($name_row=mysqli_fetch_array($name))
		  {
	 ?><br>
	 <form action="project_export.php" method="post">
	 	<input type="hidden" name="from" value="<?=$from?>">
	 	<input type="hidden" name="to" value="<?=$to?>">
	 	<input type="hidden" name="team_id" value="<?=$team_id?>">
	 	<input type="hidden" name="project_id" value="<?=$project_id?>">
	 	<input type="submit" name="Project_Team_Date_Export" value="Export" class="btn btn-success ml-5 mb-2">
	 </form>
	 <div class="container border bg-light shadow-sm">
				<div class="row mb-2">
					<div class="col-10 mt-2">
						<b class="h5">Name:</b><label class="ml-2 h5"><?php
						$get_name=mysqli_query($con,"select * from team where id=".$name_row['team_id']);
						while($get_name_row=mysqli_fetch_array($get_name)){echo $get_name_row['name'];}
						?></label>
					</div>
					<div class="col-2 mt-2">
						<b>Total Hrs:</b><label class="ml-2"><?php 
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
			 	echo gmdate('d',$total_seconds)." Days ";
			 	echo gmdate('H:i',$total_seconds)." hrs";
			 	}else{
			 	echo gmdate('H:i',$total_seconds)." hrs";
			 	}
						 ?></label>
					</div>
					<div class="col-3">
						<b>Project</b>
					</div>
					<div class="col-4">
						<b>Task</b>
					</div>
					<div class="col-4">
						<b>Description</b>
					</div>
					<div class="col-1">
						<b>Hrs</b>
					</div>
					<div class="col-12"><hr class="border border-secondary"></div>
					<?php 
			$each=mysqli_query($con,"select * from history where team_id=".$name_row['team_id']." and project_id=$project_id and created_date between '$from' and '$to'");
			while($each_row=mysqli_fetch_array($each))
			{
			 ?>
			 <div class="col-3">
			 	<label><?php echo "<b>".date("d-m-Y", strtotime($each_row['created_date']))."</b><br>";
			 	$project_name=mysqli_query($con,"select * from projects where id=".$each_row['project_id']);
			 	while($project_name_row=mysqli_fetch_array($project_name)){echo $project_name_row['name'];}
			 	?></label>
			 </div>
			 <div class="col-4">
			 	<label>
			 		<?php 
			 		$tasks=mysqli_query($con,"Select * from task_rows where id=".$each_row['task_row_id']);
			 		while($task=mysqli_fetch_array($tasks))
			 		{echo wordwrap($task['title'],35,'<br>',true);}
			 		 ?>
			 	</label>
			 </div>
			 <div class="col-4">
			 	<label><?=wordwrap($each_row['description'],40,'<br>',true)?></label>
			 </div>
			 <div class="col-1">
			 	<label><?=str_replace('.', ':', $each_row['spent_hrs'])." hrs"?></label>
			 </div>
			 <div class="col-12"><hr></div>
	  <?php }?>
	  		</div>
			</div><br>
	<?php } ?>

	<?php } ?>
	<?php if($main_flag==1){
		echo "<br><br><br><center><h3 class='text-danger'>No Data Found...!</h3></center>";
	}?>
		</div>
	</div>
</div>
