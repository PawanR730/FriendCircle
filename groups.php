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
	<title>Group Chat</title>
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
	<div class="col-sm-3" id="select_user">
		<?php
			global $con;
			$user=$_SESSION['user_email'];
			$get_user="select * from Usertable where email='$user'";
			$run_user=mysqli_query($con,$get_user);
			$row=mysqli_fetch_array($run_user);
			$creator=$row['user_id'];
			$groups="select * from grouptable where (creatorid='$creator' or memberid='$creator') group by groupname";
			$run_groups=mysqli_query($con,$groups);
			if ($run_groups!=""){
			while($row_user=mysqli_fetch_array($run_groups)){
				$grpname=$row_user['groupname'];
				echo "
					<div class='container-fluid'>
						<a style='text-decoration: none;cursor: pointer;color: #3897F0;'
						href='groups.php?group_id=$grpname'>
						 <strong>$grpname</strong><br><br>
						</a>
					</div>
					";
			}
		}
		?>
			<?php
				global $con;
				$user=$_SESSION['user_email'];
				$get_user="select * from Usertable where email='$user'";
				$run_user=mysqli_query($con,$get_user);
				$row=mysqli_fetch_array($run_user);
				$user_id=$row['user_id'];
				echo '
					<form action="" method="POST">
					<input type="text" class="form-control" name="grpname" placeholder="Create New Group">
					<button type="submit" class="btn btn-info" name="submitgrp">Create Group</button></form>
				';

				if (isset($_POST['submitgrp'])){
					$grp = htmlentities($_POST['grpname']);
					if($grp!=""){
					echo "<script >alert($grp)</script>";
					$insert="insert into grouptable (groupname,creatorid,memberid,datecreated) values ('$grp','$user_id','$user_id',NOW())";
					$run_insert=mysqli_query($con,$insert);	
					echo "<script>window.open('groups.php?group_id=new','_self')</script>";
				}
				}
			
			?>
		</div>
	<div class="col-sm-6">
		<div class="load_msg" id="scroll_messages">
			<?php
				global $con;
				$user=$_SESSION['user_email'];
				$get_user="select * from Usertable where email='$user'";
				$run_user=mysqli_query($con,$get_user);
				$row=mysqli_fetch_array($run_user);
				$msg_from=$row['user_id'];
				$grpname=$_GET['group_id'];
				$sel_msg="select * from groupmessage where (groupname='$grpname') ORDER by sendtime ASC";
				$run_msg=mysqli_query($con,$sel_msg);
				while($row_msg=mysqli_fetch_array($run_msg)){
					$user_from=$row_msg['sender'];
					$get_user2="select * from Usertable where user_id='$user_from'";
					$run_user2=mysqli_query($con,$get_user2);
					$row2=mysqli_fetch_array($run_user2);
					$sendername=$row2['first_name'];
					$msg_body=$row_msg['message'];
					$msg_date=$row_msg['sendtime'];
					?>
					<div id="loaded_msg">
						<p>
							<?php 
								if($user_from==$msg_from){
								echo "<div class='message' id='blue' data-toggle='tooltip' title='$msg_date'><h6>$msg_date </h6>$msg_body</div><br><br><br>";}
								else {
								echo"<div class='message' id='green' data-toggle='tooltip' title='$msg_date'><h6>$msg_date  sender: $sendername</h6>$msg_body</div><br><br><br>"; }
							?>
						</p>
					</div>
			<?php
				}
				?>
			
		</div>
		<?php
			if(isset($_GET['group_id'])){
				$u_id=$_GET['group_id'];
				if($u_id=="new"){
					echo"
						<form action='' method='POST'>
							<center><h3> Select a Group to start chat</h3>
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
			global $con;
			if(isset($_POST['send_message'])){
				$name_group=$_GET['group_id'];
				$msg=htmlentities($_POST['msg_box']);
				if(strlen($msg)<=0){
					echo"<h4 style='color:red;text-align:center;'>Empty Message NOT SENT!></h4>";
				}
				else if(strlen($msg)>255){
					echo"<h4 style='color:red;text-aligin:center;'>Message is too long ! use only 255 characters</h4>";
				}
				else{
					$user=$_SESSION['user_email'];
					$get_user="select * from Usertable where email='$user'";
					$run_user=mysqli_query($con,$get_user);
					$row=mysqli_fetch_array($run_user);
					$sender=$row['user_id'];
					$name_group=$_GET['group_id'];
					$insert="insert into groupmessage(
					groupname,message,sender,sendtime) values 
					('$name_group','$msg','$sender',NOW())";
					$run_insert=mysqli_query($con,$insert);
					echo "<script>window.open('groups.php?group_id=$name_group','_self')</script>";
				}
			}
		?>
	</div>
	<div class="col-sm-3">
		<?php
			$name_group=$_GET['group_id'];
			if($name_group=="new"){
			}
			else{
				global $con;
				$mail=$_SESSION['user_email'];
				$get_user="select * from Usertable where email='$mail'";
				$run_user=mysqli_query($con,$get_user);
				$row=mysqli_fetch_array($run_user);
				$cur_user=$row['user_id'];
				$get_user = "select * from grouptable where groupname = '$name_group'";
				$run_user2=mysqli_query($con,$get_user);
				$row=mysqli_fetch_array($run_user2);
				$creator=$row['creatorid'];
				$get_user2="select * from Usertable where user_id='$creator'";
				$run_user2=mysqli_query($con,$get_user2);
				$row2=mysqli_fetch_array($run_user2);
				$createdby=$row2['first_name'];

				echo"<div style='background-color: #e6e6e6'; class='col-sm-9'>
							<ul class='list-group'>
								<li class='list-group-item'>
								<h6>Created by:$createdby</h6>
								</li>
							</ul>
						</div>
						<div class='col-sm-1'></div>";	
				if ($cur_user!=$creator){
					$run_user=mysqli_query($con,$get_user);	
					while($row = mysqli_fetch_array($run_user)) {
						$user=$row['memberid'];
						$getname="select * from Usertable where user_id='$user'";
						$rungetname=mysqli_query($con,$getname);
						$name=mysqli_fetch_array($rungetname);	
						$fname=$name['first_name'];
						$lname=$name['last_name'];
						echo"<div style='background-color: #e6e6e6'; class='col-sm-9'>
							<ul class='list-group'>
								<li class='list-group-item'>
								$fname $lname
								</li>
							</ul>
						</div>
						<div class='col-sm-1'></div>";	

					}
				}

				else{

					$qry1="select * from friendship where user1_id='$cur_user' and user2_id in (select memberid from grouptable where groupname='$name_group' 
					and creatorid='$cur_user')";
					$run1=mysqli_query($con,$qry1);
					if($run1!=""){
					while($row1=mysqli_fetch_array($run1)){
						$nameid=$row1['user2_id'];
						$getname="select * from Usertable where user_id='$nameid'";
						$rungetname=mysqli_query($con,$getname);
						$name=mysqli_fetch_array($rungetname);	
						$fname=$name['first_name'];
						$lname=$name['last_name'];
						echo"<div style='background-color: #e6e6e6'; class='col-sm-9'>
						<ul class='list-group'>
							<li class='list-group-item'>
							$fname $lname
							</li>
						</ul>
						<form action='' method='POST'>
						<button type='submit' class='btn btn-danger' name='remove'>Remove</button></form>
						</div>
						<div class='col-sm-1'></div>";	
						if (isset($_POST['remove'])){
						$delete="delete from grouptable where groupname='$name_group' and memberid='$nameid'";
						$rundelete=mysqli_query($con,$delete);
						echo "<script>window.open('groups.php?group_id=$name_group','_self')</script>";
						break;

				}
			}}
				$qry2="select * from friendship where user1_id='$cur_user' and user2_id not in (select memberid from grouptable where groupname='$name_group' 
					and creatorid='$cur_user')";
					$run1=mysqli_query($con,$qry2);
					if($run1!=""){
					while($row1=mysqli_fetch_array($run1)){
						$nameid=$row1['user2_id'];
						$getname="select * from Usertable where user_id='$nameid'";
						$rungetname=mysqli_query($con,$getname);
						$name=mysqli_fetch_array($rungetname);	
						$fname=$name['first_name'];
						$lname=$name['last_name'];
						echo"<div style='background-color: #e6e6e6'; class='col-sm-9'>
						<ul class='list-group'>
							<li class='list-group-item'>
							$fname $lname
							</li>
						</ul>
						<form action='' method='POST'>
						<button type='submit' class='btn btn-success' name='add'>Add</button></form>
						</div>
						<div class='col-sm-1'></div>";
						
						if (isset($_POST['add'])){
						$insert="insert into grouptable(groupname,memberid,creatorid) values('$name_group','$nameid','$cur_user')";
						$runinsert=mysqli_query($con,$insert);
						echo "<script>window.open('groups.php?group_id=$name_group','_self')</script>";
						break;
					}

				}

			}}

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