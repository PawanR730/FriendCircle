<?php


//include(" /opt/lampp/htdocs/FriendCircle/includes/connection.php");

$con = mysqli_connect("localhost","root","","friendcircle") or die("Connection was not established");


//function for inserting post

function search_user($profile_id){ 
		global $con; 
		if (isset($_POST['search_user_btn'])) { 
			$search_query = htmlentities($_POST['search_user']); 
			$get_user ="select * from Usertable where first_name like '%$search_query%' OR last_name like '%$search_query%' and user_id!='$profile_id'";
			} 
		else{ 
			$get_user = "select * from Usertable where user_id!='$profile_id'";
			}

		$run_user = mysqli_query($con, $get_user); 
		while ($row_user=mysqli_fetch_array($run_user)) {
			$user_id = $row_user['user_id']; 
			$f_name= $row_user['first_name'];
			$l_name = $row_user['last_name']; 
			//$username = $row['user_name']; 
			$profile_pic = $row_user['profile_pic'];

            $find_query="select * from friendship where (user1_id='$profile_id' and user2_id='$user_id') or (user1_id='$user_id' and user2_id='$profile_id')";
            $run_find_query=mysqli_query($con,$find_query);
            $check=mysqli_num_rows($run_find_query);

            $find_pending="select * from friendrequests where user_from='$profile_id' and user_to='$user_id' and status='pending'";
            $run_find_pending=mysqli_query($con,$find_pending);
            $check2=mysqli_num_rows($run_find_pending);


            if($check2==0&&$check==0)
            {
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
					 <a style='text-decoration:none;cursor:pointers;color:#3897f0;'href='user_profile.php?user_id=$user_id'> 
					 <strong><h2>$f_name $l_name</h2></strong> 
					 </a>
					 <div class='col-sm--6'>
					 <a style='text-decoration:none;cursor:pointers;color:#3897f0;' href='friend_request.php?user_id=$user_id'>
					 <strong><h2>Send Friend Request</h2></strong></a>
					 </div>
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
           else if($check==0&&$check2==1)
            {
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
					 <a style='text-decoration:none;cursor:pointers;color:#3897f0;'href='user_profile.php?user_id=$user_id'> 
					 <strong><h2>$f_name $l_name</h2></strong> 
					 </a>
					 <div class='col-sm--6'>
					  <h2 style='text-decoration:none;cursor:pointers;color:#3897f0;'>Friend Request Pending</h2></strong>
					 </div>
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
		else
		{
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
					 <a style='text-decoration:none;cursor:pointers;color:#3897f0;'href='user_profile.php?user_id=$user_id'> 
					 <strong><h2>$f_name $l_name</h2></strong> 
					 </a>
					 <div class='col-sm--6'>
					 
					 <h2 style='text-decoration:none;cursor:pointers;color:#3897f0;'>Already a Friend</h2></strong>
					 </div>
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
	
	//echo $user_id;
	 
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

function search_requests()
{
	global $con;
	global $user_id;
	$get_user="select * from friendrequests where user_to='$user_id' and status='pending'";
	$run_query=mysqli_query($con,$get_user);
	$check_array=mysqli_num_rows($run_query);
	if($check_array==0)
	{
		echo "<p style='text-decoration:none;cursor:pointers;color:#3897f0;'><center><h2>Sorry,You dont have any Friend Requests</h2></center></p><br><br>"; 
	}
	while ($row_user=mysqli_fetch_array($run_query)) {
			$get_id = $row_user['user_from'];
			$get_user="select * from Usertable where user_id='$get_id'";
			$run_get_user=mysqli_query($con,$get_user);
			$user_array=mysqli_fetch_array($run_get_user); 
			$f_name= $user_array['first_name'];
			$l_name = $user_array['last_name']; 
			//$username = $row['user_name']; 
			$profile_pic = $user_array['profile_pic'];

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
					 <a style='text-decoration:none;cursor:pointers;color:#3897f0;'href='user_profile.php?user_id=$user_id'> 
					 <strong><h2>$f_name $l_name</h2></strong> 
					 </a>
					 <div class='col-sm--6'>
					 <a style='text-decoration:none;cursor:pointers;color:#3897f0;' href='accept_friend_request.php?user_id=$get_id'>
					 <strong><h2>Accept Friend Request</h2></strong></a>
					 </div>
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
function single_post(){
	if (isset($_GET['post_id'])){
	 	global $con; 
	 	$get_id = $_GET['post_id'];
	 	$get_posts = "select * from posttable where post_id='$get_id';";
	 	$run_posts = mysqli_query($con, $get_posts); 
	 	$row_posts = mysqli_fetch_array($run_posts); 
 		$post_id = $row_posts['post_id'];
 		$user_id = $row_posts['user_id']; 
 		$content = $row_posts['post_content'];
 		$upload_image = $row_posts['upload_image'];
 		$post_date = $row_posts['post_date'];
 		$user = "select * from Usertable where user_id='$user_id' AND posts='yes';"; 
		$run_user = mysqli_query($con, $user); 
		$row_user = mysqli_fetch_array($run_user);
		$user_image = $row_user['profile_pic']; 
		$user_name = $row_user['first_name'];
		$user_com = $_SESSION['user_email'];
		$get_com = "select * from  Usertable where email='$user_com';";
		$run_com = mysqli_query($con, $get_com);
		$row_com = mysqli_fetch_array($run_com);
		$user_com_id = $row_com['user_id'];
		$user_com_name = $row_com['first_name'];
		if(isset($_GET['post_id'])){
			$post_id = $_GET['post_id'];
		}
		$get_posts = "select post_id from Usertable where post_id='$post_id';";
		$run_user = mysqli_query($con, $get_posts);
		$post_id = $_GET['post_id'];
		$get_user = "select * from posttable where post_id='$post_id';";
		$run_user = mysqli_query($con, $get_user);
		$row = mysqli_fetch_array($run_user);
		$p_id = $row['post_id']; 
		if($p_id != $post_id){
				echo "<script>alert('ERROR')</script>";
				echo "<script>window.open('home.php','_self')</script>";
		}
		else{
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
						<h3><a style='text-decoration:none; cursor:pointer;color #3897f0;' href='user_profile.php?u_id=$user_id'>$user_name</a></h3>
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
					<br>
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
								<h3><a style='text-decoration:none; cursor:pointer;color #3897f0;' href='user_profile.php?user_id=$user_id'>$user_name</a></h3>
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
					 <br>
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
						<h3><a style='text-decoration:none; cursor:pointer;color #3897f0;' href='user_profile.php?user_id=$user_id'>$user_name</a></h3>
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
				
				</div>
				<div class='col-sm-3'>
				</div>
				</div><br><br>
				"; 
			}//end of else
			include("comments.php");
			echo"
			<div class='row'> 
				<div class='col-md-6 col-md-offset-3'> 
					<div class='panel panel-info'>
					 	<div class='panel-body'>
					 	 	<form action='' method='post' class='form-inline'> 
					 	 	<textarea placeholder='Write your comment here!'
					 	 	 	class='pb-cmnt-textarea' name='comment'></textarea> 
					 	 	<button class='btn btn-info pull-right' name='reply'>Comment</button>
					 	 	</form>
					 	</div> 
					 </div>
				</div>
			</div> 
			";
			if(isset($_POST['reply'])){ 
				$comment = htmlentities($_POST['comment']);
				if($comment == ""){
					echo "<script>alert('Enter your comment!')</script>";
					echo "<script>window.open('single.php?post_id=$post_id', '_self')</script>";
				}
				else{
					$insert = "insert into comments (post_id,user_id,comment,com_auth,com_date)
					values('$post_id','$user_id','$comment','$user_com_name',NOW());";
					$run = mysqli_query($con, $insert); 
					echo "<script>alert('Your Comment added!')</script>"; 
					echo "<script>window.open('single.php?post_id=$post_id' , '_self')</script>";
				}
			}
		}	
	}
}	


function results(){
	

	if(isset($_GET['search'])){
		global $con;
		$search_query=htmlentities($_GET['user_query']);


	$get_posts="select * from posttable where post_content like '%$search_query%' OR upload_image like '%$search_query%'";

	$run_posts=mysqli_query($con,$get_posts);

	while ($row_posts=mysqli_fetch_array($run_posts)) {
		$post_id=$row_posts['post_id'];
		$user_id=$row_posts['user_id'];
		$content=$row_posts['post_content'];
		$upload_image=$row_posts['upload_image'];
		$post_date=$row_posts['post_date'];

		$user="select * from Usertable where user_id='$user_id' AND posts='yes'";

		$run_user=mysqli_query($con,$user);
		$row_user=mysqli_fetch_array($run_user);

		// $user_name=$row_user['user_name'];
		$first_name=$row_user['first_name'];
		$last_name=$row_user['last_name'];
		$user_image=$row_user['profile_pic'];

		// displaying posts

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
				<h3><a style='text-decoration:none; cursor:pointer;color #3897f0;' 
				href='user_profile.php?u_id=$user_id'>$first_name</a></h3>
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
		}//end of else
	}
}
}

?>