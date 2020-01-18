<?php 
require('config.php');
if($_REQUEST['date'])
{
	$date=$_REQUEST['date'];
	echo "<div class='row'>
		<div class='col-12'>";
?>
	<?php $f=0;
	$name=mysqli_query($con,"select * from history where created_date='$date' group by team_id");
	while($name_row=mysqli_fetch_array($name))
		  {$f=1;
			?>
			<div class="container border bg-light shadow-sm">
				<div class="row mb-2">
					<div class="col-10 mt-2">
						<b class="h5">Name:</b><label class="ml-2 h5"><?php
						$get_name=mysqli_query($con,"select * from team where id=".$name_row['team_id']);
						while($get_name_row=mysqli_fetch_array($get_name)){echo $get_name_row['name'];}
						?></label>
						<hr>
					</div>
					<div class="col-2 mt-2">
						<b>Total Hrs:</b><label class="ml-2"><?php 
						$total_hours=mysqli_query($con,"select sum(spent_hrs) from history where team_id=".$name_row['team_id']);
						while($total_hours_row=mysqli_fetch_array($total_hours)){echo $total_hours_row[0]." hrs";}
						 ?></label><hr>
					</div>
					<div class="col-3">
						<b>Project</b>
					</div>
					<div class="col-8">
						<b>Description</b>
					</div>
					<div class="col-1">
						<b>Hrs</b>
					</div>
			<?php 
			$each=mysqli_query($con,"select * from history where team_id=".$name_row['team_id']." and created_date='$date'");
			while($each_row=mysqli_fetch_array($each))
			{
			 ?>
			 <div class="col-3">
			 	<label><?php 
			 	$project_name=mysqli_query($con,"select * from projects where id=".$each_row['project_id']);
			 	while($project_name_row=mysqli_fetch_array($project_name)){echo $project_name_row['name'];}
			 	?></label>
			 </div>
			 <div class="col-8">
			 	<label><?=$each_row['description']?></label>
			 </div>
			 <div class="col-1">
			 	<label><?=$each_row['spent_hrs']." hrs"?></label>
			 </div>
	  <?php }?>
				</div>
			</div><br>
	<?php  }if($f==0){echo "<div class='col-12 text-center'><h1 class='text-danger font-weight-normal'>No Updates....</h1></div>";}?>
<?php
		echo "</div></div>";  
     } ?>
