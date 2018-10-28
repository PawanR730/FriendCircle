<?php


//include(" /opt/lampp/htdocs/FriendCircle/includes/connection.php");

$con = mysqli_connect("localhost","root","","friendcircle") or die("Connection was not established");


//function for inserting post
function search_user(){ 
		global $con; 
		if (isset($_POST['search_user_btn'])) { 
			$search_query = htmlentities($_POST['search_user']); 
			$get_user ="select * from Usertable where first_name like '%$search_query%' OR last_name like '%$search_query%'";
			} 
		else{ 
			$get_user = "select * from Usertable";
			}

		$run_user = mysqli_query($con, $get_user); 
		while ($row_user=mysqli_fetch_array($run_user)) {
			$user_id = $row_user['user_id']; 
			$f_name= $row_user['first_name'];
			$l_name = $row_user['last_name']; 
			//$username = $row['user_name']; 
			$profile_pic = $row_user['profile_pic'];

			echo"
			<div class='row'> 
				<div class='col-sm-3'> 
				</div> 
				<div class='col-sm-6'>
				<div class='row' id='find_people'> 
					<div class='col-sm-4'> 
					<a href='user_profile.php?user_id=$user_id'> 
					<img src='users/$profile_pic' width='150px' height='140px' title='$f_name' style='float:left; ,margin:1px;'/>
					 </a> 
					 </div><br><br> 
					 <div class='col-sm--6'> 
					 <a style='text-decoration:none;cursor:pointers;color:#3897f0;href='user_profile.php?user_id=$user_id'> 
					 <strong><h2>$f_name $l_name</h2></strong> 
					 </a>
					</div>
					<div class='col-sm-3'>
					</div>
				</div>
			</div>
			<div class='col-sm-4'>
			</div>
			</div><br>
			";

		} 

	}



function insertPost(){
	if(isset($_POST['submit_post'])){

		global $con;
		global $user_id;
        
		$content = htmlentities(mysqli_real_escape_string($con,$_POST['content']));  //content of the post
		$upload_image = $_FILES['upload_image']['name']; //here upload_image s the name of the entity where we ae uploading our image
		$image_tmp = $_FILES['upload_image']['tmp_name'];
		$random_number = rand(1, 10000); //to give unique name to a particular pic uploaded

		if(strlen($content) > 255){
			echo "<script>alert('Please Use 255 or less than 255 words!')</script>";
			echo "<script>window.open('home.php', '_self')</script>";
		}else{
			// both pic and content is included in the post
			if(strlen($upload_image) >= 1 && strlen($content) >= 1){
				move_uploaded_file($image_tmp, "imagepost/$upload_image.$random_number"); //moving into thr imagepost folder 
				$insert = "insert into posttable (user_id, post_content, upload_image, post_date) values('$user_id', '$content', '$upload_image.$random_number', NOW())";

				$run = mysqli_query($con, $insert);

				if($run){
					echo "<script>alert('Your Post updated a moment ago!')</script>";
					echo "<script>window.open('home.php', '_self')</script>";

					$update = "update Usertable set posts='yes' where user_id='$user_id'";

					$run_update = mysqli_query($con, $update);
				}

				exit();
			}else{
				if($upload_image=='' && $content == ''){
					echo "<script>alert('Error Occured while uploading!')</script>";
					echo "<script>window.open('home.php', '_self')</script>";
				}else{
					if($content==''){
						move_uploaded_file($image_tmp, "imagepost/$upload_image.$random_number");
						$insert = "insert into posttable (user_id,post_content,upload_image,post_date) values ('$user_id','No','$upload_image.$random_number',NOW())";
						$run = mysqli_query($con, $insert);

						if($run){
							echo "<script>alert('Your Post updated a moment ago!')</script>";
							echo "<script>window.open('home.php', '_self')</script>";

							$update = "update Usertable set posts='yes' where user_id='$user_id'";
							$run_update = mysqli_query($con, $update);
						}

						exit();
					}else{
						$insert = "insert into posttable (user_id, post_content, post_date) values('$user_id', '$content', NOW())";
						$run = mysqli_query($con, $insert);
                        // echo "<script>window.open('dubug.php', '_self')</script>";
                        
						if($run){
							
							echo "<script>alert('Your Post updated a moment ago!')</script>";
							echo "<script>window.open('home.php', '_self')</script>";

							$update = "update Usertable set posts='yes' where user_id='$user_id'";
							$run_update = mysqli_query($con, $update);
							exit(0);
							//echo "<script>window.open('main.php', '_self')</script>";

						}
					}
				}
			}
		}
	}
}

