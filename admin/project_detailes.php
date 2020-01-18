<?php 
require('config.php');
$id=$_REQUEST['id'];
$status="";
require('header.php');
 ?>
 <div class="container">
 	<?php 
 	if(isset($_REQUEST['success']))
 		{$status="success";$message="Updated successfully!";}
 	if(isset($_REQUEST['added']))
 		{$status="success";$message="Task Added successfully!";}
 	if($status == 'success')
			{ ?>
				<div class="alert alert-success" role="alert">
				<button type="button" class="close" data-dismiss="alert">
				<span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
				<strong><?php echo $message; ?></strong> 
				</div>
		  <?php }?>
 	<table class="table text-center border bg-light">
 			<tr>
 				<th><form class="form-inline">
 					<b class="h3">Project:</b>
 					<select id="change_project" class="form-control ml-3">
 						<?php 
 						$dash=mysqli_query($con,"SELECT `tasks`.`project_id`,`tasks`.`delete_status`,`tasks`.`id`,`task_rows`.`task_id`,`task_rows`.`title`,`task_rows`.`status` FROM `tasks` INNER JOIN `task_rows` ON `tasks`.`id` = `task_rows`.`task_id` group by `tasks`.`project_id` order by project_id desc");
 						while($dash_row=mysqli_fetch_array($dash))
 						{
 							if($dash_row['delete_status']==0){
 							$pro_name=mysqli_query($con,"select * from projects where id=".$dash_row['project_id']);
 							while($pro_name_row=mysqli_fetch_array($pro_name))
 							{
 							if($id==$pro_name_row['id'])
 							{
 						  ?>
 						  <option value="<?=$pro_name_row['id']?>" selected><?=$pro_name_row['name']?></option>
 				  <?php } else {
 				  	?>
 				  		  <option value="<?=$pro_name_row['id']?>"><?=$pro_name_row['name']?></option>
 				 <?php }}}}?>
 					</select></form>
 				</th>
 				<th><form class="form-inline">
 					Status:
 					<select id="get_status" class="form-control ml-3">
 						<option value="all">ALL</option>
 						<option value="open">OPEN</option>
 						<option value="in_progress">IN PROGRESS</option>
 						<option value="completed">COMPLETED</option>
 					</select></form>
 				</th>
 				<th>
 					<label class="mt-2">Total:</label>
 					<?php 
 					$total_hours=mysqli_query($con,"SELECT `task_rows`.`spent_hrs` FROM `tasks` INNER JOIN `task_rows` ON `tasks`.`id` = `task_rows`.`task_id` WHERE task_rows.delete_status=0 and project_id=$id");
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
			 	echo gmdate('H:i',$total_seconds)." hrs";
 					 ?>
 				</th>
 				<!-- <th>
 					<a href="#" data-toggle="modal" data-target="#add_own_task" class="btn btn-success">ADD TASK</a>
 				</th> -->
 			</tr>
 	</table>
 	<div class="modal fade" id="add_own_task">
	<div class="modal-dialog shadow">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">ADD TASK</h4>
				<button class="close" data-dismiss="modal">
					&times;
				</button>
			</div>
			<div class="modal-body">
					<form class="form-group" action="add_own_task.php" method="post">
						<div class="container">
							<div class="col-12">
								<label><b>Title:</b></label>
								<input type="hidden" name="project_id" value="<?=$id?>">
								<input type="text" name="own_title" class="form-control" required>
							</div>
						</div>
						<div class="text-center">
							<br>
						<input type="submit" name="add_own_task" value="ADD" class="btn btn-primary w-25 form-control">
					    <button class="btn btn-danger" data-dismiss="modal">Close</button>
					</div>
					</form>
				</div>
		</div>
	</div>
