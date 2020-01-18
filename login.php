<?php 
require('config.php');
$status="";
if(isset($_REQUEST['login']))
{
	session_start();
	$userName=$_REQUEST['userName'];
	$password=base64_encode($_REQUEST['password']);
	$check=mysqli_query($con,"select * from team where username='$userName' and password='$password'");
	$f=0;
	while($res=mysqli_fetch_array($check))
	{
		$_SESSION['u_id']=$res['id'];
		$_SESSION['u_name']=$res['name'];
		$f=1;
	}
	if($f==0){
		$status="fail";
		$message="Invalid Credentials";
		session_destroy();
	}
	else{
		header("location:index.php");
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<script src="https://kit.fontawesome.com/a2c04e75f7.js"></script>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('#open').click(function(event) {
			$('#shp').attr('type', 'text');
			$(this).hide();
			$('#close').show();
		});
		$('#close').click(function(event) {
			$('#shp').attr('type', 'password');
			$(this).hide();
			$('#open').show();
		});
	});
</script>
<style type="text/css">
	#open,#close{
		position: absolute;
		top: 163px;
		left: 470px;
	}
</style>
</head>
<body>	
<div class="container mt-5 pt-5">
	<div class="col-12">
		  <div class="container pt-2">
		  	<div class="col-6 offset-3 border shadow text-center">
		<h1 class="text-center font-weight-normal mt-2">LOGIN</h1>
		<form accept="login.php" method="post">
		<table class="table text-center">
			<tr>
				<th>
					<p class="p-1">User Name:</p>
				</th>
				<td>
					<input type="text" name="userName" class="form-control text-center">
				</td>
			</tr>
			<tr>
				<th>
					<p class="p-1">Password:</p>
				</th>
				<td>
					<input type="Password" name="password" class="form-control text-center" id="shp"><i class="far fa-eye" id="open"></i><i style="display: none;" class="far fa-eye-slash" id="close"></i>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input type="submit" name="login" class="btn btn-success" value="LOGIN"><br><br>
				</td>
			</tr>
		</table>
	</form>
	<?php if(isset($_REQUEST['s']))
		{
			$status="fail";
			$message="Logged Out Successfully";
		}
		if($status == 'fail')
			{ ?>
				<div class="alert alert-danger w-75 offset-2 text-center" role="alert">
				<button type="button" class="close" data-dismiss="alert">
				<span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
				<strong><?php echo $message; ?></strong> 
				</div>
		  <?php }?>
		  <?php if(isset($_REQUEST['a']))
		{
			$status="success";
			$message="You can Now Login";
		}
		if($status == 'success')
			{ ?>
				<div class="alert alert-success w-75 offset-2 text-center" role="alert">
				<button type="button" class="close" data-dismiss="alert">
				<span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
				<strong><?php echo $message; ?></strong> 
				</div>
		  <?php }?>
</div>
</div>
	</div>
</div>
</body>
</html>