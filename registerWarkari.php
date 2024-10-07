<?php
include_once('dbUtil/connect.php');

/* Template Name: Wari Template */

session_start();

$user_id = get_current_user_id();

$query = "SELECT ID FROM `gFs_users` WHERE user_email='$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);


if (isset($_POST["cityID"]) && is_ajax()) {
  $query = "SELECT * FROM areaMaster WHERE cityID = '" . $_POST["cityID"] . "'";
  $result = mysqli_query($conn, $query);
  $areas = mysqli_fetch_all($result, MYSQLI_ASSOC);
  echo json_encode($areas);
  exit;
}

function is_ajax()
{
  return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}
function loadCity()
{
  global $conn;
  $query = "SELECT * FROM cityMaster";
  $result = mysqli_query($conn, $query);
  $cityID = mysqli_fetch_all($result, MYSQLI_ASSOC);

  return $cityID;
}
function loadCompany($includeOtherOption = false)
{
  global $conn;
  $query = "SELECT * FROM companyMaster";
  if ($includeOtherOption) {
    $query .= " UNION SELECT NULL, 'Other' FROM dual";
  }
  $result = mysqli_query($conn, $query);
  $companyID = mysqli_fetch_all($result, MYSQLI_ASSOC);

  return $companyID;
}
function generateDropdownOptions()
{
  $selectedCompanyID = $_POST['companyID'] ?? '';

  $companyID = loadCompany($selectedCompanyID === 'Other');
  foreach ($companyID as $Company) {
    $selected = $Company['companyID'] === $selectedCompanyID ? 'selected' : '';
    echo "<option id='" . $Company['companyID'] . "' value='" . $Company['companyID'] . "' $selected>" . $Company['companyName'] . "</option>";
  }
}


