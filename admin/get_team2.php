<?php 
$id=$_REQUEST['id'];
require('config.php');
if($id!=0){
$get_team=mysqli_query($con,"select * from project_assigned_to where project_id=$id");
echo "<option value='0'>----Select Team----</option>";
while($row=mysqli_fetch_array($get_team))
{
	$names=mysqli_query($con,"select * from team where id=".$row['team_id']);
	while($res=mysqli_fetch_array($names))
	{
?>
	<option value="<?=$res['id']?>"><?=$res['name']?></option>	
<?php
} } }else{
$team=mysqli_query($con,"select * from team where delete_status=0");
echo "<option value='0'>----Select Team----</option>";
while($team_row=mysqli_fetch_array($team))
	{
		?>
			<option value="<?=$team_row['id']?>"><?=$team_row['name']?></option>
		<?php	
	}
}
?>