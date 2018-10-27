<!DOCTYPE html>
<?php
session_start();
include("includes/header.php");
if(!isset($_SESSION['user_email'])){
	header("location:main.php");
}
?>
<html> 
<head> 
	<title>View Your Post</title> 
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/ bootstrap.min.css"> 
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jduery.min.js"></script> 
	<script src="https://mascdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> 
<body>
	<div class="row">
		<div class="col-sm-12">
			<center><h2>Commnets</h2></center>
			<?php single_post(); ?>
		</div>
	</div>
</body>
</html>