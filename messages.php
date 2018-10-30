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
	<title>Messages</title>
	<meta charset="utf-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="style/home_style2.css">

</head>
<style>
	#scroll_messages{
		max-height: 500px;
		overflow: scroll;
	}
	#btn-msg{
		width: 20%;
		height: 28px;
		border-radius: 5px;
		margin: 5px;
		border: none;
		color: #fff;
		float: right;
		background-color: #2ecc71;
	}
	#select_user{
		max-height: 500px;
		overflow: scroll;
	}
	#green{
		background-color: #3498db;
		border-color: #2980b9;
		width: 45%;
		padding: 2.5px;
		font-size: 16px;
		border-radius: 3px;
		float: right;
		margin-bottom: 5px; 
	}
	#blue{
		background-color: #2ecc71;
		border-color: #27ae60;
		width: 45%;
		padding: 2.5px;
		font-size: 16px;
		border-radius: 3px;
		float: right;
		margin-bottom: 5px; 
	}
</style>
<body>
<div class="row">
<?php
	if(isset($_GET['user_id'])){
		global $con;

		$get_id=$_GET['user_id'];

		$get_user="select * from Usertable where user_id='$get_id'";

		$run_user=mysqli_query($con,$get_user);
		$row_user=mysqli_fetch_array($run_user);

		$user_to_msg=$row_user['user_id'];
		echo $user_to_msg;
		//user retraival
		$user_to_name=$row_user['first_name'];

	}
	$user=$_SESSION['user_email'];
	$get_user="select * from Usertable where email='$user'";
	$run_user=mysqli_query($con,$get_user);
	//$row=mysqli_query($con,$get_user);
	$row=mysqli_fetch_array($run_user);

	$user_from_msg=$row['user_id'];
	$user_from_name=$row['first_name'];
?>	
	<div class="col-sm-3" id="select_user">
		<?php
			global $con;
			
			$user=$_SESSION['user_email'];
	$get_user="select * from Usertable where email='$user'";
	$run_user=mysqli_query($con,$get_user);
	
	$row=mysqli_fetch_array($run_user);
	$user_from_msg=$row['user_id'];
	$user="select * from Usertable where user_id in (select user2_id from friendship where user1_id='$user_from_msg' )";

		
			//$user="select * from Usertable ";

			$run_user=mysqli_query($con,$user);
			while($row_user=mysqli_fetch_array($run_user)){
				$user_id=$row_user['user_id'];
				//$user_name=$row_user['user_name'];
				$first_name=$row_user['first_name'];
				$last_name=$row_user['last_name'];
				$user_image=$row_user['profile_pic'];

				echo "
					<div class='container-fluid'>
						<a style='text-decoration: none;cursor: pointer;color: #3897F0;'
						href='messages.php?user_id=$user_id'>
						<img class='img-circle' src='users/$user_image' width='90px'
						height='80px' > <strong>&nbsp $first_name
						$last_name</strong><br><br>
						</a>
					</div>
					";
			}
		?>
	</div>
	<div class="col-sm-6">
		<div class="load_msg" id="scroll_messages">
			<?php
				//global $con;
				$sel_msg="select * from user_messages where (user_to='$user_to_msg' AND user_from='$user_from_msg') 
				OR (user_from='$user_to_msg' AND user_to='$user_from_msg') ORDER by date ASC";

				$run_msg=mysqli_query($con,$sel_msg);

				while($row_msg=mysqli_fetch_array($run_msg)){
					$user_to=$row_msg['user_to'];
					$user_from=$row_msg['user_from'];
					$msg_body=$row_msg['msg_body'];
					$msg_date=$row_msg['date'];
					?>
					<div id="loaded_msg">
						<p>
							<?php if($user_to==$user_to_msg AND $user_from==$user_from_msg){
								echo "<div class='message' id='blue' data-toggle='tooltip' title='$msg_date'>$msg_body</div><br><br><br>";}
								else if($user_from==$user_to_msg AND $user_to==$user_from_msg){
									echo"<div class='message' id='green' data-toggle='tooltip' title='$msg_date'>$msg_body</div><br><br><br>"; }?>
						</p>
					</div>
					<?php
				}
			?>
		</div>
		<?php
			if(isset($_GET['user_id'])){
				$u_id=$_GET['user_id'];
				if($u_id=="new"){
					echo"
						<form action='' method='POST'>
							<center><h3> Select Someone to start conversation</h3>
							</center>
							<textarea class='form-control' placeholder='Enter Your message'></textarea> 
							<input type='submit' class='btn btn-default'  disabled value='Send'>
						</form><br><br>
					";
				}
				else{
					echo "<form action='' method='POST'>
							<textarea class='form-control' value='' placeholder='Enter Your message' name='msg_box' id='message_textarea'></textarea> 
							<input type='submit' name='send_message' id='btn-msg' value='Send'>
						</form><br><br>
						";
				}
			}
		?>
		<?php
			if(isset($_POST['send_message'])){
				$msg=htmlentities($_POST['msg_box']);
				if(strlen($msg)<=0){
					echo"<h4 style='color:red;text-align:center;'>Empty Message NOT SENT!></h4>";
				}
				else if(strlen($msg)>255){
					echo"<h4 style='color:red;text-aligin:center;'>Message is too long ! use only 255 characters</h4>";
				}
				else{
					
					$insert="insert into user_messages(
					user_to,user_from,msg_body,date,msg_seen) values 
					('$user_to_msg','$user_from_msg','$msg',NOW(),'yes')";

					$run_insert=mysqli_query($con,$insert);
					echo "<script>window.open('messages.php?user_id=$user_to_msg','_self')</script>";
				}
			}
		?>
	</div>
	<div class="col-sm-3">
		<?php
			if(isset($_GET['user_id'])){
				global $con;

				$get_id=$_GET['user_id'];

				$get_user="select * from Usertable where user_id='$get_id'";
				$run_user=mysqli_query($con,$get_user);
				$row=mysqli_fetch_array($run_user);

				$user_id=$row['user_id'];
				//$user_name=$row['user_name'];
				$f_name=$row['first_name'];
				$l_name=$row['last_name'];
				$describe_user=$row['description'];
				$user_country=$row['country'];
				$user_image=$row['profile_pic'];
				$register_date=$row['date_registered'];
				$gender=$row['gender'];
		
		
		if($get_id=="new"){

		}
		else{
			echo" <div class='row'>
				<div class='col-sm-2'>
				</div>
				<center>
					<div style='background-color: #e6e6e6'; class='col-sm-9'>
						<h2>Information about</h2>
						<h2>$f_name</h2>
						<img  class= 'img-circle' src='users/$user_image' width='150' height='150'>
						<br><br>
						<ul class='list-group'>
							<li class='list-group-item' title='User status'><strong style='color: grey;'>
								$describe_user
							</strong>
							</li>
							<li class='list-group-item' title='Gender'>
								$gender
							</li>
							<li class='list-group-item' title='Country'>
								$user_country
							</li>
							<li class='list-group-item' title='User Registration date'>
								$register_date
							</li>
						</ul>
					</div>
					<div class='col-sm-1'>
						
					</div>
				</center>
			</div>";
		}
	}
	?>
	</div>
</div>
<script>
	var div=document.getElementById("scroll_messages");
	div.scrollTop=div.scroll_Height;
</script>
</body>
</html>