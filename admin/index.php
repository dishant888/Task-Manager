<?php 
require('config.php');
require('header.php');
?>
<style type="text/css">
	.fa-search{
		position: relative;
		top: -8px;
		left: -25px;
	}
</style>
<div class="container">
	<form class="form-inline">
	<b class="h2 mr-5 mb-4">Dashboard</b>
		<!-- <input type="text" placeholder="Search project" id="search_bar" class="mb-3 ml-5 form-control w-25"><i class="fas fa-search"></i> --></form>
	<div class="col-12">
		<table class="table table-striped text-center border shadow" id="content">
			<thead>
				<tr class="border">
					<th>SN.</th>
					<th>Project</th>
					<th>Open</th>
					<th>In Progress</th>
					<th>Completed</th>
				</tr>
			</thead>
			<tbody>
				<tr>
				<?php $i=0;$f=0;
				$dash=mysqli_query($con,"SELECT `tasks`.`project_id`,projects.name,`tasks`.`delete_status`,`tasks`.`id`,`task_rows`.`task_id`,`task_rows`.`title`,`task_rows`.`status` FROM `tasks` INNER JOIN `task_rows` ON `tasks`.`id` = `task_rows`.`task_id`INNER JOIN projects ON projects.id=tasks.project_id group by `tasks`.`project_id`  order by projects.name ASC");
				while($row=mysqli_fetch_array($dash))
				{$f=1;
					$pid=$row['project_id'];
					if($row['delete_status']==0)
					{
			    ?>
			    <td><?=++$i?></td>
			    <td><?php
			    $project_name=mysqli_query($con,"select * from projects where id=".$row['project_id']);
					while($row2=mysqli_fetch_array($project_name))
					{
						$pro_id=$row['project_id'];
					?>
					<a href="project_detailes.php?id=<?=$row['project_id']?>"><?php echo substr($row2['name'],0,25); echo strlen($row2['name'])>25?'...':'';?></a>
			  <?php } ?>
				</td>
				<td>
			 	<?php 
			 	$open=mysqli_query($con,"SELECT `tasks`.`project_id`,`tasks`.`id`,`task_rows`.`task_id`,`task_rows`.`title`,`task_rows`.`status` FROM `tasks` INNER JOIN `task_rows` ON `tasks`.`id` = `task_rows`.`task_id` where `tasks`.`project_id`=$pro_id and status='open' and task_rows.delete_status=0");
			 	$open_count=mysqli_num_rows($open);
			    if($open_count==0)
			    	echo "--";
			    else{
			 	 ?>
			 	 <a href="open.php?id=<?=$pid?>"><?=$open_count?></a>
			 	<?php } ?>
				</td>
				<td>
				<?php 
			 	$in_progress=mysqli_query($con,"SELECT `tasks`.`project_id`,`tasks`.`id`,`task_rows`.`task_id`,`task_rows`.`title`,`task_rows`.`status` FROM `tasks` INNER JOIN `task_rows` ON `tasks`.`id` = `task_rows`.`task_id` where `tasks`.`project_id`=$pro_id and status='in_progress' and task_rows.delete_status=0");
			 	$open_count=mysqli_num_rows($in_progress);
			    if($open_count==0)
			    	echo "--";
			    else{
			 	 ?>
			 	 <a href="in_progress.php?id=<?=$pid?>"><?=$open_count?></a>
			 	<?php } ?>
				</td>
				<td>
				<?php 
			 	$completed=mysqli_query($con,"SELECT `tasks`.`project_id`,`tasks`.`id`,`task_rows`.`task_id`,`task_rows`.`title`,`task_rows`.`status` FROM `tasks` INNER JOIN `task_rows` ON `tasks`.`id` = `task_rows`.`task_id` where `tasks`.`project_id`=$pro_id and status='completed' and task_rows.delete_status=0");
			 	$open_count=mysqli_num_rows($completed);
			    if($open_count==0)
			    	echo "--";
			    else{
			 	 ?>
			 	 <a href="completed.php?id=<?=$pid?>"><?=$open_count?></a>
			 	<?php } ?>
				</td>
				</tr>
		  <?php }} if($f==0){echo "<tr><td colspan='5' align='center'><h1 class='text-danger'>No Projects...</h1></td></tr>";} ?>
			</tbody>
		</table>
	</div>
</div>
<br><br>
