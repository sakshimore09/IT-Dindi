<?php
/* Template Name: Edit template */
session_start();
include_once('dbUtil/connect.php');

$user_id = get_current_user_id();

$query = "SELECT ID FROM `gFs_users` WHERE user_email='$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
$warkari_id = $_GET["warkariID"];
$sql = "SELECT w.*, c.cityName as city_name, a.areaName as area_name,cm.companyName as company_name
FROM warkari w
JOIN cityMaster c ON w.cityID = c.cityID
JOIN areaMaster a ON w.areaID = a.areaID
JOIN companyMaster cm ON w.companyID = cm.companyID WHERE warkariID='$warkari_id'";
$result = $conn->query($sql);
$data = $result->fetch_assoc();
// if (isset($_POST["cityID"]) && is_ajax()) {
//     $query = "SELECT * FROM areaMaster WHERE cityID = '".$_POST["cityID"]."'";
//     $result = mysqli_query($conn, $query);
//     $areas = mysqli_fetch_all($result, MYSQLI_ASSOC);
//     echo json_encode($areas);
//     exit;
// }

function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}
// function loadCity() {
//     global $conn;
//     $query = "SELECT * FROM cityMaster";
//     $result = mysqli_query($conn, $query);
//     $cityID = mysqli_fetch_all($result, MYSQLI_ASSOC);

//     return $cityID;
// }

// function loadArea($cityID) {
//     global $conn;
//     $query = "SELECT * FROM areaMaster WHERE cityID = $cityID";
//     $result = mysqli_query($conn, $query);
//     $areaID = mysqli_fetch_all($result, MYSQLI_ASSOC);

//     return $areaID;
// }
// function loadCompany($includeOtherOption = false) {
//   global $conn;
//   $query = "SELECT * FROM companyMaster";
//   if ($includeOtherOption) {
//       $query .= " UNION SELECT NULL, 'Other' FROM dual";
//   }
//   $result = mysqli_query($conn, $query);
//   $companyID = mysqli_fetch_all($result, MYSQLI_ASSOC);

//   return $companyID;
// }
// function generateDropdownOptions() {
//   global $conn;
//   $selectedCompanyID = $_POST['companyID'] ?? '';

//   $companyID = loadCompany($selectedCompanyID === 'Other');
//   foreach ($companyID as $Company) {
//       $selected = $Company['companyID'] === $selectedCompanyID ? 'selected' : '';
//       echo "<option id='".$Company['companyID']."' value='".$Company['companyID']."' $selected>".$Company['companyName']."</option>";
//   }
// }


if(isset($_POST['edit']) && !is_ajax()) 
{

     $userID = $_POST['user_id'];
  //  $firstName = $_POST['firstName'];
  //  $lastName = $_POST['lastName'];
  //  $email = $_POST['email'];
  //  $mobileNo = $_POST['mobileNo'];
    $whatsAppNo = $_POST['whatsAppNo'];
  //  $telegramNo = $_POST['telegramNo'];
  //  $gender = $_POST['gender'];
  //  $age = $_POST['age'];
  //  $address = $_POST['address'];
  //  $cityID = $_POST['cityID'];
  //  $areaID = $_POST['areaID'];
  //  $companyID = $_POST['companyID'];
  //  $companyName = $_POST['otherCompany'];
  //  $volunteer = $_POST['volunteer'];

 // Insert data into warkari table as before, using the updated $companyID value
//  $sql = "UPDATE warkari SET 
//  firstName='$firstName',
//  lastName='$lastName',
//  email='$email',
//  mobileNo='$mobileNo',
//  whatsAppNo='$whatsAppNo',
//  telegramNo='$telegramNo',
//  gender='$gender',
//  age='$age',
//  address='$address',
//  cityID='$cityID',
//  areaID='$areaID',
//  companyID='$companyID',
//  companyName='$companyName',
//  volunteer='$volunteer'
//  WHERE warkariID='$warkari_id'";

if ($row["whatsappFlag"] == 0) {
  // WhatsApp number field is editable
  echo '<input type="text"  id="number" placeholder="Enter phone number" name="whatsAppNo" value="'.$row["whatsAppNo"].'">';
} else {
  // WhatsApp number field is readonly
  echo '<input type="text"  id="number" placeholder="Enter phone number" name="whatsAppNo" value="'.$row["whatsAppNo"].'" readonly>';
  
}

// Check if the WhatsApp number field is edited and update the whatsappFlag column value
if (isset($_POST['whatsAppNo']) && $_POST['whatsAppNo'] != $row["whatsAppNo"]) {
  // WhatsApp number field is edited, update the whatsappFlag column value
  $whatsAppNo = $_POST['whatsAppNo'];
  $whatsappFlag = 1;

  // Update the database with the new values
  $sql = "UPDATE warkari SET whatsAppNo='$whatsAppNo', whatsappFlag='$whatsappFlag' WHERE warkariID='$warkari_id'";
  // Rest of the code...
}
   $res = mysqli_query($conn , $sql) ;
    
   if($res)
   {
	wp_redirect( './joinwaari' );
	exit();
   }
   else 
   {
    echo "Error inserting record: " . mysqli_error($conn);
      echo "Please fill the form again.";
      echo $sql;
   }       
}
get_header();
// LoadCity function  and cityID for area 
	

?>

