<?php
include("connection.php");
include("/opt/lampp/htdocs/FriendCircle/functions/functions.php");
?>
<nav class="navbar navbar-default">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-target="#bs-example-navbar-collapse-1" data-toggle="collapse" aria-expanded="false">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="home.php">Friend Circle</a>
		</div>

		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	      <ul class="nav navbar-nav">
	      	
	      	<?php 
			// $user = $_SESSION['user_email'];
			// $get_user = "select * from users where user_email='$user'"; 
			// $run_user = mysqli_query($con,$get_user);
			// $row=mysqli_fetch_array($run_user);

			$email = $_SESSION['user_email'];
			$get_user = "select * from Usertable where email='$email'";  // Running query by emailid
			$run_user = mysqli_query($con,$get_user);
			$row = mysqli_fetch_array($run_user);
					
			$user_id = $row['user_id']; 
			$fname = $row['first_name'];
			$lname = $row['last_name'];
			$description = $row['description'];
			$relationship = $row['relationship'];
			$password = $row['password'];
			$email = $row['email'];
			$country = $row['country'];
			$gender = $row['gender'];
			$birthday = $row['birth_date'];
			$profile_pic = $row['profile_pic'];
			$cover_pic = $row['cover_pic'];
			
			$date_registered = $row['date_registered'];
					
					
			$user_posts = "select * from posttable where user_id='$user_id'"; 
			$run_posts = mysqli_query($con,$user_posts); 
			$posts = mysqli_num_rows($run_posts);
			?>

	        <li><a href='profile.php?<?php echo "user_id=$user_id" ?>'><?php echo "$fname"; ?></a></li>
	       	<li><a href="home.php">Home</a></li>
			<li><a href="members.php">Find People</a></li>
			<li><a href="messages.php?user_id=new">Messages</a></li>   
			<li><a href="requests.php">Friend Requests</a></li>  
			<li><a href="groups.php?group_id=new">Groups</a></li>    

<!-- //hxhhhx -->
					<?php
						echo"

						<li class='dropdown'>
							<a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'><span><i class='glyphicon glyphicon-chevron-down'></i></span></a>
							<ul class='dropdown-menu'>
								
								<li>
									<a href='edit-profile.php?user_id=$user_id'>Edit Account</a>
								</li>
								<li role='separator' class='divider'></li>
								<li>
									<a href='logout.php'>Logout</a>
								</li>
							</ul>
						</li>
						";
					?>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
					<form class="navbar-form navbar-left" method="get" action="results.php">
						<div class="form-group">
							<input type="text" class="form-control" name="user_query" placeholder="Search">
						</div>
						<button type="submit" class="btn btn-info" name="search">Search</button>
					</form>
				</li>
			</ul>
		</div>
	</div>
</nav>