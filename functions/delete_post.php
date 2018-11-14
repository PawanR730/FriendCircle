<?php

session_start();

$con = mysqli_connect("localhost","root","","social_media") or die("Connection was not established");
$email = $_SESSION['user_email'];
		$get_user = "select * from Usertable where email='$email'";
		$run_user = mysqli_query($con,$get_user);
		$row = mysqli_fetch_array($run_user);
   $user_id=$row['user_id'];

if(isset($_GET['post_id'])){ 
	$post_id = $_GET['post_id'];
	$delete_post = "delete from posttable where post_id='$post_id'"; 
	$run_delete = mysqli_query($con, $delete_post);
	if($run_delete){
	 echo "<script>alert('A Post have been deleted!')</script>";
	 echo "<script>window.open('../profile.php?user_id=$user_id', '_self')</script>";
	 }
} 
?>