<!DOCTYPE html>
<html style="opacity: 80%;" lang="en">
	<head>
	<meta charset="utf-8">
		<!-- meta name="viewport" content="width=device-width,minimum-scale=1" -->
		<title>Register Warkari</title>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
		<style> <?php include_once "res/Style.css" ?> </style> 
	<link rel="stylesheet" href="../css/bootstrap.min.css">
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>


	</head>
	<body  style="background-image: url('https://itdindee.org/wp-content/uploads/2023/04/wbgfinal-1-3.jpg'); background-repeat: no-repeat; background-size: cover;">
  

		<form class="register-warkari-form" method="post" action="">
			<h1 style="padding: 10px;"><i class="far fa-calendar-alt"></i>Edit  Warkari Details</h1>
			<div class="fields">
				<!-- Input Elements -->
				                    
        <div class="wrapper">
	<div>
	
		<div class="field">
			<i class="fas fa-user"></i>
			<input id="First_Name" type="text" name="firstName" placeholder="First Name" style=" background-color: #808080; border-radius: 8px;" pattern="[A-Za-z ]+" required title="Please enter letters " value = "<?= $data['firstName']?>" readonly>
      <input type="hidden" name="firstName" value="<?php echo $row["firstName"]; ?>">
		</div>
	</div>
	<div class="gap"></div>
	<div>
		
		<div class="field">
			<i class="fas fa-user"></i>
			<input id="Last_Name" type="text" name="lastName" placeholder="Last Name" style=" background-color: #808080; border-radius: 8px;" pattern="[A-Za-z ]+" required title="Please enter letters "value = "<?= $data['lastName']?>" readonly>
      <input type="hidden" name="lastName" value="<?php echo $row["lastName"]; ?>">
		</div>
	</div>
</div>


<div class="field">
	<i class="fas fa-envelope" style="margin-top:-8px;"></i>
	<input id="Email" type="Email" name="email" placeholder="Your Email" style="background-color: #808080; border-radius: 8px;" required value = "<?= $data['email']?>" readonly>
  <input type="hidden" name="email" value="<?php echo $row["email"]; ?>">
</div>

<input type="hidden" name="username" required value="<?php echo $username; ?> ">
						              
  <div class="wrapper">
	<div>
		
		<div class="field">
			<i class="fas fa-mobile-alt"></i>
			<input id="mobileNo" type="tel" name="mobileNo" placeholder="Enter Your Phone Number" style="background-color: #808080;" required pattern="[0-9]{10}"value = "<?= $data['mobileNo']?>" readonly>
      <input type="hidden" name="mobileNo" value="<?php echo $row["mobileNo"]; ?>">
    </div>
	</div>
	</div>


					<div class="wrapper">
					<div>
					
					
                    <div class="field">
      <i class="fab fa-whatsapp fa-lg fa-fw"></i>
      <input type="text" id="whatsAppNo" placeholder="Enter WhatsApp Number" name="whatsAppNo" style="background-color: <?= ($data["WhatsAppFlag"] == 1) ? '#808080' : '#fff' ?>; border-radius: 8px;" required value="<?= $data['whatsAppNo'] ?>" <?= ($data["WhatsAppFlag"] == 1) ? 'readonly':''?>>
</div>

					</div>
					<div class="gap"></div>
					<div>
					
						<div class="field" >
						<i class="fab fa-telegram-plane fa-fw"></i>	
						<input type="text" id="telegramNo" placeholder="Enter Telegram Number" name="telegramNo"style="background-color: #808080; border-radius: 8px;" required value = "<?= $data['telegramNo']?>"readonly>
					</div>
					</div>
					</div>
					
					<div class="wrapper">
    <div>
       
    <div class="field">

    <input type="text" name="gender" style="background-color: #808080; border-radius: 8px; font-size:12px;" value="<?php echo $data['gender']; ?>" readonly>
</div>

    </div>
    <div class="gap"></div>
    <div>
        <div class="field">
            <input type="text" id="number" placeholder="Enter Your Age " name="age" style="background-color: #808080; border-radius: 8px;" required readonly value = "<?= $data['age']?>" >
        </div>
    </div>
</div>

					
<div class="wrapper">
<div>
   
    <div class="field">	
        <input id="City" type="textbox" name="cityID" style="background-color:#808080; border-radius: 8px; font-size:12px;" value="<?= $data['city_name'] ?>" readonly>
  
      </div>
    </div>
  <div class="gap"></div>
 <div>
    
    <div class="field">			
        <input id="Area" type="textbox" name="areaID" style="background-color: #808080; border-radius: 8px; font-size:12px;" value="<?= $data['area_name'] ?>" readonly>
      
    </div>
  </div>
  </div>
			
		
<div class="wrapper">
<div>
   
    <div class="field">
    <input id="Address" type="textbox" name="address" placeholder="Enter Your Address" style="background-color: #808080; border-radius: 8px;" required value = "<?= $data['address']?>" disabled>
     
  </div>
    </div>
    <div class="gap"></div>
    <div>
 
	<div class="field">
    <input id="Company" type="text" name="companyName" style="background-color: #808080; border-radius: 8px; font-size:12px;" value="<?= $data['company_name'] ?>" readonly>
    </div>

    </div>
    </div>
  
			
    <label for="Volunteer">Are You Interested In Volunteering?</label>

<div class="field">
    <input type="text" name="volunteer" style="background-color: #808080; border-radius: 8px; font-size:12px;" value="<?php echo $data['volunteer']; ?>" readonly>
</div>
</div>
</div> 

<button type="submit" name="edit" id="sign-in-button" class="btn-btn-primary" onclick="returnMessage();" style="margin-bottom: 10px;"; disabled>Update</button>

		
        </form>
        </body>
      
</html>