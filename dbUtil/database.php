<?php


require_once 'dbUtil/connect.php';


if(isset($_POST['save']))
{


   $firstName = $_POST['firstName'];
   $lastName = $_POST['lastName'];
   $email = $_POST['email'];
   $mobileNo = $_POST['mobileNo'];
   $whatsAppNo = $_POST['whatsAppNo'];
   $telegramNo = $_POST['telegramNo'];
   $gender = $_POST['gender'];
   $address = $_POST['address'];
   $cityID = $_POST['cityID'];
   $areaID = $_POST['areaID'];
   $volunteer = $_POST['volunteer'];
   
   if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
      echo "Invalid email format";
  }

   $sql = "INSERT INTO warkari(firstName, lastName, email, mobileNo, whatsAppNo, telegramNo, gender, address, cityID, areaID, volunteer)
           VALUES ( '$firstName', '$lastName', '$email', '$mobileNo', '$whatsAppNo', '$telegramNo', '$gender', '$address', '$cityID', '$areaID', '$volunteer')";

   $res = mysqli_query($conn , $sql) ;
    
   if($res)
   {
      header("Location:registerWarkari.php");
   }
   else 
   {
      echo "Please fill the form again.";
   }       
}
?>
