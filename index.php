<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="shortcut icon" href="<?php
    session_start();
    require '_config_database.php';
    $email = $_SESSION['email'];
    $image = mysqli_query($connect, "SELECT * FROM users WHERE email='$email'");
    $fetch = mysqli_fetch_array($image);
      $icon = $fetch['profile_pic'];

    
    echo 'data:image/jpeg;base64,'.base64_encode($icon).'';?>">
    <title>Welcome - <?php
    session_start();
     require '_config_database.php';
  $email = $_SESSION['email'];
  $name = mysqli_query($connect, "SELECT * FROM users WHERE email='$email'");
if(mysqli_num_rows($name)==1){
  while($name_row = mysqli_fetch_array($name)){
   $firstname = $name_row['firstname'];
  }
  echo $firstname;
  }?></title>
    <link rel="stylesheet" href="/lib/css/bootstrap.css">
    <style>
      .profile{
        font-size: 100px;
        color: #aaa;
        background-color: #ddd;
        width: 150px;
        height: 150px;
        text-align: center;
        border-radius: 50%;
        vertical-align: middle;
        display: inline-block;
        position: relative;
        width: 100%;
        max-width: 150px;
      }
      .fa-user{
        display: inline-block;
        vertical-align: midcdle;
      }
      img{
        border-radius: 50%;
      }
      #contextmenu{
        position: absolute;
        display:none;
      }
      .loader {
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #0000ff;
  width: 120px;
  height: 120px;
  -webkit-animation: spin 1s linear infinite; /* Safari */
  animation: spin 0.8s linear infinite;
  display: flex;
  position: absolute;
  top: 40%;
  -ms-transform: translateY(-50%);
  transform: translateY(-50%);
  left: 46%;
  margin: auto;
  margin-top: auto;
  vertical-align: middle;
  line-height: 100%;
}
.modal-backdrop{
    position: relative;
}
.modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  z-index: 1040;
  width: 100vw;
  height: 100vh;
  background-color: #000;
}
.modal-backdrop.fade {
  opacity: 0;
}
.modal-backdrop.show {
  opacity: 0.5;
}
/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
.btn-close{
      float: right;
    }
    </style>
</head>
<body>
    <script src="/lib/js/bootstrap.js"></script>
    <?php 
session_start();

if(!isset($_SESSION['login_access']) || $_SESSION['login_access']!=true){
    header("location: login");
    exit;
}
?>
<?php require '_navbar_logged_in.php'?>
<div class="container" >
<?php
if(isset($_GET['access'])){
  session_start();
  if($_GET['access']==='register' && $_GET['token']===$_SESSION['register_in_loggedin_mode']){
    echo '<br><div class="alert alert-danger" role="alert">You cannot register while you&apos;re logged in. Please logout to preform the action.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
    
  }
  $_SESSION['register_in_loggedin_mode']= "4y34ehk665u785uuui6ru8ru45e5uiki";
}
?>
<?php
if(isset($_GET['access'])){
  session_start();
  if($_GET['access']==='forgot_password' && $_GET['token']===$_SESSION['forgot_password_in_loggedin_mode']){
    echo '<br><div class="alert alert-danger" role="alert">If you have forgotten your password, please logout to reset your password. <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
    
  }
  $_SESSION['forgot_password_in_loggedin_mode']= "hrjkkk845krktr5k65kr5jrj55u5u5u2rruu222r0ru";
}
?>
<?php 
require '_config_database.php';
$email = $_SESSION['email'];
$check_email = mysqli_query($connect, "SELECT * FROM users WHERE email='$email'");
if(mysqli_num_rows($check_email)==1){
  while($row = mysqli_fetch_array($check_email)){
    $pic = $row['profile_pic'];
    //$ext = pathinfo($pic, PATHINFO_EXTENSION);
    //echo $pic;// iconv_mime_decode($ext);
  }
  
   
   // echo '<img src="'.base64_encode($pic).'"height="150px" width="150px">';
  }
  
  else{
    session_destroy();
    header("location: login");
  }

?>
<?php echo '<img src="data:image/jpeg;base64,'.base64_encode($pic).'"height="150px" width="150px">';?>
  <div class="change">
    <div class="change-button">
      
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-primary" href="change-profile_picture" onclick="hyperlinkLoader()">Change</a>
      <br>
    </div>
  </div>
  <br>
    <?php
     require '_config_database.php';
     $email = $_SESSION['email'];
     $query = mysqli_query($connect, "SELECT * FROM users WHERE email='$email'");
     while($row = mysqli_fetch_array($query)){
         echo  "<table class='table table-bordered'>" . "<br><tr><th>Name: </th>" . "<td>" . $row['firstname']  . '&nbsp;' . $row['lastname']  . "</td></tr>" ."<tr><th>"  . "Email Address:"  . "</th><td>" . $row['email'] . "</td></tr>" . "<tr><th>"  . "Gender:"  . "</th><td>" . $row['gender'] . "</td></tr>" . "<tr><th>"  . "Password:"  . "</th><td>" . "(<i>your password</i>) &nbsp;&nbsp;<a href='change-profile_password'  onclick='hyperlinkLoader()'>Change Password</a>" . "</td></tr>" . "<tr><td colspan='2'>"  . "<a href='change-profile_details'  onclick='hyperlinkLoader()'>Edit Details</a>"  . "</td></tr>";
     }
    ?>
 <!--   <div class="profile">
      <i class="fa fa-user"></i>
    </div>-->
  <script src="hyperlink.js"></script>
</div>
<!--<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" id="contextmenu" >
  <li><a class="dropdown-item" href="/login-system/">Home</a></li>
    <li><a class="dropdown-item" href="change-profile_picture">Change Profile Picture</a></li>
    <li><a class="dropdown-item" href="change-profile_details">Edit Profile Details</a></li>
    <li><a class="dropdown-item" href="change-profile_password">Change Profile Password</a></li>
    <li><a class="dropdown-item" href="logout">Logout</a></li>
  </ul>
  <script>
 window.onclick = hideContextMenu;
			window.onkeydown = listenKeys;
			var contextMenu = document.getElementById('contextmenu');

	document.getElementsByTagName("html")[0].oncontextmenu =function showContextMenu (event) {
				contextMenu.style.display = 'block';
				contextMenu.style.left = event.clientX + 'px';
				contextMenu.style.top = event.clientY + 'px';
				return false;
			}

			function hideContextMenu () {
				contextMenu.style.display = 'none';
			}

			function listenKeys (event) {
				var keyCode = event.which || event.keyCode;
				if(keyCode == 27){
					hideContextMenu();
				}
			}

  </script>-->
</body>
</html>