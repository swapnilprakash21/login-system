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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Remove Picture</title>
    <link rel="stylesheet" href="/lib/css/bootstrap.css">
    <script src="/lib/js/bootstrap.js"></script>
    <style>
        .modal{
            display: block;
        }
        .btn-close{
      float: right;
        }

    </style>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
<?php 
session_start();

if(!isset($_SESSION['login_access']) || $_SESSION['login_access']!=true){
    header("location: login");
    exit;
}
?>
<div class="modal fade show" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <?php echo $return_message_remove?>

    <div class="modal-content">
    <form action="" method="POST">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Are you sure?</h5>
        <a class="btn-close" data-bs-dismiss="modal" aria-label="Close" href="change-profile_picture"></a>
      </div>
      <div class="modal-body">
        Clicking on confirm will erase your existing profile picture and reset your profile picture to default. <br><br>
<div class="g-recaptcha" data-sitekey="6LeHnCYaAAAAAA_GGODJUOrQLfgYKIxWMvyeDXBK"></div>

      </div>
      <div class="modal-footer">
        <a class="btn btn-secondary"  href="change-profile_picture">Cancel</a>
        <button type="submit" class="btn btn-primary" name="confirm">Confirm</button></form>
        
      </div>
    </div>
  </div>
</div>
<div class="modal-backdrop fade show"></div>
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