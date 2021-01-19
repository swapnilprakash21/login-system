<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Account Password Reset</title>
    <link rel="stylesheet" href="/lib/css/bootstrap.css">
    <style>
    .alert{
        width: 100%;
    }
    a{
        text-decoration: none;
        font-size: 18px;
    }
    .btn-close{
      float: right;
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
    <?php require '_hyperlink_loader.php'?>
    </style>
    <script src="/lib/js/bootstrap.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <?php require "_navbar.php"?>
    <div class="container my-5 form-group">
   <?php
     require '_config_database.php';
    if(isset($_GET['token'])){
        $password = hash("sha512",$_POST['password']);
        $confirm_password  = hash("sha512",$_POST['confirm_password']);
        $token = $_GET['token'];
        $captcha= $_POST['g-recaptcha-response'];
$response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LeHnCYaAAAAAECny70TbT0KGVfPAnjdLBroHYtD&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
        $query = mysqli_query($connect,"SELECT * FROM users WHERE forgot_token='$token'and forgot_status='0'");
        if($query){
            if(mysqli_num_rows($query)==1){
                $login_form =  '
                <h1 class="text-center">Please enter a new password to be changed.</h1><br><form action="" method="POST">
               <input type="password" name="password" id="password" class="form-control" placeholder="New Password" value="'.$email.'">
                <br><br>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm New Password">
                <br><br>
                <div id="check">
<input type="checkbox" autocomplete="off" id="checkbox"> <label for="showpassword" id="showpassword">Show Password</label>
</div><br>
<div class="g-recaptcha" data-sitekey="6LeHnCYaAAAAAA_GGODJUOrQLfgYKIxWMvyeDXBK"></div>
<br>
                <button type="submit" name="submit" class="btn btn-primary">Update Password</button>
                </form>
                <div id="login-form"></div>
   <script src="loading.js"></script>
                </div>';
                if(isset($_POST['submit'])){
                    if($response['success'] == false){
                        $return_message = '<br><div class="alert alert-danger" role="alert">Please check the captcha box.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>';
                    }
                else if(empty(trim($_POST['password']))){
                       $return_message = '<br><div class="alert alert-danger" role="alert">Please enter a new password.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                       </div>';
                   }
                   else if($password!=$confirm_password){
                    $return_message = '<br><div class="alert alert-danger" role="alert">Passwords did not match<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                   }
                   else{
                       $update = mysqli_query($connect, "UPDATE users SET password='$password', forgot_status='1' WHERE forgot_token='$token'");
                       $return_message = '<br><div class="alert alert-success" role="alert">Password successfully updated.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                       </div>';
                       header("refresh:3, url=login");
                   }
                }
            }
            else{
                $return_message = '<br><div class="alert alert-danger" role="alert">This link is either invalid or expired.</div>';
            }
        }
    }
    else{
        header("location: login");
    }

    ?>
   <?php echo $return_message?><br>
  <?php echo $login_form?>
  <script>
       document.getElementById("check").onclick = function showpassword() {
    
  var x = document.getElementById("password");
  if (x.type === "password") {
    document.getElementById("checkbox").checked = true;
    document.getElementById("confirm_password").type = "text";
    x.type = "text";
    
  } else {
    document.getElementById("checkbox").checked = false;
    x.type = "password";
    document.getElementById("confirm_password").type = "password";
  }
}

</script>

</body>
</html>