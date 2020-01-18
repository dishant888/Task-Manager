<?php 
$status='';
require('config.php');
require('header.php');
if(isset($_REQUEST['edit']))
{
	//$pro_id=$_REQUEST['project'];
	$team_id=$_REQUEST['team'];
	$task=$_REQUEST['task'];
	$task_id=$_REQUEST['task_id'];
	mysqli_query($con,"update task_rows set team_id=$team_id,title='$task' where id=$task_id");
	$status = 'success';
	$message = 'Task Updated successfully !';
}
 ?>
 <div class="container">
 <div class="col-12">
 	<?php if($status == 'success')
			{ ?>
				<div class="alert alert-success" role="alert">
				<button type="button" class="close" data-dismiss="alert">
				<span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
				<strong><?php echo $message; ?></strong> 
				</div>
		  <?php }?>
		  <?php
		  if(isset($_REQUEST['s']))
		  {
		  	$status="fail";
		  	$message="Task Deleted";
		  }
		   if($status == 'fail')
			{ ?>
				<div class="alert alert-danger" role="alert">
				<button type="button" class="close" data-dismiss="alert">
				<span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
				<strong><?php echo $message; ?></strong> 
				</div>
		  <?php }?>
		<h2>View Task</h2>
		<div>
			<label class="mr-3"><h5>Project: </h5></label><select class="form-control-lg js-example-basic-single" id="project_selection">
				<option value="all">All</option>
				<?php 
						$pro=mysqli_query($con,"select * from projects where delete_status=0");
						while($row=mysqli_fetch_array($pro))
						{
						 ?>
						 <option value="<?=$row['id']?>"><?=$row['name']?></option>
						<?php } ?>
			</select><br>
		</div>
		<div class="box-body">
        <div id="cStatus"> </div>
        <form action="view_task.php" method="post">
          <table id="example1" class="w-100 table-striped table-bordered">
                <thead>
                <tr>
                  <th># </th>
                  <th>Project</th>
                  <th>Titile</th>
                  <th>Assigned to</th>
                  <th>Edit</th>
                  <th>Delete</th>
               </tr>
                </thead>
                <tbody id="example_tbody">
				<?php
				//SELECT `tasks`.`project_id`,`task_rows`.`id`,`task_rows`.`team_id`,`task_rows`.`title` from tasks INNER JOIN task_rows ON `tasks`.`id`=`task_rows`.`task_id` where `task_rows`.`delete_status`=0 ORDER BY `task_rows`.`id` DESC
					$show=mysqli_query($con,"SELECT `tasks`.`project_id`,`task_rows`.`id`,`task_rows`.`team_id`,`task_rows`.`title`,`projects`.`name` from tasks INNER JOIN task_rows ON `tasks`.`id`=`task_rows`.`task_id` INNER JOIN `projects` ON `tasks`.`project_id`=`projects`.`id` where `task_rows`.`delete_status`=0 ORDER BY `projects`.`name` ASC");
					$i=0;
					while($row=mysqli_fetch_array($show))
					{
						?>
						<tr>
							<td class="text-center"><b><?php echo ++$i; ?></b></td>
							<td class="text-center"><?=$row['name']?></td>
							<td><?php echo wordwrap($row['title'],85,'<br>',true); ?></td>
							<td class="text-center">
								<?php 
									$names=mysqli_query($con,"select * from team where id=".$row['team_id']);
									while($name=mysqli_fetch_array($names))
									{
										echo $name['name'];
									}
								 ?>
							</td>
							<td class="text-center">	
								 	<a style="color:#fff;" class="btn btn-info btn-rounded btn-sm" href="#" data-toggle="modal" data-target="#mod<?=$row['id']?>">
									<span class="fa fa-edit"></span>
								</a>
							</td>
							<td class="text-center">
								<a class="mb-control1 btn btn-danger btn-rounded btn-sm" onclick="return confirm('Are you sure ?')" href="delete_task.php?id=<?php echo $row['id']; ?>">
									<span class="fa fa-times"></span>
								</a>
							</td>
							<td>
								<div class="modal fade" id="mod<?=$row['id']?>">
								<div class="modal-dialog shadow">
									<div class="modal-content">
										<div class="modal-header">
											<h4 class="modal-title">EDIT TASK</h4>
											<button class="close" data-dismiss="modal">
												&times;
											</button>
										</div>
										<div class="modal-body">
												<form class="form-group" action="view_task.php" method="post">
								<label for="user">
									Title
								</label>
								<textarea name="task" class="form-control"><?=$row['title']?></textarea>
								<input type="hidden" name="project" value='<?=$row['project_id']?>'>
								<input type="hidden" name="task_id" value='<?=$row['id']?>'>
								<label for="pass">
									Team
								</label>
								<select class="form-control" name="team">
					<?php 
					$teams=mysqli_query($con,'select * from project_assigned_to where project_id='.$row['project_id']);
					while($team=mysqli_fetch_array($teams))
					{
						$names=mysqli_query($con,"select * from team where id=".$team['team_id']);
						while($name=mysqli_fetch_array($names))
						{
							if($name['id']==$row['team_id'])
							{
							?>
							<option value="<?=$name['id']?>" selected><?=$name['name']?></option>
							<?php
						}else{
							?>
							<option value="<?=$name['id']?>"><?=$name['name']?></option>
							<?php
						}

						}
					}
					 ?>
								</select>
								<br>
								<div class="text-center">
								<input type="submit" name="edit" value="Save" class="btn btn-primary w-25 form-control">
								<button class="btn btn-danger" data-dismiss="modal">Close</button>
								</div>
								</form>
									</div>
									</div>
								</div>
							</div>
							</td>
						</tr>
						<?php
					}
				?>                 
               </tbody>
              </table>
            </form> 
      </div>
	</div>
</div>
<br><br>
<script type="text/javascript">
	$(document).ready(function() {
		$('#project_selection').on('change', function(event) {
		var project_id = $(this).val();
		$.ajax({
			url: 'task_filter.php',
			type: 'GET',
			data: {id: project_id},
			beforeSend:function(){
				$('#example1').html('<br><br><center><h3>Working on...!</h3>');
			},
			success:function(data){
				$('#example1').html(data);
			}
			});
		});
	});
</script>