<?php
/* Template Name: Payment Template*/

// Start output buffering
ob_start();

include_once('dbUtil/connect.php');

get_header();

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
session_start();
$warkariID = $_SESSION['warkariID'];
$tappaIDs = $_SESSION['tappaIDs'];
$tappaData = $_SESSION['tappaData'];


// Initialize total contribution
$totalContribution = 0;

// Handle form submission
if (isset($_POST['submit'])) {
  $transaction_id= $_POST['transaction_id'];

  // Select the maximum enrollmentID from tappaenrollment and add 1 to it
  $result = mysqli_query($conn, "SELECT max(enrollmentID)+1 FROM tappaEnrollment");
  $result = $result->fetch_array();
  $maxEID = intval($result[0]);

  // Loop through each tappaData array element
  foreach ($tappaData as $key => $tappa) {
    $tappaID = intval($tappaIDs[$key]); // Get the correct tappaID from the $tappaIDs array using the $key variable
    $tappaDate = $tappa['tappaDate'];
    $contribution = $tappa['contribution'];
    $totalContribution = $tappa['totalContribution'];
    $tappaName = $tappa['tappaName'];
    $returnFlag = $tappa['returnFlag'];
   
   
    $sql = "INSERT INTO tappaEnrollment (enrollmentID, warkariID, tappaID, tappaDate, contribution, totalContribution, returnFlag, registerDate, transactionID, confirmFlag) 
            VALUES ($maxEID, $warkariID, $tappaID, '$tappaDate', $contribution, $totalContribution, $returnFlag, TIMESTAMP(NOW()), '$transaction_id', 1)";
    $result = mysqli_query($conn, $sql);

if (!$result) {
echo "Error inserting record: " . mysqli_error($conn);
}
  }

$ID = get_current_user_id();

$sql = "SELECT user_email FROM gFs_users WHERE ID = $ID";
$result = mysqli_query($conn, $sql);

$row = mysqli_fetch_assoc($result);

$userEmail = $row['user_email'];

$sql = "SELECT tappa FROM tappaMaster WHERE tappaID = $tappaID";
$result = mysqli_query($conn, $sql);

// Fetch the data from the $result variable and assign it to the $row variable.
$row = mysqli_fetch_assoc($result);
$tappaName = $row['tappa'];

$sql = "SELECT email,firstName,lastName FROM warkari WHERE warkariID = $warkariID";
$result = mysqli_query($conn, $sql);

$row = mysqli_fetch_assoc($result);
$warkariEmail = $row['email'];

$warkariName = $row['firstName'] . ' ' . $row['lastName'];

// Create an HTML table with transaction details using the data from the $tappaData variable.
$table = '<table style="border-collapse: collapse; width: 100%;">
<thead>
<tr>
<th style="border: 1px solid black; padding: 10px; text-align: center;" rowspan="' . count($tappaData) . '">Warkari Name</th>
<th style="border: 1px solid black; padding: 10px; text-align: center;">Tappa Name</th>
<th style="border: 1px solid black; padding: 10px; text-align: center;">Tappa Date</th>
<th style="border: 1px solid black; padding: 10px; text-align: center;">Total Contribution</th>
<th style="border: 1px solid black; padding: 10px; text-align: center;">Transaction ID</th>
<th style="border: 1px solid black; padding: 10px; text-align: center;">Register Date</th>
</tr>
</thead>
<tbody">';



foreach ($tappaData as $key => $tappa) {
$tappaID = intval($tappaIDs[$key]);
$tappaName = '';
$tappaDate = $tappa['tappaDate'];
$contribution = $tappa['contribution'];
$totalContribution = $tappa['totalContribution'];
$registerDate = date('Y-m-d');

// Get the tappa name from the database
$sql = "SELECT tappa FROM tappaMaster WHERE tappaID = $tappaID";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

if ($row) {
$tappaName = $row['tappa'];
}


$table .= '<tr>';

// If it's the first iteration of the loop, add the Warkari name to the table
if ($key == 0) {
$table .= '<td style="border: 1px solid black; padding: 10px; text-align: center;" rowspan="' . count($tappaData) . '">' . $warkariName . '</td>';
}

$table .= '<td style="border: 1px solid black; padding: 10px; text-align: center;">' . $tappaName . '</td>';
$table .= '<td style="border: 1px solid black; padding: 10px; text-align: center;">' . $tappaDate . '</td>';
$table .= '<td style="border: 1px solid black; padding: 10px; text-align: center;">' . $totalContribution . '</td>';
$table .= '<td style="border: 1px solid black; padding: 10px; text-align: center;">' . $transaction_id . '</td>';
$table .= '<td style="border: 1px solid black; padding: 10px; text-align: center;">' . $registerDate . '</td>';
$table .= '</tr>';
}
$table .= '</tbody></table>';


// Set recipient email address and add user's email to the CC field
$to = $warkariEmail;
$cc = $userEmail; 

$subject = 'IT Dindee';
$body = "Ram Krishna Hari $warkariName Mauli! 🙏🏼

$userEmail mauli has enrolled your name for following Wari Tappas for iTDindi:

$table

Once enrollment is processed by the backend team, we will send you the confirmation details with a link to join the respective WhatsApp group/Telegram channel for following updates.<br>

Regards,<br>
iT Dindee";

// Set email headers
$headers = array('Content-Type: text/html; charset=UTF-8', 'Cc:' . $cc);
$result = wp_mail($to, $subject, $body, $headers);

if ($result) {
echo 'Email sent successfully!';
} else {
echo 'Failed to send email.';
}

// Set maxEID session variable to $maxEID value
$_SESSION['maxEID'] = $maxEID;


wp_redirect("https://itdindee.org/staging/7363/transaction-details/");
exit();
}

