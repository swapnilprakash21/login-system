
<?php
require '_config_database.php';
$password = hash("sha512",$_POST['password']);
$email = $_POST['email'];
$captcha= $_POST['g-recaptcha-response'];
$response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LeHnCYaAAAAAECny70TbT0KGVfPAnjdLBroHYtD&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
session_start();

if(!isset($_SESSION['email'])|| $_SESSION['login_access']!=true){
    
}
else{
    header("location: /login-system/");
}

if(isset($_POST['submit'])){
    if($response['success'] == false){
        $return_message = '<br><div class="alert alert-danger" role="alert">Please check the recaptcha box.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
    }
    else if(empty(trim($_POST['email']))){
        $return_message = '<br><div class="alert alert-danger" role="alert">Incorrect email and/or password.  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
       
    }
    
    else if(empty(trim($_POST['password']))){
            $return_message = '<br><div class="alert alert-danger" role="alert">Incorrect email and/or password.  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
           
        }
        else{
              $password_verify = mysqli_query($connect, "SELECT * FROM users WHERE email='$email' and password='$password'");
              if(mysqli_num_rows($password_verify)==1){
                  while($row = mysqli_fetch_array($password_verify)){
                      $name = $row['firstname'];
                      $status = $row['status'];
                  }
                  $fail_login = mysqli_query($connect, "SELECT *  FROM users WHERE email='$email' and status='0'");
                  if(mysqli_num_rows($fail_login)==1){
                      session_start();
                      $_SESSION['unverified'] = true;
                      $_SESSION['unverified_email'] = $email;
                      header("location: unverified");
                  }
                  else{
                      mysqli_query($connect, "UPDATE users SET last_login=current_timestamp() WHERE email='$email'");
                      session_start();
              
                  $_SESSION['login_access'] = true;
                  $_SESSION['email'] = $email;
                  $_SESSION['name'] = $name;
                  header("location: /login-system/");
                  }
              }
              else{
                $return_message = '<br><div class="alert alert-danger" role="alert">Incorrect email and/or password.  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
                
              }

          }
          
        }
    

/*CREATE TABLE `users` (
    `id` int(255) NOT NULL,
    `email` longtext NOT NULL,
    `password` longtext NOT NULL,
    `firstname` varchar(255) NOT NULL,
    `lastname` varchar(255) NOT NULL,
    `gender` varchar(20) NOT NULL,
    `activationcode` varchar(2550) NOT NULL,
    `status` int(20) NOT NULL,
    `dt` varchar(255) NOT NULL DEFAULT current_timestamp(),
    `forgot_status` int(100) NOT NULL,
    `forgot_token` longtext NOT NULL,
    `last_login` longtext NOT NULL,
    `profile_pic` longtext NOT NULL,
    `type` longtext NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
*/
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Login</title>
    <link rel="stylesheet" href="/lib/css/bootstrap.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <meta property="og:image" content="https://swapnilprakash.tunnelto.dev/login-system/php-tutsplus.png">
    <meta name="description" content="Login to continue">
    <style>
    body{
     /*   justify-content: center;
        align-items: center;
        display: flex;
        flex-direction: column;
        */
    }
    form{
       /* width: 800px;
        justify-content: center;
        align-items: center;
        display: flex;
        flex-direction: column;*/
    }
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
    /*#login-form{
        display: flex;
        justify-content: center;
        margin-top: 20%;
        margin-left: 12%;
    }*/
    <?php require '_hyperlink_loader.php'?>
        </style>
    <script src="/lib/js/bootstrap.js"></script>
