<?php
require('config.php');
$name="";
$password="";
if(isset($_REQUEST['add']))
{
	$name=$_REQUEST['name'];
	$userName=$_REQUEST['userName'];
	$password=base64_encode($_REQUEST['password']);
	$check=mysqli_query($con,"select * from team where username='$userName'");
	$f=0;
	while($res=mysqli_fetch_array($check))
	{
		$f=1;
	}
	if($f==0){
	mysqli_query($con,"insert into team(name,username,password) values('$name','$userName','$password')");
	header("location:login.php?a=success");
	}
	else{
		echo "<script>alert('Choose Another Username')</script>";
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Add Team</title>
		<script src="https://kit.fontawesome.com/a2c04e75f7.js"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<style type="text/css">
	#open,#close{
		position: absolute;
		top: 205px;
		left: 640px;
	}
</style>
</head>
<body>
	<div class="container mt-5 pt-5">
		<div class="col-12">
			<div class="container">
				<div class="col-8 offset-2 border shadow">
			<h2 class="text-center font-weight-normal mt-2">Add User</h2>
			<form action="create.php" method="post">
			<table class="table text-center">
				<tr>
					<th colspan="2" align="center">
						Name:
					</th>
					<td colspan="2" align="center">
						<input type="text" name="name" class="form-control" value="<?=$name?>" required>
					</td>
					</tr>
					<tr>
					<th colspan="2" align="center">
						User Name:
					</th>
					<td colspan="2" align="center">
						<input type="text" name="userName" class="form-control" required>
					</td>
					</tr>
					<tr>
					<th>
						Password:
					</th>
					<td>
						<input type="text" name="password" class="form-control" value="<?=base64_decode($password)?>" required>
					</td>
					<th>
						Confirm Password:
					</th>
					<td>
						<input type="password" name="cpass" class="form-control" id="shp" required><i class="far fa-eye" id="open"></i><i style="display: none;" class="far fa-eye-slash" id="close"></i>
					</td>
				</tr>
				<tr>
					<td colspan="4">
						<input type="submit" name="add" value="Add User" class="btn btn-success"><br><br>
						<a href="login.php">Login</a>
					</td>
				</tr>
			</table>
		</form>
			<p class="text-center" id="er"></p>
		</div></div>
		</div>
	</div>
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

			$('input[type=submit]').click(function(event) {
				/* Act on the event */
				var password=$('input[name=password]').val();
				var confirm=$('input[name=cpass]').val();
				if(password!=confirm)
				{
					$('#er').text('*Password Didnt Match!').css('color', 'red');
					return false;
				}
				else{
					$('#er').text('');
					return true;
				}
			});
		});
	</script>
</body>
</html>