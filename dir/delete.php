<?php
/* Template Name: delete Template */
session_start();
$dbName = "mxtaxdmy_WPG8B";
$dbHost = "162.214.80.124";
$dbUser = "mxtaxdmy_WPG8B";
$dbPass = "StUGnLOTe2HKIa*.&";
$conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);

if (isset($_GET['warkariID'])) {

$id = $_GET['warkariID'];
$sql = "DELETE FROM warkari WHERE warkariID='$id'";
if(mysqli_query($conn,$sql)){
    session_start();
    $_SESSION["delete"] = "Record Deleted Successfully!";
    header("Location:welcome.php");
}else{
    die("Something went wrong");
}
}else{
    echo "Record does not exist";
}
?>