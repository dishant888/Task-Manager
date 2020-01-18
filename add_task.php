<?php
require('config.php');
$status="";
require('header.php');
if(isset($_REQUEST['submit']))
{
	$pro_id=$_REQUEST['project'];
	mysqli_query($con,"insert into tasks(project_id) values($pro_id)");
	$last_id=mysqli_insert_id($con);$i=0;


	foreach ($_REQUEST['task'] as $tasks)
	{
		$t=$tasks['row'];
		$estimated=$tasks['team_id'];
		mysqli_query($con,"insert into task_rows(task_id,team_id,title,created_by,estimated_hrs) values($last_id,".$_SESSION['u_id'].",'$t',".$_SESSION['u_id'].",$estimated)");
	}
	$status = 'success';
	$message = 'Task Added successfully !';
}
if(isset($_REQUEST['edit']))
{
	$pro_id=$_REQUEST['project'];
	$team_id=$_REQUEST['team'];
	$task=$_REQUEST['task'];
	$task_id=$_REQUEST['task_id'];
	mysqli_query($con,"update task set project_id=$pro_id,team_id=$team_id,title='$task' where id=$task_id");
	$status = 'success';
	$message = 'Task Updated successfully !';
}
 ?>
<div class="container">
	<div class="row">
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
		<h2>Add Task</h2>
	<form action="add_task.php" method="post">
		<table class="table text-center" id="main_table">
			<tr>
				<th>Select Project:</th>
				<td>
					<select name="project" id="project" class="form-control">
						<option value="0">-Select Project-</option>
						<?php
						$select=mysqli_query($con,"select * from project_assigned_to where delete_status=0 and team_id=".$_SESSION['u_id']);
						while($res=mysqli_fetch_array($select))
						{
						$pro=mysqli_query($con,"select * from projects where delete_status=0 and id=".$res['project_id']);
						while($row=mysqli_fetch_array($pro))
						{
						 ?>
						 <option value="<?=$row['id']?>"><?=$row['name']?></option>
						<?php }} ?>
					</select>
				</td>
				<th><!-- Select Team: --></th>
				<td>
					<!-- <select name="team" id="team" class="form-control">
						<option value="0">-Select Team-</option>

					</select> -->
				</td>
			</tr>
			<tbody id="main_tbody">

			</tbody>
			<tfoot>
				<tr>
					<td>
						<input type="submit" name="submit" value="Submit" class="btn btn-info">
					</td>
					<td class="p-3">
						<label id="err"></label>
					</td>
				</tr>
			</tfoot>
		</table>
		</form>
	</div>
	<!-- <div class="col-7">
		<h2>View Task</h2>
		<div class="box-body cust">
        <div id="cStatus"> </div>
        <form action="admin/update_Sequence" method="post">
          <table id="example1" class="w-100 table-striped text-center">
                <thead>
                <tr>
                  <th># </th>
                  <th>Titile</th>
                  <th>Assigned to</th>
                  <th>Edit</th>
                  <th>Delete</th>
               </tr>
                </thead>
                <tbody id="example_tbody">
				<?php
					$show=mysqli_query($con,"select * from task_rows where delete_status=0 order by id desc");
					$i=0;
					while($row=mysqli_fetch_array($show))
					{
						?>
						<tr>
							<td><?php echo ++$i; ?></td>
							<td><?php echo substr($row['title'],0,25); ?></td>
							<td>
						<?php $team_id=mysqli_query($con,"select * from tasks where id=".$row['task_id']);
						while($row1=mysqli_fetch_array($team_id))
							{
							$name=mysqli_query($con,"select * from team where id=".$row1['team_id']);
							while($row3=mysqli_fetch_array($name))
							{
						?>
								<?=$row3['name']?>
					<?php 	}
							} ?>
							</td>
							<td>
								 <a style="color:#fff;" class="btn btn-info btn-rounded btn-sm" href="edit_task.php?id=<?php echo $row['id']; ?>">
									<span class="fa fa-edit"></span>
								</a>
							</td>
							<td>
								<a class="mb-control1 btn btn-danger btn-rounded btn-sm" onclick="return confirm('Are you sure ?')" href="delete_task.php?id=<?php echo $row['id']; ?>">
									<span class="fa fa-times"></span>
								</a>
							</td>
						</tr>
						<?php
					}
				?>
               </tbody>
              </table>
            </form>
      </div>
	</div> -->
</div>
</div>
<table style="display: none;" id="second_table">
	<tbody id="second_tbody">
		<tr class="main_tr">
			<th class="p-3">Task-Title:</th>
				<td><input type="text" class="form-control" required></td>
				<td class="form-inline">
					<!-- <select class="form-control team">
						<option value="0">-Select Team-</option>
					</select> -->
					<b>Estimated Time: </b><input type="number" class="form-control ml-2" step="0.01" name="estimated_time" required>
				</td>
				<td colspan="2">
				<a href="#" class="btn btn-success add">+</a>
				<a href="#" class="btn btn-danger rem">-</a>
			</td>
		</tr>
	</tbody>
</table>
<script type="text/javascript">
	$(document).ready(function() {
		$('#project').on('change', function(event) {
			var value=$(this).val();
			$.ajax({
					url: 'get_team.php',
					type: 'GET',
					data: {id: value},
					success:function(data){
					//console.log(data);
					$('.team').html(data);
				}
				});
		});
		var tr=$('table#second_table tbody#second_tbody tr.main_tr').clone();
			$('table#main_table tbody#main_tbody').append(tr);
			rename();
		$(document).on('click', '.add', function() {
			var tr=$('table#second_table tbody#second_tbody tr.main_tr').clone();
			$('table#main_table tbody#main_tbody').append(tr);
			rename();
		});
		$(document).on('click', '.rem', function() {
			var len=$('table#main_table tbody#main_tbody tr').length;
			if(len>1){
				if(confirm('Are You Sure Want to Delete'))
				{
					$(this).closest('tr').remove();
					rename();
				}
			}else{
				alert('Cant delete');
				}
		});
		$('input[type=submit]').click(function() {
			if($('#project').val()=="0")
			{
				//$('#project').addClass('border-danger');
				$('#err').text('*Select Project!').addClass('text-danger');
				return false;
			}
			if($('#project').val()!="0")
			{
				$('#project').addClass('border-success');
				$('#err').text('');
			}
			if($('#team').val()=="0")
			{
				//$('#team').addClass('border-danger');
				$('#err').text('*Select Team!').addClass('text-danger');
				return false;
			}
			if($('#team').val()!="0")
			{
				$('#team').addClass('border-success');
				$('#err').text('');
			}
		});
	});
	function rename(){
		var i=0;
		$('table#main_table tbody#main_tbody tr.main_tr').each(function() {
			$(this).find('td:nth-child(2) input').attr({name: "task["+i+"][row]"});
			$(this).find('td:nth-child(3) input').attr({name: "task["+i+"][team_id]"});
			i++;
		});
	}
</script>
