
<?php 
get_header();
/**
 * Template Name: registerUser
 *
 */

 $dbName = "mxtaxdmy_WPG8B";
 $dbHost = "162.214.80.124";
 $dbUser = "mxtaxdmy_WPG8B";
 $dbPass = "WQlwCEu6[D%h";
 $conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);


?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title style=" text-align: center;">Registration</title>
    <style> <?php include_once "res/user.css" ?> </style> 
</head>
<body>
<?php

    
    // When form submitted, insert values into the database.
    if (isset($_REQUEST['username'])) {
        // removes backslashes
        $username = stripslashes($_REQUEST['username']);
        //escapes special characters in a string
        $username = mysqli_real_escape_string($conn, $username);
        $email    = stripslashes($_REQUEST['email']);
        $email    = mysqli_real_escape_string($conn, $email);
        $password = stripslashes($_REQUEST['password']);
        $password = mysqli_real_escape_string($conn, $password);
        $query    = "INSERT into `usermaster` (userName, email, passWord)
                     VALUES ('$username', '$email', '" . md5($password) . "')";
        $result   = mysqli_query($conn, $query);
        if ($result) {
            echo "<div class='form'>
                  <h3>You are registered successfully.</h3><br/>
                  <p class='link'>Click here to <a href='./login/'>Login</a></p>
                  </div>";
        } else {
            echo "<div class='form'>
                  <h3>Required fields are missing.</h3><br/>
                  <p class='link'>Click here to <a href='./Register/'>registration</a> again.</p>
                  </div>";
        }
    } else {
?>
    <form class="form" action="" method="post">
        <h1 class="login-title">Registration</h1>
        <input type="text" class="login-input" name="username" placeholder="Username" required />
        <input type="text" class="login-input" name="email" placeholder="Email Adress">
        <input type="password" class="login-input" name="password" placeholder="Password">
        <input type="submit" name="submit" value="Register" class="login-button">
        <p class="link"><a href="./login">Click to Login</a></p>
    </form>
<?php
    }
?>
</body>
</html>
