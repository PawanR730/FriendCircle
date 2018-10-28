
<?php

session_start();
include("functions/functions.php");
  $con = mysqli_connect("localhost","root","","friendcircle") or die("Connection was not established");
  $to_user_id=$_GET['user_id'];

   $email = $_SESSION['user_email']; ///getting the user from the session variable
   $get_user ="select * from Usertable where  email='$email'";
		 		$run_user = mysqli_query($con,$get_user);
	 		$row = mysqli_fetch_array($run_user);

				$from_user_id=$row['user_id'];
				$status='pending';
                 
                ///check weather already friend or not
                /// check if the request is already sent or not

				$insert="insert into friendrequests (user_from,user_to,status) values ('$from_user_id','$to_user_id','$status')";
				$run_insert=mysqli_query($con,$insert);

				if($run_insert)
				{
					echo "<script>alert('Friend Request sent')</script>";
					echo "<script>window.open('members.php', '_self')</script>";
				}
				else
				{
					echo "<script>alert('Failed to send friend Request')</script>";

			       echo "<script>window.open('members.php', '_self')</script>";
				}

?>