if (isset($_POST['save']) && !is_ajax()) {

  $userID = $_POST['user_id'];
  $firstName = $_POST['firstName'];
  $lastName = $_POST['lastName'];
  $email = $_POST['email'];
  $mobileNo = $_POST['mobileNo'];
  $whatsAppNo = $_POST['whatsAppNo'];
  $telegramNo = $_POST['telegramNo'];
  $gender = $_POST['gender'];
  $age = $_POST['age'];
  $address = $_POST['address'];
  $cityID = $_POST['cityID'];
  $areaID = $_POST['areaID'];
  $companyID = $_POST['companyID'];
  $companyName = $_POST['otherCompany'];
  $volunteer = $_POST['volunteer'];

  // Insert data into warkari table as before, using the updated $companyID value
  $sql = "INSERT INTO warkari(userID, firstName, lastName, email, mobileNo, whatsAppNo, telegramNo, gender, age, address, cityID, areaID, companyID, companyName, volunteer)
         VALUES ('$user_id', '$firstName', '$lastName', '$email', '$mobileNo', '$whatsAppNo', '$telegramNo', '$gender', '$age', '$address', '$cityID', '$areaID', '$companyID', '$companyName', '$volunteer')";

  $res = mysqli_query($conn, $sql);

  if ($res) {
    wp_redirect('https://itdindee.org/staging/7363/register-warkari/joinwaari');
    exit();
  } else {
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

  <head>
    <meta name="viewport" content="width=device-width, initial-scale=0.50">
    <style>
      /* Mobile styles */
      @media (max-width: 900px) {

        /* Adjust the background image */
        body {

          background-repeat: no-repeat;
          background-size: cover;
          height: 100vh;
          /* Adjust the height of the form */
          margin-top: 300px;
        }

        /* Adjust form styles */
        .register-warkari-form {
          margin: 0 auto;
          width: 90%;
          padding: 20px;
          background-color: #686c7233;
          border-radius: 8px;
        }

        .container {
          height: 100vh;
          /* Set the container height to full viewport height */

          display: flex;
          /* Use flexbox to center the form vertically */
          align-items: center;
          /* Center the form vertically */
          justify-content: center;
          /* Center the form horizontally */
        }

        .form-wrapper {
          max-width: 400px;
          /* Set the max width for the form wrapper */
          padding: 20px;
          /* Add some padding for spacing */
          background-color: rgba(255, 255, 255, 0.8);
          /* Set the background color with opacity */
        }

        /* Add additional mobile styles here */
        /* For example, you can adjust the font size, margin, padding, etc. */
      }
    </style>


    <title>Register Warkari</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <style>
      <?php include_once "res/Style.css" ?>
    </style>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

    <!-- Ajax call to get areas when city is selected -->
    <script type="text/javascript">
      $(document).ready(function () {
        $("#City").change(function () {
          var cityID = $("#City").val();
          $.ajax({
            url: window.location.href,
            method: 'post',
            data: { cityID: cityID }
          }).done(function (areas) {
            console.log(areas);
            areas = JSON.parse(areas);
            $('#Area').empty();
            areas.forEach(function (area) {
              $('#Area').append('<option value="' + area.areaID + '">' + area.areaName + '</option>')
            });
          });
        });
      });
    </script>
    <!-- Include Firebase library and initialize Firebase -->
    <script src="https://www.gstatic.com/firebasejs/8.3.1/firebase.js"></script>
    <script>
      // Your web app's Firebase configuration
      const firebaseConfig = {
        apiKey: "Your API Key",
        authDomain: "Your Auth Domain",
        projectId: "Your Project ID",
        storageBucket: "Your Storage Bucket",
        messagingSenderId: "Your Messaging Sender ID",
        appId: "Your App ID",
        measurementId: "Your Measurement ID"
      };

      // Initialize Firebase
      firebase.initializeApp(firebaseConfig);
      firebase.analytics();
    </script>
    <!-- End of head section -->
  </head>

<body
  style="background-image: url('https://itdindee.org/wp-content/uploads/2023/04/wbgfinal-1-3.jpg'); background-repeat: no-repeat; background-size: cover;">


  <form class="register-warkari-form" method="post" action="">
    <h1 style="padding: 10px;"><i class="far fa-calendar-alt"></i>Register Warkari</h1>
    <div style="text-align: center; position: relative;">
      <?php
      echo "<div style='position: absolute; top: -5px; left: 50%; transform: translateX(-50%);'><p style='color: #f00;'>All fields are mandatory <span style='color: #f00; display: inline-block; margin-left: 5px;'>*</span></p></div>";
      ?>
    </div>


    <div class="fields">
      <!-- Input Elements -->

      <div class="wrapper">

        <div>

          <div class="field">
            <i class="fas fa-user"></i>
            <input id="First_Name" type="text" name="firstName" placeholder="First Name"
              style=" background-color: #686c7233; border-radius: 8px;" pattern="[A-Za-z ]+" required
              title="Please enter letters ">

          </div>
        </div>
        <div class="gap"></div>
        <div>

          <div class="field">
            <i class="fas fa-user"></i>
            <input id="Last_Name" type="text" name="lastName" placeholder="Last Name"
              style=" background-color: #686c7233; border-radius: 8px;" pattern="[A-Za-z ]+" required
              title="Please enter letters ">

          </div>
        </div>
      </div>



      <div class="field">
        <i class="fas fa-envelope" style="margin-top:-8px;"></i>
        <input id="Email" type="Email" name="email" placeholder="Your Email"
          style=" background-color: #686c7233; border-radius: 8px;" required>
      </div>

      <input type="hidden" name="username" required value="<?php echo $username; ?> ">

      <div class="wrapper">
        <div>

          <div class="field">
            <i class="fas fa-mobile-alt"></i>
            <input id="mobileNo" type="tel" name="mobileNo" placeholder="Enter Your Phone Number" required
              pattern="[0-9]{10}">
          </div>
        </div>
        <div class="gap"></div>
        <div>

          <div class="field">
            <input type="text" id="verificationCode" placeholder="Enter Verification Code" required>
          </div>
        </div>
      </div>
      <!-- This code generates two buttons, one to send OTP and another to verify it -->
      <div id="recaptcha-container"></div>
      <div class="wrapper">
        <div class="field">
          <button type="button" onclick="phoneAuth()" style="background-color: #ffc400">Send OTP</button>
        </div>
        <div class="gap"></div>
        <div class="field">
          <button type="button" onclick="codeverify()" style="background-color: #ffc400">Verify</button>
        </div>
      </div>
      <!-- The following script is used to send and verify OTP using Firebase Authentication -->
      <script>
        // This variable is used to store the confirmation result after the OTP is sent
        var confirmationResult;

        function phoneAuth() {
          // Get the phone number entered by the user and append the country code (in this case "+91" for India)
          var number = "+91" + document.getElementById('mobileNo').value;
          // Initialize reCAPTCHA
          var recaptcha = new firebase.auth.RecaptchaVerifier('recaptcha-container', {
            'size': 'invisible'
          });

          // Send verification code
          firebase.auth().signInWithPhoneNumber(number, recaptcha)
            .then(function (result) {
              confirmationResult = result;
              console.log("OTP sent");
              showMessage("OTP sent");
            })
            .catch(function (error) {
              showMessage(error.message);
            });
        }

        function codeverify() {
          // Get the OTP entered by the user
          var code = document.getElementById('verificationCode').value;
          // Verify the OTP
          confirmationResult.confirm(code)
            .then(function (result) {
              var user = result.user;
              console.log(user);
              console.log("Verification successful");
              showMessage("Verification successful");
              // Enable form submission upon successful verification
              document.getElementById("sign-in-button").disabled = false;
            })
            .catch(function (error) {
              var errorCode = error.code;
              var errorMessage = error.message;
              if (errorCode === 'auth/invalid-verification-code') {
                showMessage('Invalid verification code');
              } else {
                showMessage(errorMessage);
              }
              console.log(error);
            });
        }
      </script>

      <div class="wrapper">
        <div>
          <div class="field">
            <i class="fab fa-whatsapp fa-lg fa-fw"></i>
            <input type="text" id="whatsAppNo" placeholder="Enter WhatsApp Number" name="whatsAppNo"
              style=" background-color: #686c7233; border-radius: 8px;" required>
          </div>
        </div>
        <div class="gap"></div>
        <div>

          <div class="field">
            <i class="fab fa-telegram-plane fa-fw"></i>
            <input type="text" id="telegramNo" placeholder="Enter Telegram Number" name="telegramNo"
              style=" background-color: #686c7233; border-radius: 8px;" required>
            <!--  It sets event listeners on the mobileNo, whatsAppNo, and telegramNo fields and updates the values of the other two fields to match the value of the mobileNo field when it is changed. -->
            <script>
              var inputs = document.querySelectorAll('input');

              // Set input event listener to change background color
              inputs.forEach(function (input) {
                input.addEventListener('input', function () {
                  if (input.value !== '') {
                    input.style.backgroundColor = 'white';
                  }
                });
              });

              var mobileNo = document.getElementById('mobileNo');
              var whatsAppNo = document.getElementById('whatsAppNo');
              var telegramNo = document.getElementById('telegramNo');

              // Set input event listener to update other fields
              mobileNo.addEventListener('input', function () {
                // Update values
                whatsAppNo.value = this.value;
                telegramNo.value = this.value;

                // Dispatch new 'input' event to trigger script
                var inputEvent = new Event('input', { bubbles: true });
                whatsAppNo.dispatchEvent(inputEvent);
                telegramNo.dispatchEvent(inputEvent);
              });
            </script>

          </div>
        </div>
      </div>

      <div class="wrapper">
        <div>

          <div class="field">
            <input type="radio" name="gender" <?php if (isset($gender) && $gender == "female")
              echo "checked"; ?>
              value="female" style="background-color: #686c7233; border-radius: 8px;" required>
            <span>Female</span>
            <input type="radio" name="gender" <?php if (isset($gender) && $gender == "male")
              echo "checked"; ?>
              value="male" style="background-color: #686c7233; border-radius: 8px;" required>
            <span>Male</span>
          </div>
        </div>
        <div class="gap"></div>
        <div>

          <div class="field">
            <input type="text" id="number" placeholder="Enter Your Age " name="age"
              style=" background-color: #686c7233; border-radius: 8px;" required>
          </div>
        </div>
      </div>


      <div class="wrapper">
        <div>

          <div class="field">

            <select id="City" name="cityID"
              style="background-color: #686c7233; border-radius: 8px;  font-size:12px; color: #858688;" required>
              <option style="color:#000; padding-top:20px;">Select City</option>
              <?php
              $cityID = loadCity();
              foreach ($cityID as $City) {
                echo "<option  id='" . $City['cityID'] . "' value='" . $City['cityID'] . "'>" . $City['cityName'] . "</option>";
              }
              ?>
            </select>
          </div>
        </div>
        <div class="gap"></div>
        <div>

          <div class="field">
            <select id="Area" name="areaID"
              style="background-color: #686c7233; border-radius: 8px; font-size:12px; color: #858688;" required>
              <option style="color:#000;">Select Area</option>
            </select>
          </div>
        </div>
      </div>

      <div class="wrapper">
        <div>

          <div class="field">
            <input id="Address" type="textbox" name="address" placeholder="Enter Your Address"
              style=" background-color: #686c7233; border-radius: 8px;" required>
          </div>
        </div>
        <div class="gap"></div>
        <div>



          <div class="field">
            <select id="Company" name="companyID"
              style="background-color: #686c7233; border-radius: 8px; font-size:12px; color: #858688;" required>

              <option style="color:#000;">Select Company </option>
              <?php
              $companyID = loadCompany();
              foreach ($companyID as $Company) {
                echo "<option id='" . $Company['companyID'] . "' value='" . $Company['companyID'] . "'>" . $Company['companyName'] . "</option>";
              }
              ?>
            </select>
           

          </div>

        </div>
      </div>
      <div id="otherTextbox" style="display:none;">
              <div class="field">
                <input type="text" id="otherCompany" name="otherCompany" placeholder="Enter Your Company Name">
              </div>
            </div>
      <script>
        /*
This script shows or hides the "Other" textbox based on the currently selected option in the Company dropdown.
If "Other" is selected, the textbox is shown. Otherwise, it is hidden.
*/
        function showOtherTextbox() {
          var selectedOptionText = document.getElementById("Company").options[document.getElementById("Company").selectedIndex].text;
          if (selectedOptionText === "Other") {
            document.getElementById("otherTextbox").style.display = "block";
          } else {
            document.getElementById("otherTextbox").style.display = "none";
          }
        }

        document.getElementById("Company").addEventListener("change", showOtherTextbox);

      </script>


      <label for="Volunteer" required>Are You Interested in Volunteering?

        <div class="field">
          <label>
            <input type="radio" name="volunteer" <?php if (isset($volunteer) && $volunteer == "yes")
              echo "checked"; ?>
              value="yes" required>
            <span>Yes</span>
          </label>
          <label>
            <input type="radio" name="volunteer" <?php if (isset($volunteer) && $volunteer == "no")
              echo "checked"; ?>
              value="no" required>
            <span>No</span>
          </label>
        </div>

    </div>

    <script>
      // Get all input and select elements and add event listeners
      var inputs = document.querySelectorAll('input, select');
      inputs.forEach(function (input) {
        // Add event listeners for input and change events
        input.addEventListener('input', setBackground);
        input.addEventListener('change', setBackground);
      });

      // Change the background color of input fields based on their value
      function setBackground() {
        if (this.value !== '') {
          this.style.backgroundColor = 'white';
        } else {
          this.style.backgroundColor = '#686c7233'; // Use a light gray color for empty fields
        }
      }
    </script>

    <style>
      button:disabled {
        pointer-events: none;
      }
    </style>
    <button type="submit" name="save" id="sign-in-button" class="btn-btn-primary" onclick="returnMessage();"
      style="margin-bottom: 10px;" ; disabled>Register</button>


  </form>
</body>
<script>
  // ---------------------------------------------
  // Script 1:
  // Get the form element and listen for a 'save' event
  const form = document.querySelector('form');
  form.addEventListener('save', (event) => {
    event.preventDefault(); // prevent the form from submitting

    // Get the values of the first and last name fields
    const firstName = document.querySelector('#First_Name').value;
    const lastName = document.querySelector('#Last_Name').value;

    // Log the values to the console
    console.log(First name: ${ firstName });
    console.log(Last name: ${ lastName });
  });

  // Script 2:
  // Define a function to show an alert message
  function showMessage(message) {
    alert(message);
  }

  // Define a function to check if verification is successful
  function returnMessage() {
    if (confirmationResult && confirmationResult.verificationId) {
      // Allow form submission if verification is successful
      return true;
    } else {
      // Show an error message and prevent form submission if verification is not successful
      showMessage("Please enter and verify your OTP first.");
      return false;
    }
  }
// ---------------------------------------------

</script>

</html>