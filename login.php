<?php
session_start();

include("includes/connection.php");

	if (isset($_POST['login'])) {

		$email = htmlentities(mysqli_real_escape_string($con, $_POST['email']));
		$password = htmlentities(mysqli_real_escape_string($con, $_POST['password']));

		$select_user = "select * from Usertable where email='$email' AND password='$password' ";
		$query= mysqli_query($con, $select_user);
		$check_user = mysqli_num_rows($query);

		if($check_user == 1){
			$_SESSION['user_email'] = $email;

			echo "<script>window.open('home.php', '_self')</script>";
			exit(0);
		}else{
			echo"<script>alert('Your Email or Password is incorrect')</script>";
		}
	}
?>