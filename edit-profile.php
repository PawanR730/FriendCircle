<!DOCTYPE html>
<?php
session_start();
include("includes/header.php");

if(!isset($_SESSION['user_email'])){
	header("location: main.php");
}
?>
<html>
<head>
	<title>Edit Account Settings</title>
	<meta charset="utf-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="style/home_style2.css">
</head>
<body>
<div class="row">
	<div class="col-sm-2">
	</div>
	<div class="col-sm-8">
		<form action="" method="post" enctype="multipart/form-data">
			<table class="table table-borderered table-hover">
				<tr align="center">
					<td colspan="6" class="active"><h2>Edit Your Profile</h2></td>
				</tr>
				<tr>
					<td style="font-weight: bold;">Change Your Firstname </td>
					<td>
						<input class =" form-control" type="text" name="f_name" required value="<?php echo $fname; ?>">
					</td>
				</tr>
				<tr>
					<td style="font-weight: bold;">Change Your Lastname</td>
					<td>
						<input class ="form-control" type="text" name="l_name" required value="<?php echo $lname; ?>">
					</td>
				</tr>
				<tr>
					<td style="font-weight: bold;">Description</td>
					<td>
						<input class =" form-control" type="text" name="describe_user" required value="<?php echo $description; ?>">
					</td>
				</tr>
				<tr>
					<td style="font-weight: bold;">Relationship</td>
					<td>
						<input class =" form-control" type="text" name="Relationship" required value="<?php echo $relationship; ?>">
					</td>
				</tr>
				<tr>
					<td style="font-weight: bold;">Password</td>
					<td>
						<input class =" form-control" type="password" name="u_pass" id="mypass" required value="<?php echo $password; ?>">
						<!-- <input type="checkbox" onclick="show_password()"><strong>Show Password</strong> -->
					</td>   
				</tr>
				<tr>
					<td style="font-weight: bold;">Email</td>
					<td>
						<input class =" form-control" type="text" name="u_email" required value="<?php echo $email; ?>">
					</td>
				</tr>
				<tr>
					<td style="font-weight: bold;">Country</td>
					<td>
						<select class ="form-control" name="u_country">
							<option><?php echo "Select your Country"; ?></option>
							<option>India</option>
							<option>Pakistan</option>
							<option>Australia</option>
							<option>UAE</option>
							<option>UK</option>
							<option>USA</option>
						</select>
					</td>
				</tr>
				<tr>
					<td style="font-weight: bold;">Gender</td>
					<td>
						<select class ="form-control" name="u_gender">
							<option><?php echo "Select Gender"; ?></option>
							<option>Male</option>
							<option>Female</option>
							<option>Other</option>
						</select>
					</td>
				</tr>
				<tr>
					<td style="font-weight: bold;">Birthdate</td>
					<td>
						<input class =" form-control input-md" type="date" name="u_birthday" required value="<?php echo $birthday; ?>">
					</td>
				</tr>
				<tr align="center">
					<td colspan="6">
					<input class="btn btn-info" type="Submit" name="update" style="width:250px;" value="Update">
					</td>
				</tr>
			</table>
		</form>
	</div>
</div>
</body>
</html>
<?php
	if(isset($_POST['update'])){
	$f_name = htmlentities($_POST['f_name']);
	$l_name = htmlentities($_POST['l_name']);
	$describe_user = htmlentities($_POST['describe_user']);
	$Relationship = htmlentities($_POST['Relationship']);
	$u_pass = htmlentities($_POST['u_pass']);
	$u_email = htmlentities($_POST['u_email']);
	$u_country = htmlentities($_POST['u_country']); 
	$u_gender=htmlentities($_POST['u_gender']);
	$u_birthday = htmlentities($_POST['u_birthday']);
	$u_pass=md5($u_pass);

	$update = "update Usertable set first_name='$f_name', last_name='$l_name', description='$describe_user', relationship='$Relationship',
	password='$u_pass',email='$u_email',country='$u_country', gender='$u_gender' , birth_date='$u_birthday' where user_id='$user_id';";

	$run = mysqli_query($con,$update);

	if($run){
		echo"<script>alert('Your Profile is Updated')</script>";

		echo"<script>window.open('home.php?user_id=$user_id','_self')</script>";
	}
	}
?>