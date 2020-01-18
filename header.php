<?php
session_start();
if(!isset($_SESSION['u_id']))
{header("location:login.php");}
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="admin/dist/bootstrap-clockpicker.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://kit.fontawesome.com/a2c04e75f7.js"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>	
<script type="text/javascript" src="admin/dist/bootstrap-clockpicker.min.js"></script>
<style type="text/css">
.dropdown-menu {display: block;visibility: hidden;opacity:0;transform: translateY(50px);transition:.5s ease all;}
.dropdown-menu.show {display: block;visibility: visible;opacity:1;transform: translateY(0px);transition:.5s ease all;}
.cust{
	height: 350px;
	overflow-y: scroll;
	overflow-x: hidden;
}
</style>
<script type="text/javascript">
	$(document).ready(function() {	
$('.clockpicker').clockpicker()
	.find('input').change(function(){
		// TODO: time changed
		console.log(this.value);
	});
$('#demo-input').clockpicker({
	autoclose: true
});

if (something) {
	// Manual operations (after clockpicker is initialized).
	$('#demo-input').clockpicker('show') // Or hide, remove ...
			.clockpicker('toggleView', 'minutes');
}
});
</script>
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

		//var password_error = false;
		$('#error_password').hide();

		$('#shp').focusout(function() {
			check();
		});
		$('#pas').click(function() {
			var cp=$('#shp').val();
			var p=$('#pass').val();
			if(p!=cp){
				$('#error_password').html("*password didnt match").addClass('text-danger');
				$('#error_password').show();
				return false;
			}
			else{
				$('#error_password').hide();
				return true;
			}
		});

	});
		function check(){
			var cp=$('#shp').val();
			var p=$('#pass').val();
			if(p!=cp){
				$('#error_password').html("*password didnt match").addClass('text-danger');
				$('#error_password').show();
				return false;
			}
			else{
				$('#error_password').hide();
				return true;
			}
		}
</script>
</head>
<body>
<nav class="navbar bg-dark navbar-dark navbar-expand">
		<div class="container">
		<a href="#" class="navbar-brand"><h1 class="font-weight-normal">PHP Poets</h1></a>
		<ul class="navbar-nav"> 
			<li>
				<a href="index.php" class="nav-link">My Projects</a>
			</li>
			<li>
				<a href="add_task.php" class="nav-link">Add Task</a>
			</li>
			<li>
				<a href="logout.php" class="nav-link">Logout</a>
			</li>

			<li>
				<p class="text-white nav-link">Hi, <?php echo$_SESSION['u_name']?></p>
			</li>
		</ul>
	</div>
	</nav>
	<br>
