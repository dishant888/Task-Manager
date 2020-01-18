<?php 
require('config.php');
if($_REQUEST['id'])
{
$project_id=$_REQUEST['id'];
if($project_id!="all")
{
	echo "<thead>
				<tr>
                  <th># </th>
                  <th>Project</th>
                  <th>Titile</th>
                  <th>Assigned to</th>
                  <th>Edit</th>
                  <th>Delete</th>
               </tr>
			</thead>";
			$show=mysqli_query($con,"SELECT `tasks`.`project_id`,`task_rows`.`id`,`task_rows`.`team_id`,`task_rows`.`title`,`projects`.`name` from tasks INNER JOIN task_rows ON `tasks`.`id`=`task_rows`.`task_id` INNER JOIN `projects` ON `tasks`.`project_id`=`projects`.`id` where `task_rows`.`delete_status`=0 and `tasks`.`project_id`=$project_id");
					$i=0;$f=0;
					while($row=mysqli_fetch_array($show))
					{$f=1;
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
					if($f==0){echo "<td colspan='10' align='center'>No Tasks</td>";}
}
else
{
	echo "<thead>
				<tr>
                  <th># </th>
                  <th>Project</th>
                  <th>Titile</th>
                  <th>Assigned to</th>
                  <th>Edit</th>
                  <th>Delete</th>
               </tr>
					</thead>";
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
}
}
 ?>