</head>
<body>
<?php 
session_start();
if(isset($_SESSION["login_access"]) && $_SESSION["login_access"] === true){
    header("location: /login-system/");
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
    <h1 class="text-center">Login to Continue</h1>
   <form action="" method="POST">
   <?php echo $return_message?><br><br>
   <br>
   <input type="email" name="email" id="" class="form-control" placeholder="Email Address" value="">
   <br><br>
   <input type="password" name="password" id="password" class="form-control" placeholder="Password">
   <br><br>
<div id="check">
<input type="checkbox" autocomplete="off" id="checkbox"> <label for="showpassword" id="showpassword">Show Password</label>
</div>
<br>
<div class="g-recaptcha" data-sitekey="6LeHnCYaAAAAAA_GGODJUOrQLfgYKIxWMvyeDXBK"></div>
<br>
   <button type="submit" name="submit" class="btn btn-primary">Login</button>
   </form>
   <div id="login-form"></div>
   <script src="loading.js"></script>
    </div>
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
<?php
/*
if(isset($_GET['token'])){
    $auto_email = $_GET['email'];
    $auto_token = $_GET['token'];
    $auto_login = mysqli_query($connect, "SELECT * FROM users WHERE email='$auto_email' and password='$auto_token'");
    if(mysqli_num_rows($auto_login)==1){
        session_start();

        while($auto_row = mysqli_fetch_array($auto_login)){
            $session_name = $auto_row['firstname'];
            $_SESSION['name'] = $session_name;
        }      
        $_SESSION['login_access'] = true;
        $_SESSION['email'] = $auto_email;
       
        header("location: /login-system/");
    }
    else{
        header("location: /login-system/login");
    }
}
*/
?>
<!--<script>
    var login = document.getElementById("login")
    document.getElementById("login").onsubmit = function loader(){
        
        login.style.display="none";
        document.getElementById("login-form").innerHTML = '<br><br><br><br><br><iframe src="data:text/html;base64,PCFET0NUWVBFIGh0bWw+DQo8aHRtbCBsYW5nPSJlbiI+DQo8aGVhZD4NCiAgICA8bWV0YSBjaGFyc2V0PSJVVEYtOCI+DQogICAgPG1ldGEgbmFtZT0idmlld3BvcnQiIGNvbnRlbnQ9IndpZHRoPWRldmljZS13aWR0aCwgaW5pdGlhbC1zY2FsZT0xLjAiPg0KICAgIDx0aXRsZT5Eb2N1bWVudDwvdGl0bGU+DQo8c3R5bGU+DQogICAgKnsNCiAgICAgICAgb3ZlcmZsb3c6IGhpZGRlbjsNCiAgICB9DQouc3Bpbm5lci1wYXRoew0KICAgIGFuaW1hdGlvbjogMS41cyBkYXNoIGluZmluaXRlOw0KICAgIHN0cm9rZTojMGQ2ZWZkOw0KICAgIHRyYW5zaXRpb246IDFzOw0KfQ0KLnNwaW5uZXItY29udGFpbmVyew0KICAgICAgICB6LWluZGV4OiAyOw0KICAgICAgICBhbmltYXRpb246IHJvdGF0ZSAycyBsaW5lYXIgaW5maW5pdGU7DQogICAgICAgDQp9DQpAa2V5ZnJhbWVzIGRhc2h7DQogICAgMCV7DQogICAgICAgIHN0cm9rZS1kYXNoYXJyYXk6IDEsMTUwOw0KICAgICAgICBzdHJva2UtZGFzaG9mZnNldDogMDsNCg0KICAgIH0NCiAgICA1MCV7DQogICAgICAgIHN0cm9rZS1kYXNoYXJyYXk6IDEwMCwxNTA7DQogICAgICAgIHN0cm9rZS1kYXNob2Zmc2V0OiAtMzU7DQoNCiAgICB9DQogICAgMTAwJXsNCiAgICAgICAgc3Ryb2tlLWRhc2hhcnJheTogMTAwLDE1MDsNCiAgICAgICAgc3Ryb2tlLWRhc2hvZmZzZXQ6IC0xMjQ7DQoNCiAgICB9DQp9DQpAa2V5ZnJhbWVzIHJvdGF0ZXsNCiAgIDEwMCUgeyANCiAgICAgICB0cmFuc2Zvcm06IHJvdGF0ZSgzNjBkZWcpOyANCiAgICAgICB9DQp9DQo8L3N0eWxlPg0KPC9oZWFkPg0KICAgIDxib2R5Pg0KICAgICAgICAgICAgPHN2ZyBjbGFzcz0ic3Bpbm5lci1jb250YWluZXIiIGhlaWdodD0iNTAlIiB3aWR0aD0iNTAlIiB2aWV3Qm94PSIwIDAgNDQgNDQiPg0KICAgICAgICAgICAgDQogICAgICAgICAgICA8Y2lyY2xlIGNsYXNzPSJzcGlubmVyLXBhdGgiIGN4PSIyMiIgY3k9IjIyIiByPSIyMCIgc3Ryb2tlLXdpZHRoPSIyIiBmaWxsPSJub25lIj48L2NpcmNsZT48L3N2Zz4NCiAgICA8L2JvZHk+DQogICAgPC9odG1sPg==" frameborder="0" oncontextmenu="return false;"></iframe>';
    }
</script>-->

</body>
</html>