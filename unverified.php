<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Please verify your email</title>
    <link rel="stylesheet" href="/lib/css/bootstrap.min.css">
    <style>
          .navbar-nav{
        margin-left: auto;
    }
    <?php require '_hyperlink_loader.php'?>

    </style>
</head>
<body>
<?php require '_navbar.php'?>
    <div class="container">
<?php 
session_start();

if(!isset($_SESSION['unverified']) || $_SESSION['unverified']!=true){
    header("location: login");
    exit;
}
?>
    <?php
    session_start();
    require '_config_database.php';
    
   $email =  $_SESSION['unverified_email'];
   $fetch_activationcode = mysqli_query($connect, "SELECT * FROM users WHERE email='$email'");
    while($row = mysqli_fetch_array($fetch_activationcode)){
        $activationcode = $row['activationcode'];
    }
        $check = mysqli_query($connect, "SELECT * FROM users WHERE email='$email' and status='0'");
        if(mysqli_num_rows($check)==1){
            $sent =  "Your email <strong>$email</strong> is not yet verified. Please click the link in your inbox to complete verification. " . '<form method="POST"><button type="submit" name="mail" class="btn btn-outline-primary">Send Link Again</button></form>';
            echo $sent;
            
        }
        else{
            $sent = "Email has already been verified.";
            echo $sent;

    session_destroy();
    header("refresh:2: url=login");
        }
    
    
        
    if(isset($_POST['mail'])){
        $query = mysqli_query($connect, "SELECT * FROM users WHERE email='$email' and status='1'");
        if(mysqli_num_rows($query)==1){
           $sent="Email has already been verified.";
          //  echo "Email has already been verified.";
            session_destroy();
            header("refresh:2, url=login");
        }
        else{
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
        }
    }
    ?>
    <div id="login-form"></div>
    <script src="loading.js"></script>
    </div>
</body>
</html>