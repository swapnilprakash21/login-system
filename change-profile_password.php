<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="/lib/css/bootstrap.css">
    
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
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="/lib/js/bootstrap.js"></script>
</head>
<body>
<?php require '_navbar_logged_in.php'?>
<?php
    session_start();
        $email = $_SESSION['email']; 
        require '_config_database.php';
        if(isset($_POST['submit'])){
        $password = hash("sha512",$_POST['old_password']);
        $new_password = hash("sha512",$_POST['password']);
        $confirm_password  = hash("sha512",$_POST['confirm_password']);
        $captcha= $_POST['g-recaptcha-response'];
        $response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LeHnCYaAAAAAECny70TbT0KGVfPAnjdLBroHYtD&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
        $query = mysqli_query($connect,"SELECT * FROM users WHERE email='$email' and password='$password'");
        if($response['success'] == false){
            $return_message = '<br><div class="alert alert-danger" role="alert">Please check the captcha box.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
            }
            else{
        if(mysqli_num_rows($query)==1){
            if($response['success'] == false){
                $return_message = '<br><div class="alert alert-danger" role="alert">Please check the captcha box.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
                }
                else if($new_password!=$confirm_password){
                    $return_message = '<br><div class="alert alert-danger" role="alert">Passwords did not match.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                  }
                  else{
                      $update_password = mysqli_query($connect, "UPDATE users SET password='$new_password' WHERE email='$email'");
                      if($update_password){
                      $return_message = '<br><div class="alert alert-success" role="alert">Password Successfully Updated<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
                      }
                      else{
                        $return_message = '<br><div class="alert alert-danger" role="alert">Sorry! Your password couldn&apos;t be updated. Please try again later.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
                      }
                  }
            }
            else{
                $return_message = '<br><div class="alert alert-danger" role="alert">Old password is invalid.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
            }
        }
        }
    

    ?>
<div class="container">
<?php 
session_start();

if(!isset($_SESSION['login_access']) || $_SESSION['login_access']!=true){
    header("location: login");
    exit;
}
?>
<br>
<h1 class="text-center">Change your Password</h1>
<br>
<?php echo $return_message?>
<form action="" method="post">
<input type="password" name="old_password" id="old_password" class="form-control" placeholder="Old Password">
<br>
<input type="password" name="password" id="password" class="form-control" placeholder="New Password">
   <br>
   <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm New Password">
   <br>
   <div id="check">
<input type="checkbox" autocomplete="off" id="checkbox"> <label for="showpassword" id="showpassword">Show Password</label>
</div><br>
<div class="g-recaptcha" data-sitekey="6LeHnCYaAAAAAA_GGODJUOrQLfgYKIxWMvyeDXBK"></div>
<br>
                <button type="submit" name="submit" class="btn btn-primary">Update Password</button>
   
   </form>
   <div id="login-form"></div>
   <script src="loading.js"></script>
   </div>
   <script>
       document.getElementById("check").onclick = function showpassword() {
    
  var x = document.getElementById("password");
  if (x.type === "password") {
    document.getElementById("checkbox").checked = true;
    document.getElementById("confirm_password").type = "text";
    document.getElementById("old_password").type = "text";
    document.getElementById("password").type = "text";
    x.type = "text";
    
  } else {
    document.getElementById("checkbox").checked = false;
    x.type = "password";
    document.getElementById("confirm_password").type = "password";
    document.getElementById("old_password").type = "password";
    document.getElementById("password").type = "password";
  }
}

</script>
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