<?php 
require('config.php');
require('header.php');
$date_team_flag=-1;
if(isset($_REQUEST['filter']))
{
	$team_id=$_REQUEST['team_selection'];
	$from=$_REQUEST['from_date'];
	$to=$_REQUEST['to_date'];
	$from = date("Y-m-d", strtotime($from));
	$to = date("Y-m-d", strtotime($to));
	if(!empty($team_id)&&!empty($from)&&!empty($to))
	{
		$name=mysqli_query($con,"select * from history where team_id=$team_id and created_date BETWEEN '$from' AND '$to' group by team_id");
		$date_team_flag=mysqli_num_rows($name);
	}
}
 ?>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
   <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>
  <script type="text/javascript">
  	$(document).ready(function() {
  		$( ".datepicker" ).datepicker({ dateFormat: 'dd-mm-yy' });
  	});
  </script>
<div class="container">
	<div class="row">
		<div class="col-12">
			<h1>Team Report</h1><br>
		</div>
		<div class="col-lg-3"><form action="team_report.php" method="get">
			<select class="form-control js-example-basic-single" name="team_selection" required>
				<option value="">---Select Team---</option>
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
		</div>
		<div class="col-lg-4 form-inline">
			<b>From:</b>
			<?php 
			if(isset($_REQUEST['from_date']))
			{
			 ?>
<input type="text" class="datepicker form-control ml-2" name="from_date" value="<?=date('d-m-Y',strtotime($from))?>" required><?php }else{ ?>
<input type="text" class="datepicker form-control ml-2" name="from_date" value="<?=date('d-m-Y')?>" required><?php } ?>
		</div>
		<div class="col-lg-4 form-inline">
			<b>To:</b>
			<?php
			if(isset($_REQUEST['to_date']))
			{
			?>
<input type="text" class="datepicker form-control ml-2" name="to_date" value="<?=date('d-m-Y',strtotime($to))?>" required><?php }else{ ?>
<input type="text" class="datepicker form-control ml-2" name="to_date" value="<?=date('d-m-Y')?>" required><?php } ?>
		</div>
		<div class="col-lg-1">
			<input type="submit" name="filter" value="Search" class="btn btn-primary"></form>
		</div>
	</div><hr>
	<div class="col-12">
		<?php 
		if($date_team_flag!=0&&$date_team_flag!==-1){
			while($name_row=mysqli_fetch_array($name))
			{
			?>
			<div class="container">
				<div class="row">
					<div class="col-12">
						<h4>Name: 
							<?php $get_name=mysqli_query($con,"select * from team where id=".$name_row['team_id']);
						while($get_name_row=mysqli_fetch_array($get_name)){echo $get_name_row['name'];}?>
						</h4>
					</div>
				</div><br>
				<?php 
				$each=mysqli_query($con,"select * from history where team_id=$team_id and created_date BETWEEN '$from' AND '$to' group by project_id");
				while($each_row=mysqli_fetch_array($each))
				{
				 ?>
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th colspan="4"><h4>
								Project Name: <?php $get_name=mysqli_query($con,"select * from projects where id=".$each_row['project_id']);while($get_name_row=mysqli_fetch_array($get_name)){echo $get_name_row['name'];}?>
							</h4></th>
							<th colspan="3"><h4>
								Total Hours: <?php $total_hours=mysqli_query($con,"select sum(spent_hrs) from history where project_id=".$each_row['project_id']." and created_date between '$from' and '$to' and status='completed' and team_id=".$each_row['team_id']);while($t=mysqli_fetch_array($total_hours)){echo $sp_total=$t[0];}?>
							</h4></th>
						</tr>
						<tr>
							<th>S.No.</th>
							<th>Date</th>
							<th>Task</th>
							<th>Description</th>
							<th>Est. Hrs.</th>
							<th>Spent Hrs.</th>
							<th>Diff.</th>
						</tr>
					</thead>
					<tbody class="tbody">
						<tr>
				<?php //"select * from history where team_id=".$each_row['team_id']." and created_date between '$from' and '$to' and status='completed' and project_id=".$each_row['project_id']

		$data=mysqli_query($con,"Select history.team_id,history.project_id,history.task_row_id,history.status,history.description,history.spent_hrs,history.created_date,task_rows.estimated_hrs  FROM history INNER JOIN task_rows ON task_rows.id = history.task_row_id where history.team_id=$team_id and history.created_date BETWEEN '$from' and '$to' and history.status='completed' and history.project_id=".$each_row['project_id']);$srno=0;
		$hrsTot=[];$diff_tot=[];
			while($data_row=mysqli_fetch_array($data)){
				@$hrsTot[@$data_row['project_id']] += @$data_row['estimated_hrs'];
		?>
		<td align="center"><b><?=++$srno?></b></td>
		<td><?=date("d-m-y", strtotime($data_row['created_date']))?></td>
		<td><?php $tasks=mysqli_query($con,"Select * from task_rows where id=".$data_row['task_row_id']);while($task=mysqli_fetch_array($tasks)){echo wordwrap($task['title'],40,'<br>',true);}?></td>
		<td><?=wordwrap($data_row['description'],40,'<br>',true)?></td>
		<td class="est_each"><?php $tasks=mysqli_query($con,"Select * from task_rows where id=".$data_row['task_row_id']);while($task=mysqli_fetch_array($tasks)){echo $task['estimated_hrs'];}?></td>
		<td><?=$data_row['spent_hrs']?></td>
		<td><?php $diff=$data_row['spent_hrs']-$data_row['estimated_hrs'];
	    echo $diff>0?'+'.$diff:$diff;
	    @$diff_tot[@$data_row['project_id']]+=@$diff;
		?></td>
	</tr>

	<?php }  ?>
	<tr>
		<td colspan="4" align="right">
			<b>Total:</b>
		</td>
		<td><b><?=$hrsTot[$each_row['project_id']]?></b></td>
		<td><b><?=$sp_total?></b></td>
		<td><b><?=$diff_tot[$each_row['project_id']]>0?'+'.$diff_tot[$each_row['project_id']]:$diff_tot[$each_row['project_id']]?></b></td>
	</tr>
			</tbody>
	</table><br>
				<?php 
					}
				 ?>
			</div>
			
		<?php }}else if($date_team_flag==0) {?>
			<h1>Not Updates</h1>
		<?php } ?>
	</div>
</div>