$warkariID = $_GET['warkariID'];
$tappaIDs = explode(',', $_GET['tappaIDs']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
 
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css"> 
  <style> <?php include_once "res/Style.css" ?> </style> 
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width,minimum-scale=1">

  <style>
    @media (max-width: 767px) {
      body {
        background-position: center top;
      }
      .payment-container {
        padding: 20px;
      }
     
    }
  </style>
</head>
<body  style="background-image: url('https://itdindee.org/wp-content/uploads/2023/03/Picsart_23-03-04_01-44-34-150-1-2-scaled.jpg'); background-repeat: no-repeat; background-size: cover;">
  <div class="payment-container">
    <div class="center-container">
      <h1>Payment Page</h1></br>


      <h5 style="color:#ffc107;font-weight:bold">You Have Selected Following Tappas</h5></br>
<table style="border-collapse: collapse; width: 100%;">
<thead>
<tr>
<th style="border: 1px solid black; padding: 10px; text-align: center; color:#FFF">Tappa Name</th>
<th style="border: 1px solid black; padding: 10px; text-align: center;  color:#FFF">Tappa Date</th>
<th style="border: 1px solid black; padding: 10px; text-align: center;  color:#FFF">Contribution</th>

</tr>
</thead>
<tbody>
<?php
session_start();
$tappaIDs = $_SESSION['tappaIDs'];
$tappaData = $_SESSION['tappaData'];

foreach ($tappaData as $key => $tappa) {
  $tappaID = intval($tappaIDs[$key]); 
  $tappaName = '';
  $tappaDate = $tappa['tappaDate'];
  $contribution = $tappa['contribution'];
  
  $sql = "SELECT tappa FROM tappaMaster WHERE tappaID = $tappaID";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);

  if ($row) {
    $tappaName = $row['tappa'];
  }


  echo '<tr>';
  echo '<td style="border: 1px solid black; padding: 10px; text-align: center;color:white">' . $tappaName . '</td>';
  echo '<td style="border: 1px solid black; padding: 10px; text-align: center;color:white">' . $tappaDate . '</td>';
  echo '<td style="border: 1px solid black; padding: 10px; text-align: center;color:white">' . $contribution . '</td>';

  echo '</tr>';
}

?>
</tbody>
</table>



      <p><h3 class="enrollment-message">Please Make Your Payment To Confirm Your Enrollment</h3></p>
     
      <form method="post" >
        <label for="transaction-id" style="display: block">Transaction ID:</label>
        <input type="text" name="transaction_id" id="transaction-id" required><br><br>
       
        <a  href="joinwaari"  style="color:#FFF;position: absolute;  left:20px; top:70px; font-size: 54px; margin-right:100px">
          <i class="bi bi-arrow-left-short bi-2x" ></i>
        </a>
       
        <?php if (isset($_POST['submit'])): ?>
          <hr>
          <h2 id="transaction-details-<?php echo $transaction_id; ?>">Transaction Details</h2>
          <p>Transaction ID: <?php echo $transaction_id; ?></p>
          <p>Total Contribution: <?php echo $totalContribution; ?></p>
         
        <?php endif; ?>
        <input type="submit" name="submit" value="Submit Payment">
      </form>
    </div>
  </div>
</body>
</html>