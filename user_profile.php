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
	<title>Find People</title>
	<meta charset="utf-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="style/home_style2.css">
</head>
<style >
#own_post{
	border: 5px solid #e6e6e6;
	padding: 40px 50px;
	width: 90%;
}	
#posts_img{
	height: 300px;
	width: 100%;
}
</style>
<body>
<div class="row">
	<?php
		if(isset($_GET['user_id'])){
		$user_id = $_GET['user_id'];
		}
		if($user_id < 0 ||$user_id == ""){
			echo"<script>window.open('home.php', '_self')</script>";}

	?>
	<div class="col-sm-12">
		<?php
		if(isset($_GET['user_id'])){
			global $con;
			$user_id = $_GET['user_id'];
			$select= "select * from Usertable where user_id='$user_id'";
			$run=mysqli_query($con,$select);
			$row=mysqli_fetch_array($run);
			$name = $row['user_name'];

		}
		?>

		<?php
			if(isset($_GET['user_id'])){
				global $con;

				$user_id = $GET['user_id'];

				$select = "select * from Usertable where user_id='$user_id'";
				$run=mysqli_query($con,$select);
				$row=mysqli_fetch_array($run);

				$id = $row['user_id'];
				$profile_pic = $row['profile_pic'];
				
				$first_name = $row['first_name'];
				$last_name = $row['last_name'];
				$description = $row['description'];
				$country = $row['country'];
				$gender = $row['gender'];
				$date_regisered= $row['date_regisered'];

				echo"
					<div class='row'>
						<div class='col-sm-1'>
						</div>
						<center>
						<div style='background-color: #e6e6e6' class='col-sm-3'>
						<h2>Information about</h2>
						<img class='img-circle' src='users/$profile_pic' width='150' height='150'><br><br>
						<ul class='list-group'>
							<li class='list-group-item' title='Username'><strong>
							$first_name $last_name</strong></li>

							<li class='list-group-item' title='User Status'><strong style='color:gray;''>$description</strong></li>

							<li class='list-group-item' title='Gender'><strong>$gender</strong></li>

							<li class='list-group-item' title='Country'><strong>$country</strong></li>

							<li class='list-group-item' title='User Registration Data'> <strong>$date_registered</strong></li>
						</ul>
				";
				$email = $_SESSION['user_email'];
				$get_user ="select * from users where user_email='$email'";
				$run_user = mysqli_query($con,$get_user);
				$row = mysqli_fetch_array($run_user);

				$userown_id = $row['user_id'];

				if($user_id == $userown_id){
					echo"<a href='edit_profile.php?u_id=$userown_id class='btn btn-success'/>Edit Profile</a><br><br><br>";
				}
				echo"
					</div>
					</center>
				";
			}
		?>
		<div class="col-sm-8"> 
			<center><h1><strong><?php echo "$f_name $l_name"; ?></strong> Posts</h1></center>
			<?php 
				global $con; 
				if( isset ( $_GET['user_id'] )
					{ $user_id = $_GET['user_id'];}
					

			$get_posts = "select * from posttable where user_id='$user_id' ORDER by post_date DESC "; 
			$run_posts = mysqli_query($con, $get_posts); 
			while($row_posts = mysqli_fetch_array($run_posts)){ 
				$post_id = $row_posts[ 'post_id' ]; 
				$user_id = $row_postst 'user_id' ];


				$content = $row_posts['post_content']; 
				$upload_image = $row_posts['upload_image']; 
				$post_date = $row_posts['post_date']; 

				$user = "select * from Usertable where user_id='$user_id' AND posts='yes'";

				$run_user = mysqli_query($con, $user); 
				$row_user = mysqli_fetch_array($run_user);

				
				$first_name = $row_user['first_name']; 
				$1ast_name = $row_user['last_name']; 
				$profile_pic = $row_user['profile_pic']; 

			if($content=="No" && strlen($upload_image) >= 1){ 
				echo" 
					<div id='own_posts'> 
						<div class='row'> 
							<div class='col-sm-2'> 
								<p><img src='users/$profile_pic' class='img-circle' width='100px' height='100px'></p> 
							</div> 
							<div class='col-sm-6'> 
								<h3><a style='text-decoration: none;cursor:pointer;color: #3897f0;' href='user_profile.php?user_id=$user_id'>$first_name</a> 
								</h3> 
								<h4><small style='color: black;'>Updated a post on <strong>$post_date</strong></small></h>
								 </div> 
								<div class='col-sm-4'> 

								</div> 
							</div>
							<div class='row'> 
								<div class='col-sm-12'> 
									<img id='posts-img' src=' imagepost/$upload_image' style='height: 350px'> 
									</div> 
								</div><br> 
								<a href='single.php?post_id' style='float: right;'><button class='btn btn-success'>View</ button></a> 
								<a href='functions/delete_post.php?post_id=$post_id' style='floot:right;'><button class='btn btn-danger'> Delete</button></a> 
							</div><br/><br/>
				"; 

			} 
			else if(strlen($content) >= 1 && strlen($upload_image) >= 1){ 
				echo" 
					<div id='own_posts'> 
						<div class='row'>
							 <div class='col-sm-2'> 
							 	<p><img src='users/$profile_pic' class='img-circle' width='100px' height='100px'></p> 
							 </div> 
							 <div class='col-sm-6'>
							 	<h3><a style='text-decoration:none; cursor:pointer;color #3897f0;' href='user_profile.php?user_id=$user_id'>$first_name</a></h3> 
							 	<h4><small style='color:black;'>Updated a post on <strong>$ post_date</strong></small></h4>
							 </div> 
							<div class='col-sm-4'>
							</div>
						</div> 
						<div class='row'> 
							<div class='col-sm-12'> 
								<p>$content</p>
								<img id='posts-img' src='imagepost/$upload_image' style='height:350px;'>

							</div>
						</div><br>
						<a href='single.pDelete</button></a?
					</div><br><br>

				";
			} 

			else{
				echo" 
				<div id='own_posts'> 
					<div class='row'> 
						<div <p><img src='users/$profile_pic' class='img-circle' width='100px' height='100px'></p> 
						</div> 
						<div class='col-sm-6'>
							<h3><a style='text-decoration:none; cursor:pointer;color #3897f0;' href='user_profile.php?user_id=$user_id'>$first_name</a></h3> 
							<h4><small style='color:black;'>Updated a post on <strong>$post_date</strong></sma11></h4> 
							</div> 
							<div class='col-sm-4'> 
							</div> 
						</div> 
						<div class='row'> 
							<div class='col-sm-12'> 
								<h3><p>$content</p></h3> 
							</div> 
							</div><br>
							<a href='single.php?post_id='$post_id ' style='float:right';'><button class='btn btn-info'>Comment</button></a><br>
					</div><br><br>
				";
			}
		} 

			
		?>
	</div>
	</div>
</div>
<?php} ?>
</body>
</html>