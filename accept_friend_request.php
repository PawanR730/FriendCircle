<?php

   session_start();
include("functions/functions.php");
  $con = mysqli_connect("localhost","root","","social_media") or die("Connection was not established");
  $user1_id=$_GET['user_id'];

   $email = $_SESSION['user_email']; ///getting the user from the session variable
   $get_user ="select * from Usertable where  email='$email'";
		 		$run_user = mysqli_query($con,$get_user);
	 		$row = mysqli_fetch_array($run_user);

				$user2_id=$row['user_id'];
				$status='accepted';
                 
                ///check weather already friend or not
                /// check if the request is already sent or not

				$insert1="insert into friendship (user1_id,user2_id) values ('$user1_id','$user2_id')";
				$run_insert1=mysqli_query($con,$insert1);

				$insert2="insert into friendship (user2_id,user1_id) values ('$user1_id','$user2_id')";
				$run_insert2=mysqli_query($con,$insert2);





				$update="update friendrequests set status='$status' where user_from='$user1_id' and user_to='$user2_id'";
				$run_update=mysqli_query($con,$update);

				if($run_insert1&&$run_insert1&&$run_update)
				{
					echo "<script>alert('Friend Request Accepted')</script>";
					echo "<script>window.open('requests.php', '_self')</script>";
				}
				else
				{
					echo "<script>alert('Failed to accept friend request')</script>";

			       echo "<script>window.open('requests.php', '_self')</script>";
				}

?>