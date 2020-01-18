<?php 
require('config.php');
$status="";
require('header.php'); 
$id=$_REQUEST['id'];
$search=mysqli_query($con,"select * from team where id=$id");
while($row=mysqli_fetch_array($search))
{
	$oldname=$row['name'];
	$user=$row['username'];
	$pass=base64_decode($row['password']);
}
?>

 <style type="text/css">
 	#open,#close{
 		position: relative;
 		top: -30px;
 		left: 500px;
 	}
 </style>
<link href="admin_assest/admin_css/jquery.dataTables.min.css" rel="stylesheet" />
 
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
  	<div class="container">
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
		  <?php if($status == 'fail')
			{ ?>
				<div class="alert alert-danger" role="alert">
				<button type="button" class="close" data-dismiss="alert">
				<span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
				<strong><?php echo $message; ?></strong> 
				</div>
		  <?php }?>		

		  </div>
        <!-- left column -->
        <div class="col-6">
		<form role="form" method="post" action="add_team.php">
		<div class="box box-primary">
			<div class="box-header with-border">
			  <h3 class="box-title">Edit Team</h3>
			</div>
			<div class="box-body">
				<div class="box-body">
					<div class="form-group">
						<label>Name</label>
						<input class="form-control" name="name" type="text" placeholder="Name" value="<?=$oldname?>" required>
						<input type="hidden" name="id" value="<?=$id?>">
					</div>
					<div class="form-group">
						<label>User Name:</label>
						<input class="form-control" name="username" type="text" placeholder="User Name" value='<?=$user?>' required>
					</div>
					<div class="form-group">
						<label>Password</label>
						<input class="form-control" name="password" type="password" placeholder="Password" value='<?=$pass?>' id="shp" required><i class="far fa-eye" id="open"></i><i style="display: none;" class="far fa-eye-slash" id="close"></i>
					</div>	
				 </div>
			</div> 
			<div class="box-footer">
	         <button type="submit" class="btn btn-info pull-right" name="edit">Save Changes</button>
	         <a class="mb-control1 btn btn-danger btn-rounded" onclick="return confirm('Are you sure ?')" href="delete_team.php?id=<?php echo $id; ?>">Delete</a>
	        </div>      
        </div>
      </form>
  </div> 
    <div class="col-6">
          <h3 class="box-title">View Team</h3>
        <div class="box-body cust">
        <div id="cStatus"> </div>
        <form action="admin/update_Sequence" method="post">
          <table id="example1" class="table table-striped text-center">
                <thead>
                <tr>
                  <th># </th>
                  <th>Name</th>
                  <th>Edit</th>
                  <th>Delete</th>
               </tr>
                </thead>
                <tbody>
				<?php
					$show=mysqli_query($con,"select * from team where delete_status=0 order by id desc");
					$i=0;
					while($row=mysqli_fetch_array($show))
					{
						?>
						<tr>
							<td><?php echo ++$i; ?></td>
							<td><?php echo $row['name']; ?></td>
							
							<td>	
								<a style="color:#fff;" class="btn btn-info btn-rounded btn-sm" href="edit_team.php?id=<?php echo $row['id']; ?>">
									<span class="fa fa-edit"></span>
								</a>
							</td>
							<td>
								<a class="mb-control1 btn btn-danger btn-rounded btn-sm" onclick="return confirm('Are you sure ?')" href="delete_team.php?id=<?php echo $row['id']; ?>">
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
