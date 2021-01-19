<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Change Profile Picture</title>
    <link rel="stylesheet" href="/lib/css/bootstrap.css">
  <style>
        .btn-close{
      float: right;
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
    
</head>
<script src="/lib/js/bootstrap.js"></script>
<script src="https://www.google.com/recaptcha/api.js"></script>
<body>
<?php require '_navbar_logged_in.php'?>

    <div class="container">
<?php 
session_start();

if(!isset($_SESSION['login_access']) || $_SESSION['login_access']!=true){
    header("location: login");
    exit;
}
?>
<?php
     require '_config_database.php';
     if(isset($_POST["submit"])){
        $captcha= $_POST['g-recaptcha-response'];
        $response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LeHnCYaAAAAAECny70TbT0KGVfPAnjdLBroHYtD&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
            
        if($response['success'] == false){
            $return_message = '<br><div class="alert alert-danger" role="alert">Please check the captcha box.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
            }
        else if ($_FILES['image']['size'] == 0)
        {
            $return_message = '<br><div class="alert alert-danger" role="alert">Please select an image.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        }
        else if ($_FILES['image']['size'] > 20621440)
        {
            $return_message = '<br><div class="alert alert-danger" role="alert">Please select an image less than 2.5 MB.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        }
         else{
         session_start();
         $email = $_SESSION['email'];
         $path = $_FILES['image']['name'];
        // $photoTmpPath = $_FILES['image']['tmp_name'];
      // $data = file_get_contents($photoTmpPath);
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $allowed = array('png', 'jpg', 'jpeg');
    if (!in_array($type, $allowed)) {
        $return_message = '<br><div class="alert alert-danger" role="alert">Image extension not allowed. Only images with .png, .jpg, .jpeg extensions are allowed.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    }
    else{
 //  $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        $image = addslashes(file_get_contents($_FILES["image"]["tmp_name"])); 
          $query = "UPDATE users SET profile_pic='$image' WHERE email='$email'";  
          if(mysqli_query($connect, $query)){
             header("location: /login-system/");
            }
          else{
              echo '<br><div class="alert alert-danger" role="alert">Your browser isn&apos;t allowing us to update your profile picture. We recommend you to use a PC instead.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
          }
     }  
    }
     }
     // echo base64_encode_image ('$data','type'); 
?>
<?php
if(isset($_POST['confirm'])){
    session_start();
    $email = $_SESSION['email'];
    require '_config_database.php';
    $captcha= $_POST['g-recaptcha-response'];
$response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LeHnCYaAAAAAECny70TbT0KGVfPAnjdLBroHYtD&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
    
if($response['success'] == false){
    $return_message_remove = '<br><div class="alert alert-danger" role="alert">Please check the captcha box.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    }
    else{
        $pic = addslashes(file_get_contents("profile_picture.jpg"));
       $update =  mysqli_query($connect, "UPDATE users SET profile_pic='$pic'");
       if($update){
           header("location: /login-system/");
       }
       else{
        $return_message_remove = '<br><div class="alert alert-danger" role="alert">Your browser isn&apos;t allowing us to update your profile picture. We recommend you to use a PC instead.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
       }
    }
}
?>

<?php echo $return_message?><br><br>
<?php echo $return_message_remove?><br><br>
<form method="post" enctype="multipart/form-data">
<input type="file" id="real-file" hidden="hidden" accept=".png, .jpg, .jpeg" name="image">
<button type="button" id="custom-button" class="btn btn-outline-info">CHOOSE AN IMAGE</button>
<br><br>
<div class="g-recaptcha" data-sitekey="6LeHnCYaAAAAAA_GGODJUOrQLfgYKIxWMvyeDXBK"></div>


   <!-- <input type="file" name="image" accept="image/*"> -->
    <br><br>
    <button type="submit" class="btn btn-success" name="submit">UPDATE PICTURE</button>
    &nbsp;&nbsp;
    <a href="" class="btn btn-outline-danger"  data-bs-toggle="modal" data-bs-target="#exampleModal">Remove Profile Picture</a>
    </form>
    <div id="login-form"></div>
   <script src="loading.js"></script>
    <script src="custom-file-input.js"></script>


</div>
<div class="modal fade show" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">

    <div class="modal-content">
    <form action="" method="POST">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Are you sure?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Clicking on confirm will erase your existing profile picture and reset your profile picture to default. <br><br>
<div class="g-recaptcha" data-sitekey="6LeHnCYaAAAAAA_GGODJUOrQLfgYKIxWMvyeDXBK"></div>

      </div>
      <div class="modal-footer">
        <a class="btn btn-secondary" data-bs-dismiss="modal">Cancel</a>
        <button type="submit" class="btn btn-primary" name="confirm">Confirm</button></form>
        
      </div>
    </div>
  </div>
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