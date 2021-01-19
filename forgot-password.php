<?php 
    if(isset($_POST['submit'])){
        require '_config_database.php';
        $email = $_POST['email'];
        $captcha= $_POST['g-recaptcha-response'];
$response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LeHnCYaAAAAAECny70TbT0KGVfPAnjdLBroHYtD&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
        
    if($response['success'] == false){
    $return_message = '<br><div class="alert alert-danger" role="alert">Please check the captcha box.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    }
    else if(empty(trim($_POST['email']))){
            $return_message = '<br><div class="alert alert-danger" role="alert">Please enter your Email Address.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        }
        else{
            $verify_email = mysqli_query($connect, "SELECT * FROM users WHERE email = '$email'");
            if(mysqli_num_rows($verify_email)==1){
                $verified = mysqli_query($connect, "SELECT * FROM users WHERE email='$email' and status='1'");
                if(mysqli_num_rows($verified)==1){
                    $forgot_token=hash("sha512",$email.time());
                    while($row = mysqli_fetch_array($verified)){
                        $firstname = $row['firstname'];
                    }
                    $query = mysqli_query($connect, "UPDATE users SET forgot_token = '$forgot_token', forgot_status='0' WHERE email='$email'");
                    $to=$email;

		
		            $subject="Changing Password";
		
                    $headers .= "MIME-Version: 1.0"."\r\n";

                    $headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
        
                    $headers .= 'From:Swapnil Prakash <swapnil.prakash20@gmail.com>'."\r\n" . "CC: swapnil.prakash20@gmail.com" .  "\r\n";
		            $msg.="<html></body><div><div>Dear $firstname,</div></br></br>";

                    $msg.="<div style='padding-top:8px;'>Please click The following link for resetting the password of your account</div>

                    <div style='padding-top:10px;'><a href='http://swapnilprakash.tunnelto.dev/login-system/password-reset?token=$forgot_token'>Click Here</a></div>



</body></html>";
		
        mail($to,$subject,$msg,$headers);
        $return_message = '<br><div class="alert alert-success" role="alert">A link has been sent to '.$email.' for resetting the password. Please Click on the link to reset password.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
                }
            }
            else{
                $return_message = '<br><div class="alert alert-danger">Email does not exist.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Forgot Password</title>
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
<?php 
session_start();
if(isset($_SESSION["login_access"]) && $_SESSION["login_access"] === true){
    $forgot_password_in_loggedin_mode_email = $_SESSION['email'];
    $forgot_password_in_loggedin_mode = hash("sha512", $forgot_password_in_loggedin_mode_email.time());
    $_SESSION['forgot_password_in_loggedin_mode'] = $forgot_password_in_loggedin_mode;
    header("location: /login-system/?access=forgot_password&token=$forgot_password_in_loggedin_mode");
    exit;
}
else{
    if(isset($_SESSION["unverified"]) && $_SESSION["unverified"] === true){
        header("location: /login-system/unverified");
        exit;
    }
}
?>
    <?php require "_navbar.php"?>
    <div class="container my-5 form-group">
        <h4 class="text-center">Please Enter your Email Address. We will send you a link to reset your password.</h2>
        <?php echo $return_message?>
        <form method="post">
            <input type="email" name="email" id="email" class="form-control" placeholder="Enter your Email Address"><br>
            <div class="g-recaptcha" data-sitekey="6LeHnCYaAAAAAA_GGODJUOrQLfgYKIxWMvyeDXBK"></div>
<br>
            <button type="submit" name="submit" class="btn btn-primary">Send Link</button>
        </form>
        <div id="login-form"></div>
   <script src="loading.js"></script>
    </div>
</body>
</html>