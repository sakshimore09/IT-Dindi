<?php 
session_start();
$dbName = "mxtaxdmy_WPG8B";
$dbHost = "162.214.80.124";
$dbUser = "mxtaxdmy_WPG8B";
$dbPass = "WQlwCEu6[D%h";
$conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
   
  function display_data(){
    global $conn;
    $user_email = $_SESSION['user_email'];
    $query = "SELECT ID FROM `gFs_users` WHERE user_email='$user_email'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);
    $userID = $user['ID'];
    $query = "SELECT * FROM `warkari` WHERE userID='$userID'";
    $result = mysqli_query($conn, $query);
    return $result;
}
function display_tappa(){
  global $conn;
  $sql = "SELECT * FROM tappaMaster";
  $result = mysqli_query($conn, $sql);
  return $result;
}



?>
