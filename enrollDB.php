<?php

require_once 'db.php';

// Check if the form has been submitted
if(isset($_POST['submit'])) {
    $warkariID = $_GET['warkariID'];
    if (isset($_POST['tappa'])) {
      $tappaName = $_POST['tappa'];
      $query = "SELECT tappaID FROM tappamaster WHERE tappa = '$tappaName'";
      $result = mysqli_query($con, $query);
      $row = mysqli_fetch_assoc($result);
      $tappaID = $row['tappaID'];
    }
  
    if (isset($_POST['contribution_'])) {
      $contribution = implode(',', $_POST['contribution_']);
    } else {
      $contribution = '';
    }
    
    $totalContribution = $_POST['totalContribution'];
  
    $query = "INSERT INTO tappaenrollment (warkariID, tappaID, contribution, totalContribution)
    VALUES ('$warkariID', '$tappaID','$contribution' ,'$totalContribution')";
  
    $result = mysqli_query($con, $query);
  
    if ($result) {
      echo "Data successfully inserted into the database";
    } else {
      echo "Error in inserting data into the database";
    }
  
}


?>
