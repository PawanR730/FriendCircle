<?php
include('includes/connection.php');
if(isset($_POST['register']))
{
	$email=htmlentities(mysqli_real_escape_string($con,$_POST['email']));
	$fname=htmlentities(mysqli_real_escape_string($con,$_POST['fname']));
	$lname=htmlentities(mysqli_real_escape_string($con,$_POST['lname']));
	$country=htmlentities(mysqli_real_escape_string($con,$_POST['country']));
	$password=htmlentities(mysqli_real_escape_string($con,$_POST['password']));
	$password1=htmlentities(mysqli_real_escape_string($con,$_POST['password1']));
	$birthdate=htmlentities(mysqli_real_escape_string($con,$_POST['birthdate']));
	$gender=htmlentities(mysqli_real_escape_string($con,$_POST['gender']));
	$description="yet-to-update";
	$relationship="yet-to-update";
	$password=md5($password);
	$password1=md5($password1);
	
	$posts="no";
	if($gender=='Male')
	$profile_pic='male.png';
    else 
    {
    	$profile_pic='female.png';
    }
    $cover_pic='sea.jpeg';

	//echo "$email.$fname.$lname.$country.$password.$birthdate.$gender.$posts";
	
    
    if(strcmp($password,$password1)>0||strcmp($password, $password1)<0)
    {
    	echo "<script>alert('Recheck Password Entered')</script>";
    	echo "<script>window.open('register-form.php','_self')</script>";
    	exit(0);

    }
	if(strlen($password) < 3 ){
			echo"<script>alert('Password should be minimum 3 characters!')</script>";
			exit(0);
		}

	$email_check="select * from Usertable where email='$email'";
    $email_check_query=mysqli_query($con,$email_check);
    $check=mysqli_num_rows($email_check_query);
    if($check==1)
    {
    	echo "<script>alert('Email already Exists')</script>";
    	echo "<script>window.open('register-form.php','_self')</script>";
    	exit(0);

    }
    $insert="insert into Usertable(first_name,last_name,country,relationship,description,birth_date,posts,password,email,gender,profile_pic,cover_pic) values ('$fname','$lname','$country','$relationship','$description','$birthdate','$posts','$password','$email','$gender','$profile_pic','$cover_pic')";

    $insert_query=mysqli_query($con,$insert);
    
   if($insert_query){
			echo "<script>alert('Well Done $first_name, you are good to go.')</script>";
			echo "<script>window.open('login-form.php', '_self')</script>";
			exit(0);
		}
		else{
			echo "<script>alert('Registration failed, please try again!')</script>";
			echo "<script>window.open('register-form.php', '_self')</script>";
			exit(0);
		}
}

?>