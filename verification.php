<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Account Verification</title>
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
        $newcode = hash("sha512",$email.time());
    $email = $_POST['email'];
    $captcha= $_POST['g-recaptcha-response'];
$response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LeHnCYaAAAAAECny70TbT0KGVfPAnjdLBroHYtD&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
        $token = $_GET['token'];
        $query = mysqli_query($connect,"SELECT * FROM users WHERE activationcode='$token' and status='0'");
        if($query){
            if(mysqli_num_rows($query)==1){
                $login_form =  '
                <h1 class="text-center">Please enter your details to verify your account</h1><br><form action="" method="POST">
               <input type="email" name="email" id="" class="form-control" placeholder="Email Address" value="'.$email.'">
                <br><br>
                <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                <br><br>
                <div id="check">
<input type="checkbox" autocomplete="off" id="checkbox"> <label for="showpassword" id="showpassword">Show Password</label>
</div><br>
<div class="g-recaptcha" data-sitekey="6LeHnCYaAAAAAA_GGODJUOrQLfgYKIxWMvyeDXBK"></div>
<br>
                <button type="submit" name="submit" class="btn btn-primary">Verify Account</button>
                </form>
                <div id="login-form"></div>
   <script src="loading.js"></script>
                </div>';
                if(isset($_POST['submit'])){
                    $password = hash("sha512",$_POST['password']);
    $email = $_POST['email'];
    if($response['success'] == false){
        $return_message = '<br><div class="alert alert-danger" role="alert">Please check the captcha box.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
    }
    else if(empty(trim($_POST['email']))){
        $return_message = '<br><div class="alert alert-danger" role="alert">Incorrect email and/or password.  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
       
    }
    else{
        if(empty(trim($_POST['password']))){
            $return_message = '<br><div class="alert alert-danger" role="alert">Incorrect email and/or password.  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
           
        }
        else{
              $password_verify = mysqli_query($connect, "SELECT * FROM users WHERE email='$email' and password='$password' and activationcode='$token'");
              if(mysqli_num_rows($password_verify)==1){
                $token = $_GET['token'];
                $verified = "1";
               $activate  = mysqli_query($connect,"UPDATE users SET status='$verified', activationcode='$newcode'  WHERE activationcode='$token'");
               if($activate){
                $return_message = '<br><div class="alert alert-success" role="alert">Email Successfully verified <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
                
                   session_destroy();
                   header("refresh:3, url=login");
               }
            }
            else{
                   $return_message = '<br><div class="alert alert-danger" role="alert">Incorrect email and/or password.  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                   </div>';
                    
                  
            }
        }
    }
}
            }
            else{
                $active_email = mysqli_query($connect, "SELECT * FROM users WHERE activationcode='$token' and status='1'");
                if(mysqli_num_rows($active_email)==1){
                    $return_message = "Email has already been verified no need to verify again.";
                }
                else{
                    $return_message = "Invalid Activation Link";
                }
               
            }
        }
    else{
        $return_message = "Invalid Verification Link";
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
    x.type = "text";
    
  } else {
    document.getElementById("checkbox").checked = false;
    x.type = "password";
  }
}

</script>

</body>
</html>