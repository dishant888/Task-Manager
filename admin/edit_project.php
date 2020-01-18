<?php 
require('config.php');
$status="";
require('header.php'); 
$id=$_REQUEST['id'];
?>
<link href="admin_assest/admin_css/jquery.dataTables.min.css" rel="stylesheet" />
 
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
  	<div class="container-fluid">
    <!-- Main content -->
    <section class="content">
       <div class="row">
        <div class="col-md-12">
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
		  	$message="Project Deleted";
		  }
		  if(isset($_REQUEST['a']))
		  {
		  	$status="fail";
		  	$message="Project with this Name Already exists";
		  }
		   if($status == 'fail')
			{ ?>
				<div class="alert alert-danger" role="alert">
				<button type="button" class="close" data-dismiss="alert">
				<span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
				<strong><?php echo $message; ?></strong> 
				</div>
		  <?php }?>		

		  </div>
        <!-- left column -->
        <div class="col-5">
		<form role="form" method="post" action="add_project.php">
		<div class="box box-primary">
			<div class="box-header with-border">
			  <h3 class="box-title">Edit Project</h3>
			</div>
			<div class="box-body">
				<div class="box-body">
					<?php $get_det=mysqli_query($con,"select * from projects where id=$id");
						  while($row=mysqli_fetch_array($get_det))
						  {
					 ?>
					<div class="form-group">
						<label>Project</label>
						<input class="form-control" name="name" id="add" type="text" placeholder="Project Name" value='<?=$row['name']?>' required>
					</div>
					<div class="form-group">
						<label>Company</label>
						<input class="form-control" name="company" id="add" type="text" placeholder="Company Name(Optional)" value='<?=$row['company']?>'>
					</div>
					<div class="form-group">
						<label>Contact</label>
						<input class="form-control" name="contact" id="add" type="text" placeholder="Contact Detailes(Optional)" value='<?=$row['contact']?>'>
					</div>
					<div class="form-group">
						<label>Select Users</label>
						<select name="team[]" id="team" multiple class="form-control" required>
						<?php 
						$selecteds=mysqli_query($con,"select * from project_assigned_to where project_id=$id");
						$team_ids=[];
						foreach($selecteds as $selected){ 
						$team_ids[$selected['team_id']] = $selected['team_id'];
						}
						$teams=mysqli_query($con,"select * from team where delete_status=0");
						$i=0;
						foreach ($teams as $team) {							
							if(@$team_ids[@$team['id']] == @$team['id']){
						 ?>
						 <option value="<?=$team['id']?>" selected><?=$team['name']?></option>
						<?php }else{ ?>
						 <option value="<?=$team['id']?>"><?=$team['name']?></option>
						<?php } }
						?>
					</select>
					</div>
				 </div>
			</div> 
		<?php } ?>
			<div class="box-footer">
						<input type="hidden" name="id" value="<?=$id?>">
	         <button type="submit" id="pro_add" class="btn btn-info pull-right" name="edit">Save Changes</button><br><label id="exists"></label>
	        </div>      
        </div>
      </form>
  </div> 
    <div class="col-7">
          <h3 class="box-title">View Project</h3>
        <div class="box-body cust">
        <div id="cStatus"> </div>
        <form action="admin/update_Sequence" method="post">
          <table id="example1" class="w-100 table-striped text-center">
                <thead>
                <tr>
                  <th># </th>
                  <th>Project</th>
                  <th>Company</th>
                  <th>Contact</th>
                  <th>Edit</th>
                  <th>Delete</th>
               </tr>
                </thead>
                <tbody id="example_tbody">
				<?php
					$show=mysqli_query($con,"select * from projects where delete_status=0  order by id desc");
					$i=0;
					while($row=mysqli_fetch_array($show))
					{
						?>
						<tr>
							<td><?php echo ++$i; ?></td>
							<td><?php echo substr($row['name'],0,20); echo strlen($row['name'])>20?'...':'';?></td>
							<td><?php if($row['company']==""){echo "--";}else{echo substr($row['name'],0,20);}echo strlen($row['company'])>20?'...':'';?></td>
							<td><?php if($row['contact']==""){echo "--";}else{echo substr($row['name'],0,20);}echo strlen($row['contact'])>20?'...':'';?></td>
							<td>	
								<a style="color:#fff;" class="btn btn-info btn-rounded btn-sm" href="edit_project.php?id=<?php echo $row['id']; ?>">
									<span class="fa fa-edit"></span>
								</a>
							</td>
							<td>
								<a class="mb-control1 btn btn-danger btn-rounded btn-sm" onclick="return confirm('Are you sure ?All the Tasks Related to this project will be deleted')" href="delete_project.php?id=<?php echo $row['id']; ?>">
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
      <!-- /.box -->
  		</div>

	</div>
    </section>
    <!-- /.content -->
</div>
   </div>