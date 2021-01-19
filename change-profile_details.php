<?php
session_start();
require '_config_database.php';

$email = $_SESSION['email'];
$default = mysqli_query($connect, "SELECT * FROM users WHERE email='$email'");
while($row = mysqli_fetch_array($default)){
    $default_firstname = $row['firstname'];
    $default_lastname = $row['lastname'];
}
if(isset($_POST['submit'])){
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $gender = $_POST['gender'];
    $captcha= $_POST['g-recaptcha-response'];
    $response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LeHnCYaAAAAAECny70TbT0KGVfPAnjdLBroHYtD&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
require '_config_database.php';
  
    
    if($response['success'] == false){
    $return_message = '<br><div class="alert alert-danger" role="alert">Please check the captcha box.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    }
    else if(empty(trim($_POST['firstname']))){
        $return_message = '<br><div class="alert alert-danger" role="alert">All fields are required<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
       
    }
               
            
            else if(empty(trim($_POST['lastname']))){
                $return_message = '<br><div class="alert alert-danger" role="alert">All fields are required<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
                    
                   
                }
               
                  else if(empty(trim($_POST['gender']))){
                    $return_message = '<br><div class="alert alert-danger" role="alert">All fields are required<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                       
                  }
                    else{
                        require '_config_database.php';
                        if($connect){
                           
                           
                                
                        
                             
                                $firstname = $_POST['firstname'];
                                $lastname = $_POST['lastname'];
                                $gender = $_POST['gender'];
                                $query = mysqli_query($connect, "UPDATE users SET firstname='$firstname', lastname='$lastname', gender='$gender' WHERE  email='$email'");
                               
                                $_SESSION['name'] = $firstname;
                                header("location: /login-system/");
                            }
                        }
                    }
                
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Edit Details</title>
    <link rel="stylesheet" href="/lib/css/bootstrap.css">
    <link rel="shortcut icon" href="<?php
    session_start();
    require '_config_database.php';
    $email = $_SESSION['email'];
    $image = mysqli_query($connect, "SELECT * FROM users WHERE email='$email'");
    $fetch = mysqli_fetch_array($image);
      $icon = $fetch['profile_pic'];

    
    echo 'data:image/jpeg;base64,'.base64_encode($icon).'';?>">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        .alert{
        width: 100%;
    }
    .btn-close{
      float: right;
    }
    a{
        text-decoration: none;
        font-size: 18px;
    }
    #showpassword{
        cursor: pointer;
        user-select: none;
        -moz-user-select: none;
        -webkit-user-select: none;
        text-decoration: none;
    }
    .navbar-nav{
        margin-left: auto;
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
    </style>
    <script src="/lib/js/bootstrap.js"></script>
</head>
<body>
<?php require '_navbar_logged_in.php'?>
<div class="container my-5 form-group">
<h1 class="text-center">Edit Profile Details</h1>
<?php 
session_start();

if(!isset($_SESSION['login_access']) || $_SESSION['login_access']!=true){
    header("location: login");
    exit;
}
?>

<form method="post">
   <br><br>
   <?php echo $return_message?><br><br>
   <input type="email" disabled id="" class="form-control" value="<?php echo $email?>">
   <small class="help-block">Email Address cannot be changed.</small>
   <br><br>
   <input type="text" name="firstname" id="" class="form-control" placeholder="First Name" value="<?php echo $default_firstname?>">
   <br><br>
   <input type="text" name="lastname" id="" class="form-control" placeholder="Last Name" value="<?php echo $default_lastname?>">
   <br><br>
   <select name="gender" id="" class="form-select" required>
    <option value="" disabled selected>Gender</option>
    <option value="Male">Male</option>
    <option value="Female">Female</option>
    </select>
    <br>
<div class="g-recaptcha" data-sitekey="6LeHnCYaAAAAAA_GGODJUOrQLfgYKIxWMvyeDXBK"></div>
<br>
    <button type="submit" name="submit" class="btn btn-primary">Update Details</button>
    </form>
    <div id="login-form"></div>
   <script src="loading.js"></script>
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
  <script src="hyperlink.js"></script>

</body>
</html>