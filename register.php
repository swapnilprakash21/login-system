<?php 
if(isset($_POST['submit'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $gender = $_POST['gender'];
    $male = "Male";
    $female = "Female";
    $captcha= $_POST['g-recaptcha-response'];
$response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LeHnCYaAAAAAECny70TbT0KGVfPAnjdLBroHYtD&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
    if($response['success'] == false){
    $return_message = '<br><div class="alert alert-danger" role="alert">Please check the captcha box.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
}
   else if(empty(trim($_POST['email']))){
        $return_message = '<br><div class="alert alert-danger" role="alert">All fields are required<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
       
    }
  
     else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $return_message = '<br><div class="alert alert-danger" role="alert">Invalid Email Address.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
      }
    else if(empty(trim($_POST['password']))){
        $return_message = '<br><div class="alert alert-danger" role="alert">All fields are required<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
           
        }
    
        else if(empty(trim($_POST['firstname']))){
            $return_message = '<br><div class="alert alert-danger" role="alert">All fields are required<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
            }
               
            else if (!preg_match("/^[a-zA-Z-' ]*$/",$firstname)) {
                $return_message = '<br><div class="alert alert-danger" role="alert">Only letters and white space allowed in First Name.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
              }
            else if(empty(trim($_POST['lastname']))){
                $return_message = '<br><div class="alert alert-danger" role="alert">All fields are required<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
                    
                   
                }
                else if (!preg_match("/^[a-zA-Z-' ]*$/",$lastname)) {
                    $return_message = '<br><div class="alert alert-danger" role="alert">Only letters and white space allowed in Last Name.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                  }
                  else if(empty(trim($_POST['gender']))){
                    $return_message = '<br><div class="alert alert-danger" role="alert">All fields are required<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                       
                  }
                  else if($password!=$confirm_password){
                    $return_message = '<br><div class="alert alert-danger" role="alert">Passwords did not match.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                  }
                    else{
                        require '_config_database.php';
                        if($connect){
                            $email_not_exists = mysqli_query($connect, "SELECT * FROM users WHERE email='$email'");
                            if(mysqli_num_rows($email_not_exists)==1){
                                $return_message =  '<br><div class="alert alert-danger" role="alert">Email already exists<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>';
                            }
                            else{
                                $insert_password = hash("sha512",$password);
                                $activationcode=hash("sha512",$email.time());
                                $status="0";
                                $email = $_POST['email'];
                                $password = $_POST['password'];
                                $firstname = $_POST['firstname'];
                                $lastname = $_POST['lastname'];
                                $gender = $_POST['gender'];
                                $img = file_get_contents('profile_picture.jpg'); 
                                $pic = addslashes(file_get_contents("profile_picture.jpg"));      
                                    // Encode the image string data into base64 
                                   // $pic = "data:image/jpg;base64," . base64_encode($img); 
                                      
                                
                                
                                $query = mysqli_query($connect, "INSERT INTO users (email, password, firstname, lastname, gender, activationcode, status ,dt, profile_pic) VALUES ('$email', '$insert_password', '$firstname', '$lastname', '$gender', '$activationcode', '$status', current_timestamp(), '$pic')");
                                if($query){
                                    function random_strings($length_of_string) 
{ 
$str_result = '0183K58jU9jmt1JRuYcDcmXWky8CyvW79pq1qxePi72FucuiGhUuKPrMWCnxwDxHyCapmgYTypH3f54XqkLYfxhZpDmukxx1otFamYf5k8CyvW79pq1qxePi72FucuiG9HdaL4gfuNBLX4ZNU8hKPuTvEuBpDmukxx1otFamYf5kvKb8aF'; 
return substr(str_shuffle($str_result), 0, $length_of_string); 
}
                                    $to=$email;

		
		$subject="Email verification";
		
        $headers .= "MIME-Version: 1.0"."\r\n";

        $headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
        
        $headers .= 'From:Swapnil Prakash <swapnil.prakash20@gmail.com>'."\r\n" . "CC: swapnil.prakash20@gmail.com" .  "\r\n";
		$msg.="<html></body><div><div>Dear $firstname,</div></br></br>";

$msg.="<div style='padding-top:8px;'>Please click The following link For verifying and activation of your account</div>

<div style='padding-top:10px;'><a href='http://swapnilprakash.tunnelto.dev/login-system/verification?token=$activationcode'>Click Here</a></div>



</body></html>";
		
        mail($to,$subject,$msg,$headers);
        session_start();
        $token_submitted = $_SESSION['token_submitted'] = random_strings(250);
        $_SESSION['email'] = $email;
        $_SESSION['activationcode'] = $activationcode;
        $_SESSION['firstname'] = $firstname;
        $submitted = $_SESSION['submitted'] = "true";
        
         // $query=mysqli_query($connect,"INSERT INTO users_1 (firstname, lastname, grade, email, dt, status) values('$firstname', '$lastname', '$grade','$email', current_timestamp(), '0')");
        
        
        
        header("location: verify?submitted=$submitted&token=$token_submitted");
                                }
                            }
                        }
                    }
                    

                }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Register</title>
    <link rel="stylesheet" href="/lib/css/bootstrap.css">
    <style>
   /* body{
        justify-content: center;
        align-items: center;
        display: flex;
        flex-direction: column;
    }
    form{
        width: 800px;
        justify-content: center;
        align-items: center;
        display: flex;
        flex-direction: column;
    }*/
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
    .navbar-togdgler{
        background-color: white;
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
    $register_in_loggedin_mode_email = $_SESSION['email'];
    $register_in_loggedin_mode = hash("sha512", $register_in_loggedin_mode_email.time());
    $_SESSION['register_in_loggedin_mode'] = $register_in_loggedin_mode;
    header("location: /login-system/?access=register&token=$register_in_loggedin_mode");
    exit;
}
else{
    if(isset($_SESSION["unverified"]) && $_SESSION["unverified"] === true){
        header("location: /login-system/unverified");
        exit;
    }
}
?>
    <?php require"_navbar.php"?>
<div class="container my-5 form-group">
<h1 class="text-center">Register</h1>
   <form action="" method="POST">
   <?php echo $return_message?><br><br>
   <input type="email" name="email" id="" class="form-control" placeholder="Email Address" value="<?php echo $email?>">
   <br><br>
   <input type="password" name="password" id="password" class="form-control" placeholder="Password">
   <br><br>
   <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm Password">
   <br>
   <div id="check">
<input type="checkbox" autocomplete="off" id="checkbox"> <label for="showpassword" id="showpassword">Show Password</label>
</div>
<br>
   <input type="text" name="firstname" id="" class="form-control" placeholder="First Name" value="<?php echo $firstname?>">
   <br><br>
   <input type="text" name="lastname" id="" class="form-control" placeholder="Last Name" value="<?php echo $lastname?>">
   <br><br>
    <select name="gender" id="" class="form-select" required>
    <option value="" disabled selected>Gender</option>
    <option value="Male">Male</option>
    <option value="Female">Female</option>
    </select>
    <br><br>
<div class="g-recaptcha" data-sitekey="6LeHnCYaAAAAAA_GGODJUOrQLfgYKIxWMvyeDXBK"></div>
<br><br>
   <button type="submit" name="submit" class="btn btn-primary">Register</button>
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