</div>
 	<table class="table text-center table-striped border" id="content">
 		<thead>
 			<tr>
 				<th>SN.</th>
 				<th>Assigned to</th>
 				<th>Task-Title</th>
 				<th>Spent Hrs.</th>
 				<th>Status</th>
 				<th>Assigned on</th>
 				<th>Details</th>
 			</tr>
 		</thead>
 		<tbody><tr><!-- 
 			SELECT `tasks`.`project_id`,`tasks`.`id`,`task_rows`.`spent_hrs`,`task_rows`.`created_on`,`tasks`.`team_id`,`task_rows`.`task_id`,`task_rows`.`title`,`task_rows`.`status` FROM `tasks` INNER JOIN `task_rows` ON `tasks`.`id` = `task_rows`.`task_id` WHERE team_id=".$_SESSION['u_id']." AND project_id=".$id -->
 			<?php $k=0;
 			$detailes=mysqli_query($con,"SELECT `task_rows`.`id`,`tasks`.`project_id`,`task_rows`.`spent_hrs`,`task_rows`.`created_on`,`task_rows`.`team_id`,`task_rows`.`task_id`,`task_rows`.`title`,`task_rows`.`status` FROM `tasks` INNER JOIN `task_rows` ON `tasks`.`id` = `task_rows`.`task_id` WHERE task_rows.delete_status=0 and project_id=$id order by id desc");
 			while($get_detailes=mysqli_fetch_array($detailes))
 			{
 			 ?>
 			 <td><?=++$k?></td>
 			 <td>
 			 	<?php 
 			 	$names=mysqli_query($con,"select * from team where id=".$get_detailes['team_id']);
 			 	while($name=mysqli_fetch_array($names))
 			 	{echo $name['name'];}
 			 	 ?>
 			 </td>
 			 <td align="left"><?php echo wordwrap($get_detailes['title'],65,'<br>',true)?></td>
 			 <td><?=$get_detailes['spent_hrs']==0?'--':$get_detailes['spent_hrs']?></td>
 			 <td><?=$get_detailes['status']?></td>
 			 <td><?=date('d-m-Y',strtotime($get_detailes['created_on']))?></td>
 			 <td><a href="#" data-toggle="modal" data-target="#mod<?=$get_detailes['id']?>">Details</a></td>
 	<div class="modal fade" id="mod<?=$get_detailes['id']?>">
	<div class="modal-dialog modal-xl shadow">
		<div class="modal-content">
			<div class="modal-header">
				<div class="container">
					<div class="row">
						<div class="col-4">
				<h4 class="modal-title text-center">UPDATE</h4></div>
				<div class="col-8">
				<h4 class="modal-title text-center">HISTORY</h4></div>
				</div></div>
				<button class="close" data-dismiss="modal">
					&times;
				</button>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<div class="row">
						<div class="col-4 border-right">
				<form class="form-group form-inline" action="update_task.php" method="post">
					<input type="hidden" name="task_row_id" value="<?=$get_detailes['id']?>">
					<input type="hidden" name="project_id" value="<?=$id?>">
					<div class="container">
						<div class="row">
					<?php 
					$pop=mysqli_query($con,"select * from task_rows where id=".$get_detailes['id']);
					while($res=mysqli_fetch_array($pop))
					{
					 ?>
				<div class="col-12">
			  		<label >
			  		<b>Assigned on:</b>
			  		</label>
			  		<label><?=substr($res['created_on'],0,10)?></label>
			  	</div>
				<div class="col-12 overflow-auto">
					<label>
							<b>Title:</b>
					</label>
					<label><?=$res['title']?></label>
				</div>
			  	<div class="col-12">
					<label>
						<b>Status:</b>
					</label>
					<select class="form-control" name="status_selection">
						<?php if($res['status']=="open"){ ?>
						<option value="open" selected>OPEN</option>
						<option value="in_progress">IN PROGRESS</option>
						<option value="completed">COMPLETED</option>
					<?php } else if($res['status']=="in_progress"){ ?>
						<option value="in_progress" selected>IN PROGRESS</option>
						<option value="completed">COMPLETED</option>
					<?php }else{ ?>
						<option value="in_progress">IN PROGRESS</option>
						<option value="completed" selected>COMPLETED</option>
					<?php } ?>
					</select>
				</div>
				<div class="col-6">
					<label><b>Hours Spent:</b></label>
					<div class="input-group clockpicker" data-placement="right" data-align="top" data-autoclose="true">
					<input type="text" class="form-control" value="00:00" name="hours">
					<span class="input-group-addon">
						<span class="glyphicon glyphicon-time"></span>
					</span>
				</div>
				</div>
				<div class="col-6">
					<label><b>Completed By:</b></label>
					<select class="form-control" name="completed_by">
						<?php 
						$get_teams=mysqli_query($con,"select * from project_assigned_to where project_id=$id");
						while($get_team=mysqli_fetch_array($get_teams))
						{
							$names=mysqli_query($con,"select * from team where id=".$get_team['team_id']);
							while($name=mysqli_fetch_array($names))
							{
								if($get_team['team_id']==$_SESSION['u_id'])
								{
								?>
								<option selected value="<?=$name['id']?>"><?=$name['name']?></option>
								<?php
							    }else{
							    	?>
							    	<option  value="<?=$name['id']?>"><?=$name['name']?></option>
							    	<?php
							    }
							}
						}
						 ?>
					</select>
				</div>
				<div class="col-12">
					<b>Date:</b><input type="date" name="update_date" id="today" class="form-control" required value="<?php echo date('Y-m-d');?>">
				</div>
				<div class="col-12">
					<b>Description:</b>
					<textarea class="form-control" name="description" style="resize: none; height: 90px;"></textarea>
				</div>
				<br><br>
				<div class="col-6 text-center">
					<br>
					<input type="submit" name="save" value="Save" class="btn btn-info mt-2 w-75">
				</div>
			  <?php } ?>
			</div>
			</div>
				</form>
			</div>
			<div class="col-8">
				<div class="container-fluid text-center">
					<div class="row">
						<div class="col-1 p-0">
							<b>SN.</b>
						</div>
						<div class="col-2 p-0"> 
							<b>Completed by</b>
						</div>
						<div class="col-2 p-0">
							<b>Status</b>
						</div>
						<div class="col-1 p-0 pr-5">
							<b>Hrs</b>
						</div>
						<div class="col-6 p-0">
							<b>Description</b>
						</div>
					</div>
					<hr>
					<div class="cust">
				<?php 
				$history=mysqli_query($con,"select * from history where task_row_id=".$get_detailes['id']." order by id desc");$i=0;
				while($history_row=mysqli_fetch_array($history))
				{
				 ?>
				 <div class="row">
				 <div class="col-1 p-0">
							<label><?=++$i?></label>
						</div>
						<div class="col-2 overflow-auto p-0">
							<label><?php $names=mysqli_query($con,"select * from team where id=".$history_row['team_id']);
							while($name=mysqli_fetch_array($names))
								{echo $name['name'];}
							?></label>
						</div>
						<div class="col-2 p-0">
							<label><?=$history_row['status']?></label>
						</div>
						<div class="col-1 p-0">
							<label><?=str_replace('.', ':', $history_row['spent_hrs'])?></label>
						</div>
						<div class="col-6 p-0 overflow-auto">
							<label><?=$history_row['description']?></label>
						</div>
					</div>
					<hr>
		  <?php } ?>
				</div>
			</div>
			</div>
			</div>
			</div>
		</div><br>
		</div>
	</div>
</div>
 			</tr>
 	  <?php } ?>
 		</tbody>
 	</table>
 <input type="hidden" name="id" id="project_id" value="<?=$id?>">
 </div>
 <script type="text/javascript">
 	$(document).ready(function() {

 		var i=0;var t=0;
 		$('table#content tbody tr').each(function() {
 		if($(this).find('td:nth-child(4)').text()=="--"){i=0;}
 		else{i=parseFloat($(this).find('td:nth-child(4)').text());}t=t+i;});
 		if(t==0){$('#t_h').text('--');}else{$('#t_h').text(t.toFixed(2)+'hrs');}

 		$('#get_status').on('change', function(event) {
 			var value = $(this).val();
 			var pro_id = $('#project_id').val();
 			$.ajax({
 				url: 'filter.php',
 				type: 'GET',
 				data: {request: value , id: pro_id},
 				beforeSend:function(){
 					$('#content').html("<h1 class='text-center'>Working on...!</h1>");
 				},
 				success:function(data){
 					$('#content').html(data);
 				}
 			});
 		});
 		$('#change_project').on('change', function(event) {
 			var id = $(this).val();
 			location.href='project_detailes.php?id='+id;
 		});
 	});
 </script>