function get_posts(){
	global $con;
	global $user_id;
	$per_page = 10;

	if(isset($_GET['page'])){
		$page = $_GET['page'];
	}else{
		$page=1;
	}

	$start_from = ($page-1) * $per_page;
	
	echo $user_id;
	 
 /////////Getting posts from our friends onlt
	// $get_posts = "select * from posttable  ORDER by post_date DESC LIMIT $start_from, $per_page";
	// user_id in (select user2_id from friendship where user1_id='$user_id' ) or
	$get_posts = "select * from posttable where   user_id in (select user2_id from friendship where user1_id='$user_id' ) or user_id ='$user_id' ORDER by post_date DESC LIMIT $start_from, $per_page";

	$run_posts = mysqli_query($con, $get_posts);

	while($row_posts = mysqli_fetch_array($run_posts)){

		$post_id = $row_posts['post_id'];
		$user_id = $row_posts['user_id'];

		$content = $row_posts['post_content'];
		$upload_image = $row_posts['upload_image'];
		$post_date = $row_posts['post_date'];

		$user = "select * from Usertable where user_id='$user_id' AND posts='yes'";
		$run_user = mysqli_query($con,$user);
		$row_user = mysqli_fetch_array($run_user);

		$first_name=$row_user['first_name'];
		$user_image = $row_user['profile_pic'];

		//now displaying posts from database

		if($content=="No" && strlen($upload_image) >= 1){
			echo"
			<div class='row'>
				<div class='col-sm-3'>
				</div>
				<div id='posts' class='col-sm-6'>
					<div class='row'>
						<div class='col-sm-2'>
						<p><img src='users/$user_image' class='img-circle' width='100px' height='100px'></p>
						</div>
						<div class='col-sm-6'>
							<h3><a style='text-decoration:none; cursor:pointer;color #3897f0;' href='user_profile.php?u_id=$user_id'>$first_name</a></h3>
							<h4><small style='color:black;'>Updated a post on <strong>$post_date</strong></small></h4>
						</div>
						<div class='col-sm-4'>
						</div>
					</div>
					<div class='row'>
						<div class='col-sm-12'>
							<img id='posts-img' src='imagepost/$upload_image' style='height:350px;'>
						</div>
					</div><br>
					<a href='single.php?post_id=$post_id' style='float:right;'><button class='btn btn-info'>Comment</button></a><br>
				</div>
				<div class='col-sm-3'>
				</div>
			</div><br><br>
			";
		}

		else if(strlen($content) >= 1 && strlen($upload_image) >= 1){
			echo"
			<div class='row'>
				<div class='col-sm-3'>
				</div>
				<div id='posts' class='col-sm-6'>
					<div class='row'>
						<div class='col-sm-2'>
						<p><img src='users/$user_image' class='img-circle' width='100px' height='100px'></p>
						</div>
						<div class='col-sm-6'>
							<h3><a style='text-decoration:none; cursor:pointer;color #3897f0;' href='user_profile.php?user_id=$user_id'>$first_name</a></h3>
							<h4><small style='color:black;'>Updated a post on <strong>$post_date</strong></small></h4>
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
					<a href='single.php?post_id=$post_id' style='float:right;'><button class='btn btn-info'>Comment</button></a><br>
				</div>
				<div class='col-sm-3'>
				</div>
			</div><br><br>
			";
		}

		else{
			echo"
			<div class='row'>
				<div class='col-sm-3'>
				</div>
				<div id='posts' class='col-sm-6'>
					<div class='row'>
						<div class='col-sm-2'>
						<p><img src='users/$user_image' class='img-circle' width='100px' height='100px'></p>
						</div>
						<div class='col-sm-6'>
							<h3><a style='text-decoration:none; cursor:pointer;color #3897f0;' href='user_profile.php?user_id=$user_id'>$first_name</a></h3>
							<h4><small style='color:black;'>Updated a post on <strong>$post_date</strong></small></h4>
						</div>
						<div class='col-sm-4'>
						</div>
					</div>
					<div class='row'>
						<div class='col-sm-12'>
							<h3><p>$content</p></h3>
						</div>
					</div><br>
					<a href='single.php?post_id=$post_id' style='float:right;'><button class='btn btn-info'>Comment</button></a><br>
				</div>
				<div class='col-sm-3'>
				</div>
			</div><br><br>
			";
		}
	}

	include("pagination.php");
}

?>