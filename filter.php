<?php 
session_start();
require('config.php');
if($_REQUEST['request'])
{
$request=$_REQUEST['request'];
$id=$_REQUEST['id'];
if($request!='all')
{
	echo"<thead>
				<tr>
					<th>SN.</th>
					<th>Task-Title</th>
					<th>Spent Hrs.</th>
					<th>Status</th>
					<th>Assigned on</th>
					<th>Detailes</th>
				</tr>
			</thead>";
			$detailes=mysqli_query($con,"SELECT `task_rows`.`id`,`tasks`.`project_id`,`task_rows`.`spent_hrs`,`task_rows`.`created_on`,`task_rows`.`team_id`,`task_rows`.`task_id`,`task_rows`.`title`,`task_rows`.`status` FROM `tasks` INNER JOIN `task_rows` ON `tasks`.`id` = `task_rows`.`task_id` WHERE team_id=".$_SESSION['u_id']." and task_rows.delete_status=0 and project_id=$id and status='$request' order by id desc");$maini=0;$f=0;
 			while($get_detailes=mysqli_fetch_array($detailes))
 			{
 				$f=1;
 				?>
 				<td><?=++$maini?></td>
 			 <td align="left"><?php echo wordwrap($get_detailes['title'],85,'<br>',true)?></td>
 			 <td><?=$get_detailes['spent_hrs']==0?'--':$get_detailes['spent_hrs']?></td>
 			 <td><?=$get_detailes['status']?></td>
 			 <td><?=date('d-m-Y',strtotime($get_detailes['created_on']))?></td>
 			 <td><a href="#" data-toggle="modal" data-target="#mod<?=$get_detailes['id']?>">Details</a></td>
 			  	
 			</tr>
 <?php }  if($f==0){echo "<td colspan='6' class='text-danger'><h2>No $request tasks</h2></td></tr>";} }
 else{
 	echo"<thead>
				<tr>
					<th>SN.</th>
					<th>Task-Title</th>
					<th>Spent Hrs.</th>
					<th>Status</th>
					<th>Assigned on</th>
					<th>Detailes</th>
				</tr>
			</thead>";
			$detailes=mysqli_query($con,"SELECT `task_rows`.`id`,`tasks`.`project_id`,`task_rows`.`spent_hrs`,`task_rows`.`created_on`,`task_rows`.`team_id`,`task_rows`.`task_id`,`task_rows`.`title`,`task_rows`.`status` FROM `tasks` INNER JOIN `task_rows` ON `tasks`.`id` = `task_rows`.`task_id` WHERE team_id=".$_SESSION['u_id']." and task_rows.delete_status=0 and project_id=$id order by id desc");$maini=0;
 			while($get_detailes=mysqli_fetch_array($detailes))
 			{
 				?>
 			<td><?=++$maini?></td>
 			 <td align="left"><?php echo wordwrap($get_detailes['title'],85,'<br>',true)?></td>
 			 <td><?=$get_detailes['spent_hrs']==0?'--':$get_detailes['spent_hrs']?></td>
 			 <td><?=$get_detailes['status']?></td>
 			 <td><?=date('d-m-Y',strtotime($get_detailes['created_on']))?></td>
 			 <td><a href="#" data-toggle="modal" data-target="#mod<?=$get_detailes['id']?>">Details</a></td>
 			  	
 			</tr>		
 	<?php
 } } }?>
<script type="text/javascript">
	$(document).ready(function() {
		var i=0;var t=0;
 		$('table#content tbody tr').each(function() {
 		if($(this).find('td:nth-child(3)').text()=="--"){i=0;}
 		else{i=parseFloat($(this).find('td:nth-child(3)').text());}t=t+i;});
 		if(t==0){$('#t_h').text('--');}else{$('#t_h').text(t.toFixed(2)+'hrs');}
	});
